<template>
    <div class="store-view">
        <!-- Shop -->
        <div class="home">
            <div class="home_blur" :style="'background-image: url('+storeItem.background_image+')'"></div>
            <div class="home_background">
                <div class="container" style="width: 100%;height: 100%;">
                    <div class="social">
                        <ul v-if="storeItem.contacts">
                            <li v-if="storeItem.contacts.whatsapp" title="Whatsapp"><a :href="'whatsapp://send?phone='+storeItem.contacts.whatsapp"><i class="fab fa-whatsapp"></i></a></li>
                            <li v-if="storeItem.contacts.phones">
                                <a href="javascript:void(0)" class="dropdown-toggle" id="dropdownMenu2" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-phone"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                    <a v-for="phone in storeItem.contacts.phones" :href="'tel:'+phone" class="dropdown-item"><i class="fas fa-phone"></i> {{ phone }}</a>
                                </div>
                            </li>
                            <li v-if="storeItem.contacts.facebook" title="Facebook"><a target="_blank" :href="storeItem.contacts.facebook"><i class="fab fa-facebook-f"></i></a></li>
                            <li v-if="storeItem.contacts.instagram" title="Instagram"><a target="_blank" :href="'https://instagram.com/'+storeItem.contacts.instagram.replace('@', '')"><i class="fab fa-instagram"></i></a></li>
                        </ul>
                        <ul>
                            <li v-if="storeItem.about" :title="$lang.store.about_us"><a class="about_dropdown_placeholder clc_about" href="javascript:void(0)"><i class="fas fa-info clc_about"></i></a></li>
                            <li><a :title="storeItem.address" class="map_dropdown_placeholder clc_map" href="javascript:void(0)"><i class="fas fa-map-marker-alt clc_map"></i></a></li>
                            <li v-if="isAdmin">
                                <a :title="$lang.store.edit_store" href="javascript:void(0)" class="dropdown-toggle" id="dropdownMenu3" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenu3">
                                    <router-link :to="{ name:'stores.edit', params:{ slug:storeSlug } }" class="dropdown-item"><i class="fas fa-cog"></i> {{ $lang.store.settings }}</router-link>
                                </div>
                            </li>
                        </ul>
                        <div v-if="storeItem.about" class="about_dropdown clc_about">
                            <div class="about_list clc_about">
                                <div class="about_content clc_about">
                                    <h3 class="clc_about">{{ $lang.store.about_us }}</h3>
                                    <p class="clc_about">{{ storeItem.about }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="map_dropdown clc_map">
                            <div class="map_list clc_map">
                                <div class="map_content clc_map">
                                    <h3 class="clc_map">{{ $lang.store.our_address }}</h3>
                                    <p class="clc_map"><a target="_blank" :href="'https://2gis.kg/search/'+storeItem.address"><i class="fas fa-map-marker-alt"></i> {{ storeItem.address }}</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="width: 100%;height: 100%;" class="d-flex flex-column align-items-center justify-content-center">
                        <img :src="storeItem.icon"  />
                        <h2>{{ storeItem.name }}</h2>
                        @{{ storeItem.slug }}
                        <small>{{ storeItem.description }}</small>
                    </div>
                </div>
            </div>
        </div>
        <orders/>
        <div class="shop" id="products">
            <div class="container" id="productContainer">
                <div class="row">
                    <div class="col-lg-3">
                        <!-- Shop Sidebar -->
                        <div class="sidebar_title"><i class="fas fa-filter"></i> {{ $lang.product.Filter_By }} <i class="fas fa-bars"></i></div>
                        <div class="shop_sidebar" ref="shop_sidebar" v-if="storeItem.categories.length>0">
                            <div class="sidebar_section">
                                <!--<div class="sidebar_title">{{ $lang.app.categories }}</div>-->
                                <i title="Close" class="fas fa-window-close close-filter filter-close-button"></i>
                                <ul class="sidebar_categories">
                                    <li v-for="category in storeItem.categories" v-bind:key="category.id"><a href="javascript:void(0)" @click="filterCategory(category)">{{ category.name }} <i v-if="selectedCategory.id == category.id" class="fas fa-check"></i></a></li>
                                </ul>
                            </div>
                            <div v-if="selectedCategory.id">
                                <div class="sidebar_section" v-if="selectedCategory.max_price>0">
                                    <div class="sidebar_subtitle">{{ $lang.product.Price }} ({{ $lang.product.som }})</div>
                                    <div class="filter_price">
                                        <div id="slider-range" class="slider_range" :data-current-start="filterPriceCurrentStart" :data-current-end="filterPriceCurrentEnd" data-start="0" :data-end="selectedCategory.max_price"></div>
                                        <p>{{ $lang.product.Range }}: </p>
                                        <p><input ref="amount" type="text" id="amount" class="amount" readonly style="border:0; font-weight:bold;"></p>
                                    </div>
                                </div>
                                <div class="sidebar_section" v-if="selectedCategory.colors.length>0">
                                    <div class="sidebar_subtitle color_subtitle">{{ $lang.product.Color }} <span v-if="colorRgb" class="color" :style="'color:' + colorRgb"><i class="fas fa-circle" style="text-shadow: 1px 1px 1px #e1e1e1"></i> </span></div>
                                    <ul class="colors_list">
                                        <li v-for="color in selectedCategory.colors" v-bind:key="color.id" :title="color.name" class="color">
                                            <a @click="filterColor(color.id, color.rgb)" href="javascript:void(0)" :style="'background:' + color.rgb + '; border: solid 1px #e1e1e1;'">&nbsp;<i v-if="color.id==colorId" class="fas fa-check"></i>&nbsp;</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="sidebar_section" v-if="selectedCategory.brands.length>0">
                                    <div class="sidebar_subtitle brands_subtitle">{{ $lang.product.Brand }}</div>
                                    <ul class="brands_list">
                                        <li v-for="brand in selectedCategory.brands" v-bind:key="brand.id" :title="brand.name" class="brand">
                                            <a @click="filterBrand(brand.id)" href="javascript:void(0)">{{ brand.name }} <i v-if="brand.id==brandId" class="fas fa-check"></i></a>
                                        </li>
                                    </ul>
                                </div>
                                <br/>
                                <div class="sidebar_section close-filter">
                                    <button @click="fetchProducts(true)" type="button" class="btn btn-sm btn-primary"><i class="fas fa-filter"></i> {{ $lang.product.Filter }}</button>
                                    <button v-if="filter" @click="resetFilter()" type="button" class="btn btn-sm btn-default">{{ $lang.product.Reset }}</button>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-9">

                        <!-- Shop Content -->

                        <div class="shop_content">
                            <div class="shop_bar clearfix">
                                <div class="shop_product_count"><span>{{ total }}</span> {{ $lang.product.products_found }}</div>
                                <div class="shop_sorting">
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
    import Orders from './Orders.vue'
    export default {
        components:{
            orders: Orders
        },
        data(){
            return {
                storeItem:{
                    id:'',
                    name:'',
                    categories:[],
                    contacts:{},
                    description:'',
                    about:''
                },
                products:[],
                product:{
                    name:'',
                    price:'',
                    title:'',
                    sale:0,
                    image:'',
                    color:{},
                    brand:{}
                },
                storeSlug:this.$route.params.slug,
                brandId:'',
                colorId:'',
                colorRgb:'',
                // for button reset
                filter:false,
                filterPriceCurrentStart:null,
                filterPriceCurrentEnd:null,
                total:0,
                pagination:{
                    currentPage:'',
                    lastPage:''
                },
                sortBy:'',
                // price range html element
                range: null,
                selectedCategory:{
                    id:'',
                    name:'',
                    colors:[],
                    max_price:0,
                    section:{
                        id:'',
                        name:''
                    }
                },
                isAdmin:false
            }
        },
        created(){
            this.fetchStore()
                .then(()=>{
                    this.isAdmin = this.$auth.isStoreAdmin(this.$route.params.slug);
                    this.loadMobile();
                    this.fetchProducts();
                })
        },
        watch:{
            selectedCategory(){
                this.rangeFunctionUpdate();
            },
            '$route.params.slug' (slug) {
                this.storeSlug = slug;
                this.fetchStore()
                    .then(()=>{
                        this.fetchProducts();
                        this.scrollToPageContent();
                        this.isAdmin = this.$auth.isStoreAdmin(slug)
                    });
            },
            '$route.query.page' () {
                this.fetchProducts(false, this.$route.query.page);
                this.scrollToPageContent()
            },
            '$lang' () {
                this.fetchStore()
                    .then(this.fetchProducts());
            },
            '$store.getters.user' () {
                this.isAdmin = this.$auth.isStoreAdmin(this.$route.params.slug)
            }
        },
        methods:{
            fetchStore(){
                return axios.get('/api/stores/'+this.storeSlug)
                    .then(response=>{
                        this.storeItem = response.data;
                        this.loadBreadcrumbs();
                        this.loadAboutFunction();
                    })
            },
            fetchProducts(filter, page){
                let params;
                page = page || 1;
                if(filter) {
                    filter = false;
                    let amount =  this.$refs['amount']?this.$refs['amount'].value.replace(' - ', '-').split('-'):['',''];
                    params = {
                        amount_start: (amount[0] && parseInt(amount[0]) === 0)?'':amount[0],
                        amount_end: (amount.length > 1 && parseInt(amount[1]) !== this.selectedCategory.max_price)?amount[1]:'',
                        store_id: this.storeItem.id,
                        color_id: this.colorId,
                        brand_id: this.brandId,
                        sort_by: this.sortBy,
                        category_id: this.selectedCategory.id
                    };
                    let query = {};
                    if(page>1) query.page =  page;
                    if(params.color_id) {
                        filter = true;
                        query.color_id = params.color_id;
                    }
                    if(params.brand_id) {
                        filter = true;
                        query.brand_id = params.brand_id;
                    }
                    if(params.amount_start) {
                        filter = true;
                        query.amount_start = params.amount_start;
                    }
                    if(params.amount_end) {
                        filter = true;
                        query.amount_end = params.amount_end;
                    }
                    if(params.sort_by) {
                        filter = true;
                        query.sort_by = params.sort_by;
                    }
                    if(params.category_id) {
                        filter = true;
                        query.category_id = params.category_id;
                    }
                    this.filter = filter;
                    if(query) this.$router.replace({
                        name: this.$route.name,
                        query: query
                    })
                }
                else {
                    params = {
                        store_id: this.storeItem.id,
                    };
                    if(this.$route.query.color_id) {
                        this.colorId = this.$route.query.color_id;
                        params.color_id = this.colorId;
                        this.filter = true;
                    }
                    if(this.$route.query.brand_id) {
                        this.brandId = this.$route.query.brand_id;
                        params.brand_id = this.brandId;
                        this.filter = true;
                    }
                    if(this.$route.query.amount_start) {
                        this.filterPriceCurrentStart = this.$route.query.amount_start;
                        params.amount_start = this.filterPriceCurrentStart;
                        this.filter = true;
                    }
                    if(this.$route.query.amount_end) {
                        this.filterPriceCurrentEnd = this.$route.query.amount_end;
                        params.amount_end = this.filterPriceCurrentEnd;
                        this.filter = true;
                    }
                    if(this.$route.query.page) {
                        params.page = this.$route.query.page;
                    }
                    if(this.$route.query.sort_by) {
                        this.sortBy = this.$route.query.sort_by;
                        params.sort_by = this.sortBy;
                        this.filter = true;
                    }
                    if(this.$route.query.category_id) {
                        params.category_id = this.categorySelector(this.$route.query.category_id);
                        this.filter = true;
                    }
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
                    })
            },
            filterBrand(brandId){
                if(this.brandId === brandId) this.brandId = '';
                else this.brandId = brandId
            },
            filterColor(colorId, colorRgb){
                if(this.colorId === colorId) {
                    this.colorId = '';
                    this.colorRgb = ''
                }
                else {
                    this.colorId = colorId;
                    this.colorRgb = colorRgb
                }
            },
            filterSort(e){
                this.sortBy = e.target.value;
                this.fetchProducts(true)
            },
            resetFilter(){
                this.colorId = '';
                this.colorRgb = '';
                this.brandId = '';
                this.sortBy = '';
                if(this.$route.query) this.$router.replace({
                    name: this.$route.name,
                    params: this.$route.params
                });
                this.selectedCategory = {};
                if(this.filter) {
                    this.filter=false;
                    this.fetchProducts();
                }
            },
            toPage(page){
                let res =  {
                    name:this.$route.name,
                    params:this.$route.params,
                    query:page>1?{page: page}:{}
                };
                if(this.colorId) res.query.color_id = this.colorId;
                if(this.brandId) res.query.brand_id = this.brandId;
                if(this.filterPriceCurrentStart) res.query.amount_start = this.filterPriceCurrentStart;
                if(this.filterPriceCurrentEnd) res.query.amount_end = this.filterPriceCurrentEnd;
                if(this.sortBy) res.query.sort_by = this.sortBy;
                return res;
            },
            rangeFunctionUpdate(){
                $(document).ready(() => {
                    if(!this.range || !this.range.innerHTML) {
                        this.range = $("#slider-range");
                        this.range.slider(
                            {
                                range: true,
                                min: 0,
                                max: this.selectedCategory.max_price,
                                values: [0, this.selectedCategory.max_price],
                                slide: (event, ui) => {
                                    $("#amount").val(ui.values[0] + " - " + ui.values[1]);
                                }
                            });
                    }
                    else {
                        this.range.slider("option", "min", 1);
                        this.range.slider("option", "max", this.selectedCategory.max_price);
                        this.range.slider("values", 0, 1);
                        this.range.slider("values", 1, this.selectedCategory.max_price);
                    }
                    $("#amount").val((this.filterPriceCurrentStart?this.filterPriceCurrentStart:0) + " - " + (this.filterPriceCurrentEnd?this.filterPriceCurrentEnd:this.selectedCategory.max_price));

                });
            },
            loadBreadcrumbs(){
                this.breadCrumbing([
                    {
                        text: this.$lang.store.stores, // crumb text
                        icon: 'fa fa-shopping-cart',
                        to: { name: 'stores'}
                    },
                    {
                        text: this.storeItem.name, // crumb text
                        icon: null,
                        hideTitle:true
                    }
                ])
            },
            categorySelector(category_id){
                category_id = parseInt(category_id);
                this.storeItem.categories.filter(cat=>{
                    if(category_id === cat.id) this.selectedCategory = cat;
                });
                return category_id;
            },
            loadMobile(){
                $(document).ready(() => {
                    "use strict";
                    let sidebarTitle = $(".sidebar_title");
                    let sideMenu = $(".shop_sidebar");
                    if(!sidebarTitle.onclick) sidebarTitle.on('click', function () {
                        if (window.innerWidth <= 991) sideMenu.slideToggle()
                    });
                    $(".store-view").on('click', ".close-filter", function () {
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
            filterCategory(category){
                if(this.selectedCategory.id===category.id){
                    this.resetFilter()
                }
                else {
                    if(this.filter) this.resetFilter();
                    this.selectedCategory = category;
                }
            },
            loadAboutFunction(){
                $(document).ready(function() {
                    let placeholder = $('.about_dropdown_placeholder');
                    let list = $('.about_list');
                    placeholder.on('click', function (ev) {
                        list.toggleClass('active');
                        $(document).one('click', function closeForm(e) {
                            if ($(e.target).hasClass('clc_about')) {
                                $(document).one('click', closeForm);
                            }
                            else {
                                list.removeClass('active');
                            }
                        });
                    });
                })
            },
            scrollToPageContent(){
                if(document.scrollTop !== 0) $('html, body').animate({
                    scrollTop: $("#products").offset().top
                },1000);
            }
        },
        mounted(){
            $(document).ready(function()
            {
                let map_placeholder = $('.map_dropdown_placeholder');
                let map_list = $('.map_list');
                map_placeholder.on('click', function (ev) {
                    map_list.toggleClass('active');
                    $(document).one('click', function closeForm(e)
                    {
                        if($(e.target).hasClass('clc_map'))
                        {
                            $(document).one('click', closeForm);
                        }
                        else
                        {
                            map_list.removeClass('active');
                        }
                    });
                });
            });
        }
    }
</script>
