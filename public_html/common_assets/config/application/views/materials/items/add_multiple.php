<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo $lang_arr['add_multiple_materials']?></h2>
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
<?php
$action_path = 'materials/items_add_multiple/';
if(isset($common) && $common == 1) $action_path = 'materials/items_add_multiple_common/'

?>

<form class="material_add" enctype="multipart/form-data" method="post" accept-charset="utf-8" action="<?php echo site_url($action_path) ?>">

<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="ibox ">

        <div class="ibox-content">

            <div class="alert alert-info" role="alert">
                <p>Требования к архиву:</p>
                <ul>
                    <li>Архив должен быть в формате .zip</li>
                    <li>В архиве не должно быть никаких вложенных папок, подпапок. Т.е. в архиве должны быть ТОЛЬКО файлы с изображениями</li>
                    <li>Максимальное количество файлов в архиве для едионовременной загрузки - 100</li>
                    <li>Максимальный размер одного файла изображения в архиве не более 1Mb</li>
                </ul>
                <br>
                <p>Рекомендации к файлам изображений:</p>
                <ul>
                    <li>Для более качественного отображения, размер изображения в пикселях должен быть кратным степени 2 (256, 512, 1024...)</li>
                    <li>Изображение по возможности должен быть бесшовным</li>
                </ul>
                <br>
                <p>Для того чтобы название и артикул заполнились автоматически необходимо чтобы название файла было в формате Название_Артикул (например Кремовый_0240.jpg)</p>
            </div>

            <div class="form-group" >
                <label for="category"><?php echo $lang_arr['category']?></label>
                <select class="form-control" name="category" id="category">
                    <option data-data='{"class": "top_cat"}' value="0"><?php echo $lang_arr['no']?></option>
			        <?php foreach ($materials_categories as $cat):?>
				        <?php if($cat['parent'] == 0): ?>
                            <option data-data='{"class": "top_cat"}' value="<?php echo $cat['id']?>"><?php echo $cat['name']?></option>
					        <?php if (isset($cat['children'])):?>
						        <?php foreach ($cat['children'] as $child):?>
                                    <option data-data='{"class": "sub_cat"}' value="<?php echo $child['id']?>"><?php echo $child['name']?></option>
						        <?php endforeach;?>
					        <?php endif;?>
				        <?php endif;?>
			        <?php endforeach;?>
                </select>
            </div>

            <div class="form-group map_input">
                <label for="archive">Архив с текстурами</label>
                <input type="file"  name="archive" id="archive" accept="application/zip">
            </div>

            <div class="form-group">
                <label ><?php echo $lang_arr['roughness']?></label>
                <input class="form-control" type="number" value="0.8" min="0" max="1" step="0.01" name="roughness">
            </div>

            <div class="form-group">
                <label ><?php echo $lang_arr['metalness']?></label>
                <input class="form-control" type="number" value="0" min="0" max="1" step="0.01" name="metalness">
            </div>

            <div class="form-group" >
                <label ><?php echo $lang_arr['texture_real_width']?></label>
                <input class="form-control" type="number" value="0" min="0" step="1" name="real_width">
            </div>

            <div class="form-group" >
                <label ><?php echo $lang_arr['texture_real_heght']?></label>
                <input class="form-control" type="number" value="0" min="0" step="1" name="real_height">
            </div>

            <div class="form-group" >
                <label ><?php echo $lang_arr['stretch_width']?></label>
                <select class="form-control" name="stretch_width" >
                    <option selected value="0"><?php echo $lang_arr['no']?></option>
                    <option value="1"><?php echo $lang_arr['yes']?></option>
                </select>
            </div>

            <div class="form-group" >
                <label ><?php echo $lang_arr['stretch_height']?></label>
                <select class="form-control" name="stretch_height" >
                    <option selected value="0"><?php echo $lang_arr['no']?></option>
                    <option value="1"><?php echo $lang_arr['yes']?></option>
                </select>
            </div>

            <div class="form-group" >
                <label ><?php echo $lang_arr['wrapping_type']?></label>
                <select class="form-control" name="wrapping" >
                    <option selected value="mirror"><?php echo $lang_arr['wrapping_type_mirror']?></option>
                    <option value="repeat"><?php echo $lang_arr['wrapping_type_repeat']?></option>
                </select>
            </div>

            <div class="hr-line-dashed"></div>


                <div class="form-group row">
                    <div class="col-sm-8 col-sm-offset-2">
                        <a class="btn btn-white btn-sm" href="<?php echo site_url('catalog/items/materials') ?>"><?php echo $lang_arr['cancel']?></a>
                        <button class="btn btn-primary btn-sm" type="submit"><?php echo $lang_arr['add']?></button>
                    </div>
                </div>


        </div>
    </div>

</div>
</form>


<style>
    .option.top_cat{
        font-weight: bold;
    }

    .option.sub_cat{
        padding-left: 40px;
    }

    .sp-input{
        background: #ffffff;
    }
</style>

<script>
    $(document).ready(function () {
        $('#category').selectize({
            create: false,
            render: {
                option: function (data, escape) {
                    return "<div class='option " + data.class + "'>" + data.text + "</div>"
                }
            }
        });
    })
</script>