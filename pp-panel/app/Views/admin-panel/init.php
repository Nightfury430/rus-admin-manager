<?= $this->extend('admin-panel/templates/panel') ?>

<?= $this->section('scripts') ?>
<script src="/next/modules-20240725/admin-panel/init/init.js" type="module"></script>
<?= $this->endSection() ?>

<?= $this->section('templates') ?>

<template id="v-CreateSuperUserForm">
    <form class="create-super-user-form" @submit.prevent="onSubmit">
        <p>{{ $t('pages.init.createFormHeader') }}</p>

        <p>
            <label for="email">{{ $t('fields.email') }}</label>
            <input v-model="email" type="text" id="email" required />
        </p>

        <p>
            <label for="password">{{ $t('fields.password') }}</label>
            <input v-model="password" type="password" id="password" required />
        </p>

        <p>
            <button class="button">{{ $t('pages.init.createButton') }}</button>
        </p>

        <div class="alert" v-if="message">
            <p>{{ message }}</p>
        </div>

        <div class="errors" v-if="errors.length > 0">
            <ul v-for="error in errors">
                <li>{{ error }}</li>
            </ul>
        </div>
    </form>
</template>

<?= $this->endSection() ?>
