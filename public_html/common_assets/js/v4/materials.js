function create_material(params_obj) {



    if (!params_obj) params_obj = {};
    if (!params_obj.add_params) params_obj.add_params = {};

    let loader;

    try{
        loader = texture_loader;
    } catch (e) {
        loader = new THREE.TextureLoader();
    }




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
            stretch_width: params_obj.add_params.stretch_width !== undefined ? parseInt(params_obj.add_params.stretch_width) : false,
            stretch_height: params_obj.add_params.stretch_height !== undefined ? parseInt(params_obj.add_params.stretch_height) : false,
            normal_scale: params_obj.add_params.normal_scale !== undefined ? params_obj.add_params.normal_scale : false,

            wrapping: params_obj.add_params.wrapping !== undefined ? params_obj.add_params.wrapping : 'mirror',
            normal_wrapping: params_obj.add_params.normal_wrapping !== undefined ? params_obj.add_params.normal_wrapping : 'mirror',
            roughness_wrapping: params_obj.add_params.roughness_wrapping !== undefined ? params_obj.add_params.roughness_wrapping : 'mirror',

            debug: params_obj.add_params.debug !== undefined ? params_obj.add_params.debug : false,

        }
    };

    if(params.type == 'Standart') params.type = 'Standard'

    if(params.params.transparent == 0) params.params.transparent = false
    if(params.params.transparent == 'false') params.params.transparent = false
    if(params.params.transparent == undefined) params.params.transparent = false

    if(params.params.transparent == 1) params.params.transparent = true
    if(params.params.transparent == 'true') params.params.transparent = true



    if(params.params.icon) delete params.params.icon;
    if(params.params.side) if(params.params.side == "double") params.params.side = THREE.DoubleSide;
    if(params.add_params.normal_scale) params.params.normalScale = new THREE.Vector2(params.add_params.normal_scale, params.add_params.normal_scale)

    if(params.params.alphaMap){
        let alphaMap = loader.load(correct_url(params.params.alphaMap))

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
        texture = loader.load(correct_url(params.params.map));

        if (params.add_params.wrapping === 'repeat') {
            texture.wrapS = texture.wrapT = THREE.RepeatWrapping;
        } else {
            texture.wrapS = texture.wrapT = THREE.MirroredRepeatWrapping;
        }
        if(repeat_already_set === 0) {

            if (params.add_params.rotation === 'normal') {

                if (params.add_params.stretch_width) {
                    repeat_x = 1;
                } else {
                    repeat_x = params.add_params.model_width / params.add_params.real_width;
                }

                if (params.add_params.stretch_height) {
                    repeat_y = 1;
                } else {
                    repeat_y = params.add_params.model_height / params.add_params.real_height;
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
        normal_map = loader.load(correct_url(params.params.normalMap));
        normal_map.wrapS = normal_map.wrapT = THREE.RepeatWrapping;
        if (params.add_params.normal_wrapping === 'repeat') {
            normal_map.wrapS = normal_map.wrapT = THREE.RepeatWrapping;
        } else {
            normal_map.wrapS = normal_map.wrapT = THREE.MirroredRepeatWrapping;
        }
        if(repeat_already_set === 0){
            if (params.add_params.stretch_width) {
                repeat_x = 1;
            } else {
                repeat_x = params.add_params.model_width / params.add_params.real_width;
            }
            if (params.add_params.stretch_height) {
                repeat_y = 1;
            } else {
                repeat_y = params.add_params.model_height / params.add_params.real_height;
            }
            repeat_already_set = 1;
        }
        normal_map.repeat.set(repeat_x,repeat_y);
        params.params.normalMap = normal_map;
    }

    if(params.params.metalnessMap){
        let map = loader.load(correct_url(params.params.metalnessMap))
        params.params.metalnessMap = map;
    }

    if(params.params.roughnessMap){
        let map = loader.load(correct_url(params.params.roughnessMap))
        if (params.add_params.roughness_wrapping === 'repeat') {
            map.wrapS = map.wrapT = THREE.RepeatWrapping;
        } else {
            map.wrapS = map.wrapT = THREE.MirroredRepeatWrapping;
        }
        if(repeat_already_set === 0) {
            if (params.add_params.stretch_width) {
                repeat_x = 1;
            } else {
                repeat_x = params.add_params.model_width / params.add_params.real_width;
            }
            if (params.add_params.stretch_height) {
                repeat_y = 1;
            } else {
                repeat_y = params.add_params.model_height / params.add_params.real_height;
            }
            repeat_already_set = 1;
        }
        map.repeat.set(repeat_x, repeat_y);
        params.params.roughnessMap = map;
    }

    if(params.params.aoMap){
        let map = loader.load(correct_url(params.params.aoMap))
        params.params.aoMap = map;
    }

    if(params.params.displacementMap){
        let map = loader.load(correct_url(params.params.displacementMap))
        params.params.displacementMap = map;
    }

    if(params.params.emissiveMap){
        let map = loader.load(correct_url(params.params.emissiveMap))
        params.params.emissiveMap = map;
    }


    if(params.params.envMap){
        params.params.envMap = loader.load(correct_url(params.params.envMap))
    }

    if(params.params.bumpMap){

        normal_map = loader.load(correct_url(params.params.bumpMap));

        normal_map.wrapS = normal_map.wrapT = THREE.RepeatWrapping;
        if(repeat_already_set === 0) {
            repeat_x = params.add_params.model_width * params.add_params.normal_map_width / params.add_params.real_width / params.add_params.normal_map_width;
            repeat_y = params.add_params.model_height * params.add_params.normal_map_height / params.add_params.real_height / params.add_params.normal_map_height;
            repeat_already_set = 1;
        }
        normal_map.repeat.set(repeat_x, repeat_y);

        params.params.bumpMap = normal_map;
        params.params.bumpScale = 0.2;
    }


    if(params.params.metalness) params.params.metalness = parseFloat(params.params.metalness)
    if(params.params.roughness) params.params.roughness = parseFloat(params.params.roughness)
    if(params.params.opacity) params.params.opacity = parseFloat(params.params.opacity)

    switch (params.type) {

        case 'Phong':
            material = new THREE.MeshPhongMaterial(params.params);
            break;
        case 'Standart':
            params.params.envMap = textureCube2;
            params.params.envMapIntensity = 1;
            if(params.params.transparent){
                params.params.transparent = to_int(params.params.transparent) === 1;
            }
            material = new THREE.MeshStandardMaterial(params.params);
            break;
        case 'Standard':
            try{
                params.params.envMap = textureCube2;
                params.params.envMapIntensity = 1;
            } catch (e) {

            }
            if(params.params.transparent){
                params.params.transparent = to_int(params.params.transparent) === 1;
            }
            material = new THREE.MeshStandardMaterial(params.params);
            break;
        case 'Basic':
            material = new THREE.MeshBasicMaterial(params.params);
            break;
    }

    return material;
}

function correct_url(input) {
    let url = input
    if(url.indexOf('common_assets') < 0 && url.indexOf('http') < 0 && url.indexOf('base64') < 0){
        url = global_options.acc_url + '/' + input
    }
    return url
}

function dispose_material(material) {
    if(!isArray(material)){
        if(material.map) material.map.dispose();
        if(material.normalMap) material.normalMap.dispose();
        if(material.bumpMap) material.bumpMap.dispose();
        if(material.metalnessMap) material.metalnessMap.dispose();
    } else {
        for (let i = 0; i < material.length; i++){
            if(material[i].map) material[i].map.dispose();
            if(material[i].normalMap) material[i].normalMap.dispose();
            if(material[i].bumpMap) material[i].bumpMap.dispose();
            if(material[i].metalnessMap) material[i].metalnessMap.dispose();
        }
    }
}

function apply_material(object, material) {

}

function create_material_by_id(id, catalog) {
    if(!catalog) catalog = materials_catalog;
    return create_material( get_material_by_id( parseInt(id), catalog ) );
}

function get_material_by_id (id, catalog){



    if(!catalog) catalog = materials_catalog;
    if(catalog == materials_catalog){



        return materials_lib.get_item(id)
    } else {

        let item = find_obj_by_id(catalog.items, id);
        if(item) return copy_object(item);
        return null;
    }


    //
    // if(_.find(catalog.items, function (obj) { return parseInt(obj.id) === parseInt(id); })){
    //     return copy_object( _.find(catalog.items, function (obj) { return parseInt(obj.id) === parseInt(id); }) );
    // } else {
    //     return {}
    // }
}

function get_material_category_by_id(id, catalog) {
    if(!catalog) catalog = materials_catalog;
    let category
    // if(catalog == materials_catalog){
    //      category = materials_lib.get_category(id);
    // } else {
    //      category = _.find( catalog.categories, function (obj) { return obj.id == (id); });
    // }
    category = _.find( catalog.categories, function (obj) { return obj.id == (id); });

    if(category !== null && category !== undefined){
        return copy_object(category)
    } else {
        return null;
    }
}

function get_material_category_by_id_no_items(id, catalog) {
    if(!catalog) catalog = materials_catalog;
    let category = _.find( catalog.categories, function (obj) { return obj.id === parseInt(id); });

    if(!category){
        category = {};
        category.id = 0;
        category.name = 'НЕТ КАТЕГОРИИ';
        if(catalog == glass_materials_catalog) category.name = 'Стекло';
    }

    let parent;
    let obj = {
        id: category.id,
        name: category.name,
    };
    if(category.parent){
        parent = _.find( catalog.categories, function (obj) { return obj.id === parseInt(category.parent); });
        obj.parent_id = parent.id;
        obj.parent_name = parent.name;
    }
    return obj;

}

function get_top_material_category_by_material_id(material_id) {
    let material = find_obj_by_id(materials_catalog.items, material_id);
    if(!material) return 0;
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

function check_material_in_category(arr, id) {
    if(!arr || !id) return false;


    let cat = get_top_material_category_by_material_id(id);


    if(!cat) return false;

    for (let i = 0; i < arr.length; i++){
        if(arr[i] == cat.id) return true;
    }


    return false;
}

function get_first_material_from_category(category_id) {
    console.log(category_id)
    let cat = get_material_category_by_id(category_id);
    console.log(cat)
    if(cat.items){
        return cat.items[0];
    } else {

        for (let i = 0; i < cat.categories.length; i++){
            if(cat.categories[i].items){
                if(cat.categories[i].items[0]) return cat.categories[i].items[0];
            }

        }

        // return cat.categories[0].items[0];
    }
}

function get_material_data(mat_id, catalog) {
    if(!catalog) catalog = materials_catalog;

    let result = {};


    let m_data = get_material_by_id(mat_id, catalog);

    if(!m_data) m_data = {
        id: 0,
        name: 'Материал не существует',
        code: ''
    }

    result.id = m_data.id;
    result.name = m_data.name;
    result.code = m_data.code;


    result.color = undefined;
    result.map = undefined;
    if(m_data.params){
        result.color = m_data.params.color;
        if(m_data.params.map) result.map = m_data.params.map;
    }



    let c_data = get_material_category_by_id_no_items(m_data.category, catalog);
    result.category_name = c_data.name;

    result.category = m_data.category;

    if(c_data.parent_id){
        let p_data = get_material_category_by_id_no_items(c_data.parent_id, catalog);
        result.parent_category_name = p_data.name;
        result.parent_category = p_data.id;
    }

    return result;
}