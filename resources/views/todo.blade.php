<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Todo App</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <!-- Toastr JS and CSS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
        integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            max-width: 100%;
        }

        .app {
            display: flex;
            flex-direction: column;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .taskAdd {
            display: flex;
            margin-bottom: 20px;
            padding: 2.5rem;
            gap: 10px;
        }

        .taskAdd input {
            width: 70%;
        }

        .title {
            margin-bottom: 10px;
            color: #007bff;
        }

        table {
            width: 100%;
            table-layout: fixed;
        }

        td {
            padding: 10px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: normal; /* Allow text to wrap */
            word-wrap: break-word; /* Handle long words */
        }
        
    </style>
</head>

<body>
    <div class="app">
        <h1 class="title">PHP - Simple To Do List App</h1>
        <div class="taskAdd">
            <input type="text" id="new-task" class="form-control" placeholder="Enter new task">
            <button id="add-task-btn" class="btn btn-md btn-primary">Add Task</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th colspan="1">#</th>
                    <th colspan="2">Task</th>
                    <th colspan="1">Status</th>
                    <th colspan="2">Action</th>
                </tr>
            </thead>
            <tbody id="task-list">
                @forelse($todos as $todo)
                    <tr data-id="Todo#{{ $todo->id }}">
                        <td colspan="1">{{ $todo->id }}</td>
                        <td colspan="2">{{ $todo->title }}</td>
                        <td colspan="1">{{ $todo->completed ? 'Completed' : 'Pending' }}</td>
                        <td colspan="2">
                            @if (!$todo->completed)
                                <button class="btn btn-sm btn-success" id="TodoEdit#{{ $todo->id }}"
                                    data-id="{{ $todo->id }}" onclick="updateTask(event)">
                                    <i class="fa-regular fa-square-check"></i>
                                </button>
                            @endif
                            <button class="btn btn-sm btn-danger" data-id="{{ $todo->id }}"
                                onclick="deleteTask(event)">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr class="empty">
                        <td colspan="6" class="text-center">No task found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>

<script>
    const updateTask = function(e) {
        const id = $(e.currentTarget).data('id');
        const element = e;
        const url = '{{ route('todo.update', ':id') }}'.replace(':id', id);
        $.ajax({
            url: url,
            type: 'PUT',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    console.log(response);
                    // Find the row corresponding to the task using the ID
                    const row = $('tr[data-id="Todo#' + id + '"]');
                    // Update the status text to "Completed"
                    row.find('td:nth-child(3)').text('Completed');
                    row.find('td:nth-child(4)').html('<button class="btn btn-sm btn-danger" data-id="' + id + '" onclick="deleteTask(event)">Delete</button>');
                    toastr.success(response.message);
                } else {
                    toastr.error(response ? response.message : 'An error occurred.');
                }
            },
            error: function(xhr, status, error) {
                toastr.error('An error occurred: ' + error);
            }
        });
    }

    const deleteTask = function(e) {
        const id = $(e.currentTarget).data('id');
        const url = '{{ route('todo.destroy', ':id') }}'.replace(':id', id);
        $.ajax({
            url: url,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response && response.success) {
                    $(`tr[data-id="Todo#${id}"]`).remove();
                    if ($('#task-list').find('tr').length == 0) {
                        const empty = `<tr class="empty">
                                            <td colspan="4" class="text-center">No task found</td>
                                        </tr>`;
                        $('#task-list').append(empty);
                    }
                    toastr.success(response.message);
                } else {
                    toastr.error(response ? response.message : 'An error occurred.');
                }
            },
            error: function(xhr, status, error) {
                toastr.error('An error occurred: ' + error);
            }
        });
    }

    $(document).ready(function() {
        $('#add-task-btn').click(function() {
            var title = $('#new-task').val();
            if (title == '') {
                toastr.error('Please enter task title.');
                return;
            }
            if (title.length < 3 || title.length > 255) {
                toastr.error('Task title must be at least 3 characters and at most 255 characters.');
                return;
            }
            $.ajax({
                url: '{{ route('todo.store') }}',
                type: 'POST',
                data: {
                    title: title,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response && response.success) {
                        $('.empty').remove();
                        const tr = `<tr data-id="Todo#${response.data.id}">
                                        <td colspan="1">${response.data.id}</td>
                                        <td colspan="2">${response.data.title}</td>
                                        <td colspan="1">${response.data.completed ? 'Completed' : 'Pending'}</td>
                                        <td colspan="2">
                                            <button class="btn btn-sm btn-success" data-id="${response.data.id}" onclick="updateTask(event)">
                                                <i class="fa-regular fa-square-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" data-id="${response.data.id}" onclick="deleteTask(event)">Delete</button>
                                        </td>
                                    </tr>`;
                        $('#task-list').append(tr);
                        $('#new-task').val('');
                        toastr.success(response.message);
                    } else {
                        toastr.error(response ? response.message : 'An error occurred.');
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('An error occurred: ' + error);
                }
            });
        });
    });
</script>

</html>
