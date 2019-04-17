<template>
    <div class="categories-view">

        <!-- Shop -->

        <div class="shop">
            <div class="container" id="productContainer">
                <div class="row">
                    <div class="col-lg-3">
                        <!-- Shop Sidebar -->
                        <div class="sidebar_title"><i class="fas fa-filter"></i> {{ $lang.product.Filter_By }} <i class="fas fa-bars"></i></div>
                        <div class="shop_sidebar" ref="shop_sidebar">
                            <div class="sidebar_section" v-if="category.max_price>0">
                                <div class="sidebar_subtitle">{{ $lang.product.Price }} ({{ $lang.product.som }}) <i title="Close" class="fas fa-window-close close-filter filter-close-button"></i></div>
                                <div class="filter_price">
                                    <div id="slider-range" class="slider_range" :data-current-start="filterPriceCurrentStart" :data-current-end="filterPriceCurrentEnd" data-start="0" :data-end="category.max_price"></div>
                                    <p>{{ $lang.product.Range }}: </p>
                                    <p><input ref="amount" type="text" id="amount" class="amount" readonly style="border:0; font-weight:bold;"></p>
                                </div>
                            </div>
                            <div class="sidebar_section" v-if="category.colors.length>0">
                                <div class="sidebar_subtitle color_subtitle">{{ $lang.product.Color }} <span v-if="colorRgb" class="color" :style="'color:' + colorRgb"><i class="fas fa-circle" style="text-shadow: 1px 1px 1px #e1e1e1"></i> </span></div>
                                <ul class="colors_list">
                                    <li v-for="color in category.colors" v-bind:key="color.id" :title="color.name" class="color">
                                        <a @click="filterColor(color.id, color.rgb)" href="javascript:void(0)" :style="'background:' + color.rgb + '; border: solid 1px #e1e1e1;'">&nbsp;<i v-if="color.id==colorId" class="fas fa-check"></i>&nbsp;</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="sidebar_section" v-if="category.brands.length>0">
                                <div class="sidebar_subtitle brands_subtitle">{{ $lang.product.Brand }}</div>
                                <ul class="brands_list">
                                    <li v-for="brand in category.brands" v-bind:key="brand.id" :title="brand.name" class="brand">
                                        <a @click="filterBrand(brand.id)" href="javascript:void(0)">{{ brand.name }} <i v-if="brand.id==brandId" class="fas fa-check"></i></a>
                                    </li>
                                </ul>
                            </div>
                            <br/>
                            <div class="sidebar_section">
                                <button @click="fetchProducts(true)" type="button" class="btn btn-sm btn-primary close-filter"><i class="fas fa-filter"></i> {{ $lang.product.Filter }}</button>
                                <button v-if="filter" @click="resetFilter()" type="button" class="btn btn-sm btn-default">{{ $lang.product.Reset }}</button>
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
    export default {
        data(){
            return {
                category:{
                    id:'',
                    name:'',
                    colors:[],
                    max_price:0,
                    section:{
                        id:'',
                        name:''
                    },
                    brands:[]
                },
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
                categoryId:this.$route.params.id,
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
                range: null
            }
        },
        created(){
            this.fetchCategory();
        },
        watch:{
            '$route.params.id' () {
                this.categoryId = this.$route.params.id;
                this.fetchCategory();
                this.scrollToTop();
            },
            '$route.query' () {
                this.scrollToPageContent();
            },
            '$route.query.page' () {
                this.fetchProducts(false, this.$route.query.page);
            },
            '$lang' () {
                this.fetchCategory();
            }
        },
        methods:{
            fetchCategory(){
                return axios.get('/api/categories/'+this.categoryId)
                    .then(response=>{
                        this.category = response.data;

                    })
                    .then(()=>{
                        this.fetchProducts();
                        this.loadMobile();
                        this.loadBreadcrumbs();
                        if(this.range) this.range.remove();
                        this.range = '';
                        this.rangeFunctionUpdate();
                    });
            },
            fetchProducts(filter, page){
                let params;
                page = page || 1;
                if(filter) {
                    filter = false;
                    let amount =  this.$refs['amount']?this.$refs['amount'].value.replace(' - ', '-').split('-'):['',''];
                    params = {
                        amount_start: (amount[0] && parseInt(amount[0]) === 0)?'':amount[0],
                        amount_end: (amount.length > 1 && parseInt(amount[1]) !== this.category.max_price)?amount[1]:'',
                        category_id: this.categoryId,
                        color_id: this.colorId,
                        brand_id: this.brandId,
                        sort_by: this.sortBy
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
                    this.filter = filter;
                    if(query) this.$router.replace({
                        query: query
                    })
                }
                else {
                    params = {
                        category_id: this.categoryId
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
                this.filter=false;
                this.colorId = '';
                this.colorRgb = '';
                this.brandId = '';
                this.sortBy = '';
                this.$router.replace({
                    name: this.$route.name,
                    params: this.$route.params
                });
                this.fetchProducts()
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
                    if(!this.range) {
                        this.range = $("#slider-range");
                        this.range.slider(
                            {
                                range: true,
                                min: 0,
                                max: this.category.max_price,
                                values: [0, this.category.max_price],
                                slide: (event, ui) => {
                                    $("#amount").val(ui.values[0] + " - " + ui.values[1]);
                                }
                            });
                    }
                    else {
                        this.range.slider("option", "min", 1);
                        this.range.slider("option", "max", this.category.max_price);
                        this.range.slider("values", 0, 1);
                        this.range.slider("values", 1, this.category.max_price);
                    }
                    $("#amount").val((this.filterPriceCurrentStart?this.filterPriceCurrentStart:0) + " - " + (this.filterPriceCurrentEnd?this.filterPriceCurrentEnd:this.category.max_price));

                });
            },
            loadBreadcrumbs(){
                if(this.category.name) {
                    this.breadCrumbing([
                        {
                            text: this.category.section.name, // crumb text
                            icon: null,
                            to: { name: 'sections', params: { id: this.category.section.id } }
                        },
                        {
                            text: this.category.name, // crumb text
                            icon: null
                        }
                    ])
                }
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
            }
        }
    }
</script>
