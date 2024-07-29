let settings = {
    selector: '#email_template',
    plugins: 'advlist autolink charmap code directionality hr image legacyoutput link lists nonbreaking paste preview searchreplace table visualblocks visualchars',

    toolbar: 'undo redo | styleselect | fontsizeselect | bold italic underline | forecolor backcolor | alignleft aligncenter alignright alignjustify | numlist bullist | menuDateButton |  link image hr | table | code |  charmap | outdent indent | ltr rtl',
    menubar: 'file edit view insert format tools table',
    contextmenu: "link image imagetools table spellchecker",

    table_toolbar: "tableprops  tablerowprops, tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol | tabledelete",
    toolbar_sticky: true,
    toolbar_mode: 'sliding',

    language : 'ru',
    paste_data_images: true,
    paste_as_text: true,
    image_advtab: true,
    relative_urls : false,
    remove_script_host: false,
    min_height: 400,

    force_p_newlines : false,
    force_br_newlines : true,
    convert_newlines_to_brs : false,
    remove_linebreaks : false,
    // forced_root_block : false,

    file_picker_types: 'image',

    setup: function (editor) {
        editor.ui.registry.addMenuButton('menuDateButton', {
            icon: 'code-sample',
            tooltip: document.getElementById('email_lang_insert_email_tag').innerText,
            // text: 'Теги автозамены',
            fetch: function (callback) {
                var items = [
                    {
                        type: 'menuitem',
                        text: document.getElementById('email_lang_order_number').innerText,
                        onAction: function (_) {
                            editor.insertContent('{{order_number}}');
                        }
                    },
                    {
                        type: 'menuitem',
                        text: document.getElementById('email_lang_client_name').innerText,
                        onAction: function (_) {
                            editor.insertContent('{{name}}');
                        }
                    },
                    {
                        type: 'menuitem',
                        text: document.getElementById('email_lang_client_email').innerText,
                        onAction: function (_) {
                            editor.insertContent('{{email}}');
                        }
                    },
                    {
                        type: 'menuitem',
                        text: document.getElementById('email_lang_client_phone').innerText,
                        onAction: function (_) {
                            editor.insertContent('{{phone}}');
                        }
                    },
                    {
                        type: 'menuitem',
                        text: document.getElementById('email_lang_client_comments').innerText,
                        onAction: function (_) {
                            editor.insertContent('{{comments}}');
                        }
                    },
                    {
                        type: 'menuitem',
                        text: document.getElementById('email_lang_price').innerText,
                        onAction: function (_) {
                            editor.insertContent('{{price}}');
                        }
                    },
                    {
                        type: 'menuitem',
                        text: document.getElementById('email_lang_project_filename').innerText,
                        onAction: function (_) {
                            editor.insertContent('{{project_filename}}');
                        }
                    },
                    {
                        type: 'menuitem',
                        text: document.getElementById('email_lang_logo').innerText,
                        onAction: function (_) {
                            editor.insertContent('<img src="'+ document.getElementById('email_logo_url').innerText +'">');
                        }
                    },
                ];
                callback(items);
            }
        });
    },

    images_dataimg_filter: function(img) {
        return img.hasAttribute('internal-blob');
    },

    file_picker_callback: function (cb, value, meta) {
        let input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');

        input.onchange = function () {
            let file = this.files[0];

            let reader = new FileReader();
            reader.onload = function () {

                if(file.size > 102400 * 1.5){
                    alert(document.getElementById('email_lang_file_too_big_kb').innerText)
                } else {

                    let img = new Image();

                    img.src = reader.result;

                    img.onload = function() {

                        if(img.height > 800 || img.width > 800){
                            alert(document.getElementById('email_lang_file_too_big').innerText)
                        } else {
                            cb(reader.result, { title: file.name });
                        }
                    }
                }

            };
            reader.readAsDataURL(file);
        };

        input.click();
    }

};

let settings_client = Object.assign({}, settings);
settings_client.selector = '#email_template_client';


let settings_heading = {
    selector: '#email_template_heading',
    plugins: '',

    toolbar: 'undo redo | menuDateButton | ltr rtl',

    menubar: false,
    max_height: 120,
    resize: false,


    language : 'ru',
    // paste_data_images: true,
    paste_as_text: true,
    setup: function (editor) {
        editor.ui.registry.addMenuButton('menuDateButton', {
            icon: 'code-sample',
            tooltip: document.getElementById('email_lang_insert_email_tag').innerText,
            // text: 'Теги автозамены',
            fetch: function (callback) {
                var items = [
                    {
                        type: 'menuitem',
                        text: document.getElementById('email_lang_order_number').innerText,
                        onAction: function (_) {
                            editor.insertContent('{{order_number}}');
                        }
                    },
                    {
                        type: 'menuitem',
                        text: document.getElementById('email_lang_client_name').innerText,
                        onAction: function (_) {
                            editor.insertContent('{{name}}');
                        }
                    },
                    {
                        type: 'menuitem',
                        text: document.getElementById('email_lang_client_email').innerText,
                        onAction: function (_) {
                            editor.insertContent('{{email}}');
                        }
                    },
                    {
                        type: 'menuitem',
                        text: document.getElementById('email_lang_client_phone').innerText,
                        onAction: function (_) {
                            editor.insertContent('{{phone}}');
                        }
                    }
                ];
                callback(items);
            }
        });
    }
};

let settings_heading_client = Object.assign({}, settings_heading);
settings_heading_client.selector = '#email_template_heading_client';



function reinit_tinymce(fresh, def) {

    if(!fresh) fresh = false;
    if(!def) def = false;

    let data;


    if(!fresh){
        tinymce.editors['email_template'].remove();
        tinymce.editors['email_template_heading'].remove();

        tinymce.editors['email_template_client'].remove();
        tinymce.editors['email_template_heading_client'].remove();

        data = app.get_data();
    } else {
        data = JSON.parse(document.getElementById('settings_custom_form_data').innerText)
    }

    if(def){
        data = [
            {
                label: document.getElementById('email_lang_client_name').innerText,
                id: 'name'
            },
            {
                label: document.getElementById('email_lang_client_email').innerText,
                id: 'email'
            },
            {
                label: document.getElementById('email_lang_client_phone').innerText,
                id: 'phone'
            },
            {
                label: document.getElementById('email_lang_client_comments').innerText,
                id: 'comments'
            }
        ]
    }





    settings.setup = function (editor) {
        editor.ui.registry.addMenuButton('menuDateButton', {
            icon: 'code-sample',
            tooltip: document.getElementById('email_lang_insert_email_tag').innerText,
            fetch: function (callback) {

                let items = [];

                items.push({
                    type: 'menuitem',
                    text: document.getElementById('email_lang_order_number').innerText,
                    onAction: function (_) {
                        editor.insertContent('{{order_number}}');
                    }
                });

                for (let i = 0; i < data.length; i++){
                    if(data.type != 'file'){
                        items.push({
                            type: 'menuitem',
                            text: data[i].label,
                            onAction: function (_) {
                                editor.insertContent('{{' + data[i].id +'}}');
                            }
                        })
                    }
                }

                items.push(

                    {
                        type: 'menuitem',
                        text: document.getElementById('email_lang_price').innerText,
                        onAction: function (_) {
                            editor.insertContent('{{price}}');
                        }
                    },
                    {
                        type: 'menuitem',
                        text: document.getElementById('email_lang_project_filename').innerText,
                        onAction: function (_) {
                            editor.insertContent('{{project_filename}}');
                        }
                    },
                    {
                        type: 'menuitem',
                        text: document.getElementById('email_lang_logo').innerText,
                        onAction: function (_) {
                            editor.insertContent('<img src="'+ document.getElementById('email_logo_url').innerText +'">');
                        }
                    });

                callback(items);
            }
        });
    };

    settings_client = Object.assign({}, settings);
    settings_client.selector = '#email_template_client';


    settings_heading.setup = function (editor) {
        editor.ui.registry.addMenuButton('menuDateButton', {
            icon: 'code-sample',
            tooltip: document.getElementById('email_lang_insert_email_tag').innerText,
            fetch: function (callback) {

               let items = [];

                items.push({
                    type: 'menuitem',
                    text: document.getElementById('email_lang_order_number').innerText,
                    onAction: function (_) {
                        editor.insertContent('{{order_number}}');
                    }
                });

               for (let i = 0; i < data.length; i++){
                   if(data[i].type != 'file'){
                       items.push({
                           type: 'menuitem',
                           text: data[i].label,
                           onAction: function (_) {
                               editor.insertContent('{{' + data[i].id +'}}');
                           }
                       })
                   }

               }



                callback(items);
            }
        });
    };

    settings_heading_client = Object.assign({}, settings_heading);
    settings_heading_client.selector = '#email_template_heading_client';



    tinymce.init(settings);
    tinymce.init(settings_heading);
    tinymce.init(settings_client);
    tinymce.init(settings_heading_client);

}

let updated_flag = 0;
let turn_off_flag = 0;

$(document).ready(function(){
    app = new Vue({
        el: '#sub_form',
        components:{
            draggable: vuedraggable
        },
        data: {
            use_custom_form: document.getElementById('settings_use_custom_form').innerText,
            use_email_template: document.getElementById('settings_use_email_template').innerText,
            use_email_template_client: document.getElementById('settings_use_email_template_client').innerText,
            send_to_client: document.getElementById('settings_send_to_client').innerText,
            fields:[],
            attached_files:{
                designer: {
                    screen: 1,
                    project: 1,
                    pdf: 1
                },
                client:{
                    screen: 1,
                    project: 1,
                    pdf: 0
                }
            },
            drag: false,
            errors:[],
        },
        mounted(){

            let scope = this;

            if(document.getElementById('settings_attached_files').innerText != ''){
                this.attached_files = JSON.parse(document.getElementById('settings_attached_files').innerText);
            }

            if(document.getElementById('settings_custom_form_data').innerText != ''){
                this.fields = JSON.parse(document.getElementById('settings_custom_form_data').innerText);
            }


            if(scope.use_custom_form == 1){
                reinit_tinymce(true);
            } else {
                tinymce.init(settings);
                tinymce.init(settings_heading);
                tinymce.init(settings_client);
                tinymce.init(settings_heading_client);
            }

            $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function() {

                if(turn_off_flag == 1 && updated_flag == 1){

                    turn_off_flag = 0;
                    updated_flag = 0;

                    reinit_tinymce(false, true);

                } else if (scope.use_custom_form == 1 && updated_flag == 1){

                    updated_flag = 0;
                    reinit_tinymce();

                }






            });
        },
        computed:{
            dragOptions() {
                return {
                    animation: 500,
                    group: "description",
                    disabled: false,
                    ghostClass: "ghost"
                };
            }
        },
        methods: {
            get_data: function () {
                return JSON.parse(JSON.stringify(this.fields))
            },

            resize_viewport(){
                setTimeout(function () {
                    resize_viewport();
                },10)
            },

            add_field: function () {
                this.fields.push({
                    id: '',
                    type: 'text',
                    compatibility: "",
                    label: '',
                    values: [],
                    rows: 5,
                    required: 0,
                    required_check_type: 'non_empty',
                    select_check_val: ''
                });

                updated_flag = 1;
            },

            remove_field: function (index) {

                let scope = this;

                swal({
                    title: document.getElementById('are_u_sure_message').innerHTML,
                    text: $('#delete_confirm_message').html(),
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: document.getElementById('lang_no_message').innerHTML,
                    confirmButtonText: document.getElementById('lang_yes_message').innerHTML,
                    closeOnConfirm: true
                }, function () {
                    scope.fields.splice(index, 1)
                    updated_flag = 1;
                });

            },



            add_value: function (item) {
                item.values.push({
                    text: '',
                    value: ''
                });

                updated_flag = 1;
            },

            remove_value: function (item, index) {
                let scope = this;

                swal({
                    title: document.getElementById('are_u_sure_message').innerHTML,
                    text: $('#delete_confirm_message').html(),
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: document.getElementById('lang_no_message').innerHTML,
                    confirmButtonText: document.getElementById('lang_yes_message').innerHTML,
                    closeOnConfirm: true
                }, function () {
                    item.values.splice(index, 1)
                    updated_flag = 1;
                });
            },

            load_values: function () {

            },

            process_custom_form_fields: function () {

                if(this.use_custom_form == 1){
                    this.use_email_template = 1;
                    this.use_email_template_client = 1;

                } else {
                    turn_off_flag = 1;
                }

                updated_flag = 1;
            },

            check_custom_form: function () {
               if(this.use_custom_form == 1){
                   toastr.error(document.getElementById('email_lang_must_custom_templates_message').innerText)
               }
            },

            process_label: function (item, input) {

                item.id =  input.split(' ').join('_').toLowerCase();

            },

            process_built_in_types: function (item, type) {

            },

            on_drag({ relatedContext, draggedContext }) {
                const relatedElement = relatedContext.element;
                const draggedElement = draggedContext.element;
                return (
                    (!relatedElement || !relatedElement.fixed) && !draggedElement.fixed
                );
            },


        }
    });

    $('#designer_to_client').click(function () {
        tinymce.editors['email_template_client'].setContent( tinymce.editors['email_template'].getContent() );
        tinymce.editors['email_template_heading_client'].setContent( tinymce.editors['email_template_heading'].getContent() );
    });

    $('#client_to_designer').click(function () {
        tinymce.editors['email_template'].setContent( tinymce.editors['email_template_client'].getContent() );
        tinymce.editors['email_template_heading'].setContent( tinymce.editors['email_template_heading_client'].getContent() );
    });


    $('#sub_form').submit(function (e) {
        e.preventDefault();

        let head = '<p>' + tinymce.editors['email_template_heading'].getContent() + '</p>';
        let head_client = '<p>' + tinymce.editors['email_template_heading_client'].getContent() + '</p>';

        let message = '<p>' + tinymce.editors['email_template'].getContent() + '</p>';
        let message_client = '<p>' + tinymce.editors['email_template_client'].getContent() + '</p>';



        let errors = [];

        if(app.use_email_template == 1){

            if($(head).text().trim() == ''){
                errors.push(document.getElementById('lang_email_using_custom_template').innerText + ' ' + document.getElementById('lang_email_error_empty_heading').innerText)
            }

            if($(message).text().trim() == ''){
                errors.push(document.getElementById('lang_email_using_custom_template').innerText + ' ' + document.getElementById('lang_email_error_empty_body').innerText)
            }

        }

        if(app.use_email_template_client == 1){

            if($(head_client).text().trim() == ''){
                errors.push(document.getElementById('lang_email_using_custom_template_client').innerText + ' ' + document.getElementById('lang_email_error_empty_heading').innerText)
            }

            if($(message_client).text().trim() == ''){
                errors.push(document.getElementById('lang_email_using_custom_template_client').innerText + ' ' + document.getElementById('lang_email_error_empty_body').innerText)
            }

        }

        let file_inputs_count = 0;
        let check_email = 0;
        let name_count = 0;
        let email_count = 0;
        let phone_count = 0;
        let comments_count = 0;


        let empty_flag = 0;
        let no_fields_flag = 0;

        let check_email_required = 0;

        if(app.use_custom_form == 1){
            let dt = app.get_data();

            for (let i = 0; i < dt.length; i++){
                no_fields_flag = 1;
                if(dt[i].label == '' || dt[i].id == ''){
                    empty_flag = 1;
                }

                if(dt[i].type == 'file'){
                    file_inputs_count++
                }

                if(dt[i].compatibility == 'name'){
                    name_count++
                }

                if(dt[i].compatibility == 'email'){
                    email_count++;
                    check_email = 1;
                    check_email_required = (dt[i].required == 1 && dt[i].required_check_type == 'email');
                }

                if(dt[i].compatibility == 'phone'){
                    phone_count++
                }

                if(dt[i].compatibility == 'comments'){
                    comments_count++
                }



            }


            if(empty_flag == 1){
                errors.push(document.getElementById('lang_email_error_empty_fields').innerText)
            }

            if(no_fields_flag == 0){
                errors.push(document.getElementById('lang_email_error_no_fields').innerText)
            }

            if(file_inputs_count > 1){
                errors.push(document.getElementById('lang_email_error_only_one_file_input').innerText)
            }


            if(app.send_to_client == 1 && check_email == 0 || app.send_to_client == 1 && check_email_required == 0){
                errors.push(document.getElementById('lang_email_error_no_email_type').innerText)
            }

            if(name_count > 1){
                errors.push(document.getElementById('email_error_only_one_compatibility_type').innerText + ' ' + document.getElementById('lang_email_client_name').innerText)
            }

            if(email_count > 1){
                errors.push(document.getElementById('email_error_only_one_compatibility_type').innerText + ' ' + document.getElementById('lang_email_email').innerText)
            }

            if(phone_count > 1){
                errors.push(document.getElementById('email_error_only_one_compatibility_type').innerText + ' ' + document.getElementById('lang_email_phone').innerText)
            }

            if(comments_count > 1){
                errors.push(document.getElementById('email_error_only_one_compatibility_type').innerText + ' ' + document.getElementById('lang_email_comments').innerText)
            }



        }

        app.errors = errors;

        if(errors.length){
            $(window).scrollTop(0);
            return false;
        }




        if(app.use_custom_form == 1){

            let txt = '';

            if(name_count == 0) txt += document.getElementById('lang_email_client_name').innerText + ', ';
            if(phone_count == 0) txt += document.getElementById('lang_email_phone').innerText + ', ';
            if(comments_count == 0) txt += document.getElementById('lang_email_comments').innerText;

            if(txt.length){
                swal({
                    title: document.getElementById('lang_save_warning').innerText,
                    text: document.getElementById('lang_email_warning_no_types').innerText + ' ' + txt + ' ' + document.getElementById('lang_email_warning_no_types_end').innerText,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    cancelButtonText: document.getElementById('lang_no_message').innerText,
                    confirmButtonText: document.getElementById('lang_yes_message').innerText,
                    closeOnConfirm: true
                }, function () {
                    $.ajax({
                        url: $('#sub_form').attr('action'),
                        type: 'post',
                        data: {
                            use_email_template: app.use_email_template,
                            use_email_template_client: app.use_email_template_client,
                            email_template_heading: $(head).text(),
                            email_template_heading_client: $(head_client).text(),
                            email_template: tinymce.editors['email_template'].getContent(),
                            email_template_client: tinymce.editors['email_template_client'].getContent(),
                            use_custom_form: app.use_custom_form,
                            custom_form_data: JSON.stringify(app.fields),
                            attached_files: JSON.stringify(app.attached_files),
                            send_to_client: app.send_to_client
                        }
                    }).done(function (msg) {
                        toastr.success(document.getElementById('success_message').innerText)
                    });
                });
            } else {
                $.ajax({
                    url: $('#sub_form').attr('action'),
                    type: 'post',
                    data: {
                        use_email_template: app.use_email_template,
                        use_email_template_client: app.use_email_template_client,
                        email_template_heading: $(head).text(),
                        email_template_heading_client: $(head_client).text(),
                        email_template: tinymce.editors['email_template'].getContent(),
                        email_template_client: tinymce.editors['email_template_client'].getContent(),
                        use_custom_form: app.use_custom_form,
                        custom_form_data: JSON.stringify(app.fields),
                        attached_files: JSON.stringify(app.attached_files),
                        send_to_client: app.send_to_client
                    }
                }).done(function (msg) {
                    toastr.success(document.getElementById('success_message').innerText)
                });
            }


        } else {
            $.ajax({
                url: $('#sub_form').attr('action'),
                type: 'post',
                data: {
                    use_email_template: app.use_email_template,
                    use_email_template_client: app.use_email_template_client,
                    email_template_heading: $(head).text(),
                    email_template_heading_client: $(head_client).text(),
                    email_template: tinymce.editors['email_template'].getContent(),
                    email_template_client: tinymce.editors['email_template_client'].getContent(),
                    use_custom_form: app.use_custom_form,
                    custom_form_data: JSON.stringify(app.fields),
                    attached_files: JSON.stringify(app.attached_files),
                    send_to_client: app.send_to_client
                }
            }).done(function (msg) {
                toastr.success(document.getElementById('success_message').innerText)
            });
        }


    })

});