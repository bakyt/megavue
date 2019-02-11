<template>
    <div>
        <div class="box box-solid">
            <div class="box-header with-border">
                <div class="box-title"><i class="fa fa-edit"></i> </div>
            </div>
            <form class="form-horizontal">
                <div class="box-body">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Название</label>

                        <div class="col-sm-10">
                            <input :value="role.name" type="text" class="form-control" id="name" placeholder="Название">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-sm-2 control-label">Описание</label>

                        <div class="col-sm-10">
                            <input :value="role.display_name" type="text" class="form-control" id="description" placeholder="Описание">
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </form>
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
        <button @click="updateRole" type="button" class="btn btn-success">Сохранить</button>
        <button v-if="permissions.indexOf('roles.destroy') !== -1" @click="removeRole" type="button" class="btn btn-danger">Удалить</button>
    </div>
</template>

<script>
    export default {
        data(){
            return {
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
                role:{
                    id:'',
                    name:'',
                    display_name:''
                },
                roleId:this.$route.params.id,
                permissions:window.permissions
            }
        },

        created(){
            this.fetchSections();
        },

        methods:{
            fetchSections(){
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
                axios.put('/api/roles/'+this.roleId, {
                    give_permissions:givePermissions,
                    revoke_permissions:revokePermissions,
                    name:document.getElementById('name').value,
                    display_name:document.getElementById('description').value
                })
                    .then(()=>{
                        new PNotify({
                            title: 'Успех',
                            text: 'Успешно обновлено',
                            type: "success",
                            icon: "fa fa-check"
                        });
                        this.$router.push({ name:'roles.index' });
                    });
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
