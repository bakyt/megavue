
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');

import Vue from 'vue'
import Router from './router'
import Lang from 'vue-lang'
import Auth from './auth'
import Cart from './cart'
import store from './store'
const config = require("./config.json");
let language = config.lang;
// For locale
let locales = {
    "en": require("./components/locale/en.json"),
    "ru": require("./components/locale/ru.json")
};

if(localStorage.getItem('lang')){
    language = localStorage.getItem('lang');
}
Vue.use(Lang, {lang: language, locales: locales});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
//Vue.config.devtools=false;
Auth.init();
Auth.initFirebase();
Cart.init(Vue.prototype.$lang);
Vue.prototype.$auth = Auth;
Vue.prototype.$cart = Cart;
//Vue.prototype.$config = config;
Vue.mixin({
    methods: {
        breadCrumbing(breads){
            this.$route.meta.breadCrumbs.splice(0, this.$route.meta.breadCrumbs.length);
            breads.filter(e=>{
                this.$route.meta.breadCrumbs.push(e);
            });
        },
        scrollToPageContent(){
            if(document.scrollTop !== 0) $('html, body').animate({
                scrollTop: $("#page-content").offset().top
            },1000);
        },
        scrollToTop(){
            $('html, body').animate({
                scrollTop: 0
            },1000);
        },
    }
});

Vue.component('vue-app', require('./components/App.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router:Router,
    store
});
