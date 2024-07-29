let mixin = {
    methods: {
        item_style: function (item) {
            let res = {};
            if (item.icon || item.icon == '') {

                if(item.icon == ''){
                    res['background-image'] = 'url(/common_assets/images/no_image.png)';
                } else {
                    res['background-image'] = 'url(' + item.icon + ')';
                }


            } else {
                if (item.params) {
                    if (item.params.icon) {
                        res['background-image'] = 'url(' + item.params.icon + ')';
                    } else {
                        if (item.params.map) {
                            res['background-image'] = 'url(' + item.params.map + ')';
                        } else {
                            res['background-color'] = item.params.color;
                        }
                    }
                }
            }

            return res;
        },
        lang: function (key) {
            return this.$root.lang(key);
        },
        expand_panel: function () {
            console.log(this)

            if(this.$refs && this.$refs.catalog){
                if(typeof this.$refs.catalog.check_scroll == "function") this.$refs.catalog.check_scroll();
            }

            if(typeof this.set_panel_height == "function") this.set_panel_height();

            this.expanded = !this.expanded;
            this.$root.$emit('expand_panel', this.expanded)
        },
        panel_expanded: function () {
            return !!this.$parent.panel_expanded;
        }
    }
}
let expand_mixin = {
    methods: {
        expand_panel: function () {
            if(this.$refs && this.$refs.catalog){

                if(typeof this.$refs.catalog.check_scroll == "function") this.$refs.catalog.check_scroll();
            }

            if(typeof this.set_panel_height == "function") this.set_panel_height();

            this.$root.$emit('expand_panel')
        },
        panel_expanded: function () {
            if(this.$parent.panel_expanded) return true;
            return false;
        }
    }
}

let interface_components_init = [];

function interface_new() {

    if(!custom_settings.mod_lib_no_height){
        convert_height({
            id: 0,
            name: '',
            active: 1,
            categories: mod_lib.tree,
            items: []
        })
        mod_lib.conv = true;
    }




    Vue.component('lighting_component', {
        template: '#lighting_component_template',
        components: {
            'chrome-picker': VueColor.Chrome
        },
        props: {
            enabled: {
                type: [Number, String, Boolean],
                default: false
            },
            intensity: {
                type: [Number, String],
                default: 0.5
            }

        },
        mixins: [mixin],
        data: function () {
            return {
                l_enabled: false,
                colors: '#000000',
                l_intensity: 0.5,
                spot: {
                    intensity: spot_params.intensity,
                    distance: spot_params.distance,
                    angle: spot_params.angle,
                    penumbra: spot_params.penumbra,
                    decay: spot_params.decay,
                },
            };
        },

        watch: {
            // enabled: function () {
            //     this.l_enabled = this.enabled
            // },
            // intensity: function () {
            //     this.l_intensity = this.intensity
            // }
        },

        created: function () {
            this.l_enabled = this.enabled
            this.l_intensity = this.intensity
        },

        methods: {
            upd_light:function(){
                let intensity = copy_object(parseFloat(this.l_intensity));

                active_el.params.lighting.intensity = intensity;
                console.log(active_el)
                if(!active_el.userData.light) return
                active_el.userData.light.intensity = intensity
                for (let i = 0; i < active_el.model.children.length; i++){
                    let m = active_el.model.children[i];
                    if(m.name.indexOf(custom_settings.lighting_key) > -1){
                        m.material.emissiveIntensity =intensity / 4
                    }
                }

                // active_el.userData.light.distance = parseFloat(this.spot.distance)
                // active_el.userData.light.angle = parseFloat(this.spot.angle)
                // active_el.userData.light.penumbra = parseFloat(this.spot.penumbra)
                // active_el.userData.light.decay = parseFloat(this.spot.decay)
                // spotLightHelper.update();
            },

            change_enabled: function(){
                let intensity = copy_object(parseFloat(this.l_intensity));
                set_light(active_el);
                if(this.l_enabled){
                    active_el.params.lighting.enabled = true;
                    active_el.params.lighting.color = '#ffffff';
                    active_el.userData.light.color = new THREE.Color('#ffffff')
                    active_el.userData.light.intensity = intensity
                    for (let i = 0; i < active_el.model.children.length; i++){
                        let m = active_el.model.children[i];
                        if(m.name.indexOf(custom_settings.lighting_key) > -1){
                            m.material.emissiveIntensity = intensity / 4
                            m.material.emissive = new THREE.Color('#ffffff');
                        }
                    }
                } else {
                    active_el.params.lighting.enabled = false;
                    active_el.params.lighting.color = '#000000';
                    active_el.userData.light.color = new THREE.Color('#000000')
                    active_el.userData.light.intensity = 0
                    for (let i = 0; i < active_el.model.children.length; i++){
                        let m = active_el.model.children[i];
                        if(m.name.indexOf(custom_settings.lighting_key) > -1){
                            m.material.emissiveIntensity = 0
                            m.material.emissive = new THREE.Color('#000000');
                        }
                    }
                }
            },

            test_color(){
                console.log(this.colors)
                for (let i = 0; i < active_el.model.children.length; i++){
                    let m = active_el.model.children[i];
                    if(m.name.indexOf(custom_settings.lighting_key) > -1){
                        m.material.emissiveIntensity = parseFloat(this.spot.intensity) / 4
                        m.material.emissive = new THREE.Color(this.colors.hex);
                    }
                }
                active_el.userData.light.color = new THREE.Color(this.colors.hex);
            },

            select_item: function (item) {

            },
            emit_select: function(item){

            },
            find_item: function (id) {

            }
        }
    })

    Vue.component('side_type_picker', {
        template: '#side_type_picker_template',
        props: {
            facade: {
                type: [Number, String],
                default: 0
            },
            selected: {
                type: [String, Number, Array],
                default: 0,
            },
            side: {
                type: String,
                default: 'left'
            }
        },
        mixins: [mixin],
        data: function () {
            return {
                expanded: false,
                l_selected: 0
            };
        },

        watch: {
            selected: function (n_val, o_val) {
                this.get_selected_index();
            },
        },

        created: function () {
            this.$options.facade = facades_lib.items[this.facade]
            this.get_selected_index();
        },

        methods: {
            item_style_type: function(item, index){
                let res = {};
                if(item == 'dsp'){
                    res['background-image'] = 'url(/common_assets/images/no_image.png)';
                    return res;
                }
                if(item == 'simple'){
                    res['background-image'] = 'url(/common_assets/images/icons/common_full_78.jpg)';
                    return res;
                }

                let icons_compat_obj = {
                    full: '/common_assets/images/icons/common_full_78.jpg',
                    window: '/common_assets/images/icons/common_window_78.jpg',
                    frame: '/common_assets/images/icons/common_frame_78.jpg'
                }

                let radius_compat_obj = {
                    radius: 'full',
                    radius_window: 'window',
                    radius_window2: 'radius_window2',
                    radius_frame: 'frame'
                }
                let comp_types = this.$options.facade.compatibility_types;
                let icon = item.icon;
                if(icon == ''){
                    if(item.radius){
                        Object.keys(comp_types).forEach(function (k) {
                            if(comp_types[k] == index){
                                console.log(k)
                                console.log(radius_compat_obj[k])
                                icon = icons_compat_obj[radius_compat_obj[k]]


                            }
                        })
                    } else {
                        Object.keys(comp_types).forEach(function (k) {
                            if(comp_types[k] == index) icon = icons_compat_obj[k]
                        })
                    }
                }



                if(icon == '' || icon == undefined){
                    res['background-image'] = 'url(/common_assets/images/no_image.png)';
                } else {
                    res['background-image'] = 'url(' + icon + ')';
                }
                return res;
            },
            get_selected_index: function(){

                let sel = this.selected;

                if(this.selected == 'dsp'){
                    this.l_selected = 'dsp'
                    return
                }
                if(this.selected == 'simple'){
                    this.l_selected = 'simple'
                    return
                }

                if(Array.isArray(this.selected)){
                    sel = this.selected[0];
                }

                if(isNaN(parseInt(sel))){
                    sel = this.$options.facade.compatibility_types[sel];
                } else {
                    sel = parseInt(sel)
                }

                this.l_selected = sel
            },

            select_type: function (type, index) {
                let sel;
                if(type == 'dsp'){
                    this.l_selected = 'dsp';
                    sel = 'dsp';
                } else if(type == 'simple'){
                    this.l_selected = 'simple';
                    sel = 'simple';
                } else {
                    this.l_selected = index;
                    sel = index;

                    let keys = Object.keys(this.$options.facade.compatibility_types);
                    for (let i = keys.length; i--;){
                        if(this.$options.facade.compatibility_types[keys[i]] == index){
                            if(this.radius){
                                sel = keys[i];
                                break;
                            } else {
                                sel = keys[i];
                                break
                            }
                        }
                    }
                }

                this.$emit('change_side', {
                    side: this.side,
                    style: sel
                });

                // this.item.params.style = sel;
                //
                // this.$root.$selected_object.change_facade_type_single(sel, this.item.type, this.item.index, this.item.sec_index)
            },
            check_radius: function (type) {
                if(!type.radius)  return true
            }
        }
    })

    Vue.component('facade_type_picker', {
        template: '#facade_type_picker_template',
        props: {
            facade: {
                type: [Number, String],
                default: 0
            },
            selected: {
                type: [String, Number, Array],
                default: 0,
            },
            radius: {
                type: [Number, Boolean],
                default: 0
            },
            type: {
                type: [String],
                default: 'd'
            },
            all: {
                type: [Number],
                default: 0
            },
            index: {
                type: [Number],
                default: 0
            },
            sec_index: {
                type: [Number],
                default: null
            },
            item: {
                type: [Object],
                default: {}
            },
        },
        mixins: [mixin],
        data: function () {
            return {
                expanded: false,
                l_selected: 0
            };
        },

        watch: {
            selected: function (n_val, o_val) {
                this.get_selected_index();
            },
        },

        created: function () {
            this.$options.facade = facades_lib.items[this.facade]
            this.get_selected_index();
        },

        methods: {
            item_style_type: function(item, index){
                let icons_compat_obj = {
                    full: '/common_assets/images/icons/common_full_78.jpg',
                    window: '/common_assets/images/icons/common_window_78.jpg',
                    frame: '/common_assets/images/icons/common_frame_78.jpg'
                }

                let radius_compat_obj = {
                    radius: 'full',
                    radius_window: 'window',
                    radius_window2: 'radius_window2',
                    radius_frame: 'frame'
                }
                let comp_types = this.$options.facade.compatibility_types;
                let icon = item.icon;
                if(icon == ''){
                    if(item.radius){
                        Object.keys(comp_types).forEach(function (k) {
                            if(comp_types[k] == index){
                                console.log(k)
                                console.log(radius_compat_obj[k])
                                icon = icons_compat_obj[radius_compat_obj[k]]


                            }
                        })
                    } else {
                        Object.keys(comp_types).forEach(function (k) {
                            if(comp_types[k] == index) icon = icons_compat_obj[k]
                        })
                    }
                }

                let res = {};

                if(icon == '' || icon == undefined){
                    res['background-image'] = 'url(/common_assets/images/no_image.png)';
                } else {
                    res['background-image'] = 'url(' + icon + ')';
                }
                return res;
            },

            select_item: function (item) {

            },

            get_selected_index: function(){

                let sel = this.selected;

                if(Array.isArray(this.selected)){
                    sel = this.selected[0];
                }

                if(isNaN(parseInt(sel))){
                    if(this.radius){
                        if (sel == 'full') {
                            sel = this.$options.facade.compatibility_types['radius']
                        } else if (sel == 'window') {
                            sel = this.$options.facade.compatibility_types['radius_window']
                        } else {
                            sel = this.$options.facade.compatibility_types[sel];
                        }
                    } else {
                        sel = this.$options.facade.compatibility_types[sel];
                    }

                } else {
                    sel = parseInt(sel)
                }

                this.l_selected = sel
            },

            select_type: function (type, index) {

                this.l_selected = index;

                let sel = index;

                let keys = Object.keys(this.$options.facade.compatibility_types);
                for (let i = keys.length; i--;){
                    if(this.$options.facade.compatibility_types[keys[i]] == index){
                        if(this.radius){
                            sel = keys[i];
                            break;
                        } else {
                            sel = keys[i];
                            break
                        }
                    }
                }

                if(this.radius){
                    if(sel == 'radius') sel = 'full';
                    if(sel == 'radius_window') sel = 'window';
                }



                if(this.all){
                    this.$root.$selected_object.change_facades_style(sel)
                } else {
                    this.item.params.style = sel;
                    if(this.item.path){
                        this.$root.$selected_object.change_facade_type_single(sel, this.item.type, this.item.path)
                    } else {
                        this.$root.$selected_object.change_facade_type_single(sel, this.item.type, this.item.index, this.item.sec_index)
                    }

                    this.$root.$selected_object.get_price();
                }


            },
            check_radius: function (type) {

                if(this.radius){
                    if(type.radius)  return true
                } else {
                    if(!type.radius)  return true
                }

                return false;


            }
        }
    })




    Vue.component('from_wall_sizes', {
        template: '#from_wall_sizes_template',
        mixins: [mixin],
        data: function () {
            return {
                l_floor: 0,
                l_ceiling: 0,
                l_left_wall: 0,
                l_right_wall: 0,
                l_back_wall: 0,
                l_front_wall: 0,

                delay: typeof input_delay === 'undefined' ? 500 : input_delay,
                debounce: null,
            };
        },

        created: function () {
            let scope = this;

            let sel_obj = this.$root.$selected_object;
            let dist = copy_object(sel_obj.cust_dists);
            Object.keys(dist).forEach(function (k) {
                scope['l_' + k] = dist[k]
            })



            this.$root.$on('update_sizes_from_wall', function (dist) {
                Object.keys(dist).forEach(function (k) {
                    scope['l_' + k] = dist[k]
                })
            })
        },

        methods: {
            change_position: function (side) {
                let val = 0
                switch (side) {
                    case 'left':
                        val = this.l_left_wall
                        break;
                    case 'right':
                        val = this.l_right_wall
                        break;
                    case 'ceiling':
                        val = this.l_ceiling
                        break;
                    case 'floor':
                        val = this.l_floor
                        break;
                    case 'front':
                        val = this.l_front_wall
                        break;
                    case 'back':
                        val = this.l_back_wall
                        break;
                }
                clearTimeout(this.debounce)
                let scope = this;
                this.debounce = setTimeout(function () {
                    sc.set_position_from_wall(side, val, scope.$root.$selected_object)
                }, this.delay)
            }
        }
    })


    Vue.component('facades_picker', {
        template: '#facades_picker_template',
        props: {
            heading: {
                type: String,
                default: ''
            },
            categories: {
                type: [String, Array],
                default: function () {
                    return [];
                }
            },
            items: {
                type: [String, Array],
                default: null
            },
            lib: {
                type: String,
                default: 'facades'
            },
            mode: {
                type: String,
                default: 'p'
            },
            fn: {
                type: String,
                default: ''
            },

            can_facade:{
                type: [Boolean, Number],
                default: true
            },
            can_color:{
                type: [Boolean, Number],
                default: true
            },

            facades: {
                type: [String, Object],
                default: {
                    top: 0,
                    bottom: 0
                }
            },
            materials: {
                type: [String, Object],
                default: {
                    0: {
                        gen: {
                            top: 0,
                            bottom: 0
                        }
                    }
                }
            },

            multiple: {
                type: Number,
                default: 1
            },
            method: {
                type: String,
                default: 'project',
            },
            facade_picker: {
                type: [Boolean, Number],
                default: false
            },
            color_picker: {
                type: [Boolean, Number],
                default: true
            },
        },
        mixins: [mixin],
        data: function () {
            return {
                expanded: false,
                l_categories: [],
                l_facades: {},
                l_materials: {},
                selected_materials: {
                    top: 0,
                    bottom: 0,
                },
                l_mode: 'p',
                selected_facade: 0,
                selected_key: null,
                current_facade: 0,
                l_materials_categories: [],
                is_equal: false,
                h_top: '',
                h_bottom: 'Фасады: нижние модули',
                sel_mats: {},
            };
        },
        watch: {
            materials: function () {
                if (typeof this.materials === 'string') {
                    this.l_materials = copy_object(utils.nested_object.get(project_settings, this.materials));
                } else {
                    this.l_materials = this.materials
                }
            },
            mode: function (o, n) {
                this.l_mode = this.mode;
            }
        },
        created: function () {

            this.l_mode = this.mode;

            if(this.items){

            } else {
                let cats = [];
                if(constructor_settings.multiple_facades_mode == 1){
                    for (let i = 0; i < project_settings.facade_categories.length; i++) {
                        cats.push(project_settings.facade_categories[i].id)
                    }
                } else {
                    if(!this.categories.length){
                        for (let i = 0; i < facades_lib.tree.length; i++) {
                            cats.push(facades_lib.tree[i].id)
                        }
                    } else {
                        cats = this.categories
                    }
                }


                this.l_categories = cats;
            }





            if (typeof this.facades === 'string') {
                this.l_facades = copy_object(utils.nested_object.get(project_settings, this.facades));
            } else {
                this.l_facades = this.facades
            }


            if (typeof this.materials === 'string') {
                this.l_materials = copy_object(utils.nested_object.get(project_settings, this.materials));
            } else {
                this.l_materials = this.materials
            }


            this.sel_mats = null;

            console.log(this.$props)
            console.log(this._data)
            // if(this.method != 'project'){
            //     Vue.set(this, 'sel_mats', this.l_materials[this.l_facades.top])
            //
            // }else {
            //     this.sel_mats = null;
            // }

            Vue.nextTick(() => {
                this.set_height();
            })


            this.check_equal();

        },

        methods: {

            get_color_mode: function () {
                if(this.multiple == 0) return false;
                return this.is_equal ? 1 : 0
            },

            check_equal: function () {

                if(this.multiple == 0){
                    this.is_equal = false;
                    return false;
                }

                this.is_equal = this.l_facades.top == this.l_facades.bottom;

                if (this.is_equal) {
                    this.h_top = 'Фасады'
                } else {
                    this.h_top = 'Фасады: верхние модули'
                }

            },

            find_category: function (id) {
                this.$refs.catalog.set_path(id)
                this.l_mode = 'c';
            },

            select_material: function (item) {


                let scope = this;
                this.selected_facade = item.facade_id;

                if (item.key == 'gen') {
                    this.l_materials_categories = copy_object(facades_lib.items[this.selected_facade].materials)
                } else {
                    this.l_materials_categories = copy_object(facades_lib.items[this.selected_facade].additional_materials[item.key].materials)
                }

                this.selected_key = item.key

                console.log(this.selected_materials)

                this.selected_materials.top = parseInt(this.l_materials[item.facade_id][item.key].top);
                this.selected_materials.bottom = parseInt(this.l_materials[item.facade_id][item.key].bottom);

                this.l_mode = 'fc'

                setTimeout(function () {
                    // scope.$refs.color_catalog.find_item_path_by_id(item.id)
                }, 1)


            },

            get_materials_categories: function () {
                console.log(this.selected_facade)
            },

            select_item: function (item) {

                if(this.method == 'object'){
                    this.l_facades.top = item.id
                    this.l_facades.bottom = item.id
                    this.$emit('change_facade', item);
                    return;
                }

                if(this.method == 'single'){
                    this.l_facades.top = item.id
                    this.l_facades.bottom = item.id
                    this.$emit('change_facade_single', item);
                    return;
                }


                this.l_facades.top = item.id
                this.l_facades.bottom = item.id
                // change_facade_style(item.id, 1, 1);

                sc.change_facade(item.id, ['top', 'bottom'])
                // sc.change_facade(item.id, ['top'])
                // sc.change_facade(item.id, ['bottom'])
                Vue.set(this, 'l_materials', copy_object(project_settings.selected_materials.facades))

                this.check_equal();
            },

            select_item_top: function (item) {
                this.l_facades.top = item.id
                // change_facade_style(item.id, 1, 0);
                sc.change_facade(item.id, ['top'])
                this.check_equal();
                Vue.set(this, 'l_materials', copy_object(project_settings.selected_materials.facades))
            },

            select_item_bottom: function (item) {
                this.l_facades.bottom = item.id
                // change_facade_style(item.id, 0, 1);
                sc.change_facade(item.id, ['bottom'])
                this.check_equal();

                Vue.set(this, 'l_materials', copy_object(project_settings.selected_materials.facades))

                // this.$refs.fac_top.cr();
                // this.$refs.fac_bottom.cr();
            },

            select_color: function (item) {

                if(this.method == 'object'){
                    this.$emit('change_material', {
                        id: this.selected_facade,
                        key: this.selected_key,
                        mat_id: item.id
                    });

                    return;
                }

                if(this.method == 'single'){
                    this.$emit('change_material_single', {
                        id: this.selected_facade,
                        key: this.selected_key,
                        mat_id: item.id
                    });
                    return;
                }


                sc.change_facade_material_v2(this.selected_facade, this.selected_key, item.id, 'all');
                Vue.set(this, 'l_materials', copy_object(project_settings.selected_materials.facades))

                this.$refs.fac_top.cr();

                if(this.$refs.fac_bottom){
                    this.$refs.fac_bottom.cr();
                }



            },
            select_color_top: function (item) {
                sc.change_facade_material_v2(this.selected_facade, this.selected_key, item.id, 'top');

                Vue.set(this, 'l_materials', copy_object(project_settings.selected_materials.facades))

                this.$refs.fac_top.cr();
                this.$refs.fac_bottom.cr();




            },
            select_color_bottom: function (item) {
                sc.change_facade_material_v2(this.selected_facade, this.selected_key, item.id, 'bottom');
                Vue.set(this, 'l_materials', copy_object(project_settings.selected_materials.facades))
                this.$refs.fac_top.cr();
                this.$refs.fac_bottom.cr();
            },

            set_height: function(){

                let refs = this.$refs;
                if(!this.$refs.scroll) return

                refs.scroll.style.height = 'auto'
                let panel = refs.comp_el.parentElement
                Vue.nextTick(()=> {
                    if(refs.scroll){
                        console.log(panel.offsetHeight - 35)
                        let h = panel.offsetHeight - 35;
                        let mmwh = document.getElementById('main_menu_wrapper').clientHeight;
                        if(h > mmwh)
                        refs.scroll.style.height = h + 'px';
                    }
                })

            },

            find_item: function (id) {
                console.log('find_run')
                this.$refs.catalog.check_scroll();
                // this.$refs.catalog.find_item_path_by_id(id)
                this.l_mode = 'c';
            }
        }
    })

    Vue.component('selected_facade', {
        template: '#selected_facade_template',
        props: {
            selected: {
                type: [String, Number],
                default: null
            },
            lib: {
                type: String,
                default: 'materials'
            },
            heading: {
                type: String,
                default: 'Фасад'
            },
            is_equal: {
                type: Boolean,
                default: false
            },
            materials: {
                type: Object,
                default: null
            },
            facade_style: {
                type: [String, Number],
                default: 'full'
            },
            radius: {
                type: [String, Number, Boolean],
                default: false
            },
            can_facade:{
                type: [Boolean, Number],
                default: true
            },
            can_style:{
                type: [Boolean, Number],
                default: true
            },
            can_color:{
                type: [Boolean, Number],
                default: true
            },
        },
        mixins: [mixin],
        data: function () {
            return {
                l_selected: 0,
                item: {},
                l_lib: '',
                path: []
            };
        },
        watch: {
            selected: function (n_val, o_val) {
                this.cr();
            },
            lib: function (n_val, o_val) {
                this.cr();
            },
            materials: function (n_val, o_val) {
                this.cr();
            },
        },
        created: function () {
            this.cr();
        },
        methods: {
            cr: function () {
                this.l_lib = this.lib;



                if (typeof this.selected == 'string' && isNaN(parseInt(this.selected))) {
                    this.l_selected = copy_object(utils.nested_object.get(project_settings, this.selected))
                } else {
                    this.l_selected = this.selected;
                }

                let it = facades_lib.get_item(this.l_selected);
                if(!it.additional_materials) it.additional_materials = {};
                let mat_info = {};
                Object.keys(it.additional_materials).forEach(function (k) {
                    let m = it.additional_materials[k];
                    mat_info[m.key] = {
                        key: m.key,
                        name: m.name,
                        fixed: m.fixed
                    }
                })

                mat_info.gen = {
                    key: 'gen',
                    name: 'Основной цвет',
                }
                let sel_mats = {};

                if(this.materials == null){
                    sel_mats = copy_object(project_settings.selected_materials.facades[this.l_selected]);
                } else {
                    sel_mats = this.materials[this.l_selected];
                }




                let obj = {
                    id: it.id,
                    name: it.name,
                    code: it.code !== undefined ? it.code : '',
                    icon: it.icon,
                    category: it.category,
                    materials: sel_mats,
                    mat_info: mat_info
                }

                Vue.set(this, 'item', obj)


                this.$options.lib = facades_lib;
                this.$options.item = this.$options.lib.get_item(this.l_selected);

                this.find_item_path();
            },

            add_mat: function(mat_info, key){
                if(!this.can_color) return false;
                if(key == 'gen') return true;
                if(mat_info.fixed === 0) return true;
            },

            get_type_icon: function(item){
                let icons_compat_obj = {
                    full: '/common_assets/images/icons/common_full_78.jpg',
                    window: '/common_assets/images/icons/common_window_78.jpg',
                    frame: '/common_assets/images/icons/common_frame_78.jpg'
                }

                let radius_compat_obj = {
                    full: 'radius',
                    window: 'radius_window',
                    radius_window2: 'radius_window2',
                    frame: 'radius_frame'
                }

                let fac = this.$options.lib.items[this.selected]
                let type = copy_object(this.facade_style);


                let sname = '';
                if(Array.isArray(type)){
                    type = type[0];
                }
                if(isNaN(parseInt(type))){
                    sname = type;
                    if(this.radius == 1){
                        type = fac.compatibility_types[radius_compat_obj[type]];
                    } else {
                        type = fac.compatibility_types[type];
                    }

                }

                if(type == undefined){
                    type = 0;
                }
                let icon = fac.types[type].icon;




                if(icon == '') icon = icons_compat_obj[sname];


                return icon;
            },

            get_facade_type_name: function(item){
                return this.$options.lib.items[this.selected].types[this.find_type()].name
            },

            find_type: function(){
                let fac = this.$options.lib.items[this.selected]
                let type = copy_object(this.facade_style);
                if(Array.isArray(type)){
                    type = type[0];
                }
                if(isNaN(parseInt(type))){
                    type = fac.compatibility_types[type];
                    if(!type) type = 0

                }
                return type;
            },

            select_facade_type: function (item) {

                let obj = {
                    id: item.id,
                    style: this.facade_style,
                    radius: this.radius
                }

                this.$root.$emit('select_facade_type', obj, true)
            },

            select_category: function (id, ind) {
                let p = copy_object(this.path);
                p.length = ind + 1;
                this.$emit('find_category', p);
            },

            select_material: function (item) {
                item.facade_id = this.item.id;
                this.$emit('select_material', item)
            },

            find_item_path: function () {
                let scope = this;
                let res = [];
                let c_id = this.item.category

                get_parent(c_id);

                function get_parent(id) {
                    if (!facades_lib.categories[id].parent || facades_lib.categories[id].parent == 0) {
                        res.unshift(id)
                    } else {
                        res.unshift(id)
                        get_parent(facades_lib.categories[id].parent)
                    }
                }

                this.path = res;
                return res;
            },

            find_item: function (id) {
                this.$emit('find_item', id);
            }
        }
    })


    Vue.component('model_picker', {
        template: '#model_picker_template',
        props: ['categories', 'heading', 'catalog', 'lib', 'cid', 'fn'],
        mixins: [mixin],
        data: function () {
            return {
                category: 0,
                mode: 'c',
                search: '',
                expanded: false,
                debounce: null,
                path: [0]
            };
        },

        methods: {

            reset: function () {

            },


            mouse_down: function (item) {
                let params = copy_object(item);

                this.$root.$refs.interface.classList.add("tmp_dis")

                switch (this.catalog) {
                    case 'comms':
                        params.catalog = 'Comms';
                        break;
                    case 'tech':
                        params.catalog = 'Tech';
                        break;
                    case 'interior':
                        params.catalog = 'Other';
                        break;
                }
                sc.add_object('model', params);
            },

            handle_md: function (item) {
                this.mouse_down(item)
            }
        }
    })

    Vue.component('catalog_picker', {
        template: '#catalog_picker_template',
        props: ['categories', 'heading', 'catalog', 'lib', 'cid', 'fn'],
        mixins: [mixin],
        data: function () {
            return {
                category: 0,
                mode: 'c',
                search: '',
                expanded: false,
                debounce: null,
                path: [0]
            };
        },

        methods: {
            reset: function () {},
            mouse_down: function (item) {

                if(item.params.old_type){
                    ce();
                    ce();

                    switch (item.params.old_type) {
                        case "cabinet":{
                            let params = mod_lib.get_item(item.params.old_type_id).params
                            this.$root.$refs.interface.classList.add("tmp_dis")
                            sc.add_object('cabinet', params);
                        }
                    }

                } else {

                    let params = copy_object(item.params);
                    params.is_default = true

                    this.$root.$refs.interface.classList.add("tmp_dis")
                    sc.add_object('cabinet_cn', params);
                }
            },

            handle_md: function (item) {
                this.mouse_down(item)
            }
        }
    })


    Vue.component('constr_picker', {
        template: '#constr_picker_template',
        props: ['categories', 'heading', 'catalog', 'lib', 'cid', 'fn'],
        mixins: [mixin],
        data: function () {
            return {
                category: 0,
                mode: 'c',
                search: '',
                expanded: false,
                debounce: null,
                path: [0]
            };
        },

        methods: {

            reset: function () {

            },

            expand_panel: function () {
                this.expanded = !this.expanded;
                this.$root.$emit('expand_panel', this.expanded)
            },

            mouse_down: function (item) {
                console.log(item)

                this.$root.$refs.interface.classList.add("tmp_dis")

                if (item.mode) {
                    sc.add_object('shaft', item);
                } else {
                    let params = copy_object(item);
                    params.catalog = 'Comms';
                    params.id = item.id;
                    sc.add_object('model', params);
                }

            },

            handle_md: function (item) {
                this.mouse_down(item)
            }
        }
    })

    Vue.component('module_picker', {
        template: '#module_picker_template',
        props: ['categories', 'heading', 'cid', 'fn'],
        mixins: [mixin],
        data: function () {
            return {
                mode: 'c',
                expanded: false,
            };
        },
        methods: {


            reset: function () {

            },


            expand_panel: function () {
                this.expanded = !this.expanded;
                this.$root.$emit('expand_panel', this.expanded);
            },


            mouse_down: function (item) {
                this.$root.$refs.interface.classList.add("tmp_dis")

                let params = mod_lib.get_item(item.id).params
                params.id = item.id;

                // if(document.getElementById('use_custom_module_depth').checked){
                //     if(params.cabinet){
                //         if(params.cabinet_group == 'top'){
                //             params.cabinet.depth = parseInt($('#settings_top_depth').val());
                //         }
                //     }
                //
                //     if(params.variants){
                //         if(params.cabinet_group == 'top') {
                //             params.variants.forEach(function (item) {
                //                 item.depth = parseInt($('#settings_top_depth').val());
                //             })
                //         }
                //     }
                // }

                // if(document.getElementById('use_custom_module_depth_bottom').checked){
                //     if(params.cabinet){
                //         if(params.cabinet_group == 'bottom'){
                //             params.cabinet.depth = parseInt($('#settings_bottom_depth').val());
                //         }
                //     }
                //
                //     if(params.variants){
                //         if(params.cabinet_group == 'bottom') {
                //             params.variants.forEach(function (item) {
                //                 item.depth =  parseInt($('#settings_bottom_depth').val());
                //             })
                //         }
                //     }
                // }

                sc.add_object('cabinet', params);
            },

            handle_cl: function (item) {
                console.log(item)
            },
            handle_md: function (item) {
                console.log(item)
                this.mouse_down(item)
            }

        }
    })

    Vue.component('handles_picker', {
        template: '#handles_picker_template',
        props: {
            heading: {
                type: String,
                default: ''
            },
            categories: {
                type: [String, Array],
                default: []
            },
            lib: {
                type: String,
                default: 'handles'
            },
            mode: {
                type: String,
                default: 'p'
            },
            method: {
                type: String,
                default: 'project',
            },
            multiple: {
                type: Number,
                default: 1,
            }
        },
        mixins: [mixin],
        data: function () {
            return {
                bottom_as_top: true,
                top: {
                    no_handle: false,
                    selected: 1,
                    orientation: 'vertical',
                    lockers_position: 'top',
                    preferable_size: 0
                },
                bottom: {
                    no_handle: false,
                    selected: 1,
                    orientation: 'vertical',
                    lockers_position: 'top',
                    preferable_size: 0
                },
                expanded: false,
                _selected: 0,
                l_mode: 'p'
            };
        },

        watch: {},

        created: function () {
            this.l_mode = this.mode

            Vue.set(this, 'top', {
                no_handle: project_settings.handle.top.no_handle,
                selected: project_settings.handle.top.selected_model,
                orientation: project_settings.handle.top.orientation,
                lockers_position: project_settings.handle.top.lockers_position,
                preferable_size: project_settings.handle.top.preferable_size
            })

            Vue.set(this, 'bottom', {
                no_handle: project_settings.handle.bottom.no_handle,
                selected: project_settings.handle.bottom.selected_model,
                orientation: project_settings.handle.bottom.orientation,
                lockers_position: project_settings.handle.bottom.lockers_position,
                preferable_size: project_settings.handle.bottom.preferable_size
            })
        },

        methods: {
            update: function (){

            },
            change_mode: function (mode) {
                this.l_mode = mode;
                if (this.l_mode == 'c') this.$refs.catalog.check_scroll();
            },

            select_item: function (item) {

                // console.log(item)
                // console.log(this.method)

                let size = 0;
                for (let i = 0; i < item.sizes.length; i++) {
                    if (item.sizes[i].default) size = i;
                }

                item.size = size;

                if(this.method == 'object'){
                    this.$emit('change_handle', item);
                    return
                }

                if(this.method == 'single'){
                    this.$emit('change_handle_single', item);
                    return
                }

                this.bottom_as_top = true;
                project_settings.handle.bottom_as_top = true;

                this.top.selected = item.id;
                this.bottom.selected = item.id;



                this.top.preferable_size = parseInt(size);
                this.bottom.preferable_size = parseInt(size);

                change_handle_model(
                    item.id,
                    size,
                    'top'
                )
            },
            select_item_top: function (item) {
                this.bottom_as_top = false;
                project_settings.handle.bottom_as_top = false;

                this.top.selected = item.id;

                let size = 0;
                for (let i = 0; i < item.sizes.length; i++) {
                    if (item.sizes[i].default) size = i;
                }

                this.top.preferable_size = parseInt(size);

                change_handle_model(
                    item.id,
                    size,
                    'top'
                )

            },
            select_item_bottom: function (item) {
                this.bottom_as_top = false;
                project_settings.handle.bottom_as_top = false;

                this.bottom.selected = item.id;

                let size = 0;
                for (let i = 0; i < item.sizes.length; i++) {
                    if (item.sizes[i].default) size = i;
                }

                this.bottom.preferable_size = parseInt(size);

                change_handle_model(
                    item.id,
                    size,
                    'bottom'
                )
            },

            cr: function () {

                this.bottom_as_top = project_settings.handle.bottom_as_top;

                Vue.set(this, 'top', {
                    no_handle: project_settings.handle.top.no_handle,
                    selected: project_settings.handle.top.selected_model,
                    orientation: project_settings.handle.top.orientation,
                    lockers_position: project_settings.handle.top.lockers_position,
                    preferable_size: project_settings.handle.top.preferable_size
                })

                Vue.set(this, 'bottom', {
                    no_handle: project_settings.handle.bottom.no_handle,
                    selected: project_settings.handle.bottom.selected_model,
                    orientation: project_settings.handle.bottom.orientation,
                    lockers_position: project_settings.handle.bottom.lockers_position,
                    preferable_size: project_settings.handle.bottom.preferable_size
                })

                console.log(this.top)
                console.log(this.bottom)

            },

            change_no_handle: function (group) {
                this[group].no_handle = !this[group].no_handle
                change_no_handle(this[group].no_handle, group)
            },
            change_orientation: function (group) {
                change_handles_orientation(this[group].orientation, group);
            },
            change_lockers: function (group) {
                change_lockers_handles_position(this[group].lockers_position, group);
            },
            change_size: function (group) {
                console.log(this[group].preferable_size)
            },

            find_item: function (id) {
                console.log(123)
                this.$refs.catalog.check_scroll();
                // this.$refs.catalog.find_item_path_by_id(id)
                // this.l_mode = 'c';
                this.change_mode('c')

            }
        }
    })




    Vue.component('furniture_picker', {
        template: '#furniture_picker_template',
        props: {
            furniture_data: {
                type: Object,
                default: null
            }
        },
        mixins: [mixin],
        data: function () {
            return {
                selected: {},
                types: {},
                offset: {
                    left: 0,
                    top: 'auto',
                    bottom: 'auto',
                },
                hovered: null,
                show: false,
                hovering: false,
                delay: 200,
                l_mode: 'p'
            };
        },
        created: function () {
            console.log(this.furniture_data)
            Vue.set(this, 'selected', copy_object(accsessories_data.default))
            Vue.set(this, 'types', copy_object(accsessories_data.types))
            this.$options.ad = accsessories_data
            this.$options.elem = document.getElementById('interface')
        },
        computed: {},
        mounted: function () {
            this.set_panel_height();
        },
        beforeUpdate: function () {
        },
        updated: function () {
        },
        methods: {
            get_name: function (key) {
                switch (key) {
                    case 'door':
                        return this.lang('f_door')
                    case 'locker':
                        return this.lang('f_locker')
                    case 'simple_top':
                        return this.lang('f_simple_top')
                    case 'double_top':
                        return this.lang('f_double_top')
                    case 'front_top':
                        return this.lang('f_front_top')
                    default:
                        return this.$options.ad.type_names[key]
                }
            },
            get_type_active: function(key){
                return this.$options.ad.default[key] != null
            },
            get_image: function (key) {
                let im_str = this.$options.ad.items[key].images;
                let arr = im_str.split(',')
                return arr[0].trim();
            },
            item_style: function (key) {
                let im_str = this.$options.ad.items[key].images;
                let arr = im_str.split(',')
                let url = arr[0].trim();

                let res = {};
                if (url) {
                    res['background-image'] = 'url(' + url + ')';
                }
                return res;
            },
            item_price: function (price) {
                return this.lang('price_per_piece') + ': ' + parseFloat(price).toFixed(2) + ' ' + this.lang('currency')
            },

            select_item(key, id) {

                if (this.selected[key] == id) return;
                this.selected[key] = id;
                project_settings.selected_furniture[key] = id;

                for (let i = 0; i < room.active_objects.length; i++) {
                    if (room.active_objects[i].type === 'Cabinet') {

                        let ch = 0;

                        if (room.active_objects[i].params.accessories_types[key]) {
                            if (room.active_objects[i].params.accessories_types[key].excluded.includes(id)) {
                                ch = 1;
                            }
                        }

                        if (ch == 0) {
                            room.active_objects[i].params.selected_furniture[key] = id;
                            room.active_objects[i].selected_furniture[key] = id;
                        }

                    }
                }


                get_total_price_test();

            },
            get_active: function(id){
                return this.$options.ad.items[id].active == 1
            },


            hover(key, e) {
                let scope = this;
                this.hovering = true;
                this.hovered = key;
                this.get_coords(e)

                setTimeout(function () {
                    scope.show = scope.hovering
                }, this.delay)
            },
            unhover() {
                this.hovered = null;
                this.hovering = false;
                this.show = false;
            },

            get_coords: function (e) {
                let r = e.target.getBoundingClientRect()
                let h = this.$options.elem.clientHeight / 2;

                if (r.y > h) {
                    this.offset.top = 'auto';
                    this.offset.bottom = h * 2 - r.y - r.height;
                } else {
                    this.offset.top = r.y;
                    this.offset.bottom = 'auto';
                }

                this.offset.left = r.x + r.width + 5;
            },

            get_position: function (e) {
                return {
                    top: this.offset.top == 'auto' ? 'auto' : this.offset.top + 'px',
                    bottom: this.offset.bottom == 'auto' ? 'auto' : this.offset.bottom + 'px',
                    left: this.offset.left + 'px'
                }
            },
            set_panel_height: function () {
                let refs = this.$refs;
                refs.body.style.height = 'auto'
                setTimeout(function () {
                    let h = refs.comp_el.parentElement.offsetHeight - refs.heading.offsetHeight;
                    refs.body.style.height = h + 'px';
                }, 100)
            },

            lang: function (key) {
                return this.$root.lang(key)
            }
        }
    })



    Vue.component('washer_picker', {
        template: '#washer_picker_template',
        props: [],
        mixins: [mixin],
        data: function () {
            return {
                category: 0,
                mode: 'c',
                categories: [0],
                tree: {},
                expanded: false,
                debounce: null,
                path: [0],
            };
        },
        created: function () {
            let tree = {
                id: 0,
                name: '',
                active: 1,
                categories: [],
                items: []
            }

            Object.keys(washes_catalog).forEach(function (k) {
                tree.items.push(washes_catalog[k])
            })
            Vue.set(this, 'tree', tree)
        },
        methods: {
            lang: function (key) {
                return this.$root.lang(key);
            },

            category_back: function () {
                if (this.path.length == 1) return;
                this.path.pop()
                this.category = this.path[this.path.length - 1]
                this.set_panel_height();
            },
            set_panel_height: function () {
                let refs = this.$refs;
                refs.body.style.height = 'auto'
                setTimeout(function () {
                    let h = refs.comp_el.parentElement.offsetHeight - refs.heading.offsetHeight;
                    if (refs.bread) h -= refs.bread.offsetHeight
                    refs.body.style.height = h + 'px';
                }, 1)
            },
            expand_panel: function () {
                this.expanded = !this.expanded;
                this.$root.$emit('expand_panel', this.expanded)
                this.set_panel_height();
            },
            mouse_down: function (item) {
                this.$root.$refs.interface.classList.add("tmp_dis")

                let params = copy_object(item);

                sc.add_object('washer', params);
            },
            item_style: function (item) {
                let res = {};
                if (item.icon) {
                    res['background-image'] = 'url(' + item.icon + ')';
                }
                return res;
            },
        }
    })

    Vue.component('shelve_picker', {
        template: '#shelve_picker_template',
        props: ['catalog'],
        mixins: [mixin],
        data: function () {
            return {
                category: 0,
                mode: 'c',
                categories: [0],
                tree: {},
                expanded: false,
                debounce: null,
                path: [0],
            };
        },
        created: function () {
            this.cr();
        },

        watch: {
            catalog: function (n_val, o_val) {
               this.cr();
            },
        },
        methods: {
            cr: function(){
                let tree = {
                    id: 0,
                    name: '',
                    active: 1,
                    categories: [],
                    items: []
                }

                if(this.catalog == 'shelves'){
                    tree.items = shelves_catalog.items
                } else {
                    tree.items = bardesk_catalog.items
                }

                Vue.set(this, 'tree', tree)
            },
            set_panel_height: function () {
                let refs = this.$refs;
                refs.body.style.height = 'auto'
                setTimeout(function () {
                    let h = refs.comp_el.parentElement.offsetHeight - refs.heading.offsetHeight;
                    if (refs.bread) h -= refs.bread.offsetHeight
                    refs.body.style.height = h + 'px';
                }, 1)
            },
            expand_panel: function () {
                this.expanded = !this.expanded;
                this.$root.$emit('expand_panel', this.expanded)
                this.set_panel_height();
            },
            mouse_down: function (item) {
                this.$root.$refs.interface.classList.add("tmp_dis")

                let params = copy_object(item.params);
                if(this.catalog == 'shelves'){
                    sc.add_object('shelve', params);
                } else {
                    sc.add_object('bardesk', params);
                }

            },
            item_style: function (item) {
                let res = {};
                if (item.icon) {
                    res['background-image'] = 'url(' + item.icon + ')';
                }
                return res;
            },
        }
    })

    Vue.component('bardesk_picker', {
        template: '#bardesk_picker_template',
        props: ['catalog'],
        mixins: [mixin],
        data: function () {
            return {
                category: 0,
                mode: 'c',
                categories: [0],
                tree: {},
                expanded: false,
                debounce: null,
                path: [0],
            };
        },
        created: function () {
            this.cr();
        },

        watch: {
            catalog: function (n_val, o_val) {
                this.cr();
            },
        },
        methods: {
            cr: function(){
                let tree = {
                    id: 0,
                    name: '',
                    active: 1,
                    categories: [],
                    items: []
                }

                if(this.catalog == 'shelves'){
                    tree.items = shelves_catalog.items
                } else {
                    tree.items = bardesk_catalog.items
                }

                Vue.set(this, 'tree', tree)
            },
            set_panel_height: function () {
                let refs = this.$refs;
                refs.body.style.height = 'auto'
                setTimeout(function () {
                    let h = refs.comp_el.parentElement.offsetHeight - refs.heading.offsetHeight;
                    if (refs.bread) h -= refs.bread.offsetHeight
                    refs.body.style.height = h + 'px';
                }, 1)
            },
            expand_panel: function () {
                this.expanded = !this.expanded;
                this.$root.$emit('expand_panel', this.expanded)
                this.set_panel_height();
            },
            mouse_down: function (item) {
                this.$root.$refs.interface.classList.add("tmp_dis")

                let params = copy_object(item.params);
                if(this.catalog == 'shelves'){
                    sc.add_object('shelve', params);
                } else {
                    sc.add_object('bardesk', params);
                }

            },
            item_style: function (item) {
                let res = {};
                if (item.icon) {
                    res['background-image'] = 'url(' + item.icon + ')';
                }
                return res;
            },
        }
    })


    Vue.component('project_picker', {
        template: '#project_picker_template',
        props: [],
        data: function () {
            return {
                category: 0,
                mode: 'c',
                categories: [0],
                tree: {},
                expanded: false,
                debounce: null,
                path: [0],
            };
        },
        created: function () {
            let scope = this;

            let tree = {
                id: 0,
                name: '',
                active: 1,
                categories: [],
                items: [],
                selected: null
            }


            tree.items = kitchen_templates;
            this.$options.tree = tree;


            this.$root.$on('yn_modal_project', function (data) {
                if (data) scope.load();
            })

            this.$nextTick(()=> {
                this.set_panel_height();
            })


        },


        methods: {
            lang: function (key) {
                return this.$root.lang(key);
            },

            category_back: function () {
                if (this.path.length == 1) return;
                this.path.pop()
                this.category = this.path[this.path.length - 1]
                this.set_panel_height();
            },
            set_panel_height: function () {
                let refs = this.$refs;
                refs.body.style.height = 'auto'
                setTimeout(function () {
                    let h = refs.comp_el.parentElement.offsetHeight - refs.heading.offsetHeight;
                    if (refs.bread) h -= refs.bread.offsetHeight
                    refs.body.style.height = h + 'px';
                }, 1)
            },
            expand_panel: function () {
                this.expanded = !this.expanded;
                this.$root.$emit('expand_panel', this.expanded)
                this.set_panel_height();
            },

            load: async function () {
                let data = await promise_request(this.$options.tree.items[this.selected].file)
                load_scene_new(JSON.stringify(data));
            },

            click: async function (index) {
                this.selected = index;
                this.$root.$emit('yn_modal_show', {
                    heading: '',
                    body: this.lang('project_overwrite_notice'),
                    cb: 'project'
                })
            },

            item_style: function (item) {
                let res = {};
                if (item.icon) {
                    res['background-image'] = 'url(' + item.icon + ')';
                }
                return res;
            },
        }
    })


    Vue.component('selected_side', {
        template: '#selected_side_template',
        props: {
            selected_left: {
                type: [String, Boolean],
                default: 'facade'
            },
            selected_right: {
                type: [String, Boolean],
                default: 'facade'
            },
            is_left: {
                type: Boolean,
                default: true
            },
            is_right: {
                type: Boolean,
                default: true
            },
            style_left: {
                type: [String, Number],
                default: 'full'
            },
            style_right: {
                type: [String, Number],
                default: 'full'
            },
            facade_id: {
                type: [Number, String],
                default: 58
            },
        },
        mixins: [mixin],
        data: function () {
            return {
                l_selected_left: true,
                l_selected_right: true,

                icon_left: '',
                name_left: '',
                icon_right: '',
                name_right: '',
            };
        },
        watch: {
            selected_left: function (n_val, o_val) {
                this.cr();
            },
            selected_right: function (n_val, o_val) {
                this.cr();
            },
            style_left: function(){
                this.cr();
            },
            style_right: function(){
                this.cr();
            }
        },
        created: function () {
            this.$options.facade = facades_lib.items[this.facade_id]
            this.cr();
        },
        methods: {
            get_type_icon: function(style, side){
                let icons_compat_obj = {
                    full: '/common_assets/images/icons/common_full_78.jpg',
                    window: '/common_assets/images/icons/common_window_78.jpg',
                    frame: '/common_assets/images/icons/common_frame_78.jpg'
                }
                let fac = this.$options.facade;
                let type;
                if(isNaN(parseInt(style))){
                    type = fac.compatibility_types[style];
                } else {
                    type = style;
                }
                if(type == undefined)type = 0;
                let icon = fac.types[type].icon;
                let name = fac.types[type].name;

                if(icon == '') icon = icons_compat_obj[style];


                return {
                    icon: icon,
                    name: name
                };
            },
            cr: function () {
                if(this.selected_left == true){
                    this.icon_left = '/common_assets/images/no_image.png';
                    this.name_left = 'ДСП';
                } else if(this.selected_left == 'facade_simple'){
                    this.icon_left = '/common_assets/images/icons/common_full_78.jpg';
                    this.name_left = 'Гладкий';
                } else {
                    let obj = this.get_type_icon(this.style_left, 'left')
                    this.icon_left = obj.icon;
                    this.name_left = obj.name;
                }

                if(this.selected_right == true){
                    this.icon_right = '/common_assets/images/no_image.png';
                    this.name_right = 'ДСП';
                }else if(this.selected_right == 'facade_simple'){
                    this.icon_right = '/common_assets/images/icons/common_full_78.jpg';
                    this.name_right = 'Гладкий';
                } else {
                    let obj = this.get_type_icon(this.style_right, 'right')
                    this.icon_right = obj.icon;
                    this.name_right = obj.name;
                }
            },

            select_item: function (side) {
                console.log(this.$props)
                let obj = {};
                obj.side = side
                obj.facade = this.facade_id
                if(side == 'left'){

                    if(this.selected_left == true){
                        obj['style'] = 'dsp'
                    } else if(this.selected_left == 'facade_simple'){
                        obj['style'] = 'simple'
                    } else {
                        obj['style'] = this.style_left;
                    }


                } else {

                    if(this.selected_right == true){
                        obj['style'] = 'dsp'
                    } else if(this.selected_right == 'facade_simple'){
                        obj['style'] = 'simple'
                    } else {
                        obj['style'] = this.style_right;
                    }


                }

                this.$root.$emit('select_cabinet_side', obj)
            }

        }
    })

    Vue.component('selected_material', {
        template: '#selected_material_template',
        props: {
            selected: {
                type: [String, Number],
                default: null
            },
            cropper_enabled: 0,
            lib: {
                type: String,
                default: 'materials'
            },
            heading: {
                type: String,
                default: 'Текущий материал'
            },
            f_key: {
                type: String,
                default: null
            },
            add_data: {
                type: Object,
                default: null
            }
        },
        mixins: [mixin],
        data: function () {
            return {
                _selected: 0,
                item: {},
                _lib: ''
            };
        },
        watch: {
            selected: function (n_val, o_val) {
                this.cr();
            },
            lib: function (n_val, o_val) {
                this.cr();
            }
        },
        created: function () {
            this.cr();
        },
        methods: {
            cr: function () {
                let lib = '';
                switch (this.lib) {
                    case 'materials':
                        lib = materials_lib;
                        break;
                    case 'glass':
                        lib = glass_lib;
                        break;
                    case 'washers':
                        lib = washes_materials_lib;
                        break;
                    default:
                        lib = materials_lib;
                }

                this._lib = this.lib;

                if (typeof this.selected == 'string') {
                    this._selected = utils.nested_object.get(project_settings, this.selected)
                } else {
                    this._selected = this.selected;
                }

                this.$options.lib = lib;
                this.item = this.$options.lib.get_item(this._selected)

            },

            check_embedded_image: function(){
                let result = null;
                if(this.$root.$selected_object){
                    let obj_id = this.$root.$selected_object.params.obj_id;
                    let key = this.add_data.key;
                    let id = obj_id + ':' + key;
                    let el = document.getElementById(id);
                    if(el){
                        result = {
                            'background-size': 'cover',
                            'background-image': 'url('+ el.src +')'
                        };
                    }
                }

                return result;
            },

            find_item: function (id, data = null) {
                if(this.cropper_enabled){
                    this.$root.$emit('cropper_modal_show', copy_object(this.add_data))
                    return;
                }

                if(this.add_data != null){
                    this.$emit('find_item', {
                        id: id,
                        data: this.add_data
                    });
                    return;
                }
                if (this.f_key != null) {
                    this.$emit('find_item', {
                        id: id,
                        key: this.f_key
                    });
                } else {
                    this.$emit('find_item', id);
                }

            }
        }
    })

    Vue.component('selected_material_multiple', {
        template: '#selected_material_multiple_template',
        props: {
            selected: {
                type: [String, Number],
                default: null
            },
            selected2: {
                type: [String, Number],
                default: null
            },
            lib: {
                type: String,
                default: 'materials'
            },
            heading: {
                type: String,
                default: 'Текущий материал'
            },
            f_key: {
                type: String,
                default: null
            },
            is_equal: {
                type: Boolean,
                default: false
            }
        },
        mixins: [mixin],
        data: function () {
            return {
                _selected: 0,
                _selected2: 0,
                item: {},
                item2: {},
                _lib: ''
            };
        },
        watch: {
            selected: function (n_val, o_val) {
                this.cr();
            },
            selected2: function (n_val, o_val) {
                this.cr();
            },
            lib: function (n_val, o_val) {
                this.cr();
            }
        },
        created: function () {
            this.cr();
        },
        methods: {
            cr: function () {
                let lib = '';
                switch (this.lib) {
                    case 'materials':
                        lib = materials_lib;
                        break;
                    case 'glass':
                        lib = glass_lib;
                        break;
                    default:
                        lib = materials_lib;
                }

                this._lib = this.lib;

                if (typeof this.selected == 'string') {
                    this._selected = utils.nested_object.get(project_settings, this.selected)
                } else {
                    this._selected = this.selected;
                }

                if (typeof this.selected2 == 'string') {
                    this._selected2 = utils.nested_object.get(project_settings, this.selected2)
                } else {
                    this._selected2 = this.selected2;
                }


                this.$options.lib = lib;
                this.item = this.$options.lib.get_item(this._selected)
                this.item2 = this.$options.lib.get_item(this._selected2)


            },

            rotate_texture: function(group){
                sc.change_facade_material({
                    id: parseInt(this._selected2),
                    rotated: !project_settings.selected_materials[group].facades_rotated,
                    group: group
                });
            },

            find_item: function (id, group) {

                if (this.f_key != null) {
                    this.$emit('find_item', {
                        id: id,
                        key: this.f_key,
                    });
                } else {
                    this.$emit('find_item', id);
                }


            }
        }
    })

    Vue.component('selected_material_ext', {
        template: '#selected_material_template_ext',
        props: {
            selected: {
                type: [Object, String],
                default: {
                    id: 0,
                    key: 'type_0',
                }
            },
            lib: {
                type: String,
                default: 'materials'
            },
            types_list: {
                type: String,
                default: null
            },
            heading: {
                type: String,
                default: 'Текущий материал'
            }
        },
        mixins: [mixin],
        data: function () {
            return {
                _selected: {
                    key: 'type_0',
                    id: 0
                },
                item: {},
                _lib: '',
                type_name: '',
            };
        },
        watch: {
            selected: function (n_val, o_val) {
                this.cr();
            },
            lib: function (n_val, o_val) {
                this.cr();
            }
        },
        created: function () {
            this.cr();
        },
        methods: {
            cr: function () {
                let lib = '';
                switch (this.lib) {
                    case 'materials':
                        lib = materials_lib;
                        break;
                    case 'glass':
                        lib = glass_lib;
                        break;
                    default:
                        lib = materials_lib;
                }

                this._lib = this.lib;

                if (typeof this.selected == 'string') {
                    let sel = copy_object(utils.nested_object.get(project_settings, this.selected))
                    if (!sel.id && sel.default) sel.id = sel.default;
                    delete sel.default;
                    this._selected = sel
                } else {
                    this._selected = this.selected;
                }

                let list = copy_object(utils.nested_object.get(project_settings, this.types_list))
                this.type_name = list[this._selected.key].name

                this.$options.lib = lib;
                this.item = this.$options.lib.get_item(this._selected.id)
            },

            find_item: function (id) {
                this.$emit('find_item', {
                    id: id,
                    key: this._selected.key
                });
            }
        }
    })

    Vue.component("lockers_component_new", {
        template: '#lockers_params_template',
        created: function(){
            this.$options.lockers_types = lockers_types;
            this.$options.test = {
                1: 1,
                0: 0
            }
            this.init();
        },
        data: function() {
            return {
                params: null,
            };
        },
        methods: {
            init: function(){
                let scope = this;
                Vue.set(scope, 'params', active_el.get_lockers_by_position())
            },
            root_update: function(){
                this.$root.$emit('obj_price_update')
                this.$root.$emit('module_menu_recalc')
            },
            change_param: function(item, param, is_type){
                let obj = {
                    key: param
                }
                if(is_type){
                    obj.val = item.type_params[param]
                } else {
                    obj.val = item[param]
                }


                active_el.set_locker_param(obj, is_type, 'l', item.index, item.sec_index)
                this.root_update()
            },

            check_inner_available: function(item){
                let flag = 0;
                let type = item.type;
                let l = active_el.find_child_by_index('l', item.index, item.sec_index)
                let h = l.obj.params.height
                Object.keys(lockers_types[type].types).forEach(function (key) {
                    if(lockers_types[type].types[key].is_inner == 1){
                        if (h >= lockers_types[type].types[key].min_facade_height && h <= lockers_types[type].types[key].max_facade_height) flag = 1;
                    }
                })
                return flag
            },
        }
    });

    Vue.component('kitchen_style_picker', {
        template: '#kitchen_style_picker_template',
        props: {
            selected: {
                type: [String, Number],
                default: 0,
            },
            lib: {
                type: String,
                default: 'materials'
            },
            mode: {
                type: String,
                default: 'p'
            },
            fn: {
                type: String,
                default: ''
            },
            emit: {
                type: Boolean,
                default: false
            }
        },
        mixins: [mixin],
        data: function () {
            return {
                expanded: false,
                l_mode: '',
                l_selected: 0,
                sel_desc: {
                    icon: '',
                    name: ''
                }
            };
        },

        watch: {
            l_selected: function (n_val, o_val) {
                this.sel_desc.icon = kitchen_styles[ks_map[this.l_selected]].icon
                this.sel_desc.name = kitchen_styles[ks_map[this.l_selected]].name
            },
        },

        created: function () {
            console.log(this.selected)
            this.$options.items = kitchen_styles;

            this.l_selected = this.selected
            if(this.l_selected == 0) this.l_selected = project_settings.id;

            this.l_mode = this.mode
            this.sel_desc.icon = kitchen_styles[ks_map[this.l_selected]].icon
            this.sel_desc.name = kitchen_styles[ks_map[this.l_selected]].name

            this.$root.$on('set_kitchen_style_selected', (data)=> {
                this.l_selected = data;
            })

        },

        methods: {
            item_style: function(id){
                let res = {};

                let item = kitchen_styles[ks_map[id]]
                if(item.icon == ''){
                    res['background-image'] = 'url(/common_assets/images/no_image.png)';
                } else {
                    res['background-image'] = 'url(' + item.icon + ')';
                }

                return res;
            },
            set_panel_height: function () {
                let refs = this.$refs;
                refs.body.style.height = 'auto';

                setTimeout(function () {
                    let h = refs.comp_el.parentElement.offsetHeight - refs.heading.offsetHeight;
                    if (refs.bread) h -= refs.bread.offsetHeight
                    refs.body.style.height = h + 'px';
                }, 1)
            },
            select_item: function (item) {
                // this.l_selected = item.id

                global_temp.kitchen_style_id = item.id;

                change_kitchen_style(item.id, true, true)
            },
            emit_select: function(item){
                this.$emit('change_material', item);
            },
            find_item: function (id) {
                this.set_panel_height();
                this.l_mode = 'c';
            },
            selected_item: function () {

            }
        }
    })
    Vue.component('selected_item', {
        template: '#selected_item_template',
        props: {
            selected: {
                type: [String, Number],
                default: 0,
            },
            lib: {
                type: String,
                default: 'materials'
            },
            heading: {
                type: String,
                default: ''
            }
        },
        mixins: [mixin],
        created: function(){
            this.$options.lib = libs[this.lib].get_item(this.selected);
        },
        computed: {
            item: function (){
                return libs[this.lib].get_item(this.selected);
            }
        },
        data: function () {
            return {

            };
        },
        methods: {
            select_category(it, index){

            },
            find_item(id){
                this.$emit('find_item', {
                    id: id,
                });
            }
        }
    })


    Vue.component('object_picker', {
        template: '#object_picker_template',
        data: function () {
            return {
                items: []
            };
        },
    })
    Vue.component('room_params', {
        template: '#room_params_template',
        mixins: [mixin],
        data: function () {
            return {
                w: 0,
                h: 0,
                d: 0,
                l_mode: 'p',
                delay: typeof input_delay === 'undefined' ? 500 : input_delay,
                debounce: null,
            };
        },
        created: function () {
            try {
                let obj = sc.get_room_size();
                this.w = obj.w
                this.h = obj.h
                this.d = obj.d
            } catch (e) {
                console.error('room_params')
            }
        },
        methods: {
            apply: function () {

                clearTimeout(this.debounce)
                let scope = this;
                this.debounce = setTimeout(function () {
                    sc.set_room_size(scope.w, scope.h, scope.d)
                }, this.delay)

            }
        }
    })
    Vue.component('kitchen_params', {
        template: '#kitchen_params_template',
        props: ['test'],
        data: function () {
            return {
                tabletop_thickness: 0,
                cokol_height: 0,
                cokol_float: false,
                dsp_thickness: 16,
                dsp_variants: [16, 18],
                delay: 0,
            };
        },
        created: function () {
            this.tabletop_thickness = project_settings.tabletop_thickness
            this.cokol_height = project_settings.cokol_height
            this.delay = input_delay;
            if (project_settings.cokol_disabled == undefined) project_settings.cokol_disabled = false;

            this.cokol_float = project_settings.cokol_disabled;

        },
        methods: {
            lang: function (key) {
                return this.$root.lang(key)
            },
            change_tabletop_thickness: function (e) {
                clearTimeout(this.debounce)
                let scope = this;
                this.debounce = setTimeout(function () {
                    change_tabletop_thickness(scope.tabletop_thickness)
                }, this.delay)
            },
            change_cokol_height: function () {
                clearTimeout(this.debounce)
                let scope = this;
                this.debounce = setTimeout(function () {
                    change_cokol_height(scope.cokol_height)
                }, this.delay)
            },
            change_cokol_float: function () {
                project_settings.cokol_disabled = this.cokol_float;

                for (let i = room.active_objects.length; i--;) {
                    if (room.active_objects[i].type === 'Cabinet' && room.active_objects[i].params.cabinet_group === 'bottom') {
                        room.active_objects[i].params.cokol.disabled = this.cokol_float
                        room.active_objects[i].build_cokol()
                    }
                }
            },
            change_dsp_thickness: function () {
                for (let i = room.active_objects.length - 1; i >= 0; i--) {
                    if (room.active_objects[i].type === 'Cabinet') {
                        room.active_objects[i].params.cabinet.thickness = parseInt(this.dsp_thickness)
                        room.active_objects[i].build();
                    }
                }

            }
        }
    })
    Vue.component('cabinet_tree_item', {
        template: '#cabinet_tree_item',
        props: ['item', 'current'],
        data: function () {
            return {
                is_open: false
            };
        },
        computed: {
            is_folder: function () {
                // return this.item.children && this.item.children.length;
                return this.item.type == 'folder' && this.item.children.length;
            },
            is_current: function () {
                return this.item.id == this.current
            }
        },
        methods: {
            toggle: function () {
                if (!this.item.is_open) Vue.set(this.item, 'is_open', false)
                if (this.is_folder) {
                    this.is_open = !this.is_open;
                    this.item.is_open = !this.item.is_open;
                    this.$emit("toggle", this.item);
                }
            },
            select_folder_t: function () {
                this.select_folder(this.item)
            },
            select_folder: function (item) {
                this.$emit("select_folder", item);
                this.is_open = true;
                Vue.set(this.item, 'is_open', true)
            },
            make_folder: function () {
                if (!this.is_folder) {
                    this.$emit("make_folder", this.item);
                    this.is_open = true;
                }
            }
        }
    })
    Vue.component("cabinet_conf_block", {
        template: '#cabinet_conf_block',

        data: function () {
            return {

                params: [],
                is_object: 0,


                current_item: -1,


            };
        },
        created: async function () {
            let scope = this;

            this.$options.block_keys = ['sections', 'walls', 'doors', 'lockers', ''];

            scope.$options.selected = null
            scope.$root.$on('set_selected', function (obj) {
                scope.$options.selected = obj
                scope.is_object = obj != null;
                if (obj != null) {
                    if (obj.userData.params) {
                        Vue.set(scope, 'params', copy_object(obj.userData.params.spec))
                    }

                } else {
                    Vue.set(scope, 'params', [])
                }

                console.log(scope.params)

            })
        },


        computed: {},
        methods: {

            toggle: function () {

            }
        }
    });


    let opts = {

        cabinet: [
            {
                key: 'cabinet',
                type: 'single',
                name: 'Базовые параметры',
                children: [
                    // {
                    //     key: 'group',
                    //     root_param: true,
                    //     name: 'Группа',
                    //     type: 'select',
                    //     values: [
                    //         {
                    //             label: 'Верхний',
                    //             val: 'top'
                    //         },
                    //         {
                    //             label: 'Нижний',
                    //             val: 'bottom'
                    //         }
                    //     ]
                    // },
                    {
                        key: 'width',
                        name: 'Ширина',
                        type: 'number'
                    },
                    {
                        key: 'height',
                        name: 'Высота',
                        type: 'number'
                    },
                    {
                        key: 'depth',
                        name: 'Глубина',
                        type: 'number'
                    },
                ],
            }
        ],

        shelves:[
            {
                key: 'shelves',
                type: 'multi',
                name: 'Базовые параметры',
                children: [
                    {
                        key: 'width',
                        name: 'Ширина',
                        type: 'input%'
                    },
                    {
                        key: 'height',
                        name: 'Толщина',
                        type: 'number'
                    },
                    {
                        key: 'starting_point_x',
                        name: 'Позиция по гор.',
                        type: 'input%'
                    },
                    {
                        key: 'starting_point_y',
                        name: 'Позиция по верт.',
                        type: 'input%'
                    },
                ],
            }
        ],

        doors:[
            {
                key: 'doors',
                type: 'multi',
                name: 'Базовые параметры',
                children: [
                    {
                        key: 'type',
                        name: 'Тип открывания',
                        type: 'select',
                        values: [
                            {
                                label: 'Налево',
                                val: 'ltr'
                            },
                            {
                                label: 'Направо',
                                val: 'rtl'
                            },
                            {
                                label: 'Вверх',
                                val: 'simple_top'
                            },
                            {
                                label: 'Вниз',
                                val: 'simple_bottom'
                            },
                            {
                                label: 'Aventos HL',
                                val: 'front_top'
                            },
                            {
                                label: 'Aventos HF',
                                val: 'double_top'
                            },
                            {
                                label: 'Фальшфасад',
                                val: 'falsefacade'
                            },
                            {
                                label: 'Купе налево',
                                val: 'coupe_l'
                            },
                            {
                                label: 'Купе направо',
                                val: 'coupe_r'
                            },
                        ],
                    },
                    {
                        key: 'style',
                        name: 'Тип фасада',
                        type: 'select',
                        values: [
                            {
                                label: 'Глухой',
                                val: 'full'
                            },
                            {
                                label: 'Витрина',
                                val: 'window'
                            },
                            {
                                label: 'Решетка',
                                val: 'frame'
                            }
                        ],
                    },
                    {
                        key: 'width',
                        name: 'Ширина',
                        type: 'input%'
                    },
                    {
                        key: 'height',
                        name: 'Высота',
                        type: 'input%'
                    },
                    {
                        key: 'starting_point_x',
                        name: 'Позиция по гор.',
                        type: 'input%'
                    },
                    {
                        key: 'starting_point_y',
                        name: 'Позиция по верт.',
                        type: 'input%'
                    },
                    {
                        key: 'offset_top',
                        name: 'Отступ сверху (мм)',
                        type: 'number'
                    },
                    {
                        key: 'offset_bottom',
                        name: 'Отступ снизу (мм)',
                        type: 'number'
                    },
                    {
                        key: 'handle_position',
                        name: 'Ручка',
                        type: 'select',
                        values: [
                            {
                                label: 'Сверху',
                                val: 'top'
                            },
                            {
                                label: 'По центру',
                                val: 'center'
                            },
                            {
                                label: 'Снизу',
                                val: 'bottom'
                            }
                        ],
                    },
                ],
            }
        ],


        lockers:[
            {
                key: 'lockers',
                type: 'multi',
                name: 'Базовые параметры',
                children: [
                    {
                        key: 'style',
                        name: 'Тип фасада',
                        type: 'select',
                        values: [
                            {
                                label: 'Глухой',
                                val: 'full'
                            },
                            {
                                label: 'Витрина',
                                val: 'window'
                            },
                            {
                                label: 'Решетка',
                                val: 'frame'
                            }
                        ],
                    },
                    {
                        key: 'inner',
                        name: 'Тип ящика',
                        type: 'select',
                        values: [
                            {
                                label: 'Обычный',
                                val: false
                            },
                            {
                                label: 'Внутренний',
                                val: true
                            },

                        ],
                    },

                    {
                        key: 'width',
                        name: 'Ширина',
                        type: 'input%'
                    },
                    {
                        key: 'height',
                        name: 'Высота',
                        type: 'input%'
                    },
                    {
                        key: 'starting_point_x',
                        name: 'Позиция по гор.',
                        type: 'input%'
                    },
                    {
                        key: 'starting_point_y',
                        name: 'Позиция по верт.',
                        type: 'input%'
                    },
                ],
            }
        ],



    }

    // if(typeof conf_m == "function") conf_m();



    Vue.component('component_number_with_select', {
        template: '#component_number_with_select',
        props: {
            value: [Number, String],
            delay: {
                type: Number,
                default: typeof input_delay === 'undefined' ? 500 : input_delay
            }
        },
        created: function () {
            if (typeof this.value == 'string' && this.value[this.value.length - 1] == '%') this.select_value = '%';
            this.input_value = parseFloat(this.value)

            this.$options.timeout = null;
        },
        mounted: function () {
            console.log(this.delay)
        },

        computed: {
            return_val: function () {
                return this.select_value == '%' ? this.input_value + this.select_value : parseFloat(this.input_value)
            }
        },
        data: function () {
            return {
                input_value: 0,
                select_value: '_',
                timeout: null
            };
        },
        methods: {
            on_change: function (e) {
                let scope = this;
                scope.$emit('input', scope.return_val)
                // clearTimeout(this.$options.timeout)
                // this.$options.timeout = setTimeout(function () {
                //     console.log(scope.return_val)
                //
                // }, this.delay)
            },

            lang: function (key) {
                return lang[key]
            },
        }
    })

    Vue.component('conf_new', {
        template: '#conf_block',
        props: {
            param: String,
        },
        created: function () {

            this.cr();


        },
        watch:{
            param: function(){
                this.cr();

            }
        },
        mounted: function () {
        },
        data: function () {
            return {
                params: [],
                selected_block: 0,
                is_object: 0,
                mode: 0,
                test_val: 0,
                delay: typeof input_delay === 'undefined' ? 500 : input_delay,
                debounce: null,
            };
        },
        methods: {
            cr: function(){
                let scope = this;
                let sel_obj = scope.$root.$selected_object;
                Vue.set(scope, 'params', copy_object(sel_obj.params[scope.param]))
                this.$options.interface = opts[this.param]

                console.log(this.$options.interface)
                console.log(this.param)
                if(this.params == 'doors'){
                    console.log(this.params[0].thickness)
                    console.log(this.params[1].thickness)
                }

            },

            add_item: function(){

                let add_obj = {
                    active:true
                };

                if(this.param == 'shelves'){
                    add_obj.v2 = 1;
                    add_obj.width = '100%';
                    add_obj.height = 16;
                    add_obj.starting_point_y = '0%';
                    add_obj.starting_point_x = '0%';

                }

                let sel_obj = this.$root.$selected_object;
                sel_obj.params[this.param].push(add_obj)
                sel_obj.build()
                this.cr();
                this.$parent.set_height()
                setTimeout(()=>{

                },100)
            },

            remove_item: function(index){
                let sel_obj = this.$root.$selected_object;
                sel_obj.params[this.param].splice(index, 1)
                sel_obj.build()
                this.cr();
            },

            change_param: function(block, param, is_root){
                let val = this.params[param];
                let sel_obj = this.$root.$selected_object;
                clearTimeout(this.debounce)
                this.debounce = setTimeout(function () {
                    sel_obj.params[block][param] = val;
                    sel_obj.build();
                }, this.delay)
            },
            change_param_multi: function(block, index, param, is_root){
                let val = this.params[index][param];
                let sel_obj = this.$root.$selected_object;


                clearTimeout(this.debounce)
                this.debounce = setTimeout(function () {

                    sel_obj.params[block][index][param] = val;
                    sel_obj.build();
                }, this.delay)
            },

            update_object: function () {

                if (this.$options.selected.userData.params) {
                    this.$options.selected.userData.params[this.param] = copy_object(this.params)
                    this.$options.selected.build();
                } else {
                    this.$options.selected.params[this.param] = copy_object(this.params)
                    this.$options.selected.build();
                }


            },

            lang: function (key) {
                return lang[key]
            },

            select_block: function (index) {
                // selection_box.setFromObject(this.$options.selected[this.param].children[index])
                // selection_box.visible = true;
            },

            mouse_enter: function (index) {

                if (this.$options.selected.userData.params) {
                    selection_box.setFromObject(this.$options.selected.userData.parts[this.param].children[index])
                    selection_box.visible = true;
                } else {
                    selection_box.setFromObject(this.$options.selected[this.param].children[index])
                    selection_box.visible = true;
                }


            },
            mouse_leave: function () {
                selection_box.visible = false;
            }


        }
    })


    const kitchen_models_template = `
        <div>
            <div v-show="mode == 0">
                <div v-for="(model, index) in $options.models">
                    <div @click="select_model(model,index)">
                        <div><img :src="model.icon" alt=""></div>
                        <div>{{model.name}}</div>
                    </div>
                </div>
            </div>
            <div v-if="mode == 1">
               <div @click="mode = 0">Назад</div>
               <div>
                    <div v-for="item in $options.modules.items">
                         <div @mousedown="add_module($event, item)"><img :src="item.icon" alt=""></div>
                          <div>{{item.name}}</div>
                    </div>
               </div>
            </div>
        </div>
        
    `

    Vue.component('variables_component', {
        template: '#variables_component_template',
        props: {
            variables: {
                type: Array,
                default: []
            }
        },
        mixins: [mixin],
        data: function () {
            return {
                delay: typeof input_delay === 'undefined' ? 500 : input_delay,
                debounce: null,
            };
        },


        created: function () {

        },

        methods: {
            change_select(item){

                this.$root.$selected_object.change_variable(item.key, item.value);
            },
            change_number(item){

                clearTimeout(this.debounce)
                this.debounce = setTimeout(() => {
                    console.log(item)
                    this.$root.$selected_object.change_variable(item.key, item.value);
                }, this.delay)
            }
        }
    })

    Vue.component("kitchen_models_component", {

        props: {
            type_key: String,
            mousedown: Number,
            colorpicker: Boolean,
            single: Boolean,
            active: Boolean,
        },

        template: kitchen_models_template,

        created: function () {
            let scope = this;
            this.$options.models = kitchen_styles;

        },
        mounted: function () {


        },
        computed: {},
        data: function () {
            return {
                selected_model: -1,
                mode: 0
            };
        },
        methods: {

            select_model: async function (model, index) {
                console.log(model)
                console.log(index)

                modules = await promise_request('data/module_sets/' + model.modules_set_id + '.json' + date_timestamp)

                this.$options.modules = modules
                this.mode = 1;
                this.selected_model = index;
                this.$forceUpdate();

                console.log(modules)

            },

            add_module: function (e, item) {
                e.preventDefault();
                console.log(item)

                let params = copy_object(item.params)
                // let params = copy_object(mh.items_hash[id].params);

                params.id = item.id;

                sc.add_object('cabinet', params);

            },

            lang: function (key) {
                return lang[key]
            },

        }
    });
}

function update_info_panel_accessories_list(cab) {
    bpi.$emit('accessories_list_update')
}

var isOutOfViewport = function (elem) {

    // Get element's bounding
    var bounding = elem.getBoundingClientRect();

    console.log(elem)
    console.log(bounding)

    // Check if it's out of the viewport on each side
    var out = {};
    out.top = bounding.top < 0;
    out.left = bounding.left < 0;
    out.bottom = bounding.bottom > (window.innerHeight || document.documentElement.clientHeight);
    out.right = bounding.right > (window.innerWidth || document.documentElement.clientWidth);
    out.any = out.top || out.left || out.bottom || out.right;
    out.all = out.top && out.left && out.bottom && out.right;

    return out;

};

function convert_height(tree) {
    let heights = {};

    if (tree.items) {

        for (let i = 0; i < tree.items.length; i++) {
            let it = tree.items[i];

            for (let v = 0; v < it.params.variants.length; v++) {
                let variant = it.params.variants[v];

                if (!heights[variant.height]) {
                    heights[variant.height] = {};
                }

                if (!heights[variant.height][it.id]) heights[variant.height][it.id] = it.id;

            }
        }
    }

    if (tree.categories) {
        for (let i = 0; i < tree.categories.length; i++) {
            convert_height(tree.categories[i])
        }
    }

    if (tree.categories) {
        Object.keys(heights).forEach(function (k) {
            let cat = {
                id: tree.id + '_' + k,
                name: lang['height'] + ' ' + k + ' ' + lang['mm'],
                active: 1,
                is_height: 1,
                items: [],
                categories: []
            }

            Object.keys(heights[k]).forEach(function (key) {
                cat.items.push(mod_lib.items[key])
            })

            mod_lib.categories[cat.id] = cat;

            tree.categories.push(cat)

        })

    }

}

function move_info_panel() {}
function hide_info_panel() {}
function stop_info_panel() {}
function hide_state_panel() {}