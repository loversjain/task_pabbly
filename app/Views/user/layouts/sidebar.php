<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->

        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">


                <div class="dropdown-divider"></div>
                <a href="<?= site_url('logout') ?>" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <div class="media-body">

                            <h3 class="dropdown-item-title">
                                <i class="nav-icon fas fa-sign-out-alt"></i> Logout
                            </h3>

                        </div>
                    </div>
                    <!-- Message End -->
                </a>

            </div>
        </li>
        <!-- Notifications Dropdown Menu -->


    </ul>
</nav>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSyOzzg1jHLaPuoBaJsD6NhDJgqcDmZMg1lvGGt1gP9Zg&s"
             class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">STMS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">


        <!-- SidebarSearch Form -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->


                <li class="nav-item">
                    <a href="<?= site_url('/user/dashboard') ?>" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Home</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('/user/get-task') ?>" class="nav-link">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>Add Task</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('/user/all-task') ?>" class="nav-link">
                        <i class="nav-icon fas fa-eye"></i>
                        <p>View Task</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= site_url('/logout') ?>" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>


            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>