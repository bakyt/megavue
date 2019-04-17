<template xmlns: xmlns:>
    <div class="store-view">
        <form @submit="createStore">
            <!-- Shop -->
            <div class="home">
                <div class="home_blur" :style="'background-image: url('+backgroundImageForNow+')'"></div>
                <div class="home_background">
                    <div class="container" style="width: 100%;height: 100%;">
                        <div class="store-background-image-icon" onclick="document.getElementById('background-image').click()"><i :title="$lang.store.change_background_image" class="fas fa-camera" style="text-shadow: 0 0 3px black"></i></div>
                        <div style="width: 100%;height: 100%;" class="d-flex flex-column align-items-center justify-content-center">
                            <img :src="iconImageForNow"/>
                            <span class="store-image-icon" onclick="document.getElementById('icon-image').click()"><i :title="$lang.store.change_icon_image" class="fas fa-camera" style="text-shadow: 0 0 3px black"></i></span>
                            <input type="text" class="store-name-editor" style="width: 100% !important;" v-model="name" :placeholder="$lang.store.name">
                            <input type="text" class="store-description-editor" style="width: 100% !important;" v-model="description" :placeholder="$lang.store.short_description">
                        </div>
                        <input type="file" accept="image/*" id="background-image" @change="backgroundImageChange" style="display: none"/>
                        <input type="file" accept="image/*" id="icon-image" @change="iconImageChange" style="display: none"/>
                    </div>
                </div>
            </div>
            <div class="store-edit">
                <div class="container">
                    <div class="mbr-form">
                        <div class="row row-sm-offset">
                            <div class="col-md-4 multi-horizontal">
                                <div class="form-group">
                                    <label class="form-control-label mbr-fonts-style display-7" for="name-form1-73">{{ $lang.store.name }}</label>
                                    <input type="text" class="form-control" v-model="name" required="" id="name-form1-73">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label mbr-fonts-style display-7" for="slug">{{ $lang.store.slug }}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">@</span>
                                        <input type="text" :placeholder="$lang.store.path_to_store" class="form-control" v-model="slug" required="" id="slug">
                                    </div>
                                    <small>(www.{{ host }}/{{ slug?slug:$lang.store.path_to_store }})</small>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label mbr-fonts-style display-7" for="description">{{ $lang.store.short_description }}</label>
                                    <input type="text" class="form-control" v-model="description" required="" id="description">
                                    <small>({{ description.length }}/255)</small>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label mbr-fonts-style display-7" for="about">{{ $lang.store.about_store }} ({{ $lang.store.detailed }})</label>
                                    <textarea class="form-control" v-model="about" required="" id="about"></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label mbr-fonts-style display-7" for="phone">{{ $lang.store.contacts }}</label>
                                    <div class="input-group" v-for="phone in contacts.phones">
                                        <span class="input-group-addon"><i :title="$lang.auth.phone" class="fa fa-phone"></i></span>
                                        <span class="input-group-addon">+</span>
                                        <input type="number" v-model="phone.value" class="form-control" id="phone" :placeholder="$lang.auth.phone" />
                                    </div>
                                    <div class="form-control" style="text-align: center">
                                        <button style="margin:10px" class="btn btn-primary btn-sm" @click="contacts.phones.push({ value:'' })" type="button">{{ $lang.store.add_phone }}</button>
                                        <button style="margin:10px" class="btn btn-danger btn-sm" @click="removeEmptyPhoneFields" type="button">{{ $lang.store.remove_empty_fields }}</button>
                                    </div>
                                    <br/>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i title="Whatsapp" class="fab fa-whatsapp"></i></span>
                                        <div class="input-group-btn">
                                        <select style="margin-left:0;border-bottom-right-radius: 0;border-top-right-radius: 0;border-right: 0" class="form-control" v-model="currentCode">
                                            <option v-for="code in codes" :selected="code.dial_code==currentCode" :value="code.dial_code">{{ code.dial_code }}({{ code.code }})</option>
                                        </select>
                                        </div>
                                        <input type="number" v-model="contacts.whatsapp" class="form-control" placeholder="Whatsapp" />
                                    </div><br/>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i title="Facebook" class="fab fa-facebook-f"></i></span>
                                        <input type="text" v-model="contacts.facebook" class="form-control" :placeholder="$lang.store.path_to+' Facebook'"/>
                                    </div><br/>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i title="Instagram" class="fab fa-instagram"></i></span>
                                        <input type="text" v-model="contacts.instagram" class="form-control" :placeholder="$lang.store.username+' Instagram'" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 multi-horizontal">
                                <div class="form-group">
                                    <label class="form-control-label mbr-fonts-style display-7" for="address">{{ $lang.store.store_type }}</label>
                                    <div style="margin-bottom: 10px" class="input-group" v-for="type in storeTypes" v-bind:key="type.id">
                                        <span class="input-group-addon"><input v-model="types" :value="type.id" type="checkbox" :id="'check-'+type.id"></span>
                                        <label class="form-control" :for="'check-'+type.id">{{ type.name }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 multi-horizontal">
                                <div class="form-group">
                                    <label class="form-control-label mbr-fonts-style display-7" for="address">{{ $lang.store.your_address }}</label>
                                    <small>({{ $lang.store.choose_your_location }})</small><br/>
                                    <button type="button" @click="findMe" class="btn btn-primary btn-sm" style="cursor: pointer;margin-right:10px;margin-top:10px;margin-bottom: -50px;display: inline-block; position: relative; z-index: 500; float: right"><i class="fas fa-map-marker-alt"></i> {{ $lang.store.my_location }}</button>
                                    <l-map id="map" style="display: inline-block;height:400px;"
                                           :bounds="bounds"
                                           :zoom="zoom"
                                           :center="center"
                                           @update:zoom="zoomUpdated"
                                           @update:center="centerUpdated"
                                           @update:bounds="boundsUpdated"
                                           @click="coordinateSelector">
                                        <l-tile-layer :url="url"/>
                                        <l-marker
                                            v-if="markerLatLng"
                                            :lat-lng="markerLatLng"
                                            :draggable="true"
                                            @dragend="markerSelectCoordinate"></l-marker>
                                    </l-map>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i :title="$lang.store.your_address" class="fa fa-map-marker-alt"></i></span>
                                        <input type="text" class="form-control" v-model="address" id="address" :placeholder="$lang.store.address_automatically_loads">
                                    </div>
                                    <small>{{ $lang.store.if_address_is_wrong }}</small>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit">{{ $lang.store.create_store }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import {LMap, LTileLayer, LMarker} from 'vue2-leaflet'
    import { Icon } from 'leaflet'
    import 'leaflet/dist/leaflet.css'
    delete Icon.Default.prototype._getIconUrl;
    Icon.Default.mergeOptions({
        iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
        iconUrl: require('leaflet/dist/images/marker-icon.png'),
        shadowUrl: require('leaflet/dist/images/marker-shadow.png')
    });
    export default {
        components: {
            'l-map': LMap,
            'l-tile-layer': LTileLayer,
            'l-marker': LMarker,
        },
        data(){
            return {
                storeTypes:[],
                iconImage:'',
                backgroundImage:'',
                iconImageForNow:'/storage/default-images/no-image.jpeg',
                backgroundImageForNow:'/storage/default-images/store/background.jpeg',
                name:'',
                description:'',
                contacts:{
                    whatsapp:'',
                    facebook:'',
                    instagram:'',
                    phones:[{ value:'' }]
                },
                slug:'',
                about:'',
                address:'',
                types:[],

                host:location.host,
                errorNotify:'',
                errorLength:'',

                url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                zoom: 8,
                center: [42.882004, 74.582748],
                bounds: null,
                markerLatLng: null,
                codes:[],
                currentCode:'+996'
            }
        },
        watch:{
            '$store.getters.authCheck' () {
                this.$router.push({ name:'home' });
            },
            slug (sl) {
                if(sl.match(/[^A-Za-z0-9\-._]+/g)){
                    this.slug = sl.replace(/[^A-Za-z0-9\-._]+/g, "");
                    if(!this.errorNotify) this.errorNotify =  new PNotify({
                        title: this.$lang.app.error,
                        text: this.$lang.store.slug_pattern_error_message,
                        icon: 'fa fa-error',
                        type: "error"
                    });
                    else this.errorNotify.open();
                }
            },
            description (des) {
                if(des.length>255) {
                    this.description = des.substr(0, 255);
                    if(!this.errorLength) this.errorLength =  new PNotify({
                        title: this.$lang.app.error,
                        text: this.$lang.store.max_limit,
                        icon: 'fa fa-error',
                        type: "error"
                    });
                    else this.errorLength.open();
                }
            },
            markerLatLng (ev) {
                this.getAddressByLatLng(ev)
            },
            $lang () {
                this.fetchTypes();
            }
        },
        created(){
            if(!this.$store.getters.authCheck) {
                new PNotify({
                    title: this.$lang.app.error,
                    text: this.$lang.auth.requires_auth,
                    icon: 'fa fa-error',
                    type: "error"
                });
                this.$store.dispatch('setRedirectRoute', { name: 'stores.create' });
                this.$router.push({ name: 'login' });
            }
            else {
                this.loadBreadcrumbs();
                this.fetchTypes();
                this.getCodes();
            }
        },
        methods:{
            fetchTypes () {
                axios.get('/api/stores/create')
                    .then(response=>{
                        this.storeTypes = response.data;
                    })
            },
            loadBreadcrumbs(){
                this.breadCrumbing([
                    {
                        text: this.$lang.store.create_store, // crumb text
                        icon: 'fa fa-cart-plus',
                        hideTitle:true
                    }
                ])
            },
            getCodes(){
                this.$auth.getPhoneCodes().then(response=>{
                    this.codes = response.codes;
                    if(response.current) this.currentCode = response.current.country_calling_code;
                });
            },
            backgroundImageChange(e){
                if(e.target.files[0]){
                    new ImageCompressor(e.target.files[0], {
                        quality: .6,
                        success:result => {
                            this.backgroundImage = result;
                            let reader = new FileReader();
                            reader.readAsDataURL(result);
                            reader.onloadend = () => {
                                this.backgroundImageForNow = reader.result;
                            };
                        },
                        error(ev) {
                            console.log(ev.message);
                        },
                    });
                }
            },
            iconImageChange(e){
                if(e.target.files[0]){
                    new ImageCompressor(e.target.files[0], {
                        quality: .6,
                        success:result => {
                            this.iconImage = result;
                            let reader = new FileReader();
                            reader.readAsDataURL(result);
                            reader.onloadend = () => {
                                this.iconImageForNow = reader.result;
                            };

                        },
                        error(ev) {
                            console.log(ev.message);
                        },
                    });
                }
            },
            zoomUpdated (zoom) {
                this.zoom = zoom;
            },
            centerUpdated (center) {
                this.center = center;
            },
            boundsUpdated (bounds) {
                this.bounds = bounds;
            },
            coordinateSelector (e) {
                this.markerLatLng = e.latlng;
            },
            markerSelectCoordinate(e){
                this.markerLatLng = e.target._latlng;
            },
            findMe(){
                navigator.geolocation.getCurrentPosition(function (e) {
                    console.log(e)
                }, function (e) {
                    console.log(e)
                });
            },

            getAddressByLatLng(latlng){
                axios.get('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat='+latlng.lat+'&lon='+latlng.lng+'&accept-language=ru')
                    .then(response=>{
                        let temp = [];
                        if(response.data.address.country) temp.push(response.data.address.country==='Киргизия'?'Кыргызстан':response.data.address.country);
                        if(response.data.address.state) temp.push(response.data.address.state);
                        if(response.data.address.county) temp.push(response.data.address.county);
                        if(response.data.address.city) temp.push(response.data.address.city);
                        if(response.data.address.town) temp.push(response.data.address.town);
                        if(response.data.address.village) temp.push(response.data.address.village);
                        if(response.data.address.neighbourhood) temp.push(response.data.address.neighbourhood);
                        if(response.data.address.suburb) temp.push(response.data.address.suburb);
                        if(response.data.address.road) temp.push(response.data.address.road);
                        if(response.data.address.house_number) temp.push(response.data.address.house_number);
                        this.address = temp.join(', ');
                    });
            },
            removeEmptyPhoneFields (){
                this.contacts.phones = this.contacts.phones.filter(phone=>phone.value !== '');
            },
            createStore(e){
                e.preventDefault();
                if (this.types.length<1){
                    new PNotify({
                        title: this.$lang.app.error,
                        text: this.$lang.store.type_empty,
                        icon: 'fa fa-error',
                        type: "error"
                    });
                }
                else {
                    let formData = new FormData;
                    let phones = this.contacts.phones.map(phone=>{
                        if(phone.value) return parseInt(phone.value[0])!==0?'+'+phone.value:phone.value;
                    });
                    let contacts = JSON.parse(JSON.stringify(this.contacts));
                    contacts.phones = phones;
                    contacts.whatsapp = this.currentCode+this.contacts.whatsapp;
                    formData.append('name', this.name);
                    formData.append('slug', this.slug);
                    formData.append('description', this.description);
                    formData.append('about', this.about);
                    formData.append('contacts', JSON.stringify(contacts));
                    formData.append('types', JSON.stringify(this.types));
                    formData.append('address_map', this.markerLatLng?JSON.stringify(this.markerLatLng):null);
                    formData.append('address', this.address);
                    if(this.iconImage) formData.append('icon', this.iconImage);
                    if(this.backgroundImage) formData.append('background_image', this.backgroundImage);
                    axios.post('/api/stores', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                        .then(response => {
                            let stores = this.$store.getters.myStores;
                            stores.push(response.data);
                            this.$store.dispatch('setStores', stores);
                            this.$router.push({
                                name: 'stores.view',
                                params: { slug: response.data.slug }
                            })
                        });
                }
            }
        }
    }
</script>
