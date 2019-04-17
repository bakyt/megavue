<template>
    <ul :class="(home?'home ':'')+'cat_menu'">
        <li class="hassubs" v-for="section in sections" v-bind:key="section.id">
            <a href="javascript:void(0)" :title="section.name">{{ section.name }}<i class="fas fa-chevron-right"></i></a>
            <node-tree :node="section"></node-tree>
        </li>
        <li class="sub-category">
            <router-link :to="{ name:'sections', params:{ id:'all' } }">{{ $lang.app.allCategories }}</router-link>
        </li>
    </ul>
</template>

<script>
    import NodeTree from "../partials/NodeTree";
    export default {
        props: {
            item: Object
        },
        components: {
            NodeTree
        },
        data(){
            return {
                sections:[],
                section:{
                    id:'',
                    name: '',
                    children:[],
                    categories:[]
                },
                categories:[],
                category:{
                    id:'',
                    name:''
                },
                home:false
            }
        },
        created(){
            this.fetchCategories();
            this.updateCategory();
        },
        watch:{
            '$route' () { this.updateCategory() },
            '$lang' () { this.fetchCategories(true); }
            },
        methods:{
            fetchCategories(isLang){
                if(!isLang && this.$store.getters.categories.length>0){
                    this.sections = this.$store.getters.categories
                }
                else axios.get('/api/sections')
                    .then(response=>{
                        this.sections = response.data;
                        this.$store.dispatch('setCategories', this.sections);
                    })
                    .then(()=>{
                    $(document).ready(function () {
                        $('.sub-category').on('click', function () {
                            $('.cat_menu_container .cat_menu').css('display', 'none');
                            setTimeout(()=>{
                                $('.cat_menu_container').one('mousemove', function () {
                                    $('.cat_menu_container .cat_menu').css('display', '')
                                })
                            }, 1000)
                        })
                    })
                    });
            },

            updateCategory(){
                this.home = this.$route.name==='home'
            }
        }
    }
</script>
