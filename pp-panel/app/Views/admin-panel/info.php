<?= $this->extend('admin-panel/templates/panel') ?>

<?= $this->section('scripts') ?>
<script src="/next/modules-20240725/admin-panel/shared/page.js" type="module"></script>
<?= $this->endSection() ?>

<?= $this->section('templates') ?>

<template id="v-PageRoot">
    <ap-page :page-title="$t('pages.info')">
        <div class="main-content">
            <div class="page main-block">
                <p>{{ $t('pages.info.env') }}: <?= ENVIRONMENT ?></p>
                <p>{{ $t('pages.info.ciVersion') }}: <?= CodeIgniter\CodeIgniter::CI_VERSION ?></p>
                <p>{{ $t('pages.info.shieldVersion') }}: <?= CodeIgniter\Shield\Auth::SHIELD_VERSION ?></p>
                <p>{{ $t('pages.info.sessionId') }}: <?= session()->session_id ?></p>
                <p>{{ $t('pages.info.ipAddress') }}: <?= request()->getIPAddress() ?></p>
                <p>app.baseURL: <?= esc(setting("app.baseURL")) ?></p>
                <p>app.clientFolderSaving: <?= esc(var_export(setting("app.clientFolderSaving"), true)) ?></p>
                <p>app.clientsFolderPath: <?= esc(setting("app.clientsFolderPath")) ?></p>
                <p>app.templateFolderPath: <?= esc(setting("app.templateFolderPath")) ?></p>
                <p>app.templateSubFolderPath: <?= esc(setting("app.templateSubFolderPath")) ?></p>
                <p>PHP version: <?= esc(phpversion()) ?></p>
                <p>Apache version: <?= esc(apache_get_version()) ?></p>
                <p>SQLite version: <?= esc(SQLite3::version()["versionString"] ?? "") ?></p>
            </div>
        </div>
    </ap-page>
</template>

<?= $this->endSection() ?>
