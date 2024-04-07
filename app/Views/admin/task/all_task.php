<!-- app/Views/admin/task/get_task.php -->
<?php $this->extend('admin/layouts/admin_dashboard'); ?>
<?php $this->section('page_title') ?>
Dashboard / All Task
<?php $this->endSection() ?>
<?php $this->section('content'); ?>
<?php if (session()->has('msg')): ?>
    <div class="alert alert-success"><?= session('msg') ?></div>
<?php endif; ?>
<input type="hidden" class="txt_csrfname" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

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
        <th>Confirm Task</th>
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
                'url':"<?=site_url('admin/fetch')?>",
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
                        // Check if the created by is admin
                        if (data == 'admin') {
                            return '<span style="color: green;">' + data.toUpperCase() + '</span>';
                        } else {
                            return '<span style="color: black;">' + data.toUpperCase() + '</span>';
                        }
                    }
                },
                {
                    data: 'updated_by',
                    render: function(data, type, row) {
                        // Check if the updated by is admin
                        if (data === 'admin') {
                            return '<span style="color: green;">' + data.toUpperCase() + '</span>';
                        } else if(data === 'N/A')  {
                            return '<span style="color: blue;">' + data.toUpperCase() + '</span>';
                        } else {
                            return '<span style="color: grey;">' + data.toUpperCase() + '</span>';
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
                },
                {
                    'data': null,
                    'render': function (data, type, row) {
                        var buttonsHtml = '';
                        if (typeof data === 'object' && data !== null) {
                            // Access properties of the single object
                            // Check the status property to determine button rendering
                            if (data.status == 2) {
                                // If status is 2, disable the button
                                buttonsHtml = '<button class="btn btn-success" data-id="' + data.id + '" disabled>Confirmed Task</button>';
                            } else {
                                // If status is not 2, render the button normally
                                buttonsHtml = '<button class="btn btn-warning confirm-task" data-id="' + data.id + '">Confirm Task</button>';
                            }
                        } else {
                            // Handle the case when data is not an object
                            console.error('Data is not an object');
                        }

                        return buttonsHtml;
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
                    url: '<?= site_url("/admin/delete-task") ?>',
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
            window.location.href = '<?= site_url("/admin/edit-task/") ?>' + taskId;
        });
        $('#userTable tbody').on('click', '.confirm-task', function () {
            var taskId = $(this).data('id');
            if (confirm("Are you sure you want to confirm this task?")) {
                $.ajax({
                    url: '<?= site_url("/admin/confirm-task") ?>',
                    method: 'POST',
                    data: { taskId: taskId },
                    success: function (response) {
                        if (response.success) {
                            // If task confirmation is successful, reload the table and show the success modal
                            table.ajax.reload();
                            $('#updateModal .modal-body').text('Task confirmation update successful.');
                            $('#updateModal').modal('show');
                        } else {
                            // If there's an error, show the error message in the modal
                            $('#updateModal .modal-body').text('Error: ' + response.message);
                            $('#updateModal').modal('show');
                        }
                    },
                    error: function (xhr, status, error) {
                        // If there's an AJAX error, show the error message in the modal
                        $('#updateModal .modal-body').text('AJAX Error: ' + error);
                        $('#updateModal').modal('show');
                    }
                });
            }
        });
    });
</script>
<!-- Success Modal -->


<?php $this->endSection(); ?>
