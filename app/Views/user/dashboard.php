<!-- app/Views/admin/task/get_task.php -->
<?php $this->extend('user/layouts/user_dashboard'); ?>

<?php $this->section('content'); ?>


<div class="row">
    <div class="col-md-12">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-smile"></i>
                    Welcome
                </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="callout callout-success">
                    <h5> <?= ucwords(session()->name); ?></h5>
                </div>

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>

    <!-- ./col -->
</div>

<?php $this->endSection(); ?>
