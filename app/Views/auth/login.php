<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Log in (v2)</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('plugins/fontawesome-free/css/all.min.css') ?>">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= base_url('plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('dist/css/adminlte.min.css') ?>">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <b>Simple Task Management System - Login</b>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Sign in to start your session</p>

            <form action="<?= site_url('login') ?>" method="post">
                <div class="input-group mb-3">
                    <input type="email" name="email" value="<?= old('email') ?>"
                           class="form-control <?= (isset($validation) && $validation->hasError('email')) ? 'is-invalid' : '' ?>"
                           id="exampleInputEmail1" placeholder="Enter email">
                    <?php if (isset($validation) && $validation->hasError('email')) : ?>
                        <span class="error invalid-feedback"><?= $validation->getError('email') ?></span>
                    <?php endif; ?>

                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password"
                           class="form-control <?= (isset($validation) && $validation->hasError('password')) ? 'is-invalid' : '' ?>"
                           id="exampleInputEmail1" placeholder="Enter Password">
                    <?php if (isset($validation) && $validation->hasError('password')) : ?>
                        <span class="error invalid-feedback"><?= $validation->getError('password') ?></span>
                    <?php endif; ?>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <label for="remember">
                                Remember Me
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            <br>
            <?php if (session()->has('msg')): ?>
                <div class="alert alert-danger"><?= session('msg') ?></div>
            <?php endif; ?>
            <!-- /.social-auth-links -->


        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?= base_url('plugins/jquery/jquery.min.js') ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url('plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<!-- AdminLTE App -->
<script src="<?= base_url('dist/js/adminlte.min.js') ?>"></script>
</body>
</html>
