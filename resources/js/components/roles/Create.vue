<template>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-body form-horizontal">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Название</label>

                        <div class="col-sm-10">
                            <input name="name" type="text" class="form-control" id="name" placeholder="Название">
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
            <button type="button" @click="storeRole" class="btn btn-success">Добавить</button>
        </div>
    </div>

</template>

<script>
    export default {
        data(){
            return {
                role:{
                    id:'',
                    name:''
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
            }
        },
        created(){
            this.fetchPermissions()
        },
        methods:{
            fetchPermissions(){
                axios.get('/api/roles/create')
                    .then(res=>{
                        this.sections = res.data
                    })
            },
            storeRole(){
                let givePermissions=[], revokePermissions=[];
                $.each($(".permission"), function () {
                    if(this.checked) givePermissions.push(this.name);
                });
                let object = {
                    name:document.getElementById('name').value,
                    give_permissions:givePermissions
                };
                axios.post('/api/roles', object)
                    .then(()=>{
                        new PNotify({
                            title: 'Успех',
                            text: 'Успешно обновлено',
                            type: "success",
                            icon: "fa fa-check"
                        });
                        this.$router.push({ name:'roles.index' });
                    })
            }
        }
    }
</script>
