<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= lang("App.headTitle") ?><?= !empty($page_title) ? " / " . esc($page_title) : "" ?></title>

    <link rel="icon" href="/next/favicon.ico" sizes="any">

    <link href="/next/modules-20240725/client-panel/shared/client-panel.css" rel="stylesheet">
</head>

<body>
    <div>
        <section class="app-top">
            <nav class="main-nav">
                <ul>
                    <li><a href="<?= url_to('/') ?>"><?= lang("App.menu.home") ?></a></li>
                    <li><a href="<?= url_to('admin-panel') ?>"><?= lang("App.menu.adminPanel") ?></a></li>
                    <li><a href="<?= url_to('client-panel') ?>"><?= lang("App.menu.clientPanel") ?></a></li>
                </ul>
            </nav>
        </section>

        <header class="app-header">
            <h2><?= !empty($page_title) ? esc($page_title) : "" ?></h2>
        </header>

        <main>
            <div class="main-block">
                <p><?= lang("App.homeWelcome") ?></p>
            </div>
        </main>
    </div>

    <script src="/next/modules-20240725/client-panel/shared/client-shared.js" type="module"></script>
</body>

</html>
