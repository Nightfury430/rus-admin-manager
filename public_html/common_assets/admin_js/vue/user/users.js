// selectedUser 0 editing Saving  Not 0 Updating
let app = null;
let lang = null;
let controller_name = null;
let base_url = null;
let acc_url = null;
let lang_data = null;
let users = [];

document.addEventListener('DOMContentLoaded', function () {
    base_url = document.getElementById('ajax_base_url').value;
    acc_url = document.getElementById('acc_base_url').value;
    lang = JSON.parse(document.getElementById('lang_json').value);

    if(document.getElementById('set_id')){
        set_id = '/' + document.getElementById('set_id').value
    }

    if(document.getElementById('is_common')){
        is_common = document.getElementById('is_common').value
    }

    Promise.all([
        promise_request(base_url + '/user/get_all_users'),
    ]).then(function (results) {
        userManagement(results[0]);
    }).catch(function () {
        console.log('Error');
    });
})


function userManagement(users){
    const { createApp, ref } = Vue
    const allUsers = ref(users);
    const modalTitle = ref('add_user');
    const userName = ref('');
    const userFirstName = ref('');
    const userLastName = ref('');
    const userMiddleName = ref('');
    const userGender = ref('');
    const userAddress = ref('');
    const userPhoneNumber = ref('');
    const userEmail = ref('');
    const userRole = ref('');
    const userPassword = ref('');
    const userConfirmPassword = ref('');
    let selectedUser = 0;
    function langFunc(str) {
        return lang[str]
    }

    function clearField(){
        userName.value = '';
        userFirstName.value = '';
        userLastName.value = '';
        userMiddleName.value = '';
        userAddress.value = '';
        userPhoneNumber.value = '';
        userEmail.value = '';
        userRole.value = '';
        userPassword.value = '';
        userConfirmPassword.value = '';
    }

    function saveUser(){
        if(validation()){
            let first_name = userFirstName.value;
            let middle_name = userMiddleName.value;
            let last_name = userLastName.value;
            let user_name = userName.value;
            let address = userAddress.value;
            let phoneNumber = userPhoneNumber.value;
            let role = userRole.value;
            let email = userEmail.value;
            let password = userPassword.value;
            let url = base_url + '/user/'
            let data = ''
            if(selectedUser === 0){
                url += 'insert_user';
                data = JSON.stringify({user_name, first_name, middle_name, last_name, address, phone_number : phoneNumber, role, email, password})
            } else {
                url += 'edit_user';
                data = JSON.stringify({user_name, first_name, middle_name, last_name, address, phone_number : phoneNumber, role, email, password, id : selectedUser})
            }
            let formData = new FormData();
                formData.append('data', data);
                axios({
                    method: 'post',
                    url: url,
                    data: formData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then((res) => {
                    if(selectedUser === 0){
                        allUsers.value.push(res.data);
                    } else {
                        const findIndex = allUsers.value.findIndex((user) => ( user.id === res.data.id ));
                        allUsers.value.splice( findIndex, 1, res.data )
                    }
                    changeSelUser(0)
            })
            clearField();
            document.querySelector('#UserModal .btn-close').click();
        }
    }

    function validation(){
        //form validation
        const formValidationExamples = document.getElementById('UserForm');
        let isValid = true;
        FormValidation.formValidation(formValidationExamples, {
            fields: {
                modalUserFirstName: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter your first name'
                        }
                    }
                },
                modalUserLastName: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter your last name'
                        }
                    }
                },
                modalUserMiddleName: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter your middle name'
                        }
                    }
                },
                modalUserName: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter your username'
                        }
                    },
                    stringLength: {
                        min: 6,
                        max: 30,
                        message: 'The username must be more than 6 and less than 30 characters long',
                    },
                    regexp: {
                        regexp: /^[a-zA-Z0-9_]+$/,
                        message: 'The username can only consist of alphabetical, number and underscore',
                    },
                },
                modalUserEmail: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter your email'
                        },
                        emailAddress: {
                            message: 'The value is not a valid email address'
                        }
                    }
                },
                modalUserPhoneNumber: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter your phoneNumber'
                        }
                    }
                },
                modalUserAddress: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter your address'
                        }
                    }
                },
                modalUserRole: {
                    validators: {
                        notEmpty: {
                            message: 'Please select role'
                        }
                    }
                },
                modalUserPassword: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter password'
                        }
                    }
                },
                modalUserConfirmPassword: {
                    validators: {
                        notEmpty: {
                        message: 'Please confirm password'
                        },
                        identical: {
                        compare: function () {
                            return formValidationExamples.querySelector('[name="modalUserPassword"]').value;
                        },
                        message: 'The password and its confirm are not the same'
                        }
                    }
                }
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                // bootstrap5: new FormValidation.plugins.Bootstrap5({
                // // Use this for enabling/changing valid/invalid class
                // // eleInvalidClass: '',
                // eleValidClass: '',
                // rowSelector: function (field, ele) {
                //     // field is the field name & ele is the field element
                //     switch (field) {
                //     case 'modalUserFirstName':
                //     case 'modalUserLastName':
                //     case 'modalUserMiddleName':
                //     case 'modalUserName':
                //     case 'modalUserEmail':
                //     case 'modalUserPhoneNumber':
                //     case 'modalUserAddress':
                //     case 'modalUserRole':
                //     case 'modalUserPassword':
                //     case 'modalUserConfirmPassword':
                //     default:
                //         return '.row';
                //     }
                // }
                // }),
                // submitButton: new FormValidation.plugins.SubmitButton(),
                // Submit the form when all fields are valid
                defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                autoFocus: new FormValidation.plugins.AutoFocus()
            },
            init: instance => {
                instance.on('plugins.message.placed', function (e) {
                //* Move the error message out of the `input-group` element
                if (e.element.parentElement.classList.contains('input-group')) {
                    // `e.field`: The field name
                    // `e.messageElement`: The message element
                    // `e.element`: The field element
                    e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
                }
                //* Move the error message out of the `row` element for custom-options
                if (e.element.parentElement.parentElement.classList.contains('custom-option')) {
                    e.element.closest('.row').insertAdjacentElement('afterend', e.messageElement);
                }
                });
            }
        });
    }

    function editUser(user){
        [ userFirstName.value, userLastName.value, userMiddleName.value, userName.value, userAddress.value, userEmail.value, userPhoneNumber.value, userRole.value] = [ user.first_name, user.last_name, user.middle_name, user.user_name, user.address, user.email, user.phone_number, user.role];
        changeSelUser(user.id);
        console.log('user.role', user.role);
        document.getElementById('modalUserRole').value = user.role;
    }

    function delUser(user){
        let formData = new FormData();
        formData.append('data', JSON.stringify({ id : user.id }));
        axios({
            method: 'post',
            url: base_url + '/user/delete_user',
            data: formData,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then((res) => {
            const findIndex = allUsers.value.findIndex( (user) => (
                user.id === res.data
             ) )
            allUsers.value.splice(findIndex, 1);
        })
    }

    function changeSelUser(selUser){
        selectedUser = selUser;
    }

    function saveModalShow(){
        changeSelUser(0);
    }

    const app = createApp({
        setup() {
            return {
                modalTitle,
                base_url,
                acc_url,
                lang_data,
                userName,
                userAddress,
                userPhoneNumber,
                userEmail,
                userRole,
                userPassword,
                userConfirmPassword,
                allUsers,
                selectedUser,
                userFirstName,
                userLastName,
                userMiddleName,
                lang :langFunc,
                clearField,
                saveUser,
                editUser,
                delUser,
                saveModalShow,
            }
        }
    });
    app.mount('#user_container');
}