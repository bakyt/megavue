<template>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-body">
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
                </div>
            </div>
            <div class="box box-solid">
                <div class="box-header with-border">
                    <div class="box-title"><i class="fa fa-key"></i> </div>
                </div>
                <div class="box-body">
                    <div v-for="section in sections" v-bind:key="sections.id" class="form-group">
                        <div class="checkbox">
                            <label>
                                <i :class="section.icon"></i> {{ section.name }}
                            </label>
                        </div>
                        <div class="form-group" style="padding-left:40px;">

                            <div v-for="item in section.items" v-bind:key="item.id" class="checkbox">
                                <label>
                                    <input class="permission" :name="item.route" :checked="item.access" type="checkbox">
                                    {{ item.name }}
                                </label>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <button type="button" @click="updateRole" class="btn btn-success">Сохранить</button>
            <button v-if="permissions.indexOf('roles.destroy') !== -1" type="button" @click="removeRole" class="btn btn-danger">Удалить</button>
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
                sections:[],
                section:{
                    id:'',
                    name:'',
                    link:'',
                    items:[]
                },
                item:{
                    id:'',
                    name:'',
                    route:'',
                    link:''
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
                axios.get('/api/roles/'+this.roleId+'/edit')
                    .then(res=>{
                        this.sections = res.data.sections;
                        this.role = res.data.data;
                    })
            },
            updateRole(){
                let givePermissions=[], revokePermissions=[];
                $.each($(".permission"), function () {
                    if(this.checked) givePermissions.push(this.name);
                    else revokePermissions.push(this.name);
                });
                let object = {
                    roleName:document.getElementById('roleName').value,
                    role_type:document.getElementById('role_type').value,
                    description:document.getElementById('description').value,
                    city:document.getElementById('city').value,
                    give_permissions:givePermissions,
                    revoke_permissions:revokePermissions
                };
                axios.put('/api/roles/'+this.roleId, object)
                    .then(()=>{
                        new PNotify({
                            title: 'Успех',
                            text: 'Успешно обновлено',
                            type: "success",
                            icon: "fa fa-check"
                        });
                        this.$router.push({ name:'roles.index' });
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
                    axios.delete('/api/roles/'+roleId);
                    new PNotify({
                        title: 'Успех',
                        text: 'Успешно удалено',
                        type: "success",
                        icon: "fa fa-check"
                    });
                    $router.push({ name:'roles.index' });
                })
            }
        }
    }
</script>
