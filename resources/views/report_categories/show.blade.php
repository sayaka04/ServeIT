<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Report Category</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @include('partials/bootstrap')
</head>

<body class="p-4">

    <h1>Report Category Details</h1>

    <div class="mb-3">
        <strong>Name:</strong> {{ $reportCategory->name }}
    </div>

    <div class="mb-3">
        <strong>Description:</strong><br>
        <p>{{ $reportCategory->description ?? '-' }}</p>
    </div>

    <a href="{{ route('report-categories.index') }}" class="btn btn-secondary">Back to List</a>
    <a href="{{ route('report-categories.edit', $reportCategory) }}" class="btn btn-warning">Edit Category</a>

</body>

</html>