<!-- app/Views/admin/task/get_task.php -->
<?php $this->extend('admin/layouts/admin_dashboard'); ?>
<?php $this->section('page_title') ?>
Dashboard / Add Task
<?php $this->endSection() ?>
<?php $this->section('content'); ?>
<div class="row">
    <div class="col-md-12">
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Add Task</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form class="form-horizontal" action="<?= site_url('/admin/store-task') ?>" method="post">
            <?= csrf_field() ?>
            <div class="card-body">
                <div class="form-group row">
                    <label for="Title" class="col-sm-2 col-form-label">Title</label>
                    <div class="col-sm-10">
                        <input type="text" value="<?= old('email') ?>"
                               class="form-control <?= (isset($validation) && $validation->hasError('Title')) ? 'is-invalid' : '' ?>"
                               id="Title" name="Title" placeholder="Title">
                        <?php if (isset($validation) && $validation->hasError('Title')) : ?>
                            <span class="error invalid-feedback"><?= $validation->getError('Title') ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="Description" class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-10">
                        <textarea  class="form-control <?= (isset($validation) && $validation->hasError('Description')) ? 'is-invalid' : '' ?>"
                                id="Description" name="Description"
                                  placeholder="Enter Description..."><?= old('Description') ?></textarea>
                        <?php if (isset($validation) && $validation->hasError('Description')) : ?>
                            <span class="error invalid-feedback"><?= $validation->getError('Description') ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="Due_date" class="col-sm-2 col-form-label">Select Date:</label>

                    <div class="col-sm-10">
                        <input type="text" autocomplete="off" name="Due_date" value="<?= old('Due_date') ?>" class="form-control
                        <?= (isset($validation) && $validation->hasError('Due_date')) ? 'is-invalid' : '' ?>" id="Due_date" placeholder="Select due date">
                        <?php if (isset($validation) && $validation->hasError('Due_date')) : ?>
                            <span class="error invalid-feedback"><?= $validation->getError('Due_date') ?></span>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-info">Add Task</button>
            </div>
            <!-- /.card-footer -->
        </form>
    </div>
</div>
</div>
<?php $this->endSection(); ?>
<?php $this->section('js-script'); ?>

<script>
    $(document).ready(function () {
        // Initialize the datepicker
        $('#Due_date').datepicker({
            format: 'yyyy-mm-dd', // Format the date as desired
            autoclose: true // Close the datepicker when a date is selected
        });
    });
</script>
<?php $this->endSection(); ?>

