<template>
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{ users }}</h3>

                    <p>Регистрация пользователей</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a style="cursor: pointer" @click="routeTo('users.index')" class="small-box-footer">Подробнее <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                users:''
            }
        },
        created(){
            this.fetchHome();
        },

        methods:{
            fetchHome(){
                axios.get('/api/home')
                    .then(res=>{
                        this.users = res.data.users;
                    })
            },
            routeTo(name){
                this.$router.push({ name:name })
            }
        }
    }
</script>
