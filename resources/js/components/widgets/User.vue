<template>
    <div class="user-panel">
        <div class="pull-left image">
            <img src="/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
            <p>{{ user.name }}</p>
            <!-- Status -->
            <a><i class="fa fa-circle text-success"></i> Online</a>
            <a style="cursor: pointer" @click="logout"><i class="fa fa-sign-out"></i> Выйти</a>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                user:{
                    name:''
                }
            }
        },

        created(){
            this.fetchUser()
        },

        methods:{
            fetchUser(){
                axios.get('/api/user')
                    .then(response=>{
                        this.user = response.data
                    })
                    .catch(response=>console.log(response));
            },
            logout(){
                localStorage.clear();
                location.reload()
            }
        }
    }
</script>
