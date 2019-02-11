
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');

import Vue from 'vue';
import Router from './router.js'
import axios from 'axios'

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
if(!Boolean(localStorage.getItem('token')) || localStorage.getItem('expires_in') < Date.now() || !localStorage.getItem('permissions')){
    Vue.component('vue-app', require('./components/authentication/Login.vue').default);
}
else {
    window.permissions = JSON.parse(localStorage.getItem('permissions'));
    axios.defaults.headers.common['Authorization'] = 'Bearer '+localStorage.getItem('token');
    axios.interceptors.request.use(request => {
        Pace.restart();
        return request
    });
    axios.interceptors.response.use(null, function(error) {
        let res = Object.assign({}, error).response;
        switch(res.status){
            case 401:
                localStorage.clear();
                location.reload();
                break;
            case 403:
                Router.push('/403');
                break;
            case 404:
                Router.push('/404');
                break;
            case 500:
                Router.push('/500');
                console.log(res);
                break;
            case 422:
                $.each(res.data.errors, function (key, value) {
                    new PNotify({
                        title: 'Ошибка',
                        text: value[0],
                        type: "error",
                        icon: "fa fa-warning"
                    });
                });
                break;
        }
        return Promise.reject(error);
    });
    Vue.component('vue-app', require('./components/App.vue').default);
    Vue.component('main-menu', require('./components/widgets/Menu.vue').default);
    Vue.component('main-user', require('./components/widgets/User.vue').default);
    Vue.component('breadcrambs', require('./components/widgets/Breadcrambs.vue').default);
}

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router:Router
});
