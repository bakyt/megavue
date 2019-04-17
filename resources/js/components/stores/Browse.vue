<template>
    <div class="blog shop" ref="shopContainer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <!-- Shop Sidebar -->
                    <div class="sidebar_title"><i class="fas fa-filter"></i> {{ $lang.product.Filter_By }} <i class="fas fa-bars"></i></div>
                    <div class="shop_sidebar" ref="shop_sidebar">
                        <div class="sidebar_section">
                            <form onsubmit="event.preventDefault();$('.close-filter').click()" @submit="fetchStores">
                                <div class="form-inline" style="margin-top:15px">
                                    <div class="input-group">
                                        <input v-model="searchText" type="text" class="form-control" :placeholder="$lang.app.search">
                                        <span class="input-group-btn"><button @click="fetchStores" class="btn btn-primary close-filter"><i :title="$lang.app.search" class="fa fa-search"></i> </button></span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="sidebar_section">
                            <ul class="sidebar_categories">
                                <li class="close-filter"><a href="javascript:void(0)" @click="filterStore()">{{ $lang.app.all }}<i v-if="!typeId" class="fas fa-check"></i></a></li>
                                <li class="close-filter" v-for="storeType in storeTypes" v-bind:key="storeType.id"><a href="javascript:void(0)" @click="filterStore(storeType)">{{ storeType.name }}<i v-if="storeType.id == typeId" class="fas fa-check"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <br/>
                </div>

                <div class="col-lg-9">
                    <div class="store_content">
                        <div class="store_bar clearfix">
                            <div class="store_product_count"><span>{{ total }}</span> {{ $lang.store.stores_found }}</div>
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

                        <div class="stores_grid">
                            <div class="stores_grid_border"></div>

                            <!-- Product Item -->
                            <div v-for="store in stores" v-bind:key="store.id" class="stores_item" :title="store.description" @click="$router.push({ name:'stores.view', params:{ slug:store.slug } })">
                                <div class="stores_border"></div>
                                <div class="stores_image d-flex flex-column align-items-center justify-content-center"><img :src="store.icon" alt=""></div>
                                <div class="stores_content">
                                    <div class="stores_name">
                                        <div>
                                            <router-link :to="{ name:'stores.view', params:{ slug:store.slug } }" tabindex="0">
                                                {{ store.name }}
                                            </router-link>
                                            @{{ store.slug }}
                                            <small>{{ store.description }}</small>
                                        </div>
                                    </div>
                                </div>
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
</template>

<script>
    export default {
        data(){
            return {
                storeTypes:[],
                stores:[],
                filter:false,
                pagination:{
                    currentPage:'',
                    lastPage:''
                },
                total:0,
                typeId:this.$route.query.type_id,
                searchText:this.$route.query.search
            }
        },
        watch:{
            $lang () {
                this.fetchStoreTypes()
                    .then(()=>{
                        this.storeTypeFunctionUpdate();
                    });
                this.fetchStores();
            },
            '$route.query' () {
                this.scrollToPageContent();
            },
            '$route.query.page' () {
                this.fetchStores(this.$route.query.page)
            }
        },
        created(){
            this.fetchStoreTypes()
                .then(()=>{
                this.storeTypeFunctionUpdate();
                });
            this.fetchStores();
        },
        methods:{
            fetchStores(page){
                page = page || 1;
                let params = {};
                if(page>1) params.page = page;
                if(this.typeId) params.type_id = this.typeId;
                if(this.searchText) params.search = this.searchText;
                return axios.get('/api/stores', {
                    params: params
                })
                    .then(response => {
                        let vm = {
                            currentPage: response.data.meta.current_page,
                            lastPage: response.data.meta.last_page
                        };
                        this.stores = response.data.data;
                        this.total = response.data.meta.total;
                        this.pagination = vm;
                        this.$router.replace({
                            query:params
                        });
                    })
                    .then(()=>this.loadBreadcrumbs())
            },

            fetchStoreTypes(){
                return axios.get('/api/store_types')
                    .then(response => {
                        this.storeTypes = response.data;
                    })
            },

            storeTypeFunctionUpdate(){
                $(document).ready(() => {
                    "use strict";

                    let sideMenu = $(".shop_sidebar");
                    $(".sidebar_title").on('click', function () {
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
            toPage(page){
                let res =  {
                    name:this.$route.name,
                    params:this.$route.params,
                    query:page>1?{page: page}:{}
                };
                if(this.typeId) res.query.type_id = this.typeId;
                if(this.searchText) res.query.search = this.searchText;
                return res;
            },
            filterStore(type){
                if(this.typeId === type) return false;
                if(type) this.typeId = type.id;
                else {
                    this.typeId = '';
                    this.searchText = '';
                }
                this.fetchStores();
            },
            loadBreadcrumbs(){
                this.breadCrumbing([
                    {
                        text: this.$lang.store.stores, // crumb text
                        icon: 'fa fa-shopping-cart'
                    }
                ])
            }
        }

    }
</script>
