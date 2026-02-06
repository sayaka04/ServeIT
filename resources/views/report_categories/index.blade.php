<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Report Categories</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @include('partials/bootstrap')
</head>

<body class="p-4">

    <h1>Report Categories</h1>

    <a href="{{ route('report-categories.create') }}" class="btn btn-primary mb-3">Add New Category</a>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th style="width:150px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr>
                <td>
                    <a href="{{ route('report-categories.show', $category) }}">
                        {{ $category->name }}
                    </a>
                </td>
                <td>{{ $category->description }}</td>
                <td>
                    <a href="{{ route('report-categories.edit', $category) }}" class="btn btn-sm btn-warning">Edit</a>

                    <form action="{{ route('report-categories.destroy', $category) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">No categories found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>