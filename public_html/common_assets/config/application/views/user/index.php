<div class="col-lg-12 col-md-12 mb-3 ">
    <div class="card h-10">
        <div class="card-header" style="padding: 0.8rem !important">
            <h2 style="margin-bottom : 0rem"><?php echo $lang_arr['user_management']?></h2>
        </div>
    </div>
</div>

<div id="user_container">
    <div class="card">
        <div class="card-header header-elements">
            <h5 class="mb-0 me-2"><?php echo $lang_arr['user_management'] ?></h5>
            <div class="card-header-elements ms-auto">
                <button type="button" class="btn btn-primary" @click="saveModalShow" data-bs-toggle="modal" data-bs-target="#UserModal">
                    <i class="fa-solid fa-plus"></i>
                    <?php echo $lang_arr['add']?>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table">
                <thead>
                    <tr>
                    <th>Id</th>
                    <th><?php echo $lang_arr['name']?></th>
                    <th><?php echo $lang_arr['user_name']?></th>
                    <th><?php echo $lang_arr['email']?></th>
                    <th><?php echo $lang_arr['phone_number']?></th>
                    <th><?php echo $lang_arr['role']?></th>
                    <th><?php echo $lang_arr['actions']?></th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="( user, index ) in allUsers">
                        <tr>
                            <td>{{ index + 1 }}</td>
                            <td>{{user.first_name}} {{user.middle_name }} {{user.last_name}}</td>
                            <td>{{user.user_name}}</td>
                            <td>{{user.email}}</td>
                            <td>{{user.phone_number}}</td>
                            <td>{{ user.role == 0 ?  'ADMIN' : 'USER' }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#UserModal" :title="lang('edit')" @click="editUser(user)"
                                        ><i class="ti ti-pencil me-1"></i> Edit</a
                                    >
                                    <a class="dropdown-item" :title="lang('delete')" @click="delUser(user)"
                                        ><i class="ti ti-trash me-1"></i> Delete</a
                                    >
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="UserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-6">
                        <h4 class="mb-2" v-html="lang(modalTitle)"></h4>
                    </div>
                    <form id="UserForm" class="row g-6 " onsubmit="return false">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modalUserFirstName">{{lang('first_name')}}</label>
                            <input
                            type="text"
                            id="modalUserFirstName"
                            name="modalUserFirstName"
                            class="form-control"
                            placeholder="Alexander"
                            v-model="userFirstName"
                            :value="userFirstName" 
                            />
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modalUserLastName">{{lang('last_name')}}</label>
                            <input
                            type="text"
                            id="modalUserLastName"
                            name="modalUserLastName"
                            class="form-control"
                            placeholder="Petrov"
                            v-model="userLastName"
                            :value="userLastName" 
                            />
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modalUserMiddleName">{{lang('middle_name')}}</label>
                            <input
                            type="text"
                            id="modalUserMiddleName"
                            name="modalUserMiddleName"
                            class="form-control"
                            placeholder="Ivanovich"
                            v-model="userMiddleName"
                            :value="userMiddleName" 
                            />
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modalUserName">{{lang('user_name')}}</label>
                            <input
                            type="text"
                            id="modalUserName"
                            name="modalUserName"
                            class="form-control"
                            v-model="userName"
                            :value="userName" 
                            />
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modalUserEmail">{{lang('email')}}</label>
                            <input
                            type="text"
                            id="modalUserEmail"
                            name="modalUserEmail"
                            class="form-control"
                            placeholder="example@domain.com"
                            v-model="userEmail"
                            :value="userEmail"
                            />
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modalUserPhoneNumber">{{lang('phone_number')}}</label>
                            <input
                            type="text"
                            id="modalUserPhoneNumber"
                            name="modalUserPhoneNumber"
                            class="form-control"
                            placeholder=""
                            v-model="userPhoneNumber"
                            :value="userPhoneNumber"
                            />
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modalUserAddress">{{lang('home_address')}}</label>
                            <input
                            type="text"
                            id="modalUserAddress"
                            name="modalUserAddress"
                            class="form-control"
                            placeholder=""
                            v-model="userAddress"
                            :value="userAddress"
                            />
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modalUserRole">Role</label>
                            <select
                            id="modalUserRole"
                            name="modalUserRole"
                            class="select2 form-select"
                            v-model="userRole"
                            aria-label="Default select example">
                            <option disabled selected></option>
                            <option value="0">Admin</option>
                            <option value="1">User</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modalUserPassword">{{lang('password')}}</label>
                            <input
                            type="password"
                            id="modalUserPassword"
                            name="modalUserPassword"
                            class="form-control"
                            :value="userPassword"
                            v-model="userPassword"
                            />
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modalUserConfirmPassword">{{lang('confirm_password')}}</label>
                            <input
                            type="password"
                            id="modalUserConfirmPassword"
                            name="modalUserConfirmPassword"
                            class="form-control"
                            :value="userConfirmPassword"
                            v-model="userConfirmPassword"
                            />
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" @click="saveUser" class="btn btn-primary me-3" data-bs-dismiss="modal" aria-label="Close">{{ lang('save') }}</button>
                            <button
                            type="reset"
                            class="btn btn-label-secondary"
                            data-bs-dismiss="modal"
                            @click="clearField"
                            aria-label="Close">
                            {{lang('cancel')}}
                            </button>
                        </div>
                    </form>
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
<script src="/common_assets/assets/vendor/libs/@form-validation/popular.js"></script>
<script src="/common_assets/assets/vendor/libs/@form-validation/bootstrap5.js"></script>
<script src="/common_assets/assets/vendor/libs/@form-validation/auto-focus.js"></script>

<!-- Clipboard -->
<script src="/common_assets/theme/js/plugins/clipboard/clipboard.min.js"></script>
<!-- User -->
<script src="/common_assets/admin_js/vue/user/users.js"></script>


