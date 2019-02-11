<template>
    <ul class="sidebar-menu" data-widget="tree">
        <li class="header">Меню</li>
        <!-- Optionally, you can add icons to the links -->
        <li>
            <router-link class="menu-item" :to="{name:'home'}">
                <i class="fa fa-home"></i>
                <span>Главная</span>
            </router-link>
        </li>
        <li v-for="section in sections" v-bind:key="section.id" :class="section.items?'treeview':''">
            <a href="#">
                <i :class="section.icon"></i>
                <span>{{ section.name }}</span>
                <span v-if="Boolean(section.items)" class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
            </a>
            <ul class="treeview-menu">
                <li v-for="item in section.items" v-bind:key="item.id"><router-link class="menu-item" :to="{ name:item.route }"><i :class="item.icon"></i>{{ item.name }}</router-link></li>
            </ul>
        </li>
    </ul>
</template>
<script>
    export default {
        data(){
            return {
                sections:[],
                section:{
                    id:'',
                    name:'',
                    link:'',
                    icon:'',
                    items:[]
                }
            }
        },

        created(){
            this.fetchMenus()
        },

        methods:{
            fetchMenus(){
                axios.get('/api/sections')
                    .then(res=>{
                        this.sections = res.data;
                    })
                    .catch(res=>console.log(res))
            }
        }
    }
</script>
