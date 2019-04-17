import Vuex from 'vuex'
import Vue from 'vue'
Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        categories:[],
        authCheck:false,
        cart:{
            stores:[],
            totalPrice:0,
            totalQuantity:0
        },
        favorites:[],
        auth: {
            user:{},
            permissions:[]
        },
        redirectRoute:''
    },
    getters: {
        categories: state => {
            return state.categories;
        },
        authCheck:state => {
            return state.authCheck;
        },
        cart:state => {
            return state.cart;
        },
        favorites:state => {
            if(state.favorites instanceof Array) return state.favorites;
            else return [];
        },
        user: state => {
            return state.auth.user;
        },
        permissions: state => {
            if(state.auth.permissions instanceof Array) return state.auth.permissions;
            else return [];
        },
        myStores: state => {
            if(state.auth.user.stores instanceof Array) return state.auth.user.stores;
            else return [];
        },
        redirectRoute: state => {
            return state.redirectRoute;
        }
    },
    mutations: {
        setCategories: (state, categories) => {
            state.categories = categories;
        },
        setAuthCheck: (state, status) => {
            state.authCheck = status;
        },
        setCart: (state, cart) => {
            state.cart = cart;
        },
        setFavorites: (state, fav) => {
            state.favorites = fav;
        },
        addFavorite: (state, fav) => {
            if(!(state.favorites.indexOf(fav)>-1))
                state.favorites.push(fav);
            else state.favorites.splice(state.favorites.indexOf(fav))
        },
        clearCart: (state) => {
            state.cart = {
                stores:[],
                totalPrice:0,
                totalQuantity:0
            }
        },
        setAuth: (state, auth) => {
            state.auth = auth;
        },
        setStores: (state, stores) => {
            state.auth.user.stores = stores;
        },
        removeStore:(state, slug)=>{
            state.auth.user.stores = state.auth.user.stores.filter(storeItem=>storeItem.slug!==slug);
        },
        setRedirectRoute: (state, route) => {
            state.redirectRoute = route;
        }
    },
    actions: {
        setCategories: ({ commit }, value) => commit('setCategories', value),
        setAuthCheck: ({ commit }, value) => commit('setAuthCheck', value),
        setCart: ({commit}, value) => commit('setCart', value),
        clearCart: ({commit}) => commit('clearCart'),
        setFavorites: ({commit}, value) => commit('setFavorites', value),
        addFavorite: ({commit}, value) => commit('addFavorite', value),
        setAuth: ({commit}, value) => commit('setAuth', value),
        setStores: ({commit}, value) => commit('setStores', value),
        removeStore: ({commit}, value) => commit('removeStore', value),
        setRedirectRoute: ({commit}, value) => commit('setRedirectRoute', value)
    }
});

export default store
