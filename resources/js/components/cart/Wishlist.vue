<template>
    <div class="shop">
        <div class="container" id="productContainer">
            <div class="row">

                <div class="col-lg-12">

                    <!-- Shop Content -->

                    <div class="shop_content">
                        <div class="shop_bar clearfix">
                            <div class="shop_product_count"><span>{{ total }}</span> {{ $lang.product.products_found }}</div>
                            <div class="shop_sorting">
                                <span><button class="btn btn-sm btn-danger" v-if="$store.getters.favorites.length>0" @click="truncateFavs">{{ $lang.cart.truncate }}</button></span>
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
</template>

<script>
    export default {
        data(){
            return {
                products:[],
                sortBy:'',
                pagination:{
                    currentPage:'',
                    lastPage:''
                },
                total:0
            }
        },
        created(){
            this.loadBreadcrumbs();
            this.fetchProducts()
        },
        methods:{
            fetchProducts(){
                let query = {};
                if(this.$store.getters.favorites.length>0) query.product_ids = this.$store.getters.favorites;
                else {
                    this.products = [];
                    this.pagination.currentPage = '';
                    this.pagination.lastPage = '';
                    this.total = 0;
                    return false;
                }
                return axios.get('/api/products', {
                    params: query
                })
                    .then(response=>{
                        let vm = {
                            currentPage: response.data.meta.current_page,
                            lastPage: response.data.meta.last_page
                        };
                        this.products = response.data.data;
                        this.total = response.data.meta.total;
                        this.pagination = vm;
                        if(this.total && (query.page)) this.scrollToProductContainer();
                    })
            },
            toPage(page){
                return {
                    name: this.$route.name,
                    params: this.$route.params,
                    query: page > 1 ? {page: page} : {}
                };
            },
            truncateFavs(){
                this.$store.dispatch('setFavorites', []);
                this.fetchProducts();
            },
            loadBreadcrumbs(){
                this.breadCrumbing([
                    {
                        'text': this.$lang.cart.wishlist,
                        'icon': 'fas fa-heart'
                    }
                ])
            }
        }
    }
</script>
