<template>
    <section>
        <vue-headful
            :title="'Megazon'+(breadcrumbList && breadcrumbList.length>0?' - '+breadcrumbList[breadcrumbList.length-1].text:'')"
            :description="breadcrumbList && breadcrumbList.length>0?breadcrumbList[breadcrumbList.length-1].description || $lang.app.defaultDescription:$lang.app.defaultDescription"
        />
        <div class="bread-view" v-if="breadcrumbList && breadcrumbList.length>0">
            <div :class="!breadcrumbList[breadcrumbList.length-1].hideTitle?'home':''">
                <div class="container">
                    <div class="col-md-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link :to="{ name: 'home' }"><i class="fa fa-home"></i> {{ $lang.app.home_page }}</router-link>
                            </li>
                            <li v-if="breadcrumbList" v-for="breadcrumb in breadcrumbList" v-bind:key="breadcrumb.text" :class="((typeof breadcrumb.to === 'undefined')?'active ':'')+'breadcrumb-item'">
                                <router-link v-if="typeof breadcrumb.to !== 'undefined'" :to="breadcrumb.to"><i :class="breadcrumb.icon"></i> {{ breadcrumb.text }}</router-link>
                                <span v-else><i :class="breadcrumb.icon"></i> {{ breadcrumb.text }}</span>
                            </li>
                        </ol>
                    </div>
                </div>
                <div v-if="!breadcrumbList[breadcrumbList.length-1].hideTitle" class="home_content d-flex flex-column align-items-center justify-content-center">
                    <div class="container">
                        <div class="col-md-12" style="text-align: center">
                            <h2 class="home_title"><i v-if="breadcrumbList[breadcrumbList.length-1].icon" :class="breadcrumbList[breadcrumbList.length-1].icon"></i> {{ breadcrumbList[breadcrumbList.length-1].text }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import vueHeadful from 'vue-headful'
    export default {
        components:{
            'vue-headful': vueHeadful
        },
        data(){
            return {
                breadcrumbList:[]
            }
        },
        mounted(){
            this.updateList();
        },
        watch:{
            '$route' () { this.updateList() },
            '$route.params.id' () { this.updateList() },
            '$route.meta.breadCrumbs.length' () { this.updateList() },
            '$lang' () { this.updateList() }
        },
        methods: {
            updateList(){
                this.breadcrumbList = this.$route.meta.breadCrumbs || [];
            }
        }
    }
</script>
