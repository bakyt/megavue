import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from './components/Home.vue'
import Register from './components/authentication/Register.vue'
import Login from './components/authentication/Login.vue'
import ResetPassword from './components/authentication/ResetPassword.vue'

import Sections from './components/sections/Browse.vue'
import Category from './components/categories/Browse.vue'
import Product from './components/products/View.vue'

import Stores from './components/stores/Browse.vue'
import StoreCreate from './components/stores/Create.vue'
import StoreEdit from './components/stores/Edit.vue'
import StoreView from './components/stores/View.vue'

import Cart from './components/cart/View.vue'
import Wishlist from './components/cart/Wishlist.vue'
import Search from './components/search/View.vue'

import Error404 from './components/errors/404.vue'
import Error403 from './components/errors/403.vue'
import Error500 from './components/errors/500.vue'

Vue.use(VueRouter);
const router = new VueRouter({
    mode:'history',
    routes: [
        {
            path: '/',
            name:'home',
            component: Home,
            meta: {
                breadCrumbs: []
            }
        },
        // search
        {
            path: '/_search',
            name:'search',
            component: Search,
            meta: {
                breadCrumbs: []
            }
        },
        //Register
        {
            path: '/_register',
            name:'register',
            component: Register,
            meta: {
                breadCrumbs: []
            }
        },
        //Login
        {
            path: '/_login',
            name:'login',
            component: Login,
            meta: {
                breadCrumbs: []
            }
        },
        //Reset password
        {
            path: '/_reset-password',
            name:'password-reset',
            component: ResetPassword,
            meta: {
                breadCrumbs: []
            }
        },
        // view sections list
        {
            path: '/_sections/:id',
            name:'sections',
            component: Sections,
            meta: {
                breadCrumbs: []
            }
        },
        // view products list
        {
            path: '/_categories/:id',
            name:'categories',
            component: Category,
            meta: {
                breadCrumbs: []
            }
        },
        // view products list
        {
            path: '/_products/:id',
            name:'products.view',
            component: Product,
            meta: {
                breadCrumbs: []
            }
        },
        // cart view
        {
            path: '/_cart',
            name:'cart.view',
            component: Cart,
            meta: {
                breadCrumbs: []
            }
        },
        // wishlist view
        {
            path: '/_wishlist',
            name:'wishlist',
            component: Wishlist,
            meta: {
                breadCrumbs: []
            }
        },
        // Here starts error pages
        // error 403
        {
            path: '/_403',
            name:'403',
            component: Error403,
            meta: {
                breadCrumbs: [
                    {
                        text: 'У вас нет доступа к данной странице', // crumb text
                        icon: 'fa fa-close'
                    }
                ]
            }
        },
        // error 404
        {
            path: '/_404',
            name:'404',
            component: Error404,
            meta: {
                breadCrumbs: [
                    {
                        text: 'Страница не найдена', // crumb text
                        icon: 'fa fa-close'
                    }
                ]
            }
        },
        // error 500
        {
            path: '/_500',
            name:'500',
            component: Error500,
            meta: {
                breadCrumbs: [
                    {
                        text: 'Ошибка 500', // crumb text
                        icon: 'fa fa-close'
                    }
                ]
            }
        },
        {
            path: '/_stores',
            name:'stores',
            component: Stores,
            meta: {
                breadCrumbs: []
            }
        },
        {
            path: '/_stores/create',
            name:'stores.create',
            component: StoreCreate,
            meta: {
                breadCrumbs: []
            }
        },
        {
            path: "/:slug/edit",
            name:'stores.edit',
            component: StoreEdit,
            meta: {
                breadCrumbs: []
            }
        },
        {
            path: "/:slug",
            name:'stores.view',
            component: StoreView,
            meta: {
                breadCrumbs: []
            }
        },
        {
            path: "*",
            component: Error404,
            meta: {
                breadCrumbs: []
            }
        }
    ]
});
router.afterEach((to, from) => {
    if(from.name !== to.name) {
        $('html, body').animate({
            scrollTop: $("#app").offset().top
        }, 1000);
    }
});
export default router
