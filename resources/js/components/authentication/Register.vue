<template>
    <div class="register">
        <div id="recaptcha-container"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <form class="mbr-form" @submit="verifyOtp()" onsubmit="event.preventDefault()">
                        <div class="row row-sm-offset">
                            <div class="col-md-4 multi-horizontal">
                                <div class="form-group">
                                    <label class="form-control-label mbr-fonts-style display-7" for="name-form1-73">{{ $lang.auth.name }}</label>
                                    <input type="text" class="form-control" v-model="name" required="" id="name-form1-73">
                                </div>
                            </div>
                            <div class="col-md-4 multi-horizontal">
                                <div class="form-group">
                                    <label class="form-control-label mbr-fonts-style display-7" for="1name-form1-73">{{ $lang.auth.birth_date }}</label>
                                    <input type="date" v-model="birth_date" class="form-control" name="name" id="1name-form1-73">
                                </div>
                            </div>
                            <div class="col-md-4 multi-horizontal">
                                <div class="form-group">
                                    <label class="form-control-label mbr-fonts-style display-7" for="21name-form1-73">{{ $lang.auth.gender }}</label>
                                    <select v-model="gender" style="margin-left:0" class="form-control" id="21name-form1-73">
                                        <option value="1">{{ $lang.auth.male }}</option>
                                        <option value="2">{{ $lang.auth.female }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12 multi-horizontal">
                                <div class="form-group">
                                    <label class="form-control-label mbr-fonts-style display-7" for="email-form1-73">{{ $lang.auth.phone }}</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-btn">
                                            <select style="margin-left:0;border-bottom-right-radius: 0;border-top-right-radius: 0;border-right: 0" class="form-control btn-sm" v-model="currentCode">
                                                <option v-for="code in codes" :selected="code.dial_code==currentCode" :value="code.dial_code">{{ code.dial_code }}({{ code.code }})</option>
                                            </select>
                                        </div>
                                        <input v-model="phone" type="text" id="email-form1-73" required="" class="form-control btn-sm" @blur="validatePhone">
                                        <span class="input-group-btn"><button @click="sendOtp" class="btn btn-primary btn-sm" type="button"><span v-if="!verified">{{ $lang.auth.verify }}</span><i :title="$lang.auth.resend_code" class="fas fa-redo" v-else></i> </button></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4 col-sm-12 multi-horizontal" v-if="verifiedPhone">
                                <div class="form-group">
                                    <label class="form-control-label mbr-fonts-style display-7" for="31name-form1-73">{{ $lang.auth.code }}</label>
                                    <input type="number" v-model="verificationCode" max="999999" maxlength="6" placeholder="------" class="form-control" required="" id="31name-form1-73">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 multi-horizontal">
                                <div class="form-group">
                                    <label class="form-control-label mbr-fonts-style display-7" for="51name-form1-73">{{ $lang.auth.password }}</label>
                                    <input type="password" autocomplete="new-password" v-model="password" class="form-control" required="" id="51name-form1-73">
                                </div>
                            </div>
                            <div class="col-md-4 multi-horizontal">
                                <div class="form-group">
                                    <label class="form-control-label mbr-fonts-style display-7" for="61name-form1-73">{{ $lang.auth.confirmPassword }}</label>
                                    <input type="password" v-model="confirmPassword" class="form-control" required="" id="61name-form1-73">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-form display-4">{{ $lang.auth.register }}</button>
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
            }
        },
        data(){
            return {
                name:'',
                phone:'',
                codes:[],
                currentCode:'+996',
                verificationCode:'',
                verifiedPhone:'',
                verified:false,
                password:'',
                confirmPassword:'',
                birth_date:'',
                gender:1
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
            if(store.getters.authCheck) this.$router.push({ name:'home' });
            else {
                this.$auth.initReCaptcha('recaptcha-container');
                this.getCodes();
                this.loadBreadcrumbs();
            }
        },
        methods:{
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
            getCodes(){
                this.$auth.getPhoneCodes().then(response=>{
                    this.codes = response.codes;
                    if(response.current) this.currentCode = response.current.country_calling_code
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
                        this.register();
                    })
                    .catch(error=>{
                        console.log(error)
                    });
                else this.register();
            },
            loadBreadcrumbs(){
                this.breadCrumbing([
                    {
                        text: this.$lang.auth.register, // crumb text
                        icon: 'fa fa-user-plus'
                    }
                ])
            },
            register(){
                if(this.password !== this.confirmPassword) {
                    new PNotify({
                        text: this.$lang.auth.passwords_not_match,
                        icon: 'fa fa-close',
                        title: this.$lang.app.error,
                        type: "error",
                    });
                    return false;
                }
                axios.post('/api/users', {
                    name: this.name,
                    phone: this.verifiedPhone,
                    phone_code: this.currentCode,
                    birth_date: this.birth_date,
                    password: this.password,
                    gender: this.gender
                })
                    .then(response=>{
                        this.$auth.login(this.verifiedPhone, this.password);
                    })
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
