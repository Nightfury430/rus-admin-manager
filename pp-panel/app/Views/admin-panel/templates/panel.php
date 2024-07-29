<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>

    <link rel="icon" href="/next/favicon.ico" sizes="any">

    <link href="/next/modules-20240725/admin-panel/shared/admin-panel.css" rel="stylesheet">

    <?= $this->renderSection('styles') ?>

    <?= $this->include("admin-panel/includes/langs-meta") ?>
</head>

<body>
    <div hidden>
        <?= $this->renderSection('templates') ?>
    </div>

    <div class="wrap app-mount"></div>

    <?= $this->renderSection('scripts') ?>
</body>

</html>
