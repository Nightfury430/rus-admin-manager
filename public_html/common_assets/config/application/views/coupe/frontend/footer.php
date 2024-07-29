<script src="/common_assets/libs/jquery_3_3_1.min.js"></script>
<script src="/common_assets_kupe/js/libs/lodash.js"></script>
<script src="/common_assets_kupe/js/libs/three.js"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r123/three.min.js"></script>-->
<script src="/common_assets_kupe/js/libs/inflate.min.js"></script>
<script src="/common_assets_kupe/js/libs/slick.min.js"></script>
<script src="/common_assets_kupe/js/libs/FBXLoader.js"></script>
<script src="/common_assets/libs/obj_export.js"></script>
<script src="/common_assets/libs/punycode.min.js"></script>
<!--<script src="/common_assets/libs/pdf/pdfmake.min_2.js"</script>-->
<?php

if (strpos($def_site_url, 'http://') === false && strpos($def_site_url, 'https://') === false) {
	$def_site_url = 'http://'. $def_site_url;
}

$def_site_url = parse_url( $def_site_url )['host'];
$def_site_url = str_replace('www.','', $def_site_url);



?>


<input id="vk_appid" type="hidden" value="<?php echo $vk_appid?>">

<script id="dfcpe">

    $(document).ready(function () {
        setTimeout(function () {
            function getParentUrl() {
                return parent !== window ? document.referrer : null;
            }

            if (getParentUrl()) {
                var sourceString = getParentUrl().replace('http://', '').replace('https://', '').replace('www.', '').split(/[/?#]/)[0];
                sourceString = punycode.toUnicode(sourceString);

                var fth = 1;



                if (sourceString.indexOf('<?php echo $def_site_url?>') > -1) {
                    fth = 0;
                }


                if(getParentUrl().indexOf('https://vk.com') !== 1 && window.location.search.indexOf('api_id=' + $('#vk_appid').val()) > -1 && $('#vk_appid').val() != '' || getParentUrl().indexOf('https://vk.com') !== 1 && window.location.search.indexOf('app_id=' + $('#vk_appid').val()) > -1 && $('#vk_appid').val() != ''){
                    fth = 0;
                }


                $('#vk_appid').attr('data-test',sourceString )


                if (sourceString.indexOf('dbac9542-eab1-4486-b22d-c43a8f007c37.htmlcomponentservice.com') > -1) {
                    fth = 0;
                }

                if(fth !== 0){
                    $('head').html('');
                    $('body').html('' +
                        '')
                }

                $('#dfcpe').remove();
            }
        },400)

    })






</script>




<?php if(file_exists(dirname(FCPATH) . '/assets/coupe_custom.js')):?>

<?php include dirname(FCPATH) . '/assets/coupe_custom.html'?>
<link rel="stylesheet" href="<?php echo $base_url?>assets/coupe_custom.css?<?php echo md5(date('m-d-Y-His A e'));?>">
<script src="<?php echo $base_url?>assets/coupe_custom.js?<?php echo md5(date('m-d-Y-His A e'));?>"></script>

<?php endif;?>

<!--<script src="/common_assets_kupe/js/global.js"></script>-->
<!--<script src="/common_assets_kupe/js/main.js"></script>-->
<!--<script src="/common_assets_kupe/js/materials.js"></script>-->
<!--<script src="/common_assets_kupe/js/functions.js"></script>-->
<!--<script src="/common_assets_kupe/js/interface_functions.js"></script>-->
<!--<script src="/common_assets_kupe/js/controls_orbit.js"></script>-->
<!--<script src="/common_assets_kupe/js/controls_drag.js"></script>-->
<!--<script src="/common_assets_kupe/js/Cabinet.js"></script>-->
<!--<script src="/common_assets_kupe/js/Wall.js"></script>-->
<!--<script src="/common_assets_kupe/js/Sizes_obj.js"></script>-->
<!--<script src="/common_assets_kupe/js/interface.js"></script>-->
<!--<script src="/common_assets_kupe/js/Door.js"></script>-->
<!--<script src="/common_assets_kupe/js/Profile.js"></script>-->
<!--<script src="/common_assets_kupe/js/Rail.js"></script>-->
<!--<script src="/common_assets_kupe/js/Locker.js"></script>-->
<!--<script src="/common_assets_kupe/js/Drag_helper.js"></script>-->
<!--<script src="/common_assets_kupe/js/Model.js"></script>-->
<!--<script src="/common_assets_kupe/js/lang.js"></script>-->
<!--<script src="/common_assets_kupe/js/saveload.js"></script>-->
<!--<script src="/common_assets_kupe/js/send_order.js"></script>-->
<!--<script src="/common_assets_kupe/js/Room.js"></script>-->
<!--<script src="/common_assets_kupe/js/pdf.js"></script>-->



    <script src="/common_assets_kupe/js/production/main_j_v_0.5.js?<?php echo md5(date('m-d-Y-His A e'));?>"></script>







</body>