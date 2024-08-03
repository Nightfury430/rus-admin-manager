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
    const modalShow = ref(false);
    const allUsers = ref(users);
    const modalTitle = ref('add_user');
    const userName = ref('');
    const userGender = ref('');
    const userAddress = ref('');
    const userPhoneNumber = ref('');
    const userEmail = ref('');
    const userRole = ref('');
    const userPassword = ref('');
    const userConfirmPassword = ref('');
    function langFunc(str) {
        return lang[str]
    }

    function saveUser(){

    }

    function clearField(){
        modalShow.value = false;
        userName.value = '';
        userGender.value = '';
        userAddress.value = '';
        userPhoneNumber.value = '';
        userEmail.value = '';
        userRole.value = '';
        userPassword.value = '';
        userConfirmPassword.value = '';
    }

    function saveUser(){
        let name = userName.value;
        let gender = userGender.value;
        let address = userAddress.value;
        let phoneNumber = userPhoneNumber.value;
        let role = userRole.value;
        let email = userEmail.value;
        let password = userPassword.value;
        if(validation([name, gender, address, phoneNumber, role], email)){
            let formData = new FormData();
            formData.append('data', JSON.stringify({name, gender, address, phoneNumber, role, email, password}));
            axios({
                method: 'post',
                url: base_url + '/user/insert_user',
                data: formData,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then((res) => {
                allUsers.value.push(res.data);
            })
        }else {
            alert('Insert Correctly')
        }
        clearField();
    }

    function validation(infos, email){
        if(infos.findIndex((info) => { info === '' }) === -1){
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if(emailPattern.test(email)){
                return true
            } else return false
        } return false
    }

    const app = createApp({
        setup() {
            return {
                modalShow,
                modalTitle,
                base_url,
                acc_url,
                lang_data,
                userName,
                userGender,
                userAddress,
                userPhoneNumber,
                userEmail,
                userRole,
                userPassword,
                userConfirmPassword,
                allUsers,
                lang :langFunc,
                saveUser,
                clearField,
                saveUser,
            }
        }
    });

    app.mount('#user_container');
}