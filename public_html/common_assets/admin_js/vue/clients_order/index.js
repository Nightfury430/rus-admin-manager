$('.get_bazis_proj').click(async function (e) {

    $('#blocker').css('display', 'flex');

    e.preventDefault();
    let url = $(this).attr('href');
    let name = $(this).attr('data-name');
    let account = $(this).attr('data-account');
    let json = await promise_request(url)
    console.log(json);
    let data = new FormData();
    data.append('data', JSON.stringify(json))
    data.append('key', 'as239ks')
    data.append('name', name)
    data.append('account', account)

    let p_url = $('#ajax_base_url').val() + '/clients_orders/get_bazis';

    let res = await promise_request_post(p_url, data);
    console.log(res)

    $('#blocker').css('display', 'none');
    var link = document.createElement('a');
    link.style.display = 'none';
    document.body.appendChild(link);
    link.href = res.url;
    link.download = res.filename;
    link.click();
    link.remove();

})


function save(blob, filename) {
    var link = document.createElement('a');
    link.style.display = 'none';
    document.body.appendChild(link);
    link.href = URL.createObjectURL(blob);
    link.download = filename;
    link.click();
    link.remove();
}

function saveArrayBuffer(buffer, filename) {
    save(new Blob([buffer], {type: 'application/octet-stream'}), filename);
}

$('.delete_button').click(function (e) {
    e.preventDefault();

    let scope = $(this);

    Swal.fire({
        title: "<?php echo $lang_arr['are_u_sure']?>",
        text: $('#delete_confirm_message').html(),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        cancelButtonText: "<?php echo $lang_arr['no']?>",
        confirmButtonText: "<?php echo $lang_arr['yes']?>",
    }).then(() =>{
        window.location.href = scope.attr('href');
    });
})