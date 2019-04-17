<template>
    <div style="display:inline-block">
        <div class="top_bar_user">
            <ul class="standard_dropdown top_bar_dropdown">
                <li>
                    <a href="javascript:void(0)"><i class="fas fa-shopping-bag"></i> {{ $lang.store.my_store }}</a>
                    <ul>
                        <li v-for="storeItem in user.stores" v-bind:key="storeItem.id"><router-link :to="{ name:'stores.view', params:{ slug:storeItem.slug } }"><span class="user_icon"><img :src="storeItem.icon" /></span>{{ storeItem.name }}</router-link></li>
                        <li><router-link :to="{ name:'stores.create' }"><i class="fas fa-cart-plus"></i> {{ $lang.store.create_store }}</router-link></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="top_bar_user" v-if="authCheck">
            <ul class="standard_dropdown top_bar_dropdown">
                <li>
                    <a href="javascript:void(0)"><i class="fas fa-user"></i> {{ user.name }}</a>
                    <ul class="login-panel">
                        <li><a href="javascript:void(0)" @click="logout">{{ $lang.auth.logout }}</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="top_bar_user" v-else>
            <ul class="standard_dropdown top_bar_dropdown">
                <li>
                    <a href="#"><i class="fas fa-sign-in-alt"></i> {{ $lang.auth.login }}</a>
                    <ul class="login-panel">
                        <li class="col-md-12">
                            <form onsubmit="event.preventDefault()" @submit="login">
                                <div class="form-group">
                                    <label for="phone">{{ $lang.auth.phone }}</label>
                                    <div class="input-group">
                                        <div class="input-group-btn">
                                            <select style="margin-left:0;border-bottom-right-radius: 0;border-top-right-radius: 0;border-right: 0" class="form-control" v-model="currentCode">
                                                <option v-for="code in codes" :selected="code.dial_code==currentCode" :value="code.dial_code">{{ code.dial_code }}({{ code.code }})</option>
                                            </select>
                                        </div>
                                        <input type="text" class="form-control" id="phone" v-model="phone" :placeholder="$lang.auth.phone">
                                    </div>
                                    <label for="password">{{ $lang.auth.password }}</label>
                                    <input type="password" class="form-control" id="password" v-model="password" :placeholder="$lang.auth.password">
                                </div>
                                <input type="checkbox" id="dropdownCheck2">
                                <label for="dropdownCheck2">
                                    {{ $lang.auth.remember }}
                                </label>
                                <button type="button" @click="login" class="btn btn-primary" style="float:right">{{ $lang.auth.signIn }}</button>
                                <router-link :to="{ name:'register' }" class="not-list">{{ $lang.auth.signUp }}</router-link>
                                <router-link :to="{ name:'password-reset' }" class="not-list">{{ $lang.auth.forgot_password }}?</router-link>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</template>
<script>
    import store from '../../store.js';
    export default {
        computed: {
            authCheck () {
                return store.state.authCheck;
            },
            user () {
                return store.state.auth.user;
            }
        },
        data(){
            return {
                phone:'',
                password:'',
                codes:'',
                currentCode:'+996'
            }
        },
        created(){
            this.getCodes();
        },
        methods:{
            login(){
                this.$auth.login(this.currentCode+this.phone, this.password).then(response=>{
                    if(!response.status){
                        new PNotify({
                            title: this.$lang.app.error,
                            text: response.message,
                            type: "error",
                            icon: "fa fa-warning"
                        });
                    }
                });
            },

            logout(){
                this.$auth.logout();
            },

            getCodes(){
                this.$auth.getPhoneCodes().then(response=>{
                    this.codes = response.codes;
                    if(response.current) this.currentCode = response.current.country_calling_code;
                })
            }
        }
    }
</script>
