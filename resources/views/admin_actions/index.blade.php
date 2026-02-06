<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Actions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
</head>

<body class="p-4">

    <h1>Admin Actions</h1>

    <form method="GET" class="row g-3 mb-3">
        <div class="col-auto">
            <input type="text" name="search" class="form-control" placeholder="Search by target name" value="{{ request('search') }}">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="{{ route('admin-actions.create') }}" class="btn btn-success">New Action</a>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Admin</th>
                <th>Target</th>
                <th>Action</th>
                <th>Report ID</th>
                <th>Notes</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($actions as $action)
            <tr>
                <td>{{ $action->id }}</td>
                <td>{{ $action->admin->first_name ?? 'N/A' }}</td>
                <td>{{ $action->target->first_name ?? 'N/A' }}</td>
                <td>{{ ucfirst($action->action_taken) }}</td>
                <td>{{ $action->report_id ?? '-' }}</td>
                <td>{{ \Illuminate\Support\Str::limit($action->notes, 30) }}</td>
                <td>{{ $action->created_at->format('Y-m-d') }}</td>
                <td>
                    <a href="{{ route('admin-actions.show', $action) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('admin-actions.edit', $action) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin-actions.destroy', $action) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this action?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $actions->links() }}

</body>

</html>