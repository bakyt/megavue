<template>
    <div class="row">
        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-body box-profile">


                    <h3 class="profile-username text-center">{{ user.sname }} {{ user.name }} {{ user.fname }}</h3>

                    <p class="text-muted text-center">{{ user.position.name }}</p>

                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>Пин</b> <a class="pull-right">{{ user.pin }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Роль</b> <a class="pull-right">{{ user.role.description }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Логин</b> <a class="pull-right">{{ user.username }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>E-Mail</b> <a class="pull-right">{{ user.email }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Дата последней активности</b> <a class="pull-right">{{ user.lastSeen }}</a>
                        </li>
                    </ul>

                    <router-link v-if="permissions.indexOf('users.edit') !== -1" :to="{ name:'users.edit', params:{ id:user.id } }" class="btn btn-primary btn-block"><b>Редактировать</b></router-link>
                    <a v-if="permissions.indexOf('users.destroy') !== -1" @click="deleteUser" class="btn btn-danger btn-block"><b>Удалить</b></a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <div class="box-title">История входа</div>
                </div>
                <div class="box-body">
                    <ul class="timeline timeline-inverse">

                        <!-- timeline item -->
                        <li v-for="login in logins" v-bind:key="login.id">
                            <i :class="'fa fa-key '+(login.state==='success'?'bg-blue':(login.state==='bad-password'?'bg-yellow':'bg-red'))"></i>

                            <div class="timeline-item">
                                <span class="time"><i class="fa fa-clock-o"></i> {{ login.time }}</span>

                                <h3 class="timeline-header">{{ login.ip }}</h3>

                                <div class="timeline-body">
                                    {{ login.state==="success"?"Успешно":(login.state==="bad-password"?"Не правильный пароль":"Не правильный логин") }}
                                </div>
                            </div>
                        </li>
                        <!-- END timeline item -->
                    </ul>
                </div>
                <div class="box-footer">
                    <div class="timeline-item pull-right">
                        <ul class="pagination" style="margin:0">
                            <li :class="'page-item'+(!pagination.prev_page_url?' disabled':'')"><a @click="fetchSignIns(pagination.prev_page_url)" class="page-link" href="#"><i class="fa fa-angle-double-left" title="Предыдущая"></i></a></li>
                            <li class="page-item disabled"><a class="page-link">страница {{ pagination.current_page }} из {{ pagination.last_page }}</a></li>
                            <li :class="'page-item'+(!pagination.next_page_url?' disabled':'')"><a @click="fetchSignIns(pagination.next_page_url)" class="page-link" href="#"><i class="fa fa-angle-double-right" title="Следующая"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
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
                    name:'',
                    sname:'',
                    fname:'',
                    username:'',
                    post:'',
                    email:'',
                    lastSeen:'',
                    role:{
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
                },
                logins:[],
                login:{
                    id:'',
                    user_id:'',
                    name:'',
                    state:'',
                    time:'',
                    ip:''
                },
                pagination:{},
                userId:this.$route.params.id,
                permissions:window.permissions
            }
        },

        created(){
            this.fetchUser();
            this.fetchSignIns()
        },

        methods:{
            fetchUser(){
                axios.get('/api/users/'+this.userId)
                    .then(res=>{
                        this.user = res.data
                    })
            },
            deleteUser(){
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
                    axios.delete('/api/users/'+$this.userId).then(()=>{
                        new PNotify({
                            title: 'Успех',
                            text: 'Успешно удалено',
                            type: "success",
                            icon: "fa fa-check"
                        });
                        $this.$router.push('users.index');
                    });
                })
            },
            fetchSignIns(page_url){
                let vm = this;
                page_url = page_url || '/api/sign-ins/'+this.userId;
                axios.get(page_url)
                    .then(res=>{
                        this.logins = res.data.data;
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
            }
        }
    }
</script>
