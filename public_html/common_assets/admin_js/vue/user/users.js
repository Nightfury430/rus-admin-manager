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
    let selectedUser = 0;
    function langFunc(str) {
        return lang[str]
    }

    function clearField(){
        modalShow.value = false;
        userName.value = '';
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
        let url = base_url + '/user/'
        let data = ''
        if(selectedUser === 0){
            url += 'insert_user';
            data = JSON.stringify({name, address, phone_number : phoneNumber, role, email, password})
        } else {
            url += 'edit_user';
            data = JSON.stringify({name, address, phone_number : phoneNumber, role, email, password, id : selectedUser})
        }
        if(validation([name, gender, address, phoneNumber, role], email)){
            let formData = new FormData();
            formData.append('data', data);
            axios({
                method: 'post',
                url: url,
                data: formData,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then((res) => {
                console.log('res', res);
                if(selectedUser === 0){
                    allUsers.value.push(res.data);
                } else {
                    const findIndex = allUsers.value.findIndex((user) => ( user.id === res.data.id ));
                    allUsers.value.splice( findIndex, 1, res.data )
                }
                changeSelUser(0)
            })
        }else {
            changeSelUser(0)
            alert('Insert Correctly')
        }
        clearField();
    }

    function editUser(user){
        modalShow.value = true;
        [userName.value, userAddress.value, userEmail.value, userPhoneNumber.value, userRole.value] = [user.name, user.address, user.email, user.phone_number, user.role];
        changeSelUser(user.id);
        console.log('user', user.id)
        console.log('selectedUser', selectedUser)
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

    function validation(infos, email){
        if(infos.findIndex((info) => { info === '' }) === -1){
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if(emailPattern.test(email)){
                return true
            } else return false
        } return false
    }

    function changeSelUser(selUser){
        selectedUser = selUser;
    }

    function saveModalShow(){
        changeSelUser(0);
        modalShow.value = true
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
                userAddress,
                userPhoneNumber,
                userEmail,
                userRole,
                userPassword,
                userConfirmPassword,
                allUsers,
                selectedUser,
                lang :langFunc,
                clearField,
                saveUser,
                editUser,
                delUser,
                saveModalShow
            }
        }
    });
    app.mount('#user_container');
}