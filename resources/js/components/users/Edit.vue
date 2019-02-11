<template>
    <div class="box box-default">
        <div class="box-body">
            <form class="form-horizontal" onsubmit="event.preventDefault()">
                <div class="form-group">
                    <label for="pin" class="col-sm-2 control-label">ПИН</label>

                    <div class="col-sm-10">
                        <input name="pin" type="text" :value="user.pin" class="form-control" id="pin" placeholder="Name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="login" class="col-sm-2 control-label">Логин</label>

                    <div class="col-sm-10">
                        <input name="username" type="text" :value="user.username" class="form-control" id="login" placeholder="Логин">
                    </div>
                </div>
                <div class="form-group">
                    <label for="sname" class="col-sm-2 control-label">Фамилия</label>

                    <div class="col-sm-10">
                        <input name="sname" type="text" :value="user.sname" class="form-control" id="sname" placeholder="Фамилия">
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Имя</label>

                    <div class="col-sm-10">
                        <input name="name" type="text" :value="user.name" class="form-control" id="name" placeholder="Имя">
                    </div>
                </div>
                <div class="form-group">
                    <label for="fname" class="col-sm-2 control-label">Отчество</label>

                    <div class="col-sm-10">
                        <input name="fname" type="text" :value="user.fname" class="form-control" id="fname" placeholder="Отчество">
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">E-mail</label>

                    <div class="col-sm-10">
                        <input name="email" type="email" :value="user.email" class="form-control" id="email" placeholder="E-mail">
                    </div>
                </div>
                <div class="form-group">
                    <label for="site_role" class="col-sm-2 control-label">Роль</label>

                    <div class="col-sm-10">
                        <select name="role_name" id="site_role" class="form-control">
                            <option v-for="role in roles" v-bind:key="role.id" :value="role.id" :selected="role.id===user.role.id" >{{ role.description }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="position" class="col-sm-2 control-label">Должность</label>

                    <div class="col-sm-10">
                        <select name="role_name" id="position" class="form-control">
                            <option v-for="position in positions" v-bind:key="position.id" :value="position.id" :selected="position.id===user.position.id" >{{ position.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="active" class="col-sm-2 control-label">Активность</label>

                    <div class="col-sm-10">
                        <select name="active" id="active" class="form-control">
                            <option value="0">Неактивен</option>
                            <option value="1" :selected="user.active==1">Активен</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="readonly" class="col-sm-2 control-label">Доступ</label>

                    <div class="col-sm-10">
                        <select name="readonly" id="readonly" class="form-control">
                            <option value="0">Доступ на редактирование</option>
                            <option value="1" :selected="user.readonly==1">Только чтение</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-sm-2 control-label">Пароль</label>

                    <div class="col-sm-10">
                        <input autocomplete="new-password" name="paswsword" type="password" class="form-control" id="password" placeholder="Оставьте поле пустым если не желаете изменить пароль">
                    </div>
                </div>
                <!--<div class="form-group">-->
                    <!--<label for="role" class="col-sm-2 control-label">Роль</label>-->

                    <!--<div class="col-sm-10">-->
                        <!--<select name="role_name" id="role" class="form-control">-->
                            <!--<option v-for="role in roles" v-bind:key="role.id" :value="role.name" :selected="role.name===user.role.name" >{{ role.display_name }}</option>-->
                        <!--</select>-->
                    <!--</div>-->
                <!--</div>-->
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
                    pin:'',
                    email:'',
                    name:'',
                    sname:'',
                    fname:'',
                    username:'',
                    post:'',
                    readonly:'',
                    active:'',
                    siteRole:{
                        id:'',
                        roleName:'',
                        role_type:'',
                        description:'',
                        city:''
                    },
                    position:{
                        id:'',
                        hb_id:'',
                        type:'',
                        name:'',
                        description:''
                    }
//                    role:{
//                        id:'',
//                        name:'',
//                        display_name:'',
//                        guard_name:''
//                    }
                },
                userId:this.$route.params.id,
                roles:[],
                role:{
                    id:'',
                    roleName:'',
                    role_type:'',
                    description:'',
                    city:''
                },
                positions:[],
                position:{
                    id:'',
                    hb_id:'',
                    type:'',
                    name:'',
                    description:''
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
                        this.positions = res.data.positions;
                        this.roles = res.data.roles;
                    })
            },
            updateUser(){
                let object = {
                    pin:document.getElementById('pin').value,
                    username:document.getElementById('login').value,
                    name:document.getElementById('name').value,
                    sname:document.getElementById('sname').value,
                    fname:document.getElementById('fname').value,
                    roleId:document.getElementById('site_role').value,
                    position:document.getElementById('position').value,
                    email:document.getElementById('email').value,
                    password:document.getElementById('password').value,
                    active:document.getElementById('active').value,
                    readonly:document.getElementById('readonly').value,
//                    role_name:document.getElementById('role').value
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
