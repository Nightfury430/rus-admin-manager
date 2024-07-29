<?php

declare(strict_types=1);

namespace App\Controllers\AdminPanel;

use App\Libraries\AutoRouterTrait;
use CodeIgniter\API\ResponseTrait;
// use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class JsonApiController extends BaseController
{
    use ResponseTrait;
    use AutoRouterTrait;

    protected $inputData = null;
    protected $isInputLoaded = null;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }

    protected function input()
    {
        if ($this->isInputLoaded) {
            return $this->inputData;
        }

        $this->isInputLoaded = true;
        $this->inputData = $this->request->getJSON(assoc: true);

        return $this->inputData;
    }

    protected function success($data = null)
    {
        $result = ["status" => "success"];

        if ($data !== null) {
            $result["data"] = $data;
        }

        return $this->respond($result);
    }

    protected function tableApi($method, $tableName, $ctx = null)
    {
        /** @var \App\Libraries\Table */
        $tableLib = lib("Table");

        $db = db_admin();
        $input = $this->input();

        if ($method === "get") {
            $result = $tableLib->handleGetRows($db, $tableName, $input, $ctx);

            return $this->success($result);
        }

        if ($method === "save") {
            $result = $tableLib->handleSaveRows($db, $tableName,$input, $ctx);

            if ($result === true) {
                return $this->success();
            }

            return $this->fail($result);
        }

        if ($method === "delete") {
            $result = $tableLib->handleDeleteRows($db, $tableName,$input, $ctx);

            if ($result === true) {
                return $this->success();
            }

            return $this->fail($result);
        }

        throw new \Exception("Unknown table api method!");
    }
}
