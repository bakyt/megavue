<template>
    <section class="content-header">
        <h1 v-if="breadcrumbList && breadcrumbList.length>0">
            {{ (breadcrumbList.length>1)?breadcrumbList[breadcrumbList.length-2].text:breadcrumbList[0].text }}
            <small v-if="breadcrumbList.length>1 && breadcrumbList[breadcrumbList.length-1] && typeof breadcrumbList[breadcrumbList.length-1].text !== 'undefined'">{{ breadcrumbList[breadcrumbList.length-1].text }}</small>
        </h1>
        <h1 v-else>Главная</h1>
        <ol class="breadcrumb">
            <li>
                <router-link :to="{ name: 'home' }"><i class="fa fa-home"></i> Главная</router-link>
            </li>
            <li v-if="breadcrumbList" v-for="breadcrumb in breadcrumbList" v-bind:key="breadcrumb.text" :class="(typeof breadcrumb.to === 'undefined')?'active':''">
                <router-link v-if="typeof breadcrumb.to !== 'undefined'" :to="{ path:breadcrumb.to }"><i :class="breadcrumb.icon"></i> {{ breadcrumb.text }}</router-link>
                <span v-else><i :class="breadcrumb.icon"></i> {{ breadcrumb.text }}</span>
            </li>
        </ol>
    </section>
</template>

<script>
    export default {
        data(){
            return {
                breadcrumbList:[],
                siteTitle:document.getElementById('site-title')
            }
        },
        mounted(){
            this.updateList();
//            let menuItems = $('.menu-item');
//            menuItems.on('click', function () {
//                menuItems.parent().removeClass('active');
//                $(this).parent().addClass('active');
//            });
            //menuItems.find('a[href='+location.path+']');
        },
        watch:{ '$route' () { this.updateList() } },
        methods: {
            updateList(){
                this.breadcrumbList = this.$route.meta.breadCrumbs;
                this.siteTitle.innerHTML = this.breadcrumbList?this.breadcrumbList[0].text+(typeof this.breadcrumbList[1]!=='undefined'?' - '+this.breadcrumbList[1].text:''):'Главная'
            }
        }
    }
</script>
