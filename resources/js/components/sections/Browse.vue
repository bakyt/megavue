<template>
    <div class="blog">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="blog_posts d-flex flex-row align-items-start justify-content-between">
                        <!-- Blog posts -->
                        <router-link :to="{ name:'sections', params:{ id:section.id } }" class="blog_post" v-for="section in sections" v-bind:key="section.id">
                            <div class="blog_image" :style="'background-image:url('+section.image+')'"></div>
                            <div class="blog_text">{{ section.name }}</div>
                        </router-link>
                        <!-- Blog posts -->
                        <router-link :to="{ name:'categories', params:{ id:category.id } }"  class="blog_post" v-for="category in categories" v-bind:key="category.id">
                            <div class="blog_image" :style="'background-image:url('+category.image+')'"></div>
                            <div class="blog_text">{{ category.name }}</div>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                name:'',
                sections:[],
                categories:[],
                sectionId:this.$route.params.id
            }
        },
        watch: {
            '$route.params.id': function(id){
                this.fetchSections(id);
                this.scrollToTop();
            },
            '$lang': function () {
                this.fetchSections(this.$route.params.id);
            }
        },
        created(){
            this.fetchSections();
        },
        methods:{
            fetchSections(id){
                id = id || this.sectionId;
                return axios.get('/api/sections/'+id)
                    .then(response=>{
                        this.sections = response.data.children;
                        this.categories = response.data.categories;
                        if(response.data.name) {
                            this.name = response.data.name;
                        }
                        else this.name = ''
                    }).then(()=>{
                        this.loadBreadcrumbs()
                    })
            },
            loadBreadcrumbs(){
                if(this.name) {
                    this.breadCrumbing([
                        {
                            text: this.$lang.app.categories, // crumb text
                            icon: null,
                            to: { name: 'sections', params: { id: 'all' } }
                        },
                        {
                            text: this.name, // crumb text
                            icon: null
                        }
                    ])
                }
                else {
                    this.breadCrumbing([
                        {
                            text: this.$lang.app.categories, // crumb text
                            icon: null
                        }
                    ])
                }
            }
        }
    }
</script>
