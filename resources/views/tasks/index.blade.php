@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Tasks') }}</h1>

    <div class="row">

        <div class="col-lg-12">

            <div class="card shadow mb-4">

                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Task List</h6>
                    <a href="{{ route('tugas.create') }}" class="btn btn-primary btn-sm float-right">New Task</a>
                </div>

                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered" id="tasks-table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                    <th>Owner</th>
                                    <th>Assigned To</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data akan diisi oleh JavaScript -->
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Modal untuk Assign Task -->
    <div class="modal fade" id="assignTaskModal" tabindex="-1" role="dialog" aria-labelledby="assignTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignTaskModalLabel">Assign Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="assignTaskForm">
                        <input type="hidden" id="taskId" name="task_id">
                        <div class="form-group">
                            <label for="userId">Assign to User</label>
                            <select class="form-control" id="userId" name="user_id">
                                <!-- Daftar user akan diisi oleh JavaScript -->
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="assignTaskButton">Assign</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk View Task History -->
    <div class="modal fade" id="taskHistoryModal" tabindex="-1" role="dialog" aria-labelledby="taskHistoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="taskHistoryModalLabel">Task History</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Data</th>
                                <th>Changed At</th>
                            </tr>
                        </thead>
                        <tbody id="taskHistoryTable">
                            <!-- Data akan diisi oleh JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $.ajax({
                    url: "{{ route('service.tasks.index') }}",
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('token')
                    },
                    success: function(response) {
                        console.log(response);
                        var tasks = response.tasks;
                        var tableBody = $('#tasks-table tbody');
                        tableBody.empty();

                        tasks.forEach(function(task) {
                            var row = `<tr>
                                <td>${task.id}</td>
                                <td>${task.title}</td>
                                <td>${task.description}</td>
                                <td>${task.status}</td>
                                <td>${task.due_date}</td>
                                <td>${task.user?.name}</td>
                                <td>${task.assign_to?.name}</td>
                                <td>
                                    <button class="btn btn-sm btn-info view-task-history" data-id="${task.id}" data-toggle="modal" data-target="#taskHistoryModal">History</button>
                                    <a href="/tugas/${task.id}/edit" class="btn btn-sm btn-primary">Edit</a>
                                    <button class="btn btn-sm btn-success assign-task" data-id="${task.id}" data-toggle="modal" data-target="#assignTaskModal">Assign</button>
                                    <button class="btn btn-sm btn-danger delete-task" data-id="${task.id}">Delete</button>
                                </td>
                            </tr>`;
                            tableBody.append(row);
                        });
                    },
                    error: function(response) {
                        alert('Failed to fetch tasks data');
                    }
                });

                $(document).on('click', '.delete-task', function() {
                    var taskId = $(this).data('id');
                    if (confirm('Are you sure you want to delete this task?')) {
                        $.ajax({
                            url: `api/tasks/${taskId}`,
                            method: 'DELETE',
                            headers: {
                                'Authorization': 'Bearer ' + localStorage.getItem('token')
                            },
                            success: function(response) {
                                alert('Task deleted successfully');
                                location.reload();
                            },
                            error: function(response) {
                                alert('Failed to delete task');
                            }
                        });
                    }
                });
                
                $(document).on('click', '.assign-task', function() {
                    var taskId = $(this).data('id');
                    $('#taskId').val(taskId);

                    // Ambil daftar user dari API
                    $.ajax({
                        url: "{{ route('service.users.index') }}",
                        method: 'GET',
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('token')
                        },
                        success: function(response) {
                            var users = response.users;
                            var userSelect = $('#userId');
                            userSelect.empty();

                            users.forEach(function(user) {
                                userSelect.append(`<option value="${user.id}">${user.name}</option>`);
                            });

                            $('#assignTaskModal').modal('show');
                        },
                        error: function(response) {
                            alert('Failed to fetch users data');
                        }
                    });
                });

                // Assign task ke user
                $('#assignTaskButton').on('click', function() {
                    var taskId = $('#taskId').val();
                    var userId = $('#userId').val();

                    $.ajax({
                        url: `api/tasks/${taskId}/assign`,
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('token')
                        },
                        data: {
                            assign_to: userId
                        },
                        success: function(response) {
                            console.log(response);
                            alert('Task assigned successfully');
                            $('#assignTaskModal').modal('hide');
                            location.reload();
                        },
                        error: function(response) {
                            if (response.status === 403) {
                                alert('You are not authorized to assign this task');
                            } else {
                                alert('Failed to assign task');
                            }
                        }
                    });
                });

                $(document).on('click', '.view-task-history', function() {
                    var taskId = $(this).data('id');
                    $.ajax({
                        url: `api/tasks/${taskId}/history`,
                        method: 'GET',
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('token')
                        },
                        success: function(response) {
                            var history = response.history;
                            var historyTable = $('#taskHistoryTable');
                            historyTable.empty();

                            history.forEach(function(item) {
                                var row = `<tr>
                                    <td>${item.id}</td>
                                    <td>${item.data}</td>
                                    <td>${item.updated_at}</td>
                                </tr>`;
                                historyTable.append(row);
                            });

                            $('#taskHistoryModal').modal('show');
                        },
                        error: function(response) {
                            alert('Failed to fetch task history');
                        }
                    });
                });
            });
        </script>
    @endpush

@endsection