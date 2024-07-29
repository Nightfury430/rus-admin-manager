var window_material;

var r = "/common_assets/tests/envmap/";
var urls = [
    r + "posx.jpg",
    r + "negx.jpg",
    r + "posy.jpg",
    r + "negy.jpg",
    r + "posz.jpg",
    r + "negz.jpg"
];



textureCube = new THREE.CubeTextureLoader().load( urls );
textureCube.format = THREE.RGBFormat;
textureCube.mapping = THREE.CubeReflectionMapping;

window_material = new THREE.MeshLambertMaterial( { envMap: textureCube } );


env_urls = [
    "/common_assets/tests/prod/test12.jpg",
    "/common_assets/tests/prod/test12.jpg",
    "/common_assets/tests/prod/test12.jpg",
    "/common_assets/tests/prod/test12.jpg",
    "/common_assets/tests/prod/test12.jpg",
    "/common_assets/tests/prod/test12.jpg"
]

textureCube2 = new THREE.CubeTextureLoader().load( env_urls );
textureCube2.format = THREE.RGBFormat;
textureCube2.mapping = THREE.CubeReflectionMapping;

mirror_material = new THREE.MeshLambertMaterial( { envMap: textureCube2 } );


function create_material(params_obj) {

    if (!params_obj) params_obj = {};
    if (!params_obj.add_params) params_obj.add_params = {};


    let material;
    let texture;
    let repeat_x;
    let repeat_y;

    let normal_map;

    let repeat_already_set = 0;


    let params = {
        type: params_obj.type !== undefined ? params_obj.type : 'Phong',
        params: params_obj.params !== undefined ? JSON.parse(JSON.stringify(params_obj.params)) : {},
        add_params: {
            texture_width: params_obj.add_params.texture_width !== undefined ? params_obj.add_params.texture_width : 256,
            texture_height: params_obj.add_params.texture_height !== undefined ? params_obj.add_params.texture_height : 256,

            normal_map_width: params_obj.add_params.normal_map_width !== undefined ? params_obj.add_params.normal_map_width : 256,
            normal_map_height: params_obj.add_params.normal_map_height !== undefined ? params_obj.add_params.normal_map_height : 256,

            real_width: params_obj.add_params.real_width !== undefined ? params_obj.add_params.real_width : 256,
            real_height: params_obj.add_params.real_height !== undefined ? params_obj.add_params.real_height : 256,
            model_width: params_obj.add_params.model_width !== undefined ? params_obj.add_params.model_width : 256,
            model_height: params_obj.add_params.model_height !== undefined ? params_obj.add_params.model_height : 256,
            rotation: params_obj.add_params.rotation !== undefined ? params_obj.add_params.rotation : 'normal',
            stretch_width: params_obj.add_params.stretch_width !== undefined ? params_obj.add_params.stretch_width : false,
            stretch_height: params_obj.add_params.stretch_height !== undefined ? params_obj.add_params.stretch_height : false,

            wrapping: params_obj.add_params.wrapping !== undefined ? params_obj.add_params.wrapping : 'mirror'
        }
    };

    if(params.params.icon) delete params.params.icon;

    if(params.params.alphaMap){
        let alphaMap = texture_loader.load(params.params.alphaMap)
        console.log(alphaMap)

        if (params.add_params.wrapping === 'repeat') {
            alphaMap.wrapS = alphaMap.wrapT = THREE.RepeatWrapping;
        } else {
            alphaMap.wrapS = alphaMap.wrapT = THREE.MirroredRepeatWrapping;
        }

        if(params.add_params.stretch_width){
            repeat_x = 1;
        } else {
            repeat_x = params.add_params.model_width  / params.add_params.real_width ;
        }

        if(params.add_params.stretch_height){
            repeat_y = 1;
        } else {
            repeat_y = params.add_params.model_height /  params.add_params.real_height ;
        }

        alphaMap.repeat.set(repeat_x, repeat_y);
        params.params.alphaMap = alphaMap;
    }


    if(params.params.map){

        texture = texture_loader.load(params.params.map);


        if(repeat_already_set === 0) {

            if (params.add_params.wrapping === 'repeat') {
                texture.wrapS = texture.wrapT = THREE.RepeatWrapping;
            } else {
                texture.wrapS = texture.wrapT = THREE.MirroredRepeatWrapping;
            }


            if (params.add_params.rotation === 'normal') {

                if (params.add_params.stretch_width) {
                    repeat_x = 1;
                } else {
                    repeat_x = params.add_params.model_width * params.add_params.texture_width / params.add_params.real_width / params.add_params.texture_width;
                }

                if (params.add_params.stretch_height) {
                    repeat_y = 1;
                } else {
                    repeat_y = params.add_params.model_height * params.add_params.texture_height / params.add_params.real_height / params.add_params.texture_height;
                }

                texture.repeat.set(repeat_x, repeat_y);

            } else {

                texture.center.set(0.5, 0.5);
                texture.rotation = Math.PI / 2;

                let tmp1 = JSON.parse(JSON.stringify(params.add_params.texture_width));
                let tmp2 = JSON.parse(JSON.stringify(params.add_params.texture_height));

                params.add_params.texture_width = tmp2;
                params.add_params.texture_height = tmp1;

                tmp1 = JSON.parse(JSON.stringify(params.add_params.real_width));
                tmp2 = JSON.parse(JSON.stringify(params.add_params.real_height));

                params.add_params.real_width = tmp2;
                params.add_params.real_height = tmp1;


                if (params.add_params.stretch_width) {
                    repeat_x = 1;
                } else {
                    repeat_x = params.add_params.model_height / params.add_params.real_height;
                }

                if (params.add_params.stretch_height) {
                    repeat_y = 1;
                } else {
                    repeat_y = params.add_params.model_width / params.add_params.real_width;
                }
                texture.repeat.set(repeat_x, repeat_y);

            }
            repeat_already_set = 1;
        }
        params.params.map = texture;

    }

    if(params.params.normalMap){

        normal_map = texture_loader.load(params.params.normalMap);

        if(repeat_already_set === 0){
            normal_map.wrapS = normal_map.wrapT = THREE.RepeatWrapping;

            repeat_x = params.add_params.model_width * params.add_params.normal_map_width / params.add_params.real_width / params.add_params.normal_map_width;
            repeat_y = params.add_params.model_height * params.add_params.normal_map_height /  params.add_params.real_height / params.add_params.normal_map_height;

            repeat_already_set = 1;
        }
        normal_map.repeat.set(repeat_x,repeat_y);
        params.params.normalMap = normal_map;
    }

    if(params.params.metalnessMap){
        params.params.metalnessMap = texture_loader.load(params.params.metalnessMap)
    }

    if(params.params.envMap){
        params.params.envMap = texture_loader.load(params.params.envMap)
    }

    if(params.params.bumpMap){

        normal_map = texture_loader.load(params.params.bumpMap);

        if(repeat_already_set === 0) {
            normal_map.wrapS = normal_map.wrapT = THREE.RepeatWrapping;
            repeat_x = params.add_params.model_width * params.add_params.normal_map_width / params.add_params.real_width / params.add_params.normal_map_width;
            repeat_y = params.add_params.model_height * params.add_params.normal_map_height / params.add_params.real_height / params.add_params.normal_map_height;
            repeat_already_set = 1;
        }
        normal_map.repeat.set(repeat_x, repeat_y);

        params.params.bumpMap = normal_map;
        params.params.bumpScale = 0.5;
    }





    switch (params.type) {
        case 'Phong':

            material = new THREE.MeshPhongMaterial(params.params);

            break;


        case 'Standart':
            params.params.envMap = textureCube2;
            params.params.envMapIntensity = 1;
            material = new THREE.MeshStandardMaterial(params.params);

            break;

        case 'Basic':

            material = new THREE.MeshBasicMaterial(params.params);

            break;

    }


    return material;
}

function dispose_material(material) {
    if(!isArray(material)){
        if(material.map){
            material.map.dispose();
        }
    } else {

    }
}



function create_material_by_id(id, catalog) {
    if(!catalog) catalog = materials_catalog;
    return create_material( get_material_by_id( parseInt(id), catalog ) );
}



function get_material_by_id (id, catalog){
    if(!catalog) catalog = materials_catalog;
    let item = find_obj_by_id(catalog.items, id);
    if(item) return copy_object(item);
    return {};

    //
    // if(_.find(catalog.items, function (obj) { return parseInt(obj.id) === parseInt(id); })){
    //     return copy_object( _.find(catalog.items, function (obj) { return parseInt(obj.id) === parseInt(id); }) );
    // } else {
    //     return {}
    // }
}

function get_material_category_by_id(id, catalog) {
    if(!catalog) catalog = materials_catalog;
    let category = _.find( catalog.categories, function (obj) { return obj.id === parseInt(id); });
    if(category !== null){
        return copy_object(category)
    } else {
        return null;
    }
}

function get_material_category_by_id_no_items(id) {
    let category = _.find( materials_catalog.categories, function (obj) { return obj.id === parseInt(id); });
    let parent;
    let obj = {
        id: category.id,
        name: category.name,
    };
    if(category.parent){
        parent = _.find( materials_catalog.categories, function (obj) { return obj.id === parseInt(category.parent); });
        obj.parent_id = parent.id;
        obj.parent_name = parent.name;
    }
    return obj;

}

function get_top_material_category_by_material_id(material_id) {
    let material = find_obj_by_id(materials_catalog.items, material_id);
    return get_top_material_category(material.category);
}

function get_top_material_category(category_id) {
    let cat = find_obj_by_id(materials_catalog.categories, category_id);
    if(!cat.parent || cat.parent === 0){
        return cat;
    } else {
        return get_top_material_category(cat.parent)
    }
}



function get_first_material_from_category(category_id) {
    let cat = get_material_category_by_id(category_id);
    if(cat.items){
        return cat.items[0];
    } else {
        return cat.categories[0].items[0];
    }
}


