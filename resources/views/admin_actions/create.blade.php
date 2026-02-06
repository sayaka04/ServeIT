<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>New Admin Action</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

</head>

<body class="p-4">
    <h1>Create Admin Action</h1>

    <form action="{{ route('admin-actions.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Admin</label>
            <select name="admin_id" class="form-select">
                @foreach($admins as $admin)
                <option value="{{ $admin->id }}">{{ $admin->first_name }} {{ $admin->last_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Target User</label>
            <select name="target_user_id" class="form-select">
                @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Related Report (optional)</label>
            <select name="report_id" class="form-select">
                <option value="">-- None --</option>
                @foreach($reports as $report)
                <option value="{{ $report->id }}">Report #{{ $report->id }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Action Taken</label>
            <select name="action_taken" class="form-select">
                <option value="ban">Ban</option>
                <option value="warn">Warn</option>
                <option value="suspend">Suspend</option>
                <option value="dismiss">Dismiss</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="4"></textarea>
        </div>

        <button class="btn btn-primary">Submit</button>
        <a href="{{ route('admin-actions.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</body>

</html>