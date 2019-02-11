<template>
    <div class="box box-default">
        <div class="box-body">
            <form class="form-horizontal" onsubmit="event.preventDefault()">
                <div class="form-group">
                    <label for="roleName" class="col-sm-2 control-label">Название</label>

                    <div class="col-sm-10">
                        <input name="roleName" type="text" class="form-control" id="roleName" placeholder="Название">
                    </div>
                </div>
                <div class="form-group">
                    <label for="role_type" class="col-sm-2 control-label">Тип</label>

                    <div class="col-sm-10">
                        <input name="role_type" type="text" class="form-control" id="role_type" placeholder="Тип">
                    </div>
                </div>
                <div class="form-group">
                    <label for="city" class="col-sm-2 control-label">Город</label>

                    <div class="col-sm-10">
                        <input name="city" type="text" class="form-control" id="city" placeholder="Город">
                    </div>
                </div>
                <div class="form-group">
                    <label for="description" class="col-sm-2 control-label">Описание</label>

                    <div class="col-sm-10">
                        <input name="description" type="text" class="form-control" id="description" placeholder="Описание">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="button" @click="storeRole" class="btn btn-success">Добавить</button>
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
                }
            }
        },
        methods:{
            storeRole(){
                let object = {
                    roleName:document.getElementById('roleName').value,
                    role_type:document.getElementById('role_type').value,
                    description:document.getElementById('description').value,
                    city:document.getElementById('city').value,
                };
                axios.post('/api/site-roles', object)
                    .then(()=>{
                        new PNotify({
                            title: 'Успех',
                            text: 'Успешно обновлено',
                            type: "success",
                            icon: "fa fa-check"
                        });
                        this.$router.push({ name:'site-roles.index' });
                    })
            }
        }
    }
</script>
