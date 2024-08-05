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
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const bsValidationForms = document.querySelectorAll('.needs-validation');

        // Loop over them and prevent submission
        Array.prototype.slice.call(bsValidationForms).forEach(function (form) {
            form.addEventListener(
            'submit',
            function (event) {
                event.preventDefault();
                event.stopPropagation();
                if (!form.checkValidity()) {
                    return false;
                } else {
                    return true;
                }
                form.classList.add('was-validated');
            },
            false
            );
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