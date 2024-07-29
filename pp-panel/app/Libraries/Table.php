<?php

declare(strict_types=1);

namespace App\Libraries;

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Database\RawSql;

class Table
{
    public function handleGetRows($db, $tableName, $input, $ctx = null)
    {
        $getCount = !empty($input["count"]);
        $applyFilters = !empty($input["filters"]);
        $filters = $input["filters"] ?? null;
        $pager = $input["pager"] ?? null;
        $count = 0;

        /** @var \App\Libraries\DBInfo */
        $dbInfoLib = lib("DBInfo");
        $fields = $dbInfoLib->getFields($tableName);
        $fieldsInfo = $dbInfoLib->getFieldsInfo($tableName);

        if ($getCount) {
            $query = isset($ctx["queryBuilder"]) ? $ctx["queryBuilder"]($db, $input) : $db->table($tableName);

            if ($applyFilters) {
                $this->applyQueryFilters($query, $filters, $fields, $fieldsInfo, forCount: true);
            }

            $count = $query->countAllResults();
        }

        $query = isset($ctx["queryBuilder"]) ? $ctx["queryBuilder"]($db, $input) : $db->table($tableName);

        if ($applyFilters) {
            $this->applyQueryFilters($query, $filters, $fields, $fieldsInfo);
        }

        $this->applyPager($query, $pager);

        $found = $query->get()->getResultArray() ?? [];
        $this->formatOutput($found, $fieldsInfo);

        if ($getCount) {
            return ["count" => $count, "rows" => $found];
        }

        return ["rows" => $found];
    }

    public function handleSaveRows($db, $tableName, $input, $ctx = null)
    {
        $input = $input ?? [];
        $inputRows = !empty($input["rows"]) ? $input["rows"] : [];

        $checkInput = $this->checkInputForSave($input);

        if (!$checkInput["success"]) {
            return $checkInput["message"];
        }

        /** @var \App\Libraries\DBInfo */
        $dbInfoLib = lib("DBInfo");
        $fields = $dbInfoLib->getFields($tableName);
        $fieldsInfo = $dbInfoLib->getFieldsInfo($tableName);

        /** @var BaseBuilder */
        $query = $db->table($tableName);

        $onSaveCallback = $ctx["onSaveCallback"] ?? null;
        $logger = $ctx["logger"] ?? null;

        $timeZone = new \DateTimeZone("UTC");
        $timeZoneUTC = new \DateTimeZone("UTC");

        try {
            $timeZone = new \DateTimeZone(empty($input["dateTimeZone"]) ? "UTC" : $input["dateTimeZone"]);
        } catch (\Throwable $th) {
        }

        foreach ($inputRows as $row) {
            $isNew = empty($row["id"]);
            $data = $this->filterRowOnSave($row, $fields, $fieldsInfo, $timeZone, $timeZoneUTC);

            if ($onSaveCallback) {
                $onSaveResult = $onSaveCallback(["row" => $row, "rowFiltered" => $data, "query" => $query]);

                if ($onSaveResult === false) {
                    continue;
                }

                if (gettype($onSaveResult) === "string") {
                    return $onSaveResult;
                }
            }

            if (count($data) === 0) {
                return "Nothing to save!";
            }

            if ($isNew) {
                $data["id"] = uid();

                if (!$query->insert($data)) {
                    return "Error when saving records!";
                }

                if ($logger) {
                    $logger($data);
                }

                continue;
            }

            if (!$query->set($data)->where("id", $row["id"])->update()) {
                return "Error when saving records!";
            }

            if ($logger) {
                $logger([...$data, "id" => $row["id"]]);
            }
        }

        return true;
    }

    public function handleDeleteRows($db, $tableName, $input, $ctx = null)
    {
        $ids = $input["ids"] ?? [];
        $logger = $ctx["logger"] ?? null;

        $checkInput = $this->checkInputForDelete($input);

        if (!$checkInput["success"]) {
            return $checkInput["message"];
        }

        /** @var BaseBuilder */
        $query = $db->table($tableName);

        $result = $query->whereIn("id", $ids)->delete();

        if (!$result) {
            return "Error when removing records!";
        }

        if ($logger) {
            $logger($ids);
        }

        return true;
    }

    public function applyQueryFilters(BaseBuilder $query, $filters, $fields, $fieldsInfo, $forCount = false)
    {
        $db = $query->db();

        if (!empty($filters["search"])) {
            $search = mb_strtoupper($filters["search"] ?? "");

            $query->groupStart();

            foreach (array_diff($fields, ["id", "uid"]) as $field) {
                $fieldInfo = $fieldsInfo[$field];

                $isStringSearch = $fieldInfo
                    && ($fieldInfo["type"] === "string" || $fieldInfo["type"] === "text")
                    && (!isset($fieldInfo["commonSearch"]) || $fieldInfo["commonSearch"] !== false);

                if (!$isStringSearch) {
                    continue;
                }

                $realFieldName = empty($fieldInfo["column"]) ? $field : $fieldInfo["column"];

                $rawSql = new RawSql("UCASE({$db->protectIdentifiers($realFieldName)})");
                $query->orLike($rawSql, $search);
            }

            $query->groupEnd();
        }

        if (!empty($filters["fields"])) {
            $timeZone = new \DateTimeZone("UTC");
            $timeZoneUTC = new \DateTimeZone("UTC");

            try {
                $timeZone = new \DateTimeZone(empty($filters["dateTimeZone"]) ? "UTC" : $filters["dateTimeZone"]);
            } catch (\Throwable $th) {
            }

            $filtersFields = $filters["fields"];

            foreach ($fields as $field) {
                if (empty($filtersFields[$field])) {
                    continue;
                }

                $fieldInfo = $fieldsInfo[$field];

                $data = $filtersFields[$field];

                if (!isset($data["value"])) {
                    continue;
                }

                $value = $data["value"];
                $valueType = gettype($value);

                if (!in_array($valueType, ["string", "integer", "boolean", "NULL"])) {
                    continue;
                }

                if ($value === "") {
                    continue;
                }

                $format = isset($fieldInfo["format"]) ? $fieldInfo["format"] : null;

                $value = match ($format) {
                    "phone" => $this->filterPhone($value),
                    default => $value,
                };

                $isStringSearch = $valueType === "string" && $fieldInfo
                    && ($fieldInfo["type"] === "string" || $fieldInfo["type"] === "text" || $fieldInfo["type"] === "uid");

                $isDateSearch = !$isStringSearch && $valueType === "string" && $fieldInfo && $fieldInfo["type"] === "date";

                $realFieldName = empty($fieldInfo["column"]) ? $field : $fieldInfo["column"];

                if ($isStringSearch) {
                    $rawSql = new RawSql("UCASE({$db->protectIdentifiers($realFieldName)})");
                    $value = mb_strtoupper($value);

                    $exactly = isset($data["exactly"]) ? $data["exactly"] === true : false;

                    if ($exactly) {
                        $query->where($rawSql, $value);
                        continue;
                    }

                    $query->like($rawSql, $value);
                    continue;
                }

                if ($isDateSearch) {
                    $dateFilter = $data["dateFilter"] ?? "=";
                    $dateMode = $data["dateMode"] ?? null;
                    $value2 = $data["value2"] ?? null;

                    $dateRange = $this->parseDateInterval($value, $dateMode, $timeZone, $timeZoneUTC);

                    if ($dateRange[0] === null) {
                        continue;
                    }

                    $dateRange[0] = substr($dateRange[0], 0, -4);

                    if ($dateFilter === "<") {
                        $query->where("{$realFieldName} <=", $dateRange[1]);
                    } else if ($dateFilter === ">") {
                        $query->where("{$realFieldName} >=", $dateRange[0]);
                    } else if ($dateFilter === "[]") {
                        $dateRange2 = $this->parseDateInterval($value2, $dateMode, $timeZone, $timeZoneUTC);

                        if ($dateRange2[0] === null) {
                            continue;
                        }

                        $query->where("{$realFieldName} >=", $dateRange[0]);
                        $query->where("{$realFieldName} <=", $dateRange2[1]);
                    } else {
                        $query->where("{$realFieldName} >=", $dateRange[0]);
                        $query->where("{$realFieldName} <=", $dateRange[1]);
                    }

                    continue;
                }

                $query->where($realFieldName, $value);
            }
        }

        if (!$forCount && !empty($filters["sort"]["field"])) {
            $asc = $filters["sort"]["asc"] ?? false;
            $field = $filters["sort"]["field"];

            $fieldInfo = $fieldsInfo[$field];

            $isStringSearch = !str_ends_with($field, "id") && $fieldInfo
                && ($fieldInfo["type"] === "string" || $fieldInfo["type"] === "text");

            $realFieldName = empty($fieldInfo["column"]) ? $field : $fieldInfo["column"];

            if ($isStringSearch) {
                $sql = "UCASE({$db->protectIdentifiers($realFieldName)})";
                $query->orderBy($sql, $asc ? "ASC" : "DESC");
            } else {
                $query->orderBy($realFieldName, $asc ? "ASC" : "DESC");
            }
        }
    }

    public function applyPager(BaseBuilder $query, $pager)
    {
        $pager = $pager ?? ["currentPage" => 1, "perPage" => 10];

        if ($pager === false) {
            return;
        }

        $currentPage = $pager["currentPage"];
        $perPage = $pager["perPage"];

        if ($currentPage <= 0) {
            throw new \Exception("Page number must be greater than 0!");
        }

        if ($perPage <= 0 || $perPage > 100) {
            throw new \Exception("Number of rows per page must be between 1 and 100!");
        }

        $limit = $perPage;
        $offset = ($currentPage - 1) * $perPage;

        $query->limit($limit, $offset);
    }

    public function checkInputForSave($input)
    {
        if (empty($input["rows"]) || gettype($input["rows"]) !== "array" || count($input["rows"]) === 0) {
            return ["success" => false, "message" => "Nothing to save!"];
        }

        return ["success" => true];
    }

    public function checkInputForDelete($input)
    {
        $ids = $input["ids"] ?? [];

        if (gettype($ids) !== "array" || count($ids) === 0) {
            return ["success" => false, "message" => "ids array for removing not provided!"];
        }

        return ["success" => true];
    }

    public function filterRowOnSave($row, $fields, $fieldsInfo, $timeZone, $timeZoneUTC)
    {
        $formats = array_filter($fieldsInfo, static fn ($x) => isset($x["format"]));

        $data = [];

        foreach (array_diff($fields, ["id"]) as $field) {
            if (!isset($row[$field])) {
                continue;
            }

            $value = $row[$field];
            $valueType = gettype($value);

            if (!in_array($valueType, ["string", "integer", "boolean", "NULL"])) {
                continue;
            }

            $format = isset($formats[$field]) ? $formats[$field]["format"] : null;

            $value = match ($format) {
                "phone" => $this->filterPhone($value),
                default => $value,
            };

            $info = $fieldsInfo[$field];

            if ($info["type"] === "int" || $info["type"] === "bool") {
                if (empty($value)) {
                    $value = !empty($info["notNull"]) ? 0 : null;
                }
            } else if ($info["type"] === "string" || $info["type"] === "text") {
                if (gettype($value) !== "string" || $value === "") {
                    $value = !empty($info["notNull"]) ? "" : null;
                }
            } else if ($info["type"] === "date") {
                $dateRange = $this->parseDateInterval($value, "exactly", $timeZone, $timeZoneUTC);

                if ($dateRange[0] !== null) {
                    $value = substr($dateRange[0], 0, -4);
                }

                if (empty($value)) {
                    $value = !empty($info["notNull"]) ? "" : null;
                }
            }

            $data[$field] = $value;
        }

        return $data;
    }

    public function formatOutput(&$output, $fieldsInfo)
    {
        if (count($output) === 0) {
            return;
        }

        $formats = array_filter($fieldsInfo, static fn ($x) => isset($x["format"]));

        if (count($formats) === 0) {
            return;
        }

        array_walk($output, function (&$row, $key) use ($formats) {
            foreach ($formats as $formatField => $format) {
                if (!isset($row[$formatField])) {
                    continue;
                }

                $row[$formatField] = match ($format["format"]) {
                    "phone" => $this->formatPhone($row[$formatField]),
                    default => $row[$formatField],
                };
            }
        });
    }

    public function parseIntFromString($str)
    {
        if (gettype($str) === "integer") {
            return $str;
        }

        if (gettype($str) !== "string") {
            return null;
        }

        $parsed = intval($str, 10);

        if (!$parsed) {
            return null;
        }

        return $parsed;
    }

    public function filterPhone($phone)
    {
        if (gettype($phone) !== "string") {
            return $phone;
        }

        return preg_replace('/[^\d,;+]/', '', $phone);
    }

    public function formatPhone($phone)
    {
        if (!$phone) {
            return "";
        }

        $phones = explode(",", $phone);

        $phones = array_map(static function ($phonesPart) {
            $phonesInner = explode(";", $phonesPart);

            $phonesInner = array_map(static function ($x) {
                if (preg_match("/^[+]?[\d]{11}$/", $x) !== 1) {
                    return $x;
                }

                $digits = str_split($x);
                $plus = $digits[0] === "+" ? "+" : "";

                if ($plus) {
                    array_shift($digits);
                }

                return sprintf("%s%s (%s%s%s) %s%s%s-%s%s-%s%s", $plus, ...$digits);
            }, $phonesInner);

            return implode("; ", $phonesInner);
        }, $phones);

        return implode(", ", $phones);
    }

    public function getCurrentDateString()
    {
        $timeZoneUTC = new \DateTimeZone("UTC");
        $iso8601Format = 'Y-m-d\TH:i:s.v';

        $date = (new \DateTimeImmutable("now", $timeZoneUTC))->format($iso8601Format);

        return substr($date, 0, -4);
    }

    private function parseDateParts(?string $date)
    {
        $empty = [null, null, null, null, null, null];

        if (!$date) {
            return $empty;
        }

        $dateAndTime = explode(" ", $date, 2);

        $datePart = $dateAndTime[0] ?? "";
        $timePart = $dateAndTime[1] ?? "";

        if (preg_match('/^\d{4}(-\d{2})?(-\d{2})?$/', $datePart) !== 1) {
            return $empty;
        }

        $y = substr($datePart, 0, 4);
        $m = substr($datePart, 5, 2);
        $d = substr($datePart, 8, 2);

        if (!$m) {
            $m = "01";
        }

        if (!$d) {
            $d = "01";
        }

        if (preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $timePart) !== 1) {
            return [$y, $m, $d, null, null, null];
        }

        $h = substr($timePart, 0, 2);
        $i = substr($timePart, 3, 2);
        $s = substr($timePart, 6, 2);

        if (!$s) {
            $s = "00";
        }

        return [$y, $m, $d, $h, $i, $s];
    }

    private function parseDateInterval($src, $dateMode, $timeZone, $timeZoneUTC)
    {
        $iso8601Format = 'Y-m-d\TH:i:s.v';
        $dateParts = $this->parseDateParts($src);

        $dateFrom = null;
        $dateTo = null;

        if ($dateParts[0] === null) {
            return [null, null];
        }

        if ($dateMode === "year") {
            $dateFrom = \DateTimeImmutable::createFromFormat($iso8601Format, "{$dateParts[0]}-01-01T00:00:00.000", $timeZone);

            $dateTo = $dateFrom->add(new \DateInterval("P1Y"))->sub(new \DateInterval("P1D"));
            $dateTo = $dateTo->setTime(23, 59, 59, 999999);
        } else if ($dateMode === "month") {
            $parsedMonth = "{$dateParts[0]}-{$dateParts[1]}";

            $dateFrom = \DateTimeImmutable::createFromFormat($iso8601Format, "{$parsedMonth}-01T00:00:00.000", $timeZone);

            $dateTo = $dateFrom->add(new \DateInterval("P1M"))->sub(new \DateInterval("P1D"));
            $dateTo = $dateTo->setTime(23, 59, 59, 999999);
        } else if ($dateMode === "day") {
            $parsedDay = "{$dateParts[0]}-{$dateParts[1]}-{$dateParts[2]}";

            $dateFrom = \DateTimeImmutable::createFromFormat($iso8601Format, "{$parsedDay}T00:00:00.000", $timeZone);
            $dateTo = $dateFrom->setTime(23, 59, 59, 999999);
        } else {
            $parsedDay = "{$dateParts[0]}-{$dateParts[1]}-{$dateParts[2]}";
            $parsedTime = $dateParts[3] === null ? "00:00:00.000" : "{$dateParts[3]}:{$dateParts[4]}:{$dateParts[5]}.000";

            $dateFrom = \DateTimeImmutable::createFromFormat($iso8601Format, "{$parsedDay}T{$parsedTime}", $timeZone);

            $dateTo = \DateTimeImmutable::createFromFormat($iso8601Format, "{$parsedDay}T{$parsedTime}", $timeZone);
            $dateTo = $dateParts[3] === null
                ? $dateTo->setTime(0, 0, 59, 999999)
                : $dateTo->setTime(intval($dateParts[3]), intval($dateParts[4]), 59, 999999);
        }

        $dateFrom = $dateFrom->setTimezone($timeZoneUTC)->format($iso8601Format);

        if ($dateTo !== null) {
            $dateTo = $dateTo->setTimezone($timeZoneUTC)->format($iso8601Format);
        }

        return [$dateFrom, $dateTo];
    }
}
