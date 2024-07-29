<?php

$scanPath = FCPATH . "assets/admin-panel/lang/";

$langs = [];
$files = scandir($scanPath);

foreach ($files as $file) {
    if ($file === "." || $file === "..") {
        continue;
    }

    if (is_dir($scanPath . $file)) {
        continue;
    }

    if (!str_ends_with($file, ".json")) {
        continue;
    }

    $lang = explode(".", $file, 2)[0];
    $version = filemtime($scanPath . $file);

    $langs[$lang] = $version;
}

foreach ($langs as $lang => $version) {
?>
    <meta data-pp-lang="<?= esc($lang, "attr") ?>" data-pp-version="<?= esc($version, "attr") ?>">
<?php
}
