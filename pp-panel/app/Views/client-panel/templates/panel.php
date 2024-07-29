<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= lang("AppClientPanel.headTitle") ?><?= lang("App.headTitle") ?><?= !empty($page_title) ? " / " . esc($page_title) : "" ?></title>

    <link rel="icon" href="/next/favicon.ico" sizes="any">

    <link href="/next/modules-20240725/client-panel/shared/client-panel.css" rel="stylesheet">

    <?= $this->renderSection('styles') ?>
</head>

<body>
    <div>
        <section class="app-top">
            <nav class="main-nav">
                <ul>
                    <li><a href="<?= url_to('client-panel') ?>"><?= lang("AppClientPanel.pages.home") ?></a></li>
                </ul>
            </nav>
        </section>

        <header class="app-header">
            <h2><?= !empty($page_title) ? esc($page_title) : "" ?></h2>
        </header>

        <main>
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <?= $this->renderSection('scripts') ?>
</body>

</html>
