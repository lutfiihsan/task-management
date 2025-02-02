@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Create New Task') }}</h1>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Task Details</h6>
                </div>
                <div class="card-body">
                    <form id="create-task-form">
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
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="due_date">Due Date</label>
                            <input type="date" name="due_date" id="due_date" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Task</button>
                        <a href="{{ route('tugas.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#create-task-form').on('submit', function(e) {
                    e.preventDefault();

                    $.ajax({
                        url: "{{ route('service.tasks.store') }}",
                        method: 'POST',
                        data: $(this).serialize(),
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('token')
                        },
                        success: function(response) {
                            alert('Task created successfully!');
                            window.location.href = "{{ route('tugas.index') }}";
                        },
                        error: function(response) {
                            alert('Failed to create task. Please try again.');
                            console.log(response);
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection