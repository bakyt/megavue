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
                            <input type="text" class="form-control" id="name" placeholder="Название">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-sm-2 control-label">Описание</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="description" placeholder="Описание">
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
                                <input class="permission" :name="item.route" type="checkbox">
                                {{ item.name }}
                            </label>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <button @click="createRole" type="button" class="btn btn-success">Сохранить</button>
        <router-link :to="{ name:'roles.index' }" type="button" class="btn btn-default">Отмена</router-link>
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
                }
            }
        },

        created(){
            this.fetchSections();
        },

        methods:{
            fetchSections(){
                axios.get('/api/roles/create')
                    .then(res=>{
                        this.sections = res.data;
                    })
            },
            createRole(){
                let givePermissions=[];
                $.each($(".permission"), function () {
                    if(this.checked) givePermissions.push(this.name);
                });
                axios.post('/api/roles', {
                    give_permissions:givePermissions,
                    name:document.getElementById('name').value,
                    display_name:document.getElementById('description').value
                })
                    .then(()=>{
                        new PNotify({
                            title: 'Успех',
                            text: 'Успешно добавлено',
                            type: "success",
                            icon: "fa fa-check"
                        });
                        this.$router.push({ name:'roles.index' });
                    });
            }
        }
    }
</script>
