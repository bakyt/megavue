<template>
    <div class="login-page">
        <div id="recaptcha-container"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 offset-lg-4">
                    <div class="form-group" v-if="!verified">
                        <label for="phone">{{ $lang.auth.phone }}</label>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-btn">
                                    <select style="margin-left:0;border-bottom-right-radius: 0;border-top-right-radius: 0;border-right: 0" class="form-control" v-model="currentCode">
                                        <option v-for="code in codes" :selected="code.dial_code==currentCode" :value="code.dial_code">{{ code.dial_code }}({{ code.code }})</option>
                                    </select>
                                </div>
                                <input type="text" @blur="validatePhone" class="form-control" id="phone" v-model="phone" :placeholder="$lang.auth.phone">
                            </div>
                            <button type="button" @click="sendOtp" class="btn btn-primary" style="float:right;margin-top:5px;margin-bottom:5px;"><span v-if="!verifiedPhone">{{ $lang.auth.reset }}</span><span v-else><i :title="$lang.auth.resend_code" class="fas fa-redo"></i> {{ $lang.auth.resend_code }}</span></button>
                        </div>
                        <div class="form-group" v-if="verifiedPhone">
                            <input type="number" v-model="verificationCode" max="999999" maxlength="6" :placeholder="$lang.auth.code" class="form-control" required="" id="31name-form1-73">
                            <button type="button" @click="verifyOtp" class="btn btn-primary" style="float:right;margin-top:5px;margin-bottom:5px;">{{ $lang.auth.verify }}</button>
                        </div>
                    </div>
                    <form onsubmit="event.preventDefault()" @submit="reset" v-if="verified">
                        <div class="form-group">
                            <label for="password">{{ $lang.auth.new_password }}</label>
                            <input type="password" class="form-control" id="password" v-model="password" :placeholder="$lang.auth.password">
                        </div>
                        <div class="form-group">
                            <label for="password1">{{ $lang.auth.confirmPassword }}</label>
                            <input type="password" class="form-control" id="password1" v-model="confirm_password" :placeholder="$lang.auth.password">
                        </div>
                        <button type="button" @click="reset" class="btn btn-primary" style="float:right">{{ $lang.auth.reset }}</button>
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
                confirm_password:'',
                codes:'',
                currentCode:'+996',
                verified:'',
                verifiedPhone:'',
                verificationCode:''
            }
        },
        watch:{
            $lang (){
                this.loadBreadcrumbs();
            },
            authCheck (){
                if(store.state.authCheck) this.$router.push({ name:'home' });
            }
        },
        created(){
            if(store.state.authCheck) this.$router.push({ name:'home' });
            else {
                this.$auth.initReCaptcha('recaptcha-container');
                this.getCodes();
                this.loadBreadcrumbs();
            }
        },
        methods:{
            loadBreadcrumbs(){
                this.breadCrumbing([
                    {
                        text: this.$lang.auth.reset_password, // crumb text
                        icon: 'fa fa-key'
                    }
                ])
            },
            getCodes(){
                this.$auth.getPhoneCodes().then(response=>{
                    this.codes = response.codes;
                    if(response.current) this.currentCode = response.current.country_calling_code;
                })
            },
            reset(){
                if(this.password !== this.confirm_password) {
                    new PNotify({
                        text: this.$lang.auth.passwords_not_match,
                        icon: 'fa fa-close',
                        title: this.$lang.app.error,
                        type: "error",
                    });
                    return false;
                }
                else {
                    axios.put('/api/reset-password', {
                        phone:this.verifiedPhone,
                        password:this.password
                    })
                        .then(response=>{
                            new PNotify({
                                title: this.$lang.app.success,
                                text: this.$lang.auth.password_reset_success,
                                type: "success",
                                icon: "fa fa-check"
                            });
                            this.$router.push({ name:'login' })
                        })
                }
            },
            sendOtp(){
                if (this.phone) this.$auth.sendOtp(this.currentCode+this.phone)
                    .then(response=>{
                        new PNotify({
                            title: this.$lang.app.success,
                            text: this.$lang.auth.confirmation_was_sent,
                            type: "success",
                            icon: "fa fa-check"
                        });
                        this.verifiedPhone = this.currentCode+this.phone;
                    })
                    .catch(error=>{
                        if(error.code === 'auth/invalid-phone-number') new PNotify({
                            title: this.$lang.app.success,
                            text: this.$lang.auth.phone_invalid,
                            type: "error",
                            icon: "fa fa-close"
                        });
                        else console.log(error.code)
                    });
                else new PNotify({
                    text: this.$lang.auth.phone_required,
                    icon: 'fa fa-close',
                    title: this.$lang.app.error,
                    type: "error",
                });
            },
            verifyOtp(){
                if (!this.verifiedPhone) new PNotify({
                    text: this.$lang.auth.verify_phone,
                    icon: 'fa fa-close',
                    title: this.$lang.app.error,
                    type: "error",
                });
                else if(!this.verified) this.$auth.verifyOtp(this.verificationCode)
                    .then(response=>{
                        this.verified = true;
                        new PNotify({
                            title: this.$lang.app.success,
                            text: this.$lang.auth.verified,
                            type: "success",
                            icon: "fa fa-check"
                        });
                    })
                    .catch(error=>{
                        console.log(error)
                    });
                else this.register();
            },
            validatePhone(){
                this.phone = this.phone.replace(this.currentCode, '');
                if(parseInt(this.phone[0]) === 0) this.phone = this.phone.substr(1,this.phone.length-1);
                if(this.verifiedPhone && this.verifiedPhone !== this.phone){
                    this.verifiedPhone='';
                    this.verified='';
                    this.verificationCode = '';
                }
            }
        }
    }
</script>
