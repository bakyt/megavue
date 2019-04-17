<template>
    <div class="cart_section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="cart_container">
                        <div v-for="storeItem in cart.stores" v-bind:key="storeItem.id">
                            <div class="cart_items">
                                <ul class="cart_list">
                                    <li class="cart_item">
                                        <span class="cart_title"><router-link :to="{ name:'stores.view', params:{ slug:storeItem.slug } }"><img class="store_icon" :src="storeItem.icon" /> {{ storeItem.name }}</router-link></span>
                                        <div class="trash">
                                            <button class="btn btn-danger" @click="$cart.removeStore(storeItem)" :title="$lang.cart.remove"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </li>
                                    <li class="cart_item clearfix" v-for="product in storeItem.items" v-bind:key="product.id+'_'+(product.color?product.color.id:'')">
                                        <div class="trash">
                                            <button class="btn btn-danger btn-sm" @click="$cart.removeItem(product, storeItem)" :title="$lang.cart.remove"><i class="fas fa-trash"></i></button>
                                        </div>
                                        <div class="cart_item_image"><img :src="product.image" alt=""></div>
                                        <div class="cart_item_info d-flex flex-md-row flex-column justify-content-between">
                                            <div class="cart_item_name cart_info_col">
                                                <div class="cart_item_title">{{ $lang.product.title }}</div>
                                                <div class="cart_item_text"><router-link :to="{ name:'products.view', params:{ id:product.id } }">{{ product.title }}</router-link></div>
                                            </div>
                                            <div class="cart_item_color cart_info_col" v-if="product.color">
                                                <div class="cart_item_title">{{ $lang.product.Color }}</div>
                                                <div class="cart_item_text"><span :style="'background-color:'+product.color.rgb"></span>{{ product.color.name }}</div>
                                            </div>
                                            <div class="cart_item_quantity cart_info_col">
                                                <div class="cart_item_title">{{ $lang.product.Quantity }}</div>
                                                <div class="cart_item_text">
                                                    {{ product.quantity }}
                                                    <div class="quantity_buttons">
                                                        <div @click="$cart.incrementItem(product, storeItem)" id="quantity_inc_button" class="quantity_inc quantity_control">
                                                            <i class="fas fa-chevron-up"></i>
                                                        </div>
                                                        <div @click="$cart.decrementItem(product, storeItem)" id="quantity_dec_button" class="quantity_dec quantity_control">
                                                            <i class="fas fa-chevron-down"></i>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="cart_item_price cart_info_col">
                                                <div class="cart_item_title">{{ $lang.product.Price }}</div>
                                                <div class="cart_item_text">
                                                    {{ product.price }}{{ $lang.product.som }}
                                                </div>
                                            </div>
                                            <div class="cart_item_total cart_info_col">
                                                <div class="cart_item_title">{{ $lang.product.total }}</div>
                                                <div class="cart_item_text">{{ product.total }}{{ $lang.product.som }}</div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="order_total_store clearfix">
                                        <div class="order_total_content text-md-right">
                                            <div class="order_total_title">{{ $lang.product.total }}</div>
                                            <div class="order_total_amount">{{ storeItem.total }}{{ $lang.product.som }}</div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div v-if="cart.stores.length===0">
                            <div class="col-md-12">
                                {{ $lang.cart.empty }}
                            </div>
                        </div>

                        <!-- Order Total -->
                        <div class="order_total">
                            <div class="order_total_content text-md-right">
                                <div class="order_total_title">{{ $lang.cart.total }}</div>
                                <div class="order_total_amount">{{ cart.totalPrice }}{{ $lang.product.som }}</div>
                            </div>
                        </div>
                        <div class="cart_buttons" v-if="cart.totalQuantity>0 && !customer_form">
                            <button type="button" class="button cart_button_clear" @click="$cart.truncate()">{{ $lang.cart.truncate }}</button>
                            <button type="button" @click="customer_form=!customer_form" class="button cart_button_checkout">{{ $lang.cart.checkout }}</button>
                        </div>
                        <div class="customer_form float-right col-lg-3 col-md-4 col-xs-12 col-sm-5" v-if="customer_form">
                            <input name="name" v-model="name" type="text" class="form-control mb-2 mr-sm-2" id="email2" :placeholder="$lang.auth.your_name">
                            <input name="phone" v-model="phone" type="text" class="form-control mb-2 mr-sm-2" id="email23" :placeholder="$lang.auth.phone">
                            <input name="address" v-model="address" type="text" class="form-control mb-2 mr-sm-2" id="pwd2" :placeholder="$lang.auth.your_address">
                            <button @click="checkout" type="button" class="btn btn-primary mb-2">{{ $lang.cart.send }}</button>
                            <button @click="customer_form=!customer_form" class="btn btn-default mb-2">X</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import store from '../../store.js';
    export default {
        computed: {
            cart () {
                return store.state.cart;
            }
        },
        data(){
            return {
                name:'',
                phone:'',
                address:'',
                customer_form:false
            }
        },
        created(){
            this.loadBreadcrumbs();
        },
        watch:{
            $lang () {
                this.loadBreadcrumbs();
            }
        },
        methods:{
            loadBreadcrumbs(){
                this.breadCrumbing([
                    {
                        text: this.$lang.cart.cart, // crumb text
                        icon: 'fa fa-shopping-bag'
                    }
                ])
            },
            checkout(){
                let cart = this.$store.getters.cart;
                axios.post('/api/orders', {
                    stores:cart.stores,
                    phone: this.phone,
                    address: this.address,
                    name: this.name
                })
                    .then(()=>{
                        new PNotify({
                            title: this.$lang.app.success,
                            text: this.$lang.cart.order_was_send,
                            type: "success",
                            icon: "fa fa-check"
                        });
                        this.$store.dispatch('clearCart');
                        this.customer_form = false;
                    })
            }
        }
    }
</script>
