document.addEventListener('DOMContentLoaded', function () {
    let base_url = document.getElementById('ajax_base_url').value;
    let lang_url = base_url + '/languages/get_converted_lang_ajax'

    let prices = JSON.parse(document.getElementById('js_prices').value);
    console.log(copy_object(prices))

    if(!prices.cornice) {
        prices.cornice = {
            width: 0,
            common: 0,
            radius: 0,
            radius_i: 0,
        }
    }

    if(!prices.tabletop_plints){
        if(prices.tabletop){
            prices.tabletop_plints = JSON.parse(JSON.stringify(prices.tabletop));
            for (let i = prices.tabletop_plints.length; i--;){
                prices.tabletop_plints[i].price = 0;
            }
        }
    }

    let categories = JSON.parse(document.getElementById('mat_cats').value);
    let cats = convert_lib(categories)
    let ch = get_hash(categories)

    let glass_categories = JSON.parse(document.getElementById('glass_cats').value);
    let glass_cats = convert_lib(glass_categories)
    let glass_ch = get_hash(glass_categories)

    Promise.all([
        promise_request(lang_url),
    ]).then(function (results) {
        if(results[0].custom){
            lang_data = Object.assign(results[0].custom, results[0].original)
        } else {
            lang_data = results[0].original
        }
        console.log('Done');
        init_vue();
    }).catch(function () {
        console.log('Error');
    });

    function init_vue(){
        app = new Vue({
            el: '#sub_form',
            data: {
                errors: [],
                settings: {},
                show_success_message: false,
                base_url: document.getElementById('ajax_base_url').value,
                corpus:[],
                cokol: [],
                tabletop: [],
                tabletop_plints: [],
                wallpanel: [],
                glass:[],
                back_wall: {},
                glass_shelves:{},
                edge:{},
                cornice: {
                    width: 0,
                    common: 0,
                    radius: 0,
                    radius_i: 0,
                },
                first_back_wall_key: null,
                first_glass_shelves_key: null
            },
            computed: {

            },
            created: function () {

                let scope = this;

                this.$options.prices = prices;
                this.$options.lang = lang_data;
                this.$options.tree = cats;
                this.$options.hash = ch;


                this.$options.glass_tree = glass_cats;
                this.$options.glass_hash = glass_ch;

                this.$options.dsp_thickness = JSON.parse(document.getElementById('dsp_thickness').value);
                this.$options.corpus_selected = JSON.parse(document.getElementById('corpus_sel').value);
                this.$options.edge_selected = JSON.parse(document.getElementById('edge_sel').value);
                this.$options.cokol_selected = JSON.parse(document.getElementById('cokol_sel').value);
                this.$options.tabletop_selected = JSON.parse(document.getElementById('tabletop_sel').value);
                this.$options.tabletop_plints_selected = JSON.parse(document.getElementById('tabletop_sel').value);
                this.$options.wallpanel_selected = JSON.parse(document.getElementById('wallpanel_sel').value);
                this.$options.glass_selected = JSON.parse(document.getElementById('glass_sel').value);

                if(document.getElementById('back_wall_sel').value == ''){
                    this.$options.back_wall_selected = {};
                } else {
                    this.$options.back_wall_selected = JSON.parse(document.getElementById('back_wall_sel').value);
                }
                if(document.getElementById('glass_shelves_sel').value == ''){
                    this.$options.glass_shelves_selected = {};
                } else {
                    this.$options.glass_shelves_selected = JSON.parse(document.getElementById('glass_shelves_sel').value);
                }

                let corp = {};

                for (let i = 0; i < this.$options.dsp_thickness.length; i++){
                    corp[this.$options.dsp_thickness[i]] = [];
                    for (let j = 0; j < this.$options.corpus_selected.length; j++){
                        let cat = this.$options.tree[this.$options.corpus_selected[j]]
                        if(!cat) continue
                            corp[this.$options.dsp_thickness[i]].push(copy_object(cat))

                            for (let c = 0; c < cat.children.length; c++ ){
                                corp[this.$options.dsp_thickness[i]].push(copy_object(cat.children[c]))
                            }
                    }
                }

                let edge = [];
                for (let j = 0; j < this.$options.edge_selected.length; j++){
                    let cat = this.$options.tree[this.$options.edge_selected[j]]
                    if(!cat) continue
                    edge.push(copy_object(cat))
                    for (let c = 0; c < cat.children.length; c++ ){
                        edge.push(copy_object(cat.children[c]))
                    }
                }

                if(!scope.$options.prices.edge) scope.$options.prices.edge = copy_object(edge)

                let back_wall = {};
                Object.keys(this.$options.back_wall_selected).forEach(function (key) {
                    let type = scope.$options.back_wall_selected[key];

                    if(type.mode == 'custom'){

                        back_wall[key] = [];

                        for (let j = 0; j < type.materials.length; j++){
                            let cat = scope.$options.tree[type.materials[j]]
                            if(!cat) continue
                                back_wall[key].push(copy_object(cat))
                                for (let c = 0; c < cat.children.length; c++) {
                                    back_wall[key].push(copy_object(cat.children[c]))
                                }

                        }
                    }

                    if(type.mode == 'custom_as_corpus'){

                        back_wall[key] = [];

                        for (let j = 0; j < type.materials.length; j++){
                            let cat = scope.$options.tree[type.materials[j]]
                            if(!cat) continue
                                back_wall[key].push(copy_object(cat))
                                for (let c = 0; c < cat.children.length; c++) {
                                    back_wall[key].push(copy_object(cat.children[c]))
                                }

                        }
                    }


                })


                let glass_shelves = {};
                Object.keys(this.$options.glass_shelves_selected).forEach(function (key) {
                    let type = scope.$options.glass_shelves_selected[key];

                    if(type.mode == 'custom'){

                        glass_shelves[key] = [];

                        for (let j = 0; j < type.materials.length; j++){
                            let cat = scope.$options.tree[type.materials[j]]
                            if(!cat) continue
                            glass_shelves[key].push(copy_object(cat))
                            for (let c = 0; c < cat.children.length; c++) {
                                glass_shelves[key].push(copy_object(cat.children[c]))
                            }

                        }
                    }

                })


                let cokol = [];
                for (let j = 0; j < this.$options.cokol_selected.length; j++){
                    let cat = this.$options.tree[this.$options.cokol_selected[j]]
                    if(!cat) continue
                    cokol.push(copy_object(cat))
                    for (let c = 0; c < cat.children.length; c++ ){
                        cokol.push(copy_object(cat.children[c]))
                    }
                }

                let tabletop = [];
                for (let j = 0; j < this.$options.tabletop_selected.length; j++){
                    let cat = this.$options.tree[this.$options.tabletop_selected[j]]


                    if(!cat) continue
                        tabletop.push(copy_object(cat))
                        for (let c = 0; c < cat.children.length; c++ ){
                            tabletop.push(copy_object(cat.children[c]))
                        }



                }

                let tabletop_plints = [];
                for (let j = 0; j < this.$options.tabletop_selected.length; j++){
                    let cat = this.$options.tree[this.$options.tabletop_selected[j]]

                    if(!cat) continue

                    tabletop_plints.push(copy_object(cat))
                    for (let c = 0; c < cat.children.length; c++ ){
                        tabletop_plints.push(copy_object(cat.children[c]))
                    }
                }


                let wallpanel = [];
                for (let j = 0; j < this.$options.wallpanel_selected.length; j++){
                    let cat = this.$options.tree[this.$options.wallpanel_selected[j]]
                    if(!cat) continue
                    wallpanel.push(copy_object(cat))
                    for (let c = 0; c < cat.children.length; c++ ){
                        wallpanel.push(copy_object(cat.children[c]))
                    }
                }


                let glass = [];
                if( this.$options.glass_selected == null)  this.$options.glass_selected = [];
                for (let j = 0; j < this.$options.glass_selected.length; j++){
                    let cat = this.$options.glass_tree[this.$options.glass_selected[j]]
                    if(!cat) continue
                    glass.push(copy_object(cat))
                    for (let c = 0; c < cat.children.length; c++ ){
                        glass.push(copy_object(cat.children[c]))
                    }
                }



                if(!this.$options.prices.back_wall){
                    this.$options.prices.back_wall = copy_object(back_wall)
                }

                Object.keys(this.$options.prices.back_wall).forEach(function (key) {
                    if(!back_wall[key]) delete scope.$options.prices.back_wall[key]
                })

                Object.keys(back_wall).forEach(function (key) {
                    if(!scope.$options.prices.back_wall[key]) scope.$options.prices.back_wall[key] = copy_object(back_wall[key])
                })



                if(!this.$options.prices.glass_shelves){
                    this.$options.prices.glass_shelves = copy_object(glass_shelves)
                }

                Object.keys(this.$options.prices.glass_shelves).forEach(function (key) {
                    if(!glass_shelves[key]) delete scope.$options.prices.glass_shelves[key]
                })

                Object.keys(glass_shelves).forEach(function (key) {
                    if(!scope.$options.prices.glass_shelves[key]) scope.$options.prices.glass_shelves[key] = copy_object(glass_shelves[key])
                })


                Vue.set(this, 'corpus', corp)
                Vue.set(this, 'cokol', cokol)
                Vue.set(this, 'edge', edge)
                Vue.set(this, 'tabletop', tabletop)
                Vue.set(this, 'tabletop_plints', tabletop_plints)
                Vue.set(this, 'wallpanel', wallpanel)
                Vue.set(this, 'glass', glass)
                Vue.set(this, 'back_wall', back_wall)
                Vue.set(this, 'glass_shelves', glass_shelves)
                Vue.set(this, 'cornice', prices.cornice)

                this.first_back_wall_key = Object.keys(this.back_wall).shift();
                this.first_glass_shelves_key = Object.keys(this.glass_shelves).shift();


                console.log(copy_object(corp))



                if(!this.$options.prices.v2){

                    let p_keys = ['corpus', 'cokol', 'edge', 'tabletop', 'tabletop_plints', 'wallpanel', 'glass']

                    for (let z = 0; z < p_keys.length; z++){

                        let par = [];
                        let ch = [];

                        for (let i = 0; i < this.$options.prices[p_keys[z]].length; i++){
                            let price = this.$options.prices[p_keys[z]][i];

                            if(price.get_from_parent == false) par.push(price);
                            if(price.get_from_parent == true) ch.push(price);

                        }

                        for (let i = 0; i < ch.length; i++){
                            for (let p = 0; p < par.length; p++){
                                if(ch[i].parent == par[p].id) ch[i].price = par[p].price;
                            }
                        }

                    }

                    for (let i = 0; i < this.$options.dsp_thickness.length; i++){
                        for (let j = 0; j < scope.corpus[this.$options.dsp_thickness[i]].length; j++){
                            let cat = scope.corpus[this.$options.dsp_thickness[i]][j];

                            for (let p = 0; p < this.$options.prices.corpus.length; p++){
                                let price = this.$options.prices.corpus[p];
                                if(price.id == cat.id)cat.price = price.price;
                            }
                        }
                    }

                    for (let j = 0; j < scope.cokol.length; j++){
                        let cat = scope.cokol[j];

                        for (let p = 0; p < this.$options.prices.cokol.length; p++){
                            let price = this.$options.prices.cokol[p];
                            if(price.id == cat.id)cat.price = price.price;
                        }
                    }

                    for (let j = 0; j < scope.edge.length; j++){
                        let cat = scope.edge[j];

                        for (let p = 0; p < this.$options.prices.edge.length; p++){
                            let price = this.$options.prices.edge[p];
                            if(price.id == cat.id)cat.price = price.price;
                        }
                    }


                    for (let j = 0; j < scope.tabletop.length; j++){
                        let cat = scope.tabletop[j];

                        for (let p = 0; p < this.$options.prices.tabletop.length; p++){
                            let price = this.$options.prices.tabletop[p];
                            if(price.id == cat.id)cat.price = price.price;
                        }
                    }

                    for (let j = 0; j < scope.wallpanel.length; j++){
                        let cat = scope.wallpanel[j];

                        for (let p = 0; p < this.$options.prices.wallpanel.length; p++){
                            let price = this.$options.prices.wallpanel[p];
                            if(price.id == cat.id)cat.price = price.price;
                        }
                    }

                    for (let j = 0; j < scope.glass.length; j++){
                        let cat = scope.glass[j];

                        for (let p = 0; p < this.$options.prices.glass.length; p++){
                            let price = this.$options.prices.glass[p];
                            if(price.id == cat.id)cat.price = price.price;
                        }
                    }

                } else {

                    let keys = ['corpus', 'cokol', 'edge', 'tabletop', 'tabletop_plints', 'wallpanel', 'glass', 'back_wall', 'glass_shelves'];

                    //фикс не трогать
                    let le = this.$options.prices.glass.length;
                    for (let i = this.$options.prices.tabletop.length; i--;){
                        if(this.$options.glass_hash[this.$options.prices.tabletop[i].id]){
                            if(this.$options.glass_hash[this.$options.prices.tabletop[i].id].name == this.$options.prices.tabletop[i].name){
                                let d = this.$options.prices.tabletop[i];
                                this.$options.prices.tabletop.splice(i,1)




                                for (let j = 0; j < le; j++){
                                    console.log(le)
                                    if(d.parent == this.$options.prices.glass[j].id){
                                        this.$options.prices.glass.splice(j+1, 0, d)
                                    }
                                }



                            }

                        }
                    }
                    //

                    for (let i = keys.length; i--;){

                        //переименовал и чилдрены
                        let cp = this.$options.prices[keys[i]];
                        let sel = this.$options[keys[i] + '_selected']


                        // console.log(cp)
                        // console.log(sel)

                        if (keys[i] == 'corpus') {
                            // console.log(this.$options.prices[keys[i]])
                            // console.log(sel)
                            Object.keys(this.$options.prices[keys[i]]).forEach(function (k) {
                                let cpk = cp[k]

                                for (let s = sel.length; s--;) {

                                    let flag = 0;

                                    for (let c = cpk.length; c--;) {

                                        if (sel[s] == cpk[c].id) {
                                            flag = 1;
                                        }

                                        if(cpk[c].parent == 0){
                                            if(!sel.includes(cpk[c].id)){
                                                cpk.splice(c,1)
                                            }
                                        } else{
                                            if(!sel.includes(cpk[c].parent)){
                                                cpk.splice(c,1)
                                            }
                                        }
                                    }


                                    if (flag == 0) {

                                        let cat = scope.$options.tree[sel[s]];
                                        cpk.push(copy_object(cat))
                                        if(cat.children && cat.children.length){
                                            for (let j = 0; j < cat.children.length; j++){
                                                cpk.push(copy_object(cat.children[j]))
                                            }
                                        }
                                    }

                                    // console.log(flag)

                                    if(!scope.$options.tree[sel[s]]) continue

                                    if (scope.$options.tree[sel[s]].children) {
                                        for (let a = scope.$options.tree[sel[s]].children.length; a--;) {
                                            let flag = 0;
                                            for (let c = cpk.length; c--;) {
                                                if(scope.$options.tree[sel[s]].children[a].id == cpk[c].id){
                                                    flag = 1;
                                                    // cpk[c].name = scope.$options.tree[sel[s]].children[a].name;
                                                }
                                            }
                                            if(flag == 0) cpk.push(copy_object(scope.$options.tree[sel[s]].children[a]))

                                        }

                                        for (let c = cpk.length; c--;) {
                                            if(cpk[c].parent == 0 || cpk[c].parent != sel[s]) continue
                                            let flag = 0;
                                            for (let a = scope.$options.tree[sel[s]].children.length; a--;) {
                                                if(scope.$options.tree[sel[s]].children[a].id == cpk[c].id) flag = 1;
                                            }

                                            if(flag == 0) cpk.splice(c,1)
                                        }
                                    }



                                }

                                for (let c = cpk.length; c--;) {
                                    if(!scope.$options.hash[cpk[c].id]) continue
                                    cpk[c].name = scope.$options.hash[cpk[c].id].name;
                                }

                            })
                        } else if(keys[i] == 'back_wall' || keys[i] == 'glass_shelves'){

                            // console.log(this.$options.prices[keys[i]])


                            Object.keys(this.$options.prices[keys[i]]).forEach(function (k) {

                                let sel_bw = sel[k].materials


                                let cpk = cp[k]
                                for (let s = sel_bw.length; s--;) {


                                    let flag = 0;

                                    for (let c = cpk.length; c--;) {

                                        if (sel_bw[s] == cpk[c].id) {
                                            flag = 1;
                                        }

                                        if(cpk[c].parent == 0){
                                            if(!sel_bw.includes(cpk[c].id)){
                                                cpk.splice(c,1)
                                            }
                                        } else{
                                            if(!sel_bw.includes(cpk[c].parent)){
                                                cpk.splice(c,1)
                                            }
                                        }
                                    }


                                    if (flag == 0) {

                                        let cat = scope.$options.tree[sel_bw[s]];
                                        cpk.push(copy_object(cat))

                                        if(cat.children && cat.children.length){
                                            for (let j = 0; j < cat.children.length; j++){
                                                cpk.push(copy_object(cat.children[j]))
                                            }
                                        }
                                    }

                                    // console.log(flag)

                                    if (scope.$options.tree[sel_bw[s]].children) {
                                        for (let a = scope.$options.tree[sel_bw[s]].children.length; a--;) {
                                            let flag = 0;
                                            for (let c = cpk.length; c--;) {
                                                if(scope.$options.tree[sel_bw[s]].children[a].id == cpk[c].id){
                                                    flag = 1;
                                                    // cpk[c].name = scope.$options.tree[sel_bw[s]].children[a].name;
                                                }
                                            }
                                            if(flag == 0) cpk.push(copy_object(scope.$options.tree[sel_bw[s]].children[a]))

                                        }

                                        for (let c = cpk.length; c--;) {
                                            if(cpk[c].parent == 0 || cpk[c].parent != sel_bw[s]) continue
                                            let flag = 0;
                                            for (let a = scope.$options.tree[sel_bw[s]].children.length; a--;) {
                                                if(scope.$options.tree[sel_bw[s]].children[a].id == cpk[c].id) flag = 1;
                                            }

                                            if(flag == 0) cpk.splice(c,1)
                                        }
                                    }

                                }

                                for (let c = cpk.length; c--;) {
                                    if(!scope.$options.hash[cpk[c].id]) continue
                                    cpk[c].name = scope.$options.hash[cpk[c].id].name;
                                }

                            })
                        } else {


                            for (let s = sel.length; s--;) {
                                if(sel[s] == null) continue




                                let flag = 0;

                                for (let c = cp.length; c--;) {

                                    if (sel[s] == cp[c].id) {
                                        flag = 1;
                                    }

                                    if(cp[c].parent == 0){
                                        if(!sel.includes(cp[c].id)){
                                            cp.splice(c,1)
                                        }
                                    } else{
                                        if(!sel.includes(cp[c].parent)){
                                            cp.splice(c,1)
                                        }

                                    }




                                }



                                if (flag == 0) {
                                    let cat;
                                    if(keys[i] != 'glass'){
                                        cat = scope.$options.tree[sel[s]];
                                    } else {
                                        cat = scope.$options.glass_tree[sel[s]];
                                    }
                                    if(cat){
                                        cp.push(copy_object(cat))
                                        if(cat.children && cat.children.length){
                                            for (let j = 0; j < cat.children.length; j++){
                                                cp.push(copy_object(cat.children[j]))
                                            }
                                        }
                                    }

                                }

                                if(keys[i] != 'glass'){

                                    // console.log(sel)
                                    // console.log(s)
                                    // console.log(scope.$options.tree[sel[s]])
                                    if (scope.$options.tree[sel[s]] && scope.$options.tree[sel[s]].children) {
                                        for (let a = this.$options.tree[sel[s]].children.length; a--;) {
                                            let flaga = 0;
                                            for (let c = cp.length; c--;) {
                                                if(scope.$options.tree[sel[s]].children[a].id == cp[c].id){
                                                    flaga = 1;
                                                    cp[c].name = scope.$options.tree[sel[s]].children[a].name
                                                }
                                                // if(flag==1)cp[c].name = scope.$options.tree[sel[s]].children[a].name;
                                            }
                                            if(flaga == 0) cp.push(copy_object(this.$options.tree[sel[s]].children[a]))

                                        }

                                        for (let c = cp.length; c--;) {
                                            if(cp[c].parent == 0 || cp[c].parent != sel[s]) continue
                                            let flag = 0;
                                            for (let a = scope.$options.tree[sel[s]].children.length; a--;) {
                                                if(scope.$options.tree[sel[s]].children[a].id == cp[c].id) flag = 1;
                                            }

                                            if(flag == 0) cp.splice(c,1)
                                        }
                                    }

                                } else {

                                    if (scope.$options.glass_tree[sel[s]].children) {
                                        for (let a = this.$options.glass_tree[sel[s]].children.length; a--;) {
                                            let flaga = 0;
                                            for (let c = cp.length; c--;) {
                                                if(scope.$options.glass_tree[sel[s]].children[a].id == cp[c].id){
                                                    flaga = 1;
                                                    cp[c].name = scope.$options.glass_tree[sel[s]].children[a].name
                                                }
                                                // if(flag==1)cp[c].name = scope.$options.tree[sel[s]].children[a].name;
                                            }
                                            if(flaga == 0) cp.push(copy_object(this.$options.glass_tree[sel[s]].children[a]))

                                        }

                                        for (let c = cp.length; c--;) {
                                            if(cp[c].parent == 0 || cp[c].parent != sel[s]) continue
                                            let flag = 0;
                                            for (let a = scope.$options.glass_tree[sel[s]].children.length; a--;) {
                                                if(scope.$options.glass_tree[sel[s]].children[a].id == cp[c].id) flag = 1;
                                            }

                                            if(flag == 0) cp.splice(c,1)
                                        }
                                    }

                                }

                            }

                            for (let c = cp.length; c--;) {
                                if(keys[i] != 'glass') {

                                    if(scope.$options.hash[cp[c].id]){
                                        cp[c].name = scope.$options.hash[cp[c].id].name;
                                    }


                                } else {
                                    cp[c].name = scope.$options.glass_hash[cp[c].id].name;
                                }
                            }


                        }
                    }


                    Vue.set(this, 'corpus', sort_array_corp(this.$options.prices.corpus))
                    Vue.set(this, 'edge', sort_array(copy_object(this.$options.prices.edge)))
                    Vue.set(this, 'cokol', sort_array(this.$options.prices.cokol))
                    Vue.set(this, 'tabletop', sort_array(this.$options.prices.tabletop))
                    Vue.set(this, 'tabletop_plints', sort_array(this.$options.prices.tabletop_plints))
                    Vue.set(this, 'wallpanel', sort_array(this.$options.prices.wallpanel))
                    Vue.set(this, 'glass', sort_array(this.$options.prices.glass))
                    Vue.set(this, 'back_wall', sort_array_corp(this.$options.prices.back_wall))
                    Vue.set(this, 'glass_shelves', sort_array_corp(this.$options.prices.glass_shelves))
                }



            },
            mounted: function () {

            },
            methods: {

                export_xl: async function(){
                    const script = document.createElement('script')
                    script.src = '/common_assets/libs/exceljs.min.js'
                    document.head.append(script)

                    let int = setInterval(async function () {
                        if(typeof ExcelJS === 'object'){
                            clearInterval(int)
                            let data = await promise_request(glob.base_url + '/prices/temp')
                            console.log(JSON.parse(data.data))

                            let data2 = await promise_request(glob.base_url + '/prices/temp_fac')
                            console.log(data2)

                            for (let i = 0; i < data2.length; i++){
                                console.log(JSON.parse(data2[i].data))
                            }

                        }
                    },100)
                },

                get_data: function () {
                    let data = {
                        corpus: this.corpus,
                        edge: this.edge,
                        cokol: this.cokol,
                        tabletop: this.tabletop,
                        tabletop_plints: this.tabletop_plints,
                        wallpanel: this.wallpanel,
                        glass: this.glass,
                        cornice: this.cornice,
                        back_wall: this.back_wall,
                        glass_shelves: this.glass_shelves
                    }
                    return JSON.parse(JSON.stringify(data))
                },
                change_children_price: function(pk, key){
                    for (let i = 0; i < this[pk].length; i++){
                        if(this[pk][i].parent == this[pk][key].id){
                            this[pk][i].price = this[pk][key].price;
                        }
                    }
                },

                change_corpus_children_price: function(thickness, key){
                    let scope = this;
                    Object.keys(this.corpus[thickness]).forEach(function (k) {
                        if(scope.corpus[thickness][k].parent == scope.corpus[thickness][key].id){
                            scope.corpus[thickness][k].price = scope.corpus[thickness][key].price
                        }
                    })
                },

                change_back_wall_children_price: function(thickness, key){
                    let scope = this;
                    Object.keys(this.back_wall[thickness]).forEach(function (k) {
                        if(scope.back_wall[thickness][k].parent == scope.back_wall[thickness][key].id){
                            scope.back_wall[thickness][k].price = scope.back_wall[thickness][key].price
                        }
                    })
                },

                change_glass_shelves_children_price: function(thickness, key){
                    let scope = this;
                    Object.keys(this.glass_shelves[thickness]).forEach(function (k) {
                        if(scope.glass_shelves[thickness][k].parent == scope.glass_shelves[thickness][key].id){
                            scope.glass_shelves[thickness][k].price = scope.glass_shelves[thickness][key].price
                        }
                    })
                },


                lang: function(str){
                    return this.$options.lang[str]
                },
                submit: function (e) {
                    e.preventDefault();
                    let scope = this;

                    let data = this.get_data();
                    data.v2 = true
                    let formData = new FormData();
                    formData.append('data', JSON.stringify(data));


                    console.log(data)
                    // return


                    $.ajax({
                        url : document.getElementById('sub_form').action,
                        type : 'POST',
                        data : formData,
                        cache: false,
                        processData: false,
                        contentType: false,
                        success : function(msg) {
                            toastr.options = {
                                "closeButton": true,
                                "debug": false,
                                "progressBar": true,
                                "preventDuplicates": false,
                                "positionClass": "toast-top-right",
                                "onclick": null,
                                "showDuration": "400",
                                "hideDuration": "500",
                                "timeOut": "2000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            }

                            toastr.success(scope.$options.lang['success'])


                        }
                    });

                    return false;

                }
            }
        });
    }
})

function convert_lib(input) {

    let data = JSON.parse(JSON.stringify(input))


    let parents = {};
    let children = [];

    for (let i = 0; i< data.length; i++){
        if(data[i].parent != '' && parseInt(data[i].parent) != 0){
            data[i].price = 0;
            children.push(data[i])
        } else {
            data[i].price = 0;
            data[i].children = [];
            parents[data[i].id] = data[i]
        }
    }

    for (let i = 0; i < children.length; i++){
        if(parents[children[i].parent]){
            parents[children[i].parent].children.push(children[i])
        }

    }

    return parents;

}

function sort_array(arr) {
    let result = [];

    let parent = [];
    let children = {};


    for (let i = 0; i < arr.length; i++){
        if(arr[i].parent == 0){
            parent.push(arr[i]);
        } else {
            if(!children[arr[i].parent]) children[arr[i].parent] = [];
            children[arr[i].parent].push(arr[i]);
        }

    }
    for (let i = 0; i < parent.length; i++){
        result.push(parent[i])
        if(children[parent[i].id]){
            for (let c = 0; c < children[parent[i].id].length; c++){
                result.push(children[parent[i].id][c])
            }
        }

    }


    return result;
}

function sort_array_corp(obj) {
    let obj_result = {};



    Object.keys(obj).forEach(function (k) {
        let result = []
        let parent = [];
        let children = {};
        let arr = obj[k]

        for (let i = 0; i < arr.length; i++){
            if(arr[i].parent == 0){
                parent.push(arr[i]);
            } else {
                if(!children[arr[i].parent]) children[arr[i].parent] = [];
                children[arr[i].parent].push(arr[i]);
            }

        }
        for (let i = 0; i < parent.length; i++){
            result.push(parent[i])
            if(children[parent[i].id]){
                for (let c = 0; c < children[parent[i].id].length; c++){
                    result.push(children[parent[i].id][c])
                }
            }

        }

        obj_result[k] = result
    })






    return obj_result;
}



function cl(input) {
    console.log(Object.keys(input)[0] + ':' + input)
}