<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['accessories'] ?></h2>
        <!--        <ol class="breadcrumb">-->
        <!--            <li class="breadcrumb-item">-->
        <!--                <a href="index.html">Home</a>-->
        <!--            </li>-->
        <!--            <li class="breadcrumb-item">-->
        <!--                <a>Tables</a>-->
        <!--            </li>-->
        <!--            <li class="breadcrumb-item active">-->
        <!--                <strong>Code Editor</strong>-->
        <!--            </li>-->
        <!--        </ol>-->
    </div>
    <div class="col-lg-2 pt-4">

    </div>
</div>

<div class="wrapper wrapper-content  animated fadeInRight">


    <form style="display: none;" id="csv_form" enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="<?php echo site_url('accessories/save_data/') ?>">
        <div class="ibox corpus_panel">

            <div class="ibox-content">
                <div class="form-group">
                    <a href="https://planplace.ru/common_assets/blank.csv"><?php echo $lang_arr['download_blank_csv']?></a>
                    <br>
                    <a href="https://planplace.ru/common_assets/example.csv"><?php echo $lang_arr['download_demo_csv']?></a>
                </div>
                <div class="form-group">
                    <label for="csv_file"><?php echo $lang_arr['csv_file']?></label>
                    <input type="file" name="csv_file" id="csv_file" accept=".csv">
                </div>

                <div class="form-group row">
                    <div class="col-sm-4 col-sm-offset-2">
                        <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save']?></button>
                    </div>
                </div>

            </div>
        </div>

    </form>

    <div class="ibox">
        <div class="ibox-title">
            <h5><?php echo $lang_arr['accessories_auto_add']?></h5>
        </div>
        <div class="ibox-content">
            <div class="form-group row">
                <div class="col-4">
	                <?php echo $lang_arr['hinges']?>
                </div>
                <div class="col-4">
                    <label class="switch">
                        <input <?php if($types['door'] == 1) echo 'checked'?> class="autocount" data-type="door" type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-4">
	                <?php echo $lang_arr['hinges_lockers']?>
                </div>
                <div class="col-4">
                    <label class="switch">
                        <input <?php if($types['locker'] == 1) echo 'checked'?> class="autocount" data-type="locker" type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-4">
	                <?php echo $lang_arr['hinges_simple_top']?>
                </div>
                <div class="col-4">
                    <label class="switch">
                        <input <?php if($types['simple_top'] == 1) echo 'checked'?> class="autocount" data-type="simple_top" type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-4">
	                <?php echo $lang_arr['hinges_double_top']?>
                </div>
                <div class="col-4">
                    <label class="switch">
                        <input <?php if($types['double_top'] == 1) echo 'checked'?> class="autocount" data-type="double_top" type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-4">
	                <?php echo $lang_arr['hinges_front_top']?>
                </div>
                <div class="col-4">
                    <label class="switch">
                        <input <?php if($types['front_top'] == 1) echo 'checked'?> class="autocount" data-type="front_top" type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>


    <div class="ibox">
        <div class="ibox-title">
            <h5><?php echo $lang_arr['accessories_list']?></h5>
        </div>
        <div class="ibox-content">

            <div class="row align-items-center mb-3">
                <div class="col-6">
                    <button id="add_new" class="btn btn-primary btn-sm" type="button"><?php echo $lang_arr['add']?></button>
                    <button id="export_csv" class="btn btn-primary btn-sm" type="button"><?php echo $lang_arr['export_csv']?></button>
                    <button id="import_csv" data-toggle="modal" data-target="#csv_modal" class="btn btn-primary btn-sm" type="button"><?php echo $lang_arr['import_csv']?></button>
                    <button id="clear_db" class="btn btn-danger btn-sm " type="button"><?php echo $lang_arr['clear_db']?></button>

                </div>
                <div class="col-6 justify-content-end">
                    <div id="paging-ui-container"></div>
                </div>
            </div>


            <div class="row">
                <div class="col-12">

                    <table class="footable table table-bordered table-stripped toggle-arrow-tiny" data-paging="true" data-sorting="true" data-paging-container="#paging-ui-container" data-paging-size="50">
                        <thead>
                        <tr>
                            <td data-name="id" data-type="number" data-sorted="true" data-direction="DESC">Id</td>
                            <td data-name="code"><?php echo $lang_arr['code']?></td>
                            <td data-name="name"><?php echo $lang_arr['name']?></td>
                            <td data-name="category"><?php echo $lang_arr['category']?></td>
                            <td data-name="price" data-type="number"><?php echo $lang_arr['price']?></td>
                            <td data-name="type" data-formatter="type_format"><?php echo $lang_arr['type']?></td>
                            <td data-filterable="false" data-name="default" data-formatter="def_format"><?php echo $lang_arr['default']?></td>
                            <td  data-name="tags" data-breakpoints="all"><?php echo $lang_arr['tags']?></td>
                            <td data-filterable="false" data-name="images" data-breakpoints="all"><?php echo $lang_arr['images']?></td>
                            <td data-filterable="false" data-name="description" data-breakpoints="all"><?php echo $lang_arr['description']?></td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($items as $item):?>
                            <tr>
                                <td><?php echo $item['id']?></td>
                                <td><?php echo $item['code']?></td>
                                <td><?php echo $item['name']?></td>
                                <td><?php echo $item['category']?></td>
                                <td><?php echo $item['price']?></td>
                                <td data-value="<?php echo $item['type']?>"></td>
                                <td data-value="<?php echo $item['default']?>"></td>
                                <td><?php echo $item['tags']?></td>
                                <td>
                                    <?php
                                        $imgs = explode(',',$item['images'])
                                    ?>

                                    <?php foreach ($imgs as $img):?>
                                    <img class="col-2" src="<?php echo $img?>">
                                    <?php endforeach;?>

                                </td>
                                <td><?php echo htmlspecialchars_decode($item['description'])?></td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>

                    </table>

                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <button type="button" id="save_acc_data" class="btn btn-primary" ><?php echo $lang_arr['save']?></button>
                </div>
            </div>

        </div>
    </div>



</div>


<div class="modal inmodal" id="categories_modal">
    <form id="editor">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 id="editor_title" class="modal-title"><?php echo $lang_arr['categories']?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><?php echo $lang_arr['code']?></label>
                    <div class="col-sm-10"><input id="item_code" type="text" class="form-control"></div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><?php echo $lang_arr['name']?></label>
                    <div class="col-sm-10"><input id="item_name" type="text" class="form-control" required></div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><?php echo $lang_arr['category']?></label>
                    <div class="col-sm-10"><input id="item_category" type="text" class="form-control"></div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><?php echo $lang_arr['price']?></label>
                    <div class="col-sm-10"><input id="item_price" type="text" class="form-control"></div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><?php echo $lang_arr['type']?></label>
                    <div class="col-sm-10">
                        <select class="form-control" id="item_type">
                            <option value="common"><?php echo $lang_arr['common']?></option>
                            <option value="door"><?php echo $lang_arr['hinges']?></option>
                            <option value="locker"><?php echo $lang_arr['hinges_lockers']?></option>
                            <option value="simple_top"><?php echo $lang_arr['hinges_simple_top']?></option>
                            <option value="double_top"><?php echo $lang_arr['hinges_double_top']?></option>
                            <option value="front_top"><?php echo $lang_arr['hinges_front_top']?></option>
                        </select>

                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><?php echo $lang_arr['tags']?></label>
                    <div class="col-sm-10"><input id="item_tags" type="text" class="form-control"></div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><?php echo $lang_arr['images']?></label>
                    <div class="col-sm-10">
                        <textarea rows="5" id="item_images" type="text" class="form-control"></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label"><?php echo $lang_arr['description']?></label>
                    <div class="col-sm-10">
                        <textarea rows="5" id="item_description" type="text" class="form-control"></textarea>
                    </div>
                </div>

                <input type="hidden" id="item_default" value="0">

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo $lang_arr['cancel']?></button>
                <button type="submit" class="btn btn-primary" ><?php echo $lang_arr['save']?></button>
            </div>
        </div>
    </div>
    </form>

</div>

<div class="modal inmodal" id="csv_modal">
    <form id="csv_import">

    <div class="modal-dialog">
        <div class="modal-content animated ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <i class="fa fa-laptop modal-icon"></i>
                <h4 class="modal-title"><?php echo $lang_arr['import_csv']?></h4>
            </div>
            <div class="modal-body">
                <div class="form-group"><label><?php echo $lang_arr['choose_file']?></label> <input id="modal_csv_file" name="modal_csv_file" type="file" accept=".csv"  class="form-control"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo $lang_arr['cancel']?></button>
                <button type="submit" class="btn btn-primary" ><?php echo $lang_arr['save']?></button>
            </div>
        </div>
    </div>
</form>
</div>




<!--<script src="/common_assets/libs/vue.min.js"></script>-->
<!--<script src="/common_assets/admin_js/vue/kitchen/accessories.js?--><?php //echo md5(date('m-d-Y-His A e'));?><!--"></script>-->
<!---->
<!---->


<!--<input type="file" id="ttt" accept=".csv">-->


<style>
    #paging-ui-container span.label{
        display: none;
    }
    .footable-paging-external ul.pagination, table.footable>tfoot>tr.footable-paging>td>ul.pagination{
        margin-top: 0!important;
        float: right;
    }


</style>

<script>

    // let cat_tree;

    const ac_types = {
        'common': '<?php echo $lang_arr["common"]?>',
        'door': '<?php echo $lang_arr["hinges"]?>',
        'locker': '<?php echo $lang_arr["hinges_lockers"]?>',
        'simple_top': '<?php echo $lang_arr["hinges_simple_top"]?>',
        'double_top': '<?php echo $lang_arr["hinges_double_top"]?>',
        'front_top': '<?php echo $lang_arr["hinges_front_top"]?>',
    };

    function type_format(value, options, rowData){
        return ac_types[value];
    }

    function def_format(value,options, rowData) {

        // console.log(rowData)

        if(rowData.type === 'common') return '';

        let checked = '';
        if(value == 1) checked = 'checked';


        let label = $('<label class="switch"></label>')


        console.log(checked)

        let checkbox =  $('<input class="item_default" data-id="'+ rowData.id +'" data-type="'+ rowData.type +'"  type="checkbox" '+ checked +'> <span class="slider round"></span>')

        checkbox.change(function (e) {
            e.preventDefault();


            console.log($(this).prop('checked'))
            //
            // if(!$(this).prop('checked')){
            //     $(this).prop('checked', true);
            //     return;
            // }


            let scope = $(this);
            let sc = this;
            // console.log(123)

            let id = $(this).attr('data-id');
            let type = $(this).attr('data-type');

            $.ajax({
                url: $('#ajax_base_url').val() + '/accessories/set_default/' + id + '/' + type + '/' + sc.checked,
                type: 'get'
            }).done(function (msg) {

                if(msg === 'no'){
                    sc.checked = true;
                } else {
                    $('.item_default[data-type="'+ type +'"]').each(function () {
                        if( $(this).attr('data-id') !== id ){
                            $(this).prop('checked', false)

                            let $row = $(this).closest('tr');


                        }

                    })
                }




            })

        });

        label.append(checkbox);

        return label;
    }

    function set_default(e, t, id, type) {


        console.log(e);

        console.log(t.value)

        console.log(this)


        return true;


    }



    $(document).ready(function () {


        $('#csv_import').submit(function (e) {
            e.preventDefault();

            // data:  new FormData(this),
            $.ajax({
                url: $('#ajax_base_url').val() + '/accessories/import_csv',
                type: 'post',
                data: new FormData(this),
                contentType: false,
                processData: false
            }).done(function (msg) {
                location.reload();
                // console.log(msg)
            })

        })


        // $('#categories_modal').on('shown.bs.modal', function() {
        //     $('#item_description').summernote({
        //         height: 300,
        //     });
        // })

        $('.autocount').change(function () {
            let scope = $(this);
            let val = $(this).prop('checked') ? 1 : 0;
            let type = $(this).attr('data-type');

            $.ajax({
                url: $('#ajax_base_url').val() + '/accessories/set_type_selection/' + type + '/' + val,
                type: 'get'
            }).done(function (msg) {

                if(msg == 'no_items'){
                    toastr.error('<?php echo $lang_arr["error_no_accessories_item_with_this_type"]?>')
                    scope.prop('checked', false)
                }


            })

        });

        var $modal = $('#categories_modal'),
            $editor = $('#editor'),
            $editorTitle = $('#editor_title')


        ft = FooTable.init('.footable',{


            "state": {
                "enabled": false,
                "sorting": true,
                "paging": true,
                "filtering": true
            },
            filtering:{
                enabled: false,
                "placeholder": "<?php echo $lang_arr['search']?>"
            },
            editing: {
                enabled: true,
                alwaysShow: true,
                allowAdd: false,
                allowEdit: true,

                addRow: function(){
                    $modal.removeData('row'); // remove any previous row data
                    $editor[0].reset(); // reset the form to clear any previous row data
                    $editorTitle.text('<?php echo $lang_arr["add"]?>'); // set the modal title
                    $modal.modal('show'); // display the modal
                },
                editRow: function (row) {

                    let imgs = '';







                    var values = row.val();



                    if(row.$el.find('img').length){
                        row.$el.find('img').each(function () {
                            let str = $(this).attr('src') + ',';
                            imgs +=str;
                        });



                        imgs = imgs.substring(0, imgs.length - 1);
                    } else {
                        let arr = $(values.images);

                        for (let i = 0; i < arr.length; i++){

                            if(arr[i].src && arr[i].src != undefined){
                                let str = arr[i].src + ',';
                                imgs +=str;
                            }

                        }




                        imgs = imgs.substring(0, imgs.length - 1);
                    }



                    $editor.find('#item_code').val(values.code);
                    $editor.find('#item_name').val(values.name);
                    $editor.find('#item_category').val(values.category);
                    $editor.find('#item_price').val(values.price);
                    $editor.find('#item_type').val(values.type);
                    $editor.find('#item_tags').val(values.tags);
                    $editor.find('#item_images').val(imgs);
                    $editor.find('#item_description').val(values.description);
                    $editor.find('#item_default').val(values.default);

                    $modal.data('row', row);
                    $editorTitle.text('<?php echo $lang_arr["edit"]?>');
                    $modal.modal('show');
                },
                deleteRow: function(row) {

                    swal({
                        title: "<?php echo $lang_arr['are_u_sure']?>",
                        text: $('#delete_confirm_message').html(),
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        cancelButtonText: "<?php echo $lang_arr['no']?>",
                        confirmButtonText: "<?php echo $lang_arr['yes']?>",
                        closeOnConfirm: true
                    }, function () {
                        $.ajax({
                            url: $('#ajax_base_url').val() + '/accessories/remove_item',
                            type: 'post',
                            data: {id:row.value.id}
                        }).done(function (msg) {
                            row.delete();
                        })
                    });


                }
            }
        });


        $('#add_new').click(function (e) {
            e.preventDefault();

            $modal.removeData('row'); // remove any previous row data
            $editor[0].reset(); // reset the form to clear any previous row data
            $editorTitle.text('<?php echo $lang_arr["add"]?>'); // set the modal title
            $modal.modal('show'); // display the modal
        })

        $('#clear_db').click(function () {
            swal({
                title: "<?php echo $lang_arr['are_u_sure']?>",
                text: $('#delete_confirm_message').html(),
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "<?php echo $lang_arr['no']?>",
                confirmButtonText: "<?php echo $lang_arr['yes']?>",
                closeOnConfirm: true
            }, function () {
                $.ajax({
                    url: $('#ajax_base_url').val() + '/accessories/clear_db',
                    type: 'post',
                    data: {true:true}
                }).done(function (msg) {
                    location.reload();
                })
            });
        })


        $editor.submit(function (e) {
            e.preventDefault();
            var row = $modal.data('row');
            let values = {
                code: $editor.find('#item_code').val(),
                name: $editor.find('#item_name').val(),
                category: $editor.find('#item_category').val(),
                price: $editor.find('#item_price').val(),
                type: $editor.find('#item_type').val(),
                tags: $editor.find('#item_tags').val(),
                default: parseInt($editor.find('#item_default').val()),
                images: $editor.find('#item_images').val(),
                description: $editor.find('#item_description').val(),
            };

            if (row instanceof FooTable.Row){

                let id = row.val().id

                $.ajax({
                    url: $('#ajax_base_url').val() + '/accessories/update_item',
                    type: 'post',
                    data: {
                        id: id,
                        data: values
                    }
                }).done(function (msg) {
                    let arr = values.images.split(',')
                    let res = ''
                    for (let i = 0; i < arr.length; i++){
                        res+='<img class="col-2" src="'+arr[i]+'">'
                    }
                    values.images = res;
                    row.val(values);
                    $('#categories_modal').modal('hide')
                })
            } else {

                $.ajax({
                    url: $('#ajax_base_url').val() + '/accessories/add_item',
                    type: 'post',
                    data: {data: values}
                }).done(function (msg) {
                    let data = JSON.parse(msg);

                    values.id = parseInt(data.id);
                    values.default = parseInt(data.default);
                    ft.rows.add(values);
                    $('#categories_modal').modal('hide')
                })
            }



        })





        $('#save_acc_data').click(function () {

            $.ajax({
                url: $('#ajax_base_url').val() + '/accessories/get_data_ajax/',
                type: 'get'
            }).done(function (msg) {
                let data = JSON.parse(msg)
                console.log(msg)
                toastr.success("<?php echo $lang_arr['success']?>")

            })


        })

        $('#export_csv').click(function () {

            window.open($('#ajax_base_url').val() + '/accessories/export_csv/')

            // $.ajax({
            //     url: $('#ajax_base_url').val() + '/accessories/export_csv/',
            //     type: 'get'
            // }).done(function (msg) {
            //     console.log(msg)
            // })

        });



        $('#csv_file').change(function (evt) {
            var files = evt.target.files; // FileList object
        });



        $('#csv_form').submit(function (e) {
            e.preventDefault();


            if(confirm('<?php echo $lang_arr['upload_csv_data']?>')){

                var file = $('#csv_file').prop("files")[0];

                var reader = new FileReader();
                reader.readAsText(file);
                reader.onload = function(event){
                    var csv = event.target.result;
                    var data = $.csv.toObjects(csv);

                    console.log(data)

                    var result = {
                        tree:[]
                    };

                    var categories = [];
                    var tags = [];



                    for (var i = 0; i < data.length; i++){
                        data[i].id = i;

                        data[i].price = parseFloat(data[i].replace(/\s+/g, ''))

                        categories.push(data[i].Categories);

                        var s_tags = data[i].Tags.split(",");

                        for (var t = 0; t < s_tags.length; t++){

                            tags.push(s_tags[t].trim())
                        }


                    }

                    categories = _.uniq(categories);
                    tags = _.uniq(tags);

                    for (var i = 0; i < categories.length; i++){

                        var obj = {
                            category: categories[i],
                            items:[]
                        };

                        for (var j = 0; j < data.length; j++){
                            if(data[j].Categories === categories[i]){
                                obj.items.push(data[j])
                            }
                        }

                        result.tree.push(obj)
                    }

                    result.tags = tags.sort();
                    console.log(result)


                    $.ajax({
                        url: $('#csv_form').attr('action'),
                        type: 'post',
                        data: {data:JSON.stringify(result)},
                        success: function(data){
                        }
                    });

                };



            }
        })

    })
</script>