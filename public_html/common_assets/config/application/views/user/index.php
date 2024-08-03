<link href="/common_assets/css/user.css" rel="stylesheet">
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['user_management']?></h2>
    </div>
    <div class="col-lg-2">

    </div>
</div>

<div id="user_container">
    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5><?php echo $lang_arr['user_management'] ?></h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row d-flex align-items-center justify-content-end">
                            <button class="btn btn-primary btn-sm" type="button" @click="modalShow = !modalShow"><?php echo $lang_arr['add']?></button>
                        </div>
                        <div class="row align-items-center pt-3">
                            <table class="table table-bordered table-stripped toggle-arrow-tiny" data-paging="true" data-sorting="true" data-paging-container="#paging-ui-container" data-paging-size="50">
                                <thead>
                                    <tr>
                                        <td data-name="id" data-type="number" data-sorted="true" data-direction="DESC">Id</td>
                                        <td data-name="name"><?php echo $lang_arr['name']?></td>
                                        <td data-name="email"><?php echo $lang_arr['email']?></td>
                                        <td data-name="gender"><?php echo $lang_arr['gender']?></td>
                                        <td data-name="address" data-type="number"><?php echo $lang_arr['home_address']?></td>
                                        <td data-name="phone" data-formatter="type_format"><?php echo $lang_arr['phone_number']?></td>
                                        <td data-name="role"><?php echo $lang_arr['role']?></td>
                                        <td data-name="action"><?php echo $lang_arr['actions']?></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="( user, index ) in allUsers">
                                        <tr>
                                            <td>{{ index + 1 }}</td>
                                            <td>{{user.name}}</td>
                                            <td>{{user.email}}</td>
                                            <td>{{user.gender}}</td>
                                            <td>{{user.address}}</td>
                                            <td>{{user.phone_number}}</td>
                                            <td>{{user.role}}</td>
                                            <td></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-if="modalShow" class="bpl_modal_wrapper">
        <div class="bpl_modal_background" :class="{shown: modalShow}"></div>
        <div class="bpl_modal_content">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button @click="modalShow = false" type="button" class="close"><span>&times;</span></button>
                        <h4 class="modal-title" v-html="lang(modalTitle)"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{lang('name')}}</label>
                            <div class="col-sm-10"><input v-model="userName" type="text" :value="userName" class="form-control"></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{lang('email')}}</label>
                            <div class="col-sm-10"><input v-model="userEmail" type="text" class="form-control" :value="userEmail"></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{lang('gender')}}</label>
                            <div class="col-sm-10"><input v-model="userGender" type="text" class="form-control" :value="userGender"></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{lang('home_address')}}</label>
                            <div class="col-sm-10"><input v-model="userAddress" type="text" class="form-control" :value="userAddress"></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{lang('phone_number')}}</label>
                            <div class="col-sm-10"><input v-model="userPhoneNumber" type="text" class="form-control" :value="userPhoneNumber"></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{lang('role')}}</label>
                            <div class="col-sm-10"><input v-model="userRole" type="text" class="form-control" :value="userRole"></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{lang('password')}}</label>
                            <div class="col-sm-10"><input v-model="userPassword" type="password" class="form-control" :value="userPassword"></div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{lang('confirm_password')}}</label>
                            <div class="col-sm-10"><input v-model="userConfirmPassword" type="password" class="form-control" :value="userConfirmPassword"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button @click="clearField" type="button" class="btn btn-white">{{lang('cancel')}}</button>
                        <button @click="saveUser" type="button" class="btn btn-primary">{{ lang('save') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script src="/common_assets/libs/vue3/vue.global.js"></script>
<script src="/common_assets/libs/vue3/vueuse.shared.iife.min.js"></script>
<script src="/common_assets/libs/vue3/vueuse.core.iife.min.js"></script>
<script src="/common_assets/libs/vue3/vue-slicksort.umd.js"></script>
<script src="/common_assets/libs/vue3/vue-select.umd.js"></script>
<script src="/common_assets/libs/vue3/pp-tree.js"></script>
<!-- Clipboard -->
<script src="/common_assets/theme/js/plugins/clipboard/clipboard.min.js"></script>
<!-- User -->
<script src="/common_assets/admin_js/vue/user/users.js"></script>