<template>
    <div class="header_search">
        <div class="header_search_content clc_mobile_search">
            <form class="header_search_form_container clc_mobile_search" @submit="search" onsubmit="event.preventDefault()">
                <div class="header_search_form clearfix clc_mobile_search">
                    <input type="search" v-model="searchText" required="required" class="header_search_input clc_mobile_search" :placeholder="$lang.app.search_placeholder+'...'">
                    <div class="custom_dropdown clc clc_mobile_search">
                        <div class="custom_dropdown_list clc clc_mobile_search">
                            <span class="custom_dropdown_placeholder clc clc_mobile_search"><i class="fas fa-chevron-down clc clc_mobile_search"></i>&nbsp;&nbsp;&nbsp;&nbsp;{{ selectedSectionId?selectedSectionName:$lang.app.allCategories }} </span>
                            <ul class="custom_list clc clc_mobile_search">
                                <li class="clc_mobile_search"><a class="clc_mobile_search" href="javascript:void(0)" @click="selectSection('', $lang.app.allCategories)">{{ $lang.app.allCategories }}</a></li>
                                <li class="clc_mobile_search"><a class="clc_mobile_search" href="javascript:void(0)" @click="selectSection('stores', $lang.store.stores)">{{ $lang.store.stores }}</a></li>
                                <li class="clc_mobile_search" v-for="section in sections">
                                    <a class="clc_mobile_search" href="javascript:void(0)" @click="selectSection(section.id, section.name)">{{ section.name }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <button @click="search" type="button" class="header_search_button clc_mobile_search trans_300" :title="$lang.app.search"><i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                sections:[],
                selectedSectionId:'',
                selectedSectionName:'',
                searchText:''
            }
        },
        created(){
            this.fetchCategories();
        },
        watch:{
            '$lang' () {
                this.fetchCategories(true);
            },
            '$route.query.search' () {
                this.searchText = this.$route.query.search;
            },
            '$route.query.section' () {
                if(this.$route.query.section) {
                    if(!this.sections) this.fetchCategories();
                    this.selectSection(this.$route.query.section)
                }
                else {
                    this.selectedSectionId = '';
                }
            }
        },
        methods:{
            fetchCategories(isLang){
                if(!isLang && this.$store.getters.categories.length>0){
                    this.sections = this.$store.getters.categories;
                    if(this.$route.query.section) {
                        this.selectSection(this.$route.query.section)
                    }
                    else {
                        this.selectedSectionId = '';
                    }
                }
                else axios.get('/api/sections')
                    .then(response=>{
                        this.sections = response.data;
                        this.$store.dispatch('setCategories', this.sections);
                        if(this.$route.query.search) {
                            this.searchText = this.$route.query.search;
                        }
                        if(this.$route.query.section) {
                            this.selectSection(this.$route.query.section)
                        }
                        else {
                            this.selectedSectionId = '';
                        }
                    });
            },
            selectSection(id, name){
                if(!name){
                    this.sections.filter(section=>{
                        if(section.id === parseInt(id)) {
                            this.selectedSectionId = section.id;
                            this.selectedSectionName = section.name;
                        }
                    })
                }
                else {
                    this.selectedSectionId = id;
                    this.selectedSectionName = name;
                }
            },
            search(){
                let query = {};
                if(this.searchText) query.search = this.searchText;
                if(this.selectedSectionId !== 'stores') {
                    query.section = this.selectedSectionId;
                    this.$router.push({
                        name:'search',
                        query: query
                    });
                }
                else this.$router.push({
                    name:'stores',
                    query: query
                })
            }
        },
        mounted(){
            $(document).ready(function()
            {
                let placeholder = $('.header_search .custom_dropdown_placeholder');
                let list = $('.header_search .custom_list');
                placeholder.on('click', function (ev) {
                    list.toggleClass('active');
                    $(document).one('click', function closeForm(e)
                    {
                        if($(e.target).hasClass('clc'))
                        {
                            $(document).one('click', closeForm);
                        }
                        else
                        {
                            list.removeClass('active');
                        }
                    });
                });
            });
        }
    }
</script>
