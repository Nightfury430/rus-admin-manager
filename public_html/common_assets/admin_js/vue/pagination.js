const pagination_template = `
    <ul class="pagination">
        <li :class="{ 'disabled': page == 1 }" @click="first_page()" class="paginate_button page-item"><span class="page-link"><<</span></li>
        <li :class="{ 'disabled': page == 1 }" @click="prev_page()" class="paginate_button page-item"><span class="page-link"><</span></li>
        <li v-for="item in computed_pagination" :class="{ 'active': page == item }" @click="change_page(item)" class="paginate_button page-item"><span  class="page-link">{{item}}</span></li>
        <li :class="{ 'disabled': page == pages }" @click="next_page()" class="paginate_button page-item"><span class="page-link">></span></li>
        <li :class="{ 'disabled': page == pages }" @click="last_page()" class="paginate_button page-item"><span class="page-link">>></span></li>
    </ul>
`;




Vue.component("pagination_component",{
    props: ['pages', 'total', 'per_page'],
    data: function () {
        return {
            page: 1
        }
    },
    created: function(){
        let scope = this;
        this.$root.$on('reset_page', function () {
            scope.page = 1;
        })

        this.$root.$on('change_page_sync', function (p) {
            scope.page = p;
        })

    },
    computed:{
        computed_pagination: function () {
            if(!this.pages) return [];

            let res = [];

            for (let i = 0; i < this.pages+1; i++){
                res.push(i)
            }

            let t = 0;

            if(this.page > 1) t = 1;
            if(this.page > 2) t = 2;
            if(this.page > 3) t = 3;
            if(this.page > 5) t = 4;

            return res.slice(this.page -t, this.page + (9-t));

        },
    },
    methods: {
        first_page: function(){
            if (this.page != 1) {
                this.page = 1;
                this.res();
            }
        },
        last_page: function(){
            if (this.page != this.pages){
                this.page = this.pages;
                this.res();
            }

        },
        prev_page: function(){
            if (this.page > 1){
                this.page -= 1;
                this.res();
            }

        },
        next_page: function(letter){
            if(this.page < this.pages){
                this.page += 1;
                this.res();
            }

        },
        change_page: function(i){
            if(this.page != i){
                this.page = i;
                this.res();
            }
        },
        res: function () {
            this.$emit('change-page', this.page);
        }
    },
    template: pagination_template
});

