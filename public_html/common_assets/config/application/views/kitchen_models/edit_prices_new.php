<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['prices']?></h2>
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
    <div class="col-lg-2">

    </div>
</div>
<script src="/common_assets/libs/vue.min.js"></script>
<input type="hidden" id="set_id" value="<?php echo $id?>">

<?php $price_data = json_decode($item['price_data'])?>

<div class="form-group" style="margin-bottom: 30px;">
    <label style="font-weight: bold" for="xlsx_data"> Импорт из Excel (только xlsx) </label>
    <input class="form-control" type="file" id="xlsx_data" accept=".xlsx">
    <button type="button" id="parse_xlsx_data" class="btn btn-success btn-sm">Обработать</button>
</div>
<script src="/common_assets/libs/exceljs.min.js"></script>
<script>
    $('#parse_xlsx_data').click(function () {
        let files = $('#xlsx_data')[0].files
        if(files.length == 0){
            alert('Выберите файл');
            return;
        }

        let file = files[0];

        const wb = new ExcelJS.Workbook();
        const reader = new FileReader()

        reader.readAsArrayBuffer(file)
        reader.onload = () => {
            const buffer = reader.result;
            let result = [];

            wb.xlsx.load(buffer).then(workbook => {
                console.log(workbook, 'workbook instance')
                workbook.eachSheet((sheet, id) => {
                    sheet.eachRow((row, rowIndex) => {
                        // if(rowIndex != 1){

                        if(row.values[2] != undefined && parseFloat(row.values[3]) != 0 && !isNaN(parseFloat(row.values[3]))){
                            result.push({
                                // code: row.values[1],
                                code: row.values[2] ,
                                price: parseInt(row.values[3])
                            })
                        }


                        // }
                    })
                })
                console.log(result)


                for (let i = 0; i < price_data2.length; i++){
                    let fac = price_data2[i];

                    for (let m = 0; m < fac.modules.length; m++){
                        let variants = fac.modules[m].variants;

                        for (let v = 0; v < variants.length; v++){

                            for (let r = 0; r < result.length; r++){
                                if(variants[v].code == result[r].code) variants[v].price = result[r].price;
                            }

                        }

                    }

                }


            })


        }

    })
</script>

<script>

    let app = null;
    let data = null;
    let lang = null;
    let controller_name = null;
    let base_url = null;
    let acc_url = null;
    let price_data = null;
    let modules = null;

    document.addEventListener('DOMContentLoaded', async function () {

        base_url = document.getElementById('ajax_base_url').value;
        acc_url = document.getElementById('acc_base_url').value;
        set_id = document.getElementById('set_id').value;

        let data_url = base_url + '/kitchen_models/get_prices_data/' + set_id



        data = await promise_request(data_url);

        console.log(data)

        modules = data.modules;
        facades = data.facades;
        if(data.item.price_data != null) price_data = JSON.parse(data.item.price_data);

        facades_hash = {};

        for (let i = 0; i < facades.length; i++){
            facades_hash[facades[i].id] = facades[i];
        }

        categories_hash = {};

        for (let i = 0; i < data.materials.length; i++){
            categories_hash[data.materials[i].id] = data.materials[i];
        }

        modules_hash = {};
        for (let i = 0; i < modules.length; i++){
            modules_hash[modules[i].id] = modules[i];
        }


        if(price_data != null){

        } else {


            // for (let i = 0; i < modules.length; i++){
            //     let module = modules[i];
            //     console.log(module)
            //
            //     module.variants = JSON.parse(module.variants);
            //     let variants = module.variants;
            //
            //     for (let v = 0; v < module.variants.length; v++){
            //
            //     }
            //
            //
            // }

        }


        price_data2 = [];

        for (let f = 0; f < facades.length; f++){
            let facade = facades[f];


            let obj = {
                id: facade.id,
                modules: []
            }


            for (let m = 0; m < modules.length; m++){
                let module = modules[m];
                if(typeof module.params != 'object')
                    module.params = JSON.parse(module.params)
                let variants = copy_object(module.params.params.variants);

                let m_obj = {
                    id: module.id,
                    variants:[]
                }

                for (let v = 0; v < variants.length; v++){

                    let variant = variants[v];

                    m_obj.variants.push({
                        name: variant.name,
                        code: 'MN ' + variant.code.replace('*', 'x') + ' ' + facade.name,
                        // code: variant.code + '_' + facade.category,
                        price: 0,
                        width: variant.width,
                        height: variant.height,
                        depth: variant.depth,
                    })

                }

                obj.modules.push(m_obj);

            }

            price_data2.push(obj)

        }

        init_vue();




    })

    function init_vue() {
        app = new Vue({
            el: '#sub_form',
            data: {
                price_data: null,
                active_tab: 0,
                temp: 1,
            },
            created: function () {
                Vue.set(this, 'price_data', price_data2)
            },
            computed:{

            },
            mounted: function () {

            },
            methods: {
                change_tab: function(index){
                    let scope = this;
                    this.temp = 0;
                    this.active_tab = index;
                    setTimeout(function () {
                        scope.temp = 1;
                    },500)

                },
                correct_url: function(path){
                    if(path.indexOf('common_assets') > -1){
                        return path
                    } else {
                        return acc_url + path;
                    }
                },
                get_data: function () {
                    return copy_object(this.price_data)
                },
                set_data: function (data) {
                    Vue.set(this, 'price_data', data)
                }
            }
        });

    }

</script>

<!--<input type="hidden" id="facades_data_pr" value="'--><?php //echo json_encode($facades)?><!--'">-->



<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-content">
                    <div id="sub_form">

                        <div class="tabs-container">
                            <div class="tabs-left">

                                <ul style="width: 200px" class="nav nav-tabs tabs-inner">
                                    <li @click=change_tab(name) v-for="(item, name) in price_data">
                                        <a v-bind:class="{ active: active_tab == name }" class="nav-link">
                                            <div>
                                                <img style="max-width: 50px; height: auto" :src="correct_url(facades_hash[item.id].icon)" alt="">
                                            </div>
                                            <div>
                                                {{facades_hash[item.id].name}} {{categories_hash[facades_hash[item.id].category].name}}
                                            </div>
                                        </a>
                                    </li>

                                </ul>
                            </div>

                            <div style="margin-left: 200px; border: 1px solid #e7eaec" class="tab-content">
                                <div v-bind:class="{ active: active_tab == name, temp: temp == 0 }" v-if="active_tab == name" v-for="(item, name) in price_data" class="tab-pane temp1">

                                    <div v-for="(module, index) in item.modules" class="row pb-5">
                                        <div class="col-sm-2">
                                            <img style="max-width: 100px; height: auto" :src="correct_url(modules_hash[module.id].icon)" alt="">
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="row " v-for="(variant, ind) in module.variants">
                                                <div class="col-sm-6 d-flex align-center pt-3">
                                                    {{variant.name}} {{variant.width}}x{{variant.height}}x{{variant.depth}}
                                                </div>
                                                <div class="col-sm-3 ">
                                                    <span>артикул</span>
                                                    <input class="form-control" type="text" v-model="variant.code">
                                                </div>
                                                <div class="col-sm-3 ">
                                                    <span>цена, р.</span>
                                                    <input class="form-control" type="text" v-model="variant.price">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php if (1==0):?>

    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-content">

                        <div class="sk-spinner sk-spinner-wave">
                            <div class="sk-rect1"></div>
                            <div class="sk-rect2"></div>
                            <div class="sk-rect3"></div>
                            <div class="sk-rect4"></div>
                            <div class="sk-rect5"></div>
                        </div>

                        <form id="sub_form" data-return="<?php echo site_url('kitchen_models/index/')?>" enctype="multipart/form-data" method="post" accept-charset="UTF-8" action="<?php echo site_url('kitchen_models/prices_edit_save/'.$item['id']) ?>">

                            <?php foreach ($facades as $item):?>

                                <?php
                                if ($price_data != null) {
                                    $price_facade = '';
                                    foreach ($price_data as $pd) {
                                        if ($pd->id == $item['id']) {
                                            $price_facade = $pd;
                                        }
                                    }

                                }
                                ?>

                                <div class="row fac_row" data-id="<?php echo $item['id']?>">
                                    <div class="acc_heading">
                                        <div class="col-sm-1 vcenter">
                                            <?php if($item['icon'] != null): ?>


                                                <?php if (strpos($item['icon'], 'common_assets') !== false): ?>
                                                    <img  class="img-fluid" src="<?php echo $item['icon']?>" alt="">
                                                <?php else: ?>
                                                    <img  class="img-fluid" src="<?php echo $this->config->item('const_path').$item['icon']?>" alt="">
                                                <?php endif; ?>
                                            <?php else:?>
                                                <div style="background: #ffffff"></div>
                                            <?php endif;?>
                                        </div>
                                        <div class="col-sm-2 vcenter">
                                            <?php echo $item['name']?>
                                        </div>
                                        <div class="col-sm-4 vcenter">
                                            <?php
                                            $key = array_search($item['category'], array_column($categories, 'id'));
                                            if($key == null){
                                                echo $lang_arr['no'];
                                            } else {
                                                $cat = $categories[$key]['parent'];
                                                if($cat == 0){
                                                    echo $categories[$key]['name'];
                                                } else {
                                                    $key2 = array_search($cat, array_column($materials, 'id'));
                                                    echo $materials[$key2]['name'];
                                                    echo '/';
                                                    echo $categories[$key]['name'];
                                                }

                                            }
                                            ?>
                                        </div>
                                        <div class="col-sm-3 vcenter"></div>
                                    </div>
                                    <div class="row acc_body">




                                        <?php foreach ($modules as $module):?>

                                            <?php
                                            if ($price_data != null) {
                                                $price_module = '';



                                                if ($price_facade != '') {

                                                    foreach ($price_facade->modules as $pf) {
                                                        if ($module['id'] == $pf->id) {
                                                            $price_module = $pf;
                                                        }
                                                    }
                                                }
                                            }

                                            ?>

                                            <div class="module col-sm-12" data-id="<?php echo $module['id']?>">
                                                <div class="col-sm-1 vcenter">


                                                    <?php if($item['icon'] != null): ?>

                                                        <?php if (strpos($item['icon'], 'common_assets') !== false): ?>
                                                            <img  class="img-fluid" src="<?php echo $item['icon']?>" alt="">
                                                        <?php else: ?>
                                                            <img  class="img-fluid" src="<?php echo $this->config->item('const_path').$item['icon']?>" alt="">
                                                        <?php endif; ?>

                                                    <?php else:?>
                                                        <div style="background: #ffffff"></div>
                                                    <?php endif;?>
                                                </div>
                                                <div class="col-sm-2 vcenter">
                                                    <?php echo $item['name']?>
                                                </div>
                                                <div class="col-sm-2 vcenter">
                                                    <?php if ($module['icon'] != null): ?>
                                                        <?php if (strpos($module['icon'], 'common_assets') !== false): ?>
                                                            <img style="max-width: 150px;" class="img-fluid" src="<?php echo $module['icon'] ?>"
                                                                 alt="">
                                                        <?php else: ?>
                                                            <img style="max-width: 150px;" class="img-fluid"
                                                                 src="<?php echo $this->config->item('const_path') . $module['icon'] ?>" alt="">
                                                        <?php endif; ?>

                                                    <?php endif; ?>
                                                </div>

                                                <?php

                                                $params = json_decode($module['params']);
                                                $variants = $params->params->variants;

                                                ?>


                                                <div class="col-sm-3 vcenter tright module_vars">
                                                    <?php foreach ($variants as $key=>$variant): ?>


                                                        <p data-key="<?php echo $key?>">
                                                            <?php echo $variant->name . ' ' . $variant->width . 'x' . $variant->height . 'x' . $variant->depth?>
                                                            <input type="hidden" class="m_name" value="<?php echo $variant->name?>">
                                                            <input type="hidden" class="m_width" value="<?php echo $variant->width?>">
                                                            <input type="hidden" class="m_height" value="<?php echo $variant->height?>">
                                                            <input type="hidden" class="m_depth" value="<?php echo $variant->depth?>">
                                                        </p>

                                                    <?php endforeach;?>

                                                </div>

                                                <div class="col-sm-3 vcenter module_vars_price">
                                                    <?php foreach ($variants as $key=>$variant): ?>



                                                        <?php


                                                        $variant_price = '';
                                                        $variant_code = '';

                                                        if ($price_data != null) {
                                                            $price_variant = '';

                                                            if($price_module != ''){
                                                                foreach ($price_module->variants as $pm) {
                                                                    if(
                                                                        $pm->name == $variant->name &&
                                                                        $pm->width == $variant->width &&
                                                                        $pm->height == $variant->height
                                                                    ){
                                                                        $variant_price = $pm->price;
                                                                        if(isset($pm->code)){
                                                                            $variant_code = $pm->code;
                                                                        }

                                                                    }
                                                                }
                                                            } else {


                                                                $variant_price = '';
                                                                $variant_code = '123213';
                                                            }


                                                        }

                                                        ?>

                                                        <p data-key="<?php echo $key?>">
                                                            <input class="code_input" placeholder="<?php echo $lang_arr['code']?>" value="<?php if ($price_data != null) { echo $variant_code; }?>" type="text">
                                                            <input class="price_input"  value="<?php if ($price_data != null) { echo $variant_price; }?>" type="number"> <?php echo $lang_arr['currency']?>.
                                                        </p>
                                                        <!--                                <p data-key="--><?php //echo $key?><!--"></p>-->
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endforeach;?>
                                    </div>

                                </div>

                            <?php endforeach;?>



                            <div class="hr-line-dashed"></div>

                            <div class="form-group row">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a class="btn btn-white btn-sm" href="<?php echo site_url('kitchen_models/index/') ?>"><?php echo $lang_arr['cancel']?></a>
                                    <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['save']?></button>
                                </div>
                            </div>



                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endif;?>






<style>

    .temp1{
        width: 100%;
    }

    .temp{
        width: 0%!important;
    }

    .loader{
        display: none;
        position: fixed;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        z-index: 9999;
        padding-top: 200px;
        background: #ffffff;
        text-align: center;
        font-weight: bold;
        font-size: 30px;
    }

    .vcenter {
        display: inline-block;
        vertical-align: middle;
        float: none;
    }

    .tright{
        text-align: right;
    }

    .price_input{
        max-width: 90px;
        text-align: right;
    }

    .code_input{
        max-width: 90px;
    }

    .acc_body{
        display: none;
    }

    .row.fac_row {
        margin: 0 0 20px 0;
        border: 1px solid;
        padding: 10px;
    }

    .acc_heading{
        cursor: pointer;
    }

    .module_vars p{
        height: 50px;
    }

    .module_vars_price p{
        height: 50px;
    }

    .module{
        margin-bottom: 20px;
    }

</style>


<script>
    $(document).ready(function () {
        $('.acc_heading').click(function (e) {
            e.preventDefault();
            e.stopPropagation()
            var row = $(this).parent().find('.row');

            if(row.hasClass('showed')){
                row.slideUp().removeClass('showed')
            } else {
                row.slideDown().addClass('showed')
            }

        });

        $('#sub_form').submit(function (e) {
            e.preventDefault();

            $('.ibox-content').addClass('sk-loading');


            var scope = $(this);

            var facade_rows = $('.fac_row');

            var price_data = [];

            for (var i = 0; i < facade_rows.length; i++){


                var object = {
                    id: parseInt( $(facade_rows[i]).attr('data-id') ),
                    modules:[]
                };

                var modules_rows = $(facade_rows[i]).find('.module');

                for (var m = 0; m < modules_rows.length; m++){

                    var module_variants = $(modules_rows[m]).find('.module_vars').find('p');
                    var module_prices = $(modules_rows[m]).find('.module_vars_price');

                    var module_object = {
                        id: $(modules_rows[m]).attr('data-id'),
                        variants:[]
                    };

                    for (var v = 0; v < module_variants.length; v++){

                        var variant_object = {};

                        var key = $(module_variants[v]).attr('data-key');

                        variant_object.name = $(module_variants[v]).find('.m_name').val();
                        variant_object.width = parseInt( $(module_variants[v]).find('.m_width').val() );
                        variant_object.height = parseInt( $(module_variants[v]).find('.m_height').val() );
                        variant_object.depth = parseInt( $(module_variants[v]).find('.m_depth').val() );

                        variant_object.price =  $(module_prices).find('p[data-key="'+ key +'"]').find('.price_input').val();
                        variant_object.code =  $(module_prices).find('p[data-key="'+ key +'"]').find('.code_input').val();


                        if(variant_object.price === null || variant_object.price === '') variant_object.price = 0;
                        if(variant_object.code === null || variant_object.code === '') variant_object.code = '';

                        variant_object.price = parseInt( variant_object.price )


                        module_object.variants.push(variant_object);

                    }

                    object.modules.push(module_object)

                }
                price_data.push(object)

            }


            $.ajax({
                url: $('#sub_form').attr('action'),
                type: 'post',
                data: {data:JSON.stringify(price_data)}
            }).done(function (msg) {
                window.location.href = scope.attr('data-return')
            });


        })


    })
</script>
