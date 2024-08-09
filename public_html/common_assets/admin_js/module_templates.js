
$(document).ready(function () {
    flag = 0

    let modal = $('.three_modal_wrapper');

    $('.close_three_modal').click(function () {
        modal.fadeOut();
    });

    $('#run_configurator').click(function (e) {
        e.preventDefault();
        modal.fadeIn();

        if(flag === 0){
            init_three_test('three_viewport' );
            flag = 1;
        }
    });


    $('#sub_form').submit(function (e) {

        var json = $('#template').val();

        if(json.charAt(0) !== '{'){
            json = '{' + json + '}';
        }

        try{
            var data = JSON.parse(json);

            if(data.params === undefined){
                data = {params:data}
            }

            // if(data.params.variants !== undefined){
            //     delete data.params.variants;
            // }


            $('#template').val(JSON.stringify(data));
            return true;
        } catch (e) {
            alert('ERROR')
        }

    })

});