<template>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-users"></i></h3>

                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-striped">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <th>ФИО</th>
                            <th>Роль</th>
                            <th>Логин</th>
                            <th>Должность</th>
                            <th>Дата последней активности</th>
                            <th>Действия</th>
                        </tr>
                        <tr v-for="user in users" v-bind:key="user.id">
                            <td>{{ user.id }}</td>
                            <td>{{ user.sname }} {{ user.name }} {{ user.fname }}</td>
                            <td><span v-if="Boolean(user.role)">{{ user.role.description }}</span></td>
                            <td>{{ user.username }}</td>
                            <td>{{ user.position?user.position.name:'' }}</td>
                            <td>{{ user.lastSeen }}</td>
                            <td>
                                <router-link v-if="permissions.indexOf('users.edit') !== -1" :to="{ name:'users.edit', params:{ id:user.id } }" class="btn btn-xs btn-default" title="Редактировать"><i class="fa fa-edit"></i></router-link>
                                <button v-if="permissions.indexOf('users.destroy') !== -1" @click="removeUser(user.id)" class="btn btn-xs btn-danger" title="Удалить"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        </tbody></table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <ul class="pagination pull-right" style="margin:0">
                        <li :class="'page-item'+(!pagination.prev_page_url?' disabled':'')"><a @click="fetchUsers(pagination.prev_page_url)" class="page-link" href="#">Previous</a></li>
                        <li class="page-item disabled"><a class="page-link">страница {{ pagination.current_page }} из {{ pagination.last_page }}</a></li>
                        <li :class="'page-item'+(!pagination.next_page_url?' disabled':'')"><a @click="fetchUsers(pagination.next_page_url)" class="page-link" href="#">Next</a></li>
                    </ul>
                </div>
            </div>
            <!-- /.box -->
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                users:[],
                user:{
                    id:'',
                    pin:'',
                    name:'',
                    sname:'',
                    fname:'',
                    username:'',
                    post:'',
                    email:'',
                    lastSeen:'',
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
                pagination:{},
                permissions:window.permissions
            }
        },

        created(){
            this.fetchUsers()
        },

        methods:{
            fetchUsers(page_url){
                let vm = this;
                page_url = page_url || '/api/users';
                axios.get(page_url)
                    .then(res=>{
                        this.users = res.data.data;
                        vm.makePagination(res.data.meta, res.data.links);
                    })
            },
            makePagination(meta, links){
                this.pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev
                };
            },
            removeUser(id){
                let $this = this;
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
                    axios.delete('/api/users/'+id).then(()=>{
                        new PNotify({
                            title: 'Успех',
                            text: 'Успешно удалено',
                            type: "success",
                            icon: "fa fa-check"
                        });
                        $this.fetchUsers()
                    });
                })
            }
        }
    }
</script>
