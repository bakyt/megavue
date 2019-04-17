<template>
    <div class="categories-view">

        <!-- Shop -->

        <div class="shop">
            <div class="container" id="productContainer">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="search_page_content clc_mobile_search">
                            <form class="search_page_form_container clc_mobile_search" @submit="filterSort" onsubmit="event.preventDefault()">
                                <div class="search_page_form clearfix clc_mobile_search">
                                    <input type="search" v-model="searchText" required="required" class="search_page_input clc_mobile_search" :placeholder="$lang.app.search_placeholder+'...'">
                                    <div class="custom_dropdown clc clc_mobile_search">
                                        <div class="custom_dropdown_list clc clc_mobile_search">
                                            <span class="custom_dropdown_placeholder search_page_custom_dropdown clc clc_mobile_search"><i class="fas fa-chevron-down clc clc_mobile_search"></i>&nbsp;&nbsp;&nbsp;&nbsp;{{ selectedSectionId?selectedSectionName:$lang.app.allCategories }} </span>
                                            <ul class="custom_list clc clc_mobile_search">
                                                <li class="clc_mobile_search"><a class="clc_mobile_search" href="javascript:void(0)" @click="selectSection('', $lang.app.allCategories)">{{ $lang.app.allCategories }}</a></li>
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
                    <div class="col-lg-12">

                        <!-- Shop Content -->

                        <div class="search_content">
                            <div class="search_bar clearfix">
                                <div class="search_product_count"><span>{{ total }}</span> {{ $lang.product.products_found }}</div>
                                <div class="search_sorting">
                                    <span>{{ $lang.product.Sort_by }}:</span>
                                    <ul>
                                        <li>
                                            <select @change="filterSort" class="form-control">
                                                <option value="">{{ $lang.app.Select }}</option>
                                                <option :selected="sortBy=='by_price'" value="by_price">{{ $lang.product.by_price }}</option>
                                            </select>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Shop Page Top Navigation -->
                            <div class="shop_page_nav top">
                                <div class="d-flex flex-row" v-if="pagination.lastPage>1">
                                    <div v-if="pagination.currentPage !== 1" :title="$lang.app.prev_page" class="page_prev d-flex flex-column align-items-center justify-content-center"><router-link :to="toPage(pagination.currentPage-1)"><i class="fas fa-chevron-left"></i></router-link></div>
                                    <ul class="page_nav d-flex flex-row">
                                        <li v-if="pagination.currentPage != 1"><router-link :to="toPage(1)">1</router-link></li>
                                        <li v-if="pagination.currentPage-2 > 1"><router-link :to="toPage(pagination.currentPage-2)">...</router-link></li>
                                        <li v-if="pagination.currentPage-1 > 1"><router-link :to="toPage(pagination.currentPage-1)">{{ pagination.currentPage-1 }}</router-link></li>
                                        <li class="active">{{ pagination.currentPage }}</li>
                                        <li v-if="pagination.currentPage+1 < pagination.lastPage"><router-link :to="toPage(pagination.currentPage+1)">{{ pagination.currentPage+1 }}</router-link></li>
                                        <li v-if="pagination.currentPage+2 < pagination.lastPage"><router-link :to="toPage(pagination.currentPage+2)">...</router-link></li>
                                        <li v-if="pagination.currentPage != pagination.lastPage"><router-link :to="toPage(pagination.lastPage)">{{ pagination.lastPage }}</router-link></li>
                                    </ul>
                                    <div v-if="pagination.currentPage !== pagination.lastPage" :title="$lang.app.next_page" class="page_next d-flex flex-column align-items-center justify-content-center"><router-link :to="toPage(pagination.currentPage+1)"><i class="fas fa-chevron-right"></i></router-link></div>
                                </div>
                            </div>
                            <div class="product_grid">
                                <div class="product_grid_border"></div>

                                <!-- Product Item -->
                                <div v-for="product in products" v-bind:key="product.id" :class="'product_item' + (product.sale?' discount':'')">
                                    <div class="product_border"></div>
                                    <div class="product_image d-flex flex-column align-items-center justify-content-center" @click="$router.push({ name:'products.view', params:{ id:product.id } })"><img :src="product.image" alt=""></div>
                                    <div class="product_content">
                                        <div class="product_price">
                                            {{ product.sale?parseInt(product.price/100*(100-product.sale)):product.price }} {{ $lang.product.som }}
                                            <span v-if="product.sale">{{ product.price+$lang.product.som }}</span>
                                        </div>
                                        <div class="product_name"><div><router-link :to="{ name:'products.view', params:{ id:product.id } }" tabindex="0">{{ product.title }}</router-link></div></div>
                                        <div class="product_extras">
                                            <div class="product_color">
                                                <span class="color" v-for="color in product.colors" @click="product.color=color" :style="'background:'+color.rgb" tabindex="0"><i v-if="product.color.id===color.id" class="fas fa-check"></i></span>
                                            </div>
                                            <button class="product_cart_button" tabindex="0" @click="$cart.addItem(product, 1, product.color)"><i class="fas fa-shopping-cart"></i> {{ $lang.product.add_to_cart }}</button>
                                        </div>
                                    </div>
                                    <div :class="'product_fav'+($store.getters.favorites.indexOf(product.id)>-1?' active':'')" @click="$store.dispatch('addFavorite', product.id)"><i class="fas fa-heart"></i></div>
                                    <ul class="product_marks">
                                        <li class="product_mark product_discount">{{ product.sale }}%</li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Shop Page Navigation -->

                            <div class="shop_page_nav d-flex flex-row" v-if="pagination.lastPage>1">
                                <div v-if="pagination.currentPage !== 1" :title="$lang.app.prev_page" class="page_prev d-flex flex-column align-items-center justify-content-center"><router-link :to="toPage(pagination.currentPage-1)"><i class="fas fa-chevron-left"></i></router-link></div>
                                <ul class="page_nav d-flex flex-row">
                                    <li v-if="pagination.currentPage != 1"><router-link :to="toPage(1)">1</router-link></li>
                                    <li v-if="pagination.currentPage-2 > 1"><router-link :to="toPage(pagination.currentPage-2)">...</router-link></li>
                                    <li v-if="pagination.currentPage-1 > 1"><router-link :to="toPage(pagination.currentPage-1)">{{ pagination.currentPage-1 }}</router-link></li>
                                    <li class="active">{{ pagination.currentPage }}</li>
                                    <li v-if="pagination.currentPage+1 < pagination.lastPage"><router-link :to="toPage(pagination.currentPage+1)">{{ pagination.currentPage+1 }}</router-link></li>
                                    <li v-if="pagination.currentPage+2 < pagination.lastPage"><router-link :to="toPage(pagination.currentPage+2)">...</router-link></li>
                                    <li v-if="pagination.currentPage != pagination.lastPage"><router-link :to="toPage(pagination.lastPage)">{{ pagination.lastPage }}</router-link></li>
                                </ul>
                                <div v-if="pagination.currentPage !== pagination.lastPage" :title="$lang.app.next_page" class="page_next d-flex flex-column align-items-center justify-content-center"><router-link :to="toPage(pagination.currentPage+1)"><i class="fas fa-chevron-right"></i></router-link></div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                stores:[],
                products:[],
                product:{
                    name:'',
                    price:'',
                    title:'',
                    sale:0,
                    images:[],
                    color:{},
                    brand:{}
                },
                // for button reset
                total:0,
                pagination:{
                    currentPage:'',
                    lastPage:''
                },
                sortBy:'',
                searchText:'',
                section:'',
                sections:[],
                selectedSectionId:'',
                selectedSectionName:'',
            }
        },
        created(){
            this.fetchCategories();
            if(this.$route.query.search) this.fetchProducts();
            this.loadBreadcrumbs();
        },
        watch:{
            '$route.query.page' () {
                this.fetchProducts();
            },
            '$route.query.sort_by' () {
                this.sortBy = this.$route.query.sort_by;
                if(this.$route.query.search) this.fetchProducts();
            },
            '$route.query.search' () {
                this.searchText = this.$route.query.search;
                if(this.searchText) this.fetchProducts();
                else {
                    let vm = {
                        currentPage: 0,
                        lastPage: 0
                    };
                    this.products = [];
                    this.total = 0;
                    this.pagination = vm;
                }
            },
            '$route.query.section' () {
                this.selectSection(this.$route.query.section);
                this.section = this.$route.query.section;
                this.searchText = this.$route.query.search;
                if(this.searchText) this.fetchProducts();
            },
            '$lang' () {
                if(this.$route.query.search) this.fetchProducts();
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
            scrollToProductContainer(){
                document.getElementById("productContainer").scrollIntoView()
            },
            fetchProducts(){
                if(!this.$route.query.search) {
                    this.$router.replace({
                        name: this.$route.name,
                        query: {},
                    });
                    let vm = {
                        currentPage: 0,
                        lastPage: 0
                    };
                    this.products = [];
                    this.total = 0;
                    this.pagination = vm;
                    return false;
                }
                let params;
                    params = {};
                    if(this.$route.query.page) {
                        params.page = this.$route.query.page;
                    }
                    if(this.$route.query.search) {
                        params.search = this.$route.query.search;
                        this.searchText = params.search
                    }
                    if(this.$route.query.sort_by) {
                        this.sortBy = this.$route.query.sort_by;
                        params.sort_by = this.sortBy;
                    }
                    if(this.$route.query.section) {
                        this.section = this.$route.query.section;
                        params.section_id = this.section;
                    }

                return axios.get('/api/products', {
                    params: params
                })
                    .then(response=>{
                        let vm = {
                            currentPage: response.data.meta.current_page,
                            lastPage: response.data.meta.last_page
                        };
                        this.products = response.data.data;
                        this.total = response.data.meta.total;
                        this.pagination = vm;
                        if(this.total>0) this.scrollToProductContainer();
                    })
            },
            filterSort(e){
                this.sortBy = e.target.value;
                let query = {};
                if(this.sortBy) query.sortBy = this.sortBy;
                if(this.searchText) query.search = this.searchText;
                if(this.section) query.section = this.section;
                this.$router.replace({
                    query: query
                });
                this.fetchProducts()
            },
            search(){
                if(this.searchText){
                    let query = {};
                    query.search = this.searchText;
                    if(this.sortBy) query.sortBy = this.sortBy;

                    if(this.section) query.section = this.section;
                    this.$router.replace({
                        query: query
                    });
                    this.fetchProducts()
                }
                else {
                    this.$router.replace({
                        name: this.$route.name,
                        query: {},
                    });
                    let vm = {
                        currentPage: 0,
                        lastPage: 0
                    };
                    this.products = [];
                    this.total = 0;
                    this.pagination = vm;
                }
            },
            toPage(page){
                let res =  {
                    name:this.$route.name,
                    params:this.$route.params,
                    query:page>1?{page: page}:{}
                };
                if(this.sortBy) res.query.sort_by = this.sortBy;
                if(this.section) res.query.section = this.section;
                if(this.searchText) res.query.search = this.searchText;
                return res;
            },
            loadBreadcrumbs(){
                this.breadCrumbing([
                    {
                        text: this.$lang.app.search, // crumb text
                        icon: 'fa fa-search'
                    }
                ])
            },
            loadMobile(){
                $(document).ready(() => {
                    "use strict";
                    let sidebarTitle = $(".sidebar_title");
                    let sideMenu = $(".shop_sidebar");
                    if(!sidebarTitle.onclick) sidebarTitle.on('click', function () {
                        if (window.innerWidth <= 991) sideMenu.slideToggle()
                    });
                    $(".close-filter").on('click', function () {
                        if (window.innerWidth <= 991) sideMenu.slideUp()
                    });
                    window.addEventListener('load', () => {
                        this.windowWidth = window.innerWidth;
                        if (window.innerWidth > 991) sideMenu.show();
                        else sideMenu.hide()
                    });
                    window.addEventListener('resize', () => {
                        this.windowWidth = window.innerWidth;
                        if (window.innerWidth > 991) sideMenu.show();
                        else sideMenu.hide()
                    });
                });
            },
            selectSection(id, name){
                if(!name){
                    this.sections.filter(section=>{
                        if(section.id === parseInt(id)) {
                            this.selectedSectionId = section.id;
                            this.selectedSectionName = section.name;
                            this.section = section.id;
                        }
                    })
                }
                else {
                    this.selectedSectionId = id;
                    this.selectedSectionName = name;
                    this.section = id;
                }
            }
        },
        mounted(){
            $(document).ready(function()
            {
                let placeholder = $('.search_page_content .custom_dropdown_placeholder');
                let list = $('.search_page_content .custom_list');
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
