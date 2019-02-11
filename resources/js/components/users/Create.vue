<template>
    <div class="box box-default">
        <div class="box-body">
            <form class="form-horizontal" onsubmit="event.preventDefault()">
                <div class="form-group">
                    <label for="pin" class="col-sm-2 control-label">ПИН</label>

                    <div class="col-sm-10">
                        <input name="pin" type="text" class="form-control" id="pin" placeholder="Name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="login" class="col-sm-2 control-label">Логин</label>

                    <div class="col-sm-10">
                        <input name="username" type="text" class="form-control" id="login" placeholder="Логин">
                    </div>
                </div>
                <div class="form-group">
                    <label for="sname" class="col-sm-2 control-label">Фамилия</label>

                    <div class="col-sm-10">
                        <input name="sname" type="text" class="form-control" id="sname" placeholder="Фамилия">
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Имя</label>

                    <div class="col-sm-10">
                        <input name="name" type="text" class="form-control" id="name" placeholder="Имя">
                    </div>
                </div>
                <div class="form-group">
                    <label for="fname" class="col-sm-2 control-label">Отчество</label>

                    <div class="col-sm-10">
                        <input name="fname" type="text" class="form-control" id="fname" placeholder="Отчество">
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">Email</label>

                    <div class="col-sm-10">
                        <input name="email" type="email" class="form-control" id="email" placeholder="Email">
                    </div>
                </div>
                <div class="form-group">
                    <label for="site_role" class="col-sm-2 control-label">Роль</label>

                    <div class="col-sm-10">
                        <select name="role_name" id="site_role" class="form-control">
                            <option v-for="siteRole in siteRoles" v-bind:key="siteRole.id" :value="siteRole.id">{{ siteRole.description }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="position" class="col-sm-2 control-label">Должность</label>

                    <div class="col-sm-10">
                        <select name="role_name" id="position" class="form-control">
                            <option v-for="position in positions" v-bind:key="position.id" :value="position.id">{{ position.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="active" class="col-sm-2 control-label">Активность</label>

                    <div class="col-sm-10">
                        <select name="active" id="active" class="form-control">
                            <option value="0">Неактивен</option>
                            <option value="1">Активен</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="readonly" class="col-sm-2 control-label">Доступ</label>

                    <div class="col-sm-10">
                        <select name="readonly" id="readonly" class="form-control">
                            <option value="0">Доступ на редактирование</option>
                            <option value="1">Только чтение</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-sm-2 control-label">Пароль</label>

                    <div class="col-sm-10">
                        <input autocomplete="new-password" name="paswsword" type="password" class="form-control" id="password" placeholder="Пароль">
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
                siteRoles:[],
                siteRole:{
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
                }
            }
        },

        created(){
            this.fetchCreate();
        },

        methods:{
            fetchCreate(){
                axios.get('/api/users/create')
                    .then(res=>{
                        this.siteRoles = res.data.siteRoles;
                        this.positions = res.data.positions;
                    })
                    .catch(res=>console.log(res))
            },
            storeUser(){
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
