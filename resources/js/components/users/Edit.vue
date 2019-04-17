<template>
    <div class="box box-default">
        <div class="box-body">
            <form class="form-horizontal" onsubmit="event.preventDefault()">

                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">ФИО</label>

                    <div class="col-sm-10">
                        <input name="name" type="text" :value="user.name" class="form-control" id="name" placeholder="Имя">
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">E-mail</label>

                    <div class="col-sm-10">
                        <input name="email" type="email" :value="user.email" class="form-control" id="email" placeholder="E-mail">
                    </div>
                </div>
                <div class="form-group">
                    <label for="role" class="col-sm-2 control-label">Роль</label>

                    <div class="col-sm-10">
                        <select name="role_name" id="role" class="form-control">
                            <option v-for="role in roles" v-bind:key="role.id" :value="role.id" :selected="role.id===user.role.id" >{{ role.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="col-sm-2 control-label">Пароль</label>

                    <div class="col-sm-10">
                        <input autocomplete="new-password" name="password" type="password" class="form-control" id="password" placeholder="Оставьте поле пустым если не желаете изменить пароль">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="button" @click="updateUser" class="btn btn-success">Сохранить</button>
                        <button v-if="permissions.indexOf('users.destroy') !== -1" type="button" @click="removeUser" class="btn btn-danger">Удалить</button>
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
                userId:this.$route.params.id,
                roles:[],
                role:{
                    id:'',
                    name:''
                },
                permissions:window.permissions
            }
        },

        created(){
            this.fetchUser();
        },

        methods:{
            fetchUser(){
                axios.get('/api/users/'+this.userId+'/edit')
                    .then(res=>{
                        this.user = res.data.user;
                        this.roles = res.data.roles;
                    })
            },
            updateUser(){
                let object = {
                    name:document.getElementById('name').value,
                    roleId:document.getElementById('role').value,
                    email:document.getElementById('email').value,
                    password:document.getElementById('password').value,
                };
                axios.put('/api/users/'+this.userId, object)
                    .then(()=>{
                        new PNotify({
                            title: 'Успех',
                            text: 'Успешно обновлено',
                            type: "success",
                            icon: "fa fa-check"
                        });
                        this.$router.push({ name:'users.index' });
                    })

            },
            removeUser(){
                let userId = this.userId;
                let $router = this.$router;
                (new PNotify({
                    title: 'Подтверждение',
                    text: 'Вы действительно хотите удалить?',
                    icon: 'fa fa-question-o',
                    hide: false,
                    confirm: {
                        confirm: true
                    },
                    buttons: {
                        closer: false,
                        sticker: false
                    },
                    history: {
                        history: false
                    }
                })).get().on('pnotify.confirm', function(){
                    axios.delete('/api/users/'+userId);
                    new PNotify({
                        title: 'Успех',
                        text: 'Успешно удалено',
                        type: "success",
                        icon: "fa fa-check"
                    });
                    $router.push({ name:'users.index' });
                })
            }
        }
    }
</script>
