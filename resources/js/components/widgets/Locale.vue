<template>
    <div class="top_bar_menu">
        <ul class="standard_dropdown top_bar_dropdown">
            <li>
                <a href="#"><i class="fas fa-flag"></i> {{ $lang.lang }}</a>
                <ul>
                    <li style="text-transform:uppercase"><a href="javascript:void(0)" v-for="lang in $langs" @click="setLang(lang)">{{lang}}</a></li>
                </ul>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        methods:{
            setLang(lang){
                localStorage.setItem('lang', lang);
                this.$setLang(lang);
            }
        }
    }
</script>
