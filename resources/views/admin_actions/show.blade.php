<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Action Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
</head>

<body class="p-4">
    <h1>Admin Action #{{ $adminAction->id }}</h1>

    <ul class="list-group mb-3">
        <li class="list-group-item"><strong>Admin:</strong> {{ $adminAction->admin->first_name ?? 'N/A' }}</li>
        <li class="list-group-item"><strong>Target User:</strong> {{ $adminAction->target->first_name ?? 'N/A' }}</li>
        <li class="list-group-item"><strong>Report ID:</strong> {{ $adminAction->report_id ?? '-' }}</li>
        <li class="list-group-item"><strong>Action Taken:</strong> {{ ucfirst($adminAction->action_taken) }}</li>
        <li class="list-group-item"><strong>Notes:</strong> {{ $adminAction->notes ?? 'None' }}</li>
        <li class="list-group-item"><strong>Date:</strong> {{ $adminAction->created_at->format('Y-m-d') }}</li>
    </ul>

    <a href="{{ route('admin-actions.index') }}" class="btn btn-secondary">Back</a>
</body>

</html>