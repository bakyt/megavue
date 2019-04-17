<template>
    <div class="box box-default">
        <div class="box-body">
            <form class="form-horizontal" onsubmit="event.preventDefault()">
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">ФИО</label>

                    <div class="col-sm-10">
                        <input name="name" type="text" class="form-control" id="name" placeholder="Имя">
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">E-mail</label>

                    <div class="col-sm-10">
                        <input name="email" type="email" class="form-control" id="email" placeholder="E-mail">
                    </div>
                </div>
                <div class="form-group">
                    <label for="role" class="col-sm-2 control-label">Роль</label>

                    <div class="col-sm-10">
                        <select name="role_name" id="role" class="form-control">
                            <option v-for="role in roles" v-bind:key="role.id" :value="role.id" >{{ role.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="col-sm-2 control-label">Пароль</label>

                    <div class="col-sm-10">
                        <input autocomplete="new-password" name="password" type="password" class="form-control" id="password" placeholder="Пароль">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="button" @click="storeUser" class="btn btn-success">Добавить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                user:{
                    id:'',
                    name:'',
                    email:'',
                    role:{
                        id:'',
                        name:''
                    }
                },
                roles:[],
                role:{
                    id:'',
                    name:''
                },
            }
        },

        created(){
            this.fetchCreate();
        },

        methods:{
            fetchCreate(){
                axios.get('/api/users/create')
                    .then(res=>{
                        this.roles = res.data.roles;
                    })
                    .catch(res=>console.log(res))
            },
            storeUser(){
                let object = {
                    name:document.getElementById('name').value,
                    roleId:document.getElementById('role').value,
                    email:document.getElementById('email').value,
                    password:document.getElementById('password').value,
                };
                axios.post('/api/users', object)
                    .then(()=>{
                        new PNotify({
                            title: 'Успех',
                            text: 'Успешно добавлено',
                            type: "success",
                            icon: "fa fa-check"
                        });
                        this.$router.push({ name:'users.index' });
                    })
            }
        }
    }
</script>
