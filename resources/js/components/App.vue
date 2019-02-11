<template>
    <div class="wrapper">
        <!-- Main Header -->
        <header class="main-header">

            <!-- Logo -->
            <router-link :to="{ name:'home' }" style="cursor: pointer" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><strong>ASB</strong></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><strong>ASB</strong></span>
            </router-link>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Right Menu -->
                <!--<div class="navbar-custom-menu">-->
                    <!--<ul class="nav navbar-nav">-->
                        <!--&lt;!&ndash; Messages: style can be found in dropdown.less&ndash;&gt;-->
                        <!--<li class="dropdown messages-menu">-->
                            <!--&lt;!&ndash; Menu toggle button &ndash;&gt;-->
                            <!--<a href="#" class="dropdown-toggle" data-toggle="dropdown">-->
                                <!--<i class="fa fa-envelope-o"></i>-->
                                <!--<span class="label label-success">4</span>-->
                            <!--</a>-->
                            <!--<ul class="dropdown-menu">-->
                                <!--<li class="header">You have 4 messages</li>-->
                                <!--<li>-->
                                    <!--&lt;!&ndash; inner menu: contains the messages &ndash;&gt;-->
                                    <!--<ul class="menu">-->
                                        <!--<li>&lt;!&ndash; start message &ndash;&gt;-->
                                            <!--<a href="#">-->
                                                <!--<div class="pull-left">-->
                                                    <!--&lt;!&ndash; User Image &ndash;&gt;-->
                                                    <!--<img src="/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">-->
                                                <!--</div>-->
                                                <!--&lt;!&ndash; Message title and timestamp &ndash;&gt;-->
                                                <!--<h4>-->
                                                    <!--Support Team-->
                                                    <!--<small><i class="fa fa-clock-o"></i> 5 mins</small>-->
                                                <!--</h4>-->
                                                <!--&lt;!&ndash; The message &ndash;&gt;-->
                                                <!--<p>Why not buy a new awesome theme?</p>-->
                                            <!--</a>-->
                                        <!--</li>-->
                                        <!--&lt;!&ndash; end message &ndash;&gt;-->
                                    <!--</ul>-->
                                    <!--&lt;!&ndash; /.menu &ndash;&gt;-->
                                <!--</li>-->
                                <!--<li class="footer"><a href="#">See All Messages</a></li>-->
                            <!--</ul>-->
                        <!--</li>-->
                        <!--&lt;!&ndash; /.messages-menu &ndash;&gt;-->

                        <!--&lt;!&ndash; Notifications Menu &ndash;&gt;-->
                        <!--<li class="dropdown notifications-menu">-->
                            <!--&lt;!&ndash; Menu toggle button &ndash;&gt;-->
                            <!--<a href="#" class="dropdown-toggle" data-toggle="dropdown">-->
                                <!--<i class="fa fa-bell-o"></i>-->
                                <!--<span class="label label-warning">10</span>-->
                            <!--</a>-->
                            <!--<ul class="dropdown-menu">-->
                                <!--<li class="header">You have 10 notifications</li>-->
                                <!--<li>-->
                                    <!--&lt;!&ndash; Inner Menu: contains the notifications &ndash;&gt;-->
                                    <!--<ul class="menu">-->
                                        <!--<li>&lt;!&ndash; start notification &ndash;&gt;-->
                                            <!--<a href="#">-->
                                                <!--<i class="fa fa-users text-aqua"></i> 5 new members joined today-->
                                            <!--</a>-->
                                        <!--</li>-->
                                        <!--&lt;!&ndash; end notification &ndash;&gt;-->
                                    <!--</ul>-->
                                <!--</li>-->
                                <!--<li class="footer"><a href="#">View all</a></li>-->
                            <!--</ul>-->
                        <!--</li>-->
                        <!--&lt;!&ndash; Tasks Menu &ndash;&gt;-->
                        <!--<li class="dropdown tasks-menu">-->
                            <!--&lt;!&ndash; Menu Toggle Button &ndash;&gt;-->
                            <!--<a href="#" class="dropdown-toggle" data-toggle="dropdown">-->
                                <!--<i class="fa fa-flag-o"></i>-->
                                <!--<span class="label label-danger">9</span>-->
                            <!--</a>-->
                            <!--<ul class="dropdown-menu">-->
                                <!--<li class="header">You have 9 tasks</li>-->
                                <!--<li>-->
                                    <!--&lt;!&ndash; Inner menu: contains the tasks &ndash;&gt;-->
                                    <!--<ul class="menu">-->
                                        <!--<li>&lt;!&ndash; Task item &ndash;&gt;-->
                                            <!--<a href="#">-->
                                                <!--&lt;!&ndash; Task title and progress text &ndash;&gt;-->
                                                <!--<h3>-->
                                                    <!--Design some buttons-->
                                                    <!--<small class="pull-right">20%</small>-->
                                                <!--</h3>-->
                                                <!--&lt;!&ndash; The progress bar &ndash;&gt;-->
                                                <!--<div class="progress xs">-->
                                                    <!--&lt;!&ndash; Change the css width attribute to simulate progress &ndash;&gt;-->
                                                    <!--<div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"-->
                                                         <!--aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">-->
                                                        <!--<span class="sr-only">20% Complete</span>-->
                                                    <!--</div>-->
                                                <!--</div>-->
                                            <!--</a>-->
                                        <!--</li>-->
                                        <!--&lt;!&ndash; end task item &ndash;&gt;-->
                                    <!--</ul>-->
                                <!--</li>-->
                                <!--<li class="footer">-->
                                    <!--<a href="#">View all tasks</a>-->
                                <!--</li>-->
                            <!--</ul>-->
                        <!--</li>-->
                        <!--&lt;!&ndash; Control Sidebar Toggle Button &ndash;&gt;-->
                        <!--<li>-->
                            <!--<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>-->
                        <!--</li>-->
                    <!--</ul>-->
                <!--</div>-->
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">

            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">

                <!-- Sidebar user panel (optional) -->
                <main-user></main-user>

                <!-- Sidebar Menu -->
                <main-menu></main-menu>
                <!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <breadcrambs></breadcrambs>

            <!-- Main content -->
            <section class="content container-fluid">
                <router-view></router-view>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="pull-right hidden-xs">
                Anything you want
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2016 <a href="#">Company</a>.</strong> All rights reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Create the tabs -->
            <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
                <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Home tab content -->
                <div class="tab-pane active" id="control-sidebar-home-tab">
                    <h3 class="control-sidebar-heading">Recent Activity</h3>
                    <ul class="control-sidebar-menu">
                        <li>
                            <a href="javascript:;">
                                <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                                    <p>Will be 23 on April 24th</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- /.control-sidebar-menu -->

                    <h3 class="control-sidebar-heading">Tasks Progress</h3>
                    <ul class="control-sidebar-menu">
                        <li>
                            <a href="javascript:;">
                                <h4 class="control-sidebar-subheading">
                                    Custom Template Design
                                    <span class="pull-right-container">
                    <span class="label label-danger pull-right">70%</span>
                  </span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- /.control-sidebar-menu -->

                </div>
                <!-- /.tab-pane -->
                <!-- Stats tab content -->
                <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
                <!-- /.tab-pane -->
                <!-- Settings tab content -->
                <div class="tab-pane" id="control-sidebar-settings-tab">
                    <form method="post">
                        <h3 class="control-sidebar-heading">General Settings</h3>

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Report panel usage
                                <input type="checkbox" class="pull-right" checked>
                            </label>

                            <p>
                                Some information about this general settings option
                            </p>
                        </div>
                        <!-- /.form-group -->
                    </form>
                </div>
                <!-- /.tab-pane -->
            </div>
        </aside>
        <!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placed
        immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div>
</template>
