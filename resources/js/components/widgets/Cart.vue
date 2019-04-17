<template>
    <div class="wishlist_cart d-flex flex-row align-items-center justify-content-end" >
        <div class="wishlist d-flex flex-row align-items-center justify-content-end" @click="$router.push({ name: 'wishlist' })">
            <div class="wishlist_icon"><img src="/images/heart.png" alt=""></div>
            <div class="wishlist_content">
                <div class="wishlist_text"><router-link :to="{ name:'wishlist' }">{{ $lang.cart.wishlist_short }}</router-link></div>
                <div class="wishlist_count">{{ favorites.length }}</div>
            </div>
        </div>

        <!-- Cart -->
        <div class="cart">
            <div class="cart_container d-flex flex-row align-items-center justify-content-end" @click="$router.push({ name: 'cart.view' })">
                <div class="cart_icon">
                    <img src="/images/cart.png" alt="">
                    <div class="cart_count"><span>{{ cart.totalQuantity }}</span></div>
                </div>
                <div class="cart_content">
                    <div class="cart_text"><router-link :to="{ name: 'cart.view' }">{{ $lang.cart.cart }}</router-link></div>
                    <div class="cart_price">{{ cart.totalPrice }}{{ $lang.product.som }}</div>
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
            },
            favorites () {
                return store.state.favorites;
            },
        }
    }
</script>
