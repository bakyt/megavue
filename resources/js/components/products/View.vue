<template>

    <!-- Single Product -->

    <div class="single_product">
        <div class="container">
            <div class="row">

                <!-- Images -->

                <div class="col-lg-2 order-lg-1 order-2">
                    <ul class="image_list">
                        <li v-for="image in product.thumbs" :data-image="image.replace('thumbs/', '')"><img :src="image" alt=""></li>
                    </ul>
                </div>

                <!-- Selected Image -->
                <div class="col-lg-5 order-lg-2 order-1">
                    <div class="image_selected">
                        <span class="sale" v-if="product.sale">{{ product.sale }}%</span>
                        <img :src="product.images[0]" alt="">
                    </div>
                </div>

                <!-- Description -->
                <div class="col-lg-5 order-3">
                    <div class="product_description">
                        <div class="product_category">{{ product.category.name }}</div>
                        <div class="product_name">{{ product.title }}</div>
                        <div class="product_text" v-if="product.body">
                            <ul>
                                <li v-for="body in product.body" v-bind:key="body.property"><p><strong>{{ body.property }}:</strong> {{ body.value }}</p></li>
                            </ul>
                        </div>
                        <div class="product_text" v-if="product.additional_info"><p>{{ product.additional_info }}</p></div>
                        <div class="order_info d-flex flex-row">
                            <form action="#">
                                <div class="clearfix">

                                    <!-- Product Quantity -->
                                    <div class="product_quantity">
                                        <span>{{ $lang.product.Quantity }}: </span>
                                        <input id="quantity_input" v-model="selectedQuantity" type="number" @blur="!selectedQuantity?selectedQuantity=1:null">
                                        <div class="quantity_buttons">
                                            <div id="quantity_inc_button" class="quantity_inc quantity_control" @click="selectedQuantity++"><i class="fas fa-chevron-up"></i></div>
                                            <div id="quantity_dec_button" class="quantity_dec quantity_control" @click="selectedQuantity!==1?selectedQuantity--:null"><i class="fas fa-chevron-down"></i></div>
                                        </div>
                                    </div>

                                    <!-- Product Color -->
                                    <ul v-if="product.colors.length>0" class="product_color">
                                        <li>
                                            <span>{{ $lang.product.Color }}: </span>
                                            <div class="color_mark_container"><div id="selected_color" class="color_mark" :style="'background:'+color.rgb"></div></div>
                                            <div class="color_dropdown_button"><i class="fas fa-chevron-down"></i></div>

                                            <ul class="color_list">
                                                <li v-for="productColor in product.colors">
                                                    <div class="color_mark" @click="color=productColor" :style="'border:1px solid #e5e5e5;background:'+productColor.rgb"></div>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>

                                </div>

                                <div :class="'product_price'+(product.sale?' discount':'')">
                                    {{ product.sale?parseInt(product.price/100*(100-product.sale)):product.price }} {{ $lang.product.som }}
                                    <span v-if="product.sale">{{ product.price+$lang.product.som }}</span>
                                </div>
                                <div class="button_container">
                                    <button type="button" class="button cart_button" @click="$cart.addItem(product, selectedQuantity, color)"><i class="fas fa-shopping-cart"></i> {{ $lang.product.add_to_cart }}</button>
                                    <div @click="$store.dispatch('addFavorite', product.id)" :class="'product_fav'+($store.getters.favorites.indexOf(product.id)>-1?' active':'')"><i class="fas fa-heart"></i></div>
                                    <div class="product_store">
                                        <i class="fas fa-shopping-cart"></i>
                                        {{ $lang.store.store }}: <router-link v-if="product.store.slug" :to="{ name:'stores.view', params:{ slug:product.store.slug } }">{{ product.store.name }}</router-link>
                                    </div>

                                </div>
                            </form>
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
                product:{
                    id:'',
                    title:'',
                    images:[],
                    price:'',
                    colors:[],
                    category:{
                        id:'',
                        name:'',
                        image:''
                    },
                    additional_info:'',
                    store:{
                        id:'',
                        slug:'',
                        name:''
                    },
                    body:[]
                },
                productId: this.$route.params.id,
                color:null,
                selectedQuantity:1
            }
        },
        created(){
            this.fetchProduct().then(()=>{
                $(document).ready(function () {
                    let oldContainer = $('.zoomContainer');
                    if(oldContainer.length>0) oldContainer.remove();
                    //image
                    let images = $('.image_list li');
                    let selected = $('.image_selected img');
                    selected.elevateZoom({
                        zoomWindowPosition: 2,
                        zoomWindowOffetx: 10,
                        zoomWindowFadeIn: 500,
                        zoomWindowFadeOut: 750,
                        zoomWindowWidth: 550,
                        zoomWindowHeight: 550
                    });
                    images.each(function()
                    {
                        let image = $(this);
                        image.on('click', function()
                        {
                            let imagePath = String(image.data('image'));
                            selected.attr('src', imagePath);
                        });
                        image.on('mouseover', function()
                        {
                            let imagePath = String(image.data('image'));
                            selected.attr('src', imagePath);
                            let oldContainer = $('.zoomContainer');
                            if (oldContainer.length > 0) oldContainer.remove();
                            selected.elevateZoom({
                                zoomWindowPosition: 2,
                                zoomWindowOffetx: 10,
                                zoomWindowFadeIn: 500,
                                zoomWindowFadeOut: 750,
                                zoomWindowWidth: 550,
                                zoomWindowHeight: 550
                            });
                        });
                    });
                })
            });
        },
        watch:{
            '$lang' () {
                this.fetchProduct();
            },
            selectedQuantity(quantity) {
                quantity = parseInt(quantity);
                this.selectedQuantity = quantity>999?999:quantity<=0?1:quantity;
            }
        },
        methods:{
            fetchProduct(){
                return axios.get('/api/products/'+this.productId)
                    .then(response => {
                        this.product = response.data;
                        if(this.product.colors.length>0) this.color=this.product.colors[0];
                    })
                    .then(()=>{
                        this.loadBreadcrumbs()
                    })
            },
            loadBreadcrumbs(){
                this.breadCrumbing([
                    {
                        text: this.product.category.name, // crumb text
                        icon: null,
                        to: { name: 'categories', params: { id: this.product.category.id } }
                    },
                    {
                        text: this.product.title, // crumb text
                        icon: null,
                        hideTitle:true
                    }
                ])
            }
        },
        destroyed(){
            $(document).ready(function () {
                let oldContainer = $('.zoomContainer');
                if (oldContainer.length > 0) oldContainer.remove();
            })
        }
    }
</script>
