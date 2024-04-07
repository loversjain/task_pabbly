<!-- app/Views/user/task/get_task.php -->
<?php $this->extend('user/layouts/user_dashboard'); ?>
<?php $this->section('page_title') ?>
Dashboard / All Task
<?php $this->endSection() ?>
<?php $this->section('content'); ?>
<?php if (session()->has('msg')): ?>
    <div class="alert alert-success"><?= session('msg') ?></div>
<?php endif; ?>
<input type="hidden" class="txt_csrfname" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
<div class="mb-3">
    <a href="<?= site_url('/user/get-task') ?>" class="btn btn-primary">Add Task</a>
</div>
<!-- Table -->
<table id='userTable' class='display dataTable'>

    <thead>
    <tr>
        <th>SN</th>
        <th>Title</th>
        <th>Description</th>
        <th>DueDate</th>
        <th>Status</th>
        <th>Created By</th>
        <th>Updated By</th>
        <th>Delete</th>
        <th>Edit</th>
    </tr>
    </thead>

</table>

<!-- /.card-body -->

        </div>
        <!-- /.card -->
    </div>
</div>
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Success</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Task deleted successfully.
            </div>

        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="errorModalLabel">Error</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                An error occurred while deleting the task.
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Notification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Task confirmation update successfully.
            </div>

        </div>
    </div>
</div>
<!-- JavaScript code for handling Ajax pagination -->

<?php $this->endSection(); ?>
<?php $this->section('js-script'); ?>
<script type="text/javascript">
    $(document).ready(function(){
        var table = $('#userTable').DataTable({
            'processing': true,
            'serverSide': true,
            'searching': false,
            'serverMethod': 'post',
            'ajax': {
                'url':"<?=site_url('user/fetch')?>",
                'data': function(data){
                    // CSRF Hash
                    var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
                    var csrfHash = $('.txt_csrfname').val(); // CSRF hash

                    return {
                        data: data,
                        [csrfName]: csrfHash // CSRF Token
                    };
                },
                dataSrc: function(data){

                    // Update token hash
                    $('.txt_csrfname').val(data.token);

                    // Datatable data
                    return data.taskData;
                }
            },
            'columns': [
                { data: null }, // Serial number column
                { data: 'title' },
                { data: 'description' },
                { data: 'due_date' },
                {
                    'data': 'status',
                    'render': function(data, type, row) {
                        return data == 1 ? 'Pending' : (data == 2 ? 'Complete' : ''); // Display 'Pending' for status 1, 'Complete' for status 2
                    }
                },
                {
                    data: 'created_by',
                    render: function(data, type, row) {

                        return '<span style="color: green;">' + data.toUpperCase() + '</span>';

                    }
                },
                {
                    data: 'updated_by',
                    render: function(data, type, row) {
                        if(data === 'N/A')  {
                            return '<span style="color: blue;">' + data.toUpperCase() + '</span>';
                        } else {
                            return '<span style="color: yellow;">' + data.toUpperCase() + '</span>';
                        }
                    }
                },
                {
                    'data': null,
                    'render': function (data, type, row) {
                        return '<button class="btn btn-danger delete-btn" data-id="' + data.id + '">Delete</button>';
                    }
                },
                {
                    'data': null,
                    'render': function (data, type, row) {
                        return '<button class="btn btn-primary edit-btn" data-id="' + data.id + '">Edit</button>';
                    }
                }

            ],
            'rowCallback': function(row, data, index) {
                var table = $('#userTable').DataTable();
                $('td:eq(0)', row).html(table.page.info().start + index + 1);
            }
        });
        $('#userTable tbody').on('click', '.delete-btn', function () {
            var taskId = $(this).data('id'); // Retrieve task ID from data-id attribute
            if (confirm("Are you sure you want to delete this item?")) {
                // Perform delete action via AJAX
                $.ajax({
                    url: '<?= site_url("/user/delete-task") ?>',
                    method: 'POST',
                    data: { taskId: taskId },
                    success: function (response) {
                        table.ajax.reload();
                        $('#successModal').modal('show');
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }
        });
        $('#userTable tbody').on('click', '.edit-btn', function () {
            var taskId = $(this).data('id'); // Retrieve task ID from data-id attribute
            // Redirect to edit task route with task ID
            window.location.href = '<?= site_url("/user/edit-task/") ?>' + taskId;
        });

    });
</script>
<!-- Success Modal -->


<?php $this->endSection(); ?>
