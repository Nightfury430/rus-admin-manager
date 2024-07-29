<?= $this->extend('admin-panel/templates/panel') ?>

<?= $this->section('scripts') ?>
<script src="/next/modules-20240725/admin-panel/auth/login.js" type="module"></script>
<?= $this->endSection() ?>

<?= $this->section('templates') ?>

<template id="v-LoginForm">
    <form class="login-form" @submit.prevent="onSubmit">
        <p>
            <label for="email">{{ $t('fields.email') }}</label>
            <input v-model="email" type="text" id="email" required />
        </p>

        <p>
            <label for="password">{{ $t('fields.password') }}</label>
            <input v-model="password" type="password" id="password" required />
        </p>

        <p>
            <button class="button">{{ $t('pages.login.submit') }}</button>
        </p>

        <div class="errors" v-if="errors.length > 0">
            <ul v-for="error in errors">
                <li>{{ error }}</li>
            </ul>
        </div>
    </form>
</template>

<?= $this->endSection() ?>
