import store from './store'
import * as firebase from 'firebase/app'
import 'firebase/auth'

const auth = {
    status:false,
    appVerifier:'',
    setStatus(status){
        this.status = status;
        store.dispatch('setAuthCheck', status);
        return status;
    },
    check(){
        if(!Boolean(localStorage.getItem('token')) || localStorage.getItem('expires_in') < Date.now()/1000) {
            this.logout();
            this.setStatus(false);
        }
        else this.setStatus(true);
        return this.status
    },
    init(){
        if(this.check()){
            axios.defaults.headers.common['Authorization'] = 'Bearer '+localStorage.getItem('token');
            axios.get('/api/user')
                .then(response=>{
                    window.user = response.data;
                })
                .then(()=>{
                    store.dispatch('setAuth', {
                        //permissions:JSON.parse(localStorage.getItem('permissions')),
                        user: window.user
                    });
                });
            this.setStatus(true)
        }
        else this.setStatus(false);
    },
    initFirebase(){
        firebase.initializeApp({
            apiKey: "AIzaSyAZgqIUDL44Ll2J18NcUS5inMw3UwvabbA",
            authDomain: "megazon-kg.firebaseapp.com",
            databaseURL: "https://megazon-kg.firebaseio.com",
            projectId: "megazon-kg",
            storageBucket: "megazon-kg.appspot.com",
            messagingSenderId: "187464181855"
        });
    },
    login(username, password){
        return axios.post("/api/login", {
            phone: username,
            password: password
        })
            .catch(error => {
                let res = Object.assign({}, error).response;
                console.log(res)
            })
            .then(response => {
                if (response.data.status) {
                    localStorage.setItem('token', response.data.access_token);
                    localStorage.setItem('expires_in', response.data.expires_in + Date.now());
                    // localStorage.setItem('permissions', JSON.stringify(response.data.permissions));
                    // localStorage.setItem('user', JSON.stringify(response.data.user));
                    // localStorage.setItem('stores', JSON.stringify(response.data.stores));
                    this.init();
                    return {
                        status:'success',
                        message:response.data.message,
                        user:response.data.user,
                    };
                }
                else return {
                    status:'error',
                    message:response.data.message,
                    user:null
                };
            });
    },
    user(){
        return store.getters.user;
    },
    isStoreAdmin(storeSlug){
        let stores = store.getters.myStores.filter(storeItem=>storeItem.slug===storeSlug);
        return stores.length>0
    },
    logout(){
        localStorage.removeItem('token');
        localStorage.removeItem('expires_in');
        // localStorage.removeItem('user');
        // localStorage.removeItem('permissions');
        // localStorage.removeItem('stores');
        store.dispatch('setAuth', {
            user:{},
            permissions:[]
        });
        this.setStatus(false);
    },
    sendOtp(phone){
        return firebase.auth().signInWithPhoneNumber(phone, this.appVerifier).then(response=>{
            window.confirmationResult = response;
            return response;
        });
    },
    verifyOtp(code){
        return window.confirmationResult.confirm(code);
    },
    initReCaptcha(recaptchaId){
        setTimeout(()=> {
            this.appVerifier = new firebase.auth.RecaptchaVerifier(recaptchaId, {
                'size': 'invisible',
                'callback': function (response) {
                    // reCAPTCHA solved, allow signInWithPhoneNumber.
                    // ...
                },
                'expired-callback': function () {
                    // Response expired. Ask user to solve reCAPTCHA again.
                    // ...
                }
            });
        }, 1000);
    },
    getPhoneCodes(){
        if(!Boolean(sessionStorage.getItem('countryCodes'))) return axios.get('/plugins/country_codes/code.json').then(response=>{
            sessionStorage.setItem('countryCodes', JSON.stringify(response.data));
            axios.get('https://ipapi.co/json/').then(res=>{
                sessionStorage.setItem('countryCode', JSON.stringify(res.data));
            });
            return {
                codes: response.data,
                current: JSON.parse(sessionStorage.getItem('countryCode'))
            };
        });
        return new Promise((resolve, reject)=>{
            let json;
            let code;
            try {
                json = JSON.parse(sessionStorage.getItem('countryCodes'));
            } catch(e) {
                sessionStorage.removeItem('countryCodes');
                json = this.getPhoneCodes();
            }
            try {
                code = JSON.parse(sessionStorage.getItem('countryCode'));
            } catch(e) {
                sessionStorage.removeItem('countryCode');
                json = this.getPhoneCodes();
            }
            resolve({
                codes:json,
                current:code
            });
            reject({})
        });
    }
};
export default auth
