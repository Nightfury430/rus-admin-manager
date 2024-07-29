function interface_init_callback() {
    if(window.location.href.indexOf('no_price') > -1){
        constructor_settings.price_enabled = 0;
        $('.k_sum').hide();
    }
}


