<template>
    <div class="box box-default">
        <div class="box-body">
            <form class="form-horizontal" onsubmit="event.preventDefault()">
                <div class="form-group">
                    <label for="roleName" class="col-sm-2 control-label">Название</label>

                    <div class="col-sm-10">
                        <input name="roleName" type="text" :value="role.roleName" class="form-control" id="roleName" placeholder="Название">
                    </div>
                </div>
                <div class="form-group">
                    <label for="role_type" class="col-sm-2 control-label">Тип</label>

                    <div class="col-sm-10">
                        <input name="role_type" type="text" :value="role.role_type" class="form-control" id="role_type" placeholder="Тип">
                    </div>
                </div>
                <div class="form-group">
                    <label for="city" class="col-sm-2 control-label">Город</label>

                    <div class="col-sm-10">
                        <input name="city" type="text" :value="role.city" class="form-control" id="city" placeholder="Город">
                    </div>
                </div>
                <div class="form-group">
                    <label for="description" class="col-sm-2 control-label">Описание</label>

                    <div class="col-sm-10">
                        <input name="description" type="text" :value="role.description" class="form-control" id="description" placeholder="Описание">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="button" @click="updateRole" class="btn btn-success">Сохранить</button>
                        <button v-if="permissions.indexOf('site-roles.destroy') !== -1" type="button" @click="removeRole" class="btn btn-danger">Удалить</button>
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
                role:{
                    id:'',
                    roleName:'',
                    role_type:'',
                    description:'',
                    city:''
                },
                roleId:this.$route.params.id,
                permissions:window.permissions
            }
        },

        created(){
            this.fetchRoles();
        },

        methods:{
            fetchRoles(){
                axios.get('/api/site-roles/'+this.roleId)
                    .then(res=>{
                        this.role = res.data;
                    })
            },
            updateRole(){
                let object = {
                    roleName:document.getElementById('roleName').value,
                    role_type:document.getElementById('role_type').value,
                    description:document.getElementById('description').value,
                    city:document.getElementById('city').value,
                };
                axios.put('/api/site-roles/'+this.roleId, object)
                    .then(()=>{
                        new PNotify({
                            title: 'Успех',
                            text: 'Успешно обновлено',
                            type: "success",
                            icon: "fa fa-check"
                        });
                        this.$router.push({ name:'site-roles.index' });
                    })

            },
            removeRole(){
                let roleId = this.roleId;
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
                    axios.delete('/api/site-roles/'+roleId);
                    new PNotify({
                        title: 'Успех',
                        text: 'Успешно удалено',
                        type: "success",
                        icon: "fa fa-check"
                    });
                    $router.push({ name:'site-roles.index' });
                })
            }
        }
    }
</script>
