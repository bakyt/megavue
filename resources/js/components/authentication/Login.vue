<template>
    <div class="login-page">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 offset-lg-4">
                    <form onsubmit="event.preventDefault()" @submit="login">
                        <div class="form-group">
                            <label for="phone">{{ $lang.auth.phone }}</label>
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <select style="margin-left:0;border-bottom-right-radius: 0;border-top-right-radius: 0;border-right: 0" class="form-control" v-model="currentCode">
                                        <option v-for="code in codes" :selected="code.dial_code==currentCode" :value="code.dial_code">{{ code.dial_code }}({{ code.code }})</option>
                                    </select>
                                </div>
                                <input type="text" @blur="validatePhone" class="form-control" id="phone" v-model="phone" :placeholder="$lang.auth.phone">
                            </div>
                            <label for="password">{{ $lang.auth.password }}</label>
                            <input type="password" class="form-control" id="password" v-model="password" :placeholder="$lang.auth.password">
                        </div>
                        <input type="checkbox" id="dropdownCheck2">
                        <label for="dropdownCheck2">
                            {{ $lang.auth.remember }}
                        </label>
                        <button type="button" @click="login" class="btn btn-primary" style="float:right">{{ $lang.auth.signIn }}</button>
                        <br/>
                        <router-link :to="{ name:'register' }" class="not-list">{{ $lang.auth.signUp }}</router-link><br/>
                        <router-link :to="{ name:'password-reset' }" class="not-list">{{ $lang.auth.forgot_password }}?</router-link>
                    </form>
                </div>
            </div>
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
        watch:{
            authCheck (){
                if(store.state.authCheck) {
                    if(store.state.redirectRoute){
                        this.$router.push(store.state.redirectRoute);
                        store.dispatch('setRedirectRoute', '');
                    }
                    else this.$router.push({ name:'home' });
                }
            },
            $lang (){
                this.loadBreadcrumbs();
            }
        },
        created(){
            if(store.state.authCheck) this.$router.push({ name:'home' });
            else {
                this.getCodes();
                this.loadBreadcrumbs();
            }
        },
        methods:{
            login(){
                this.$auth.login(this.currentCode+this.phone, this.password).then(response=>{
                    if(!response.user){
                        new PNotify({
                            title: this.$lang.app.error,
                            text: response.message,
                            type: "error",
                            icon: "fa fa-warning"
                        });
                    }
                });
            },
            validatePhone(){
                this.phone = this.phone.replace(this.currentCode, '');
                if(parseInt(this.phone[0]) === 0) this.phone = this.phone.substr(1,this.phone.length-1);
            },
            logout(){
                this.$auth.logout();
            },

            getCodes(){
                this.$auth.getPhoneCodes().then(response=>{
                    this.codes = response.codes;
                    if(response.current) this.currentCode = response.current.country_calling_code;
                })
            },
            loadBreadcrumbs(){
                this.breadCrumbing([
                    {
                        text: this.$lang.auth.login, // crumb text
                        icon: 'fa fa-sign-in-alt'
                    }
                ])
            }
        }
    }
</script>
