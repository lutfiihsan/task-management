@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Edit Task') }}</h1>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Task Details</h6>
                </div>
                <div class="card-body">
                    <form id="edit-task-form">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="pending">Pending</option>
                                <option value="progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="due_date">Due Date</label>
                            <input type="date" name="due_date" id="due_date" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Task</button>
                        <a href="{{ route('tugas.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Ambil task ID dari URL
                const taskId =  window.location.pathname.split('/')[2];

                // Fetch task data dari API
                $.ajax({
                    url: "/api/tasks/" + taskId, 
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('token') // Jika menggunakan token auth
                    },
                    success: function(response) {
                        // Isi form dengan data task
                        $('#title').val(response.task.title);
                        $('#description').val(response.task.description);
                        $('#status').val(response.task.status);
                        $('#due_date').val(response.task.due_date);
                    },
                    error: function(response) {
                        alert('Failed to fetch task data.');
                        console.log(response);
                    }
                });

                $('#edit-task-form').on('submit', function(e) {
                    e.preventDefault();

                    const url = `/api/tasks/${taskId}`;

                    $.ajax({
                        url: url,
                        method: 'PUT',
                        data: $(this).serialize(), 
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('token')
                        },
                        success: function(response) {
                            alert('Task updated successfully!');
                            window.location.href = "{{ route('tugas.index') }}";
                        },
                        error: function(response) {
                            if(response.status === 403){
                                alert('You are not authorized to update this task.');
                            } else {
                                alert('Failed to update task. Please try again.');
                            }
                            console.log(response);
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection