<template>
        <div class="login-box">
            <div class="login-logo">
                <strong>ASB</strong>
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <form id="login" action="" method="post">
                    <div class="form-group has-feedback">
                        <input type="text" id="username" name="username" class="form-control" placeholder="Username">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="checkbox">
                                <label>
                                    <input name="remember" type="checkbox"> Remember Me
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->
</template>

<script>
    $(function () {
        $('#login').on('submit', function (e) {
            Pace.restart();
            e.preventDefault();
            axios.post("/api/login", {
                username:document.getElementById('username').value,
                password:document.getElementById('password').value
            })
                .then(response=>{
                    if(response.data.status) {
                        localStorage.setItem('token', response.data.access_token);
                        localStorage.setItem('expires_in', response.data.expires_in+Date.now());
                        localStorage.setItem('permissions', JSON.stringify(response.data.permissions));
                        location.reload();
                    }
                    else {
                        new PNotify({
                            title: 'Ошибка',
                            text: response.data.message,
                            type: "error",
                            icon: "fa fa-warning"
                        });
                    }
                })
                .catch(response=>console.log(response));
        });
    });
</script>
