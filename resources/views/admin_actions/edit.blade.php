<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Admin Action</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

</head>

<body>
    <div class="container mt-5">
        <h1>Edit Admin Action</h1>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin-actions.update', $adminAction) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="admin_id" class="form-label">Admin</label>
                <select name="admin_id" id="admin_id" class="form-select" required>
                    @foreach($admins as $admin)
                    <option value="{{ $admin->id }}" {{ old('admin_id', $adminAction->admin_id) == $admin->id ? 'selected' : '' }}>
                        {{ $admin->name }} ({{ $admin->email }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="target_user_id" class="form-label">Target User</label>
                <select name="target_user_id" id="target_user_id" class="form-select" required>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('target_user_id', $adminAction->target_user_id) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="report_id" class="form-label">Related Report (Optional)</label>
                <select name="report_id" id="report_id" class="form-select">
                    <option value="">-- None --</option>
                    @foreach($reports as $report)
                    <option value="{{ $report->id }}" {{ old('report_id', $adminAction->report_id) == $report->id ? 'selected' : '' }}>
                        Report #{{ $report->id }} - {{ $report->description ?? 'No description' }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="action_taken" class="form-label">Action Taken</label>
                <select name="action_taken" id="action_taken" class="form-select" required>
                    @php
                    $actions = ['ban', 'warn', 'suspend', 'dismiss'];
                    @endphp
                    @foreach($actions as $action)
                    <option value="{{ $action }}" {{ old('action_taken', $adminAction->action_taken) == $action ? 'selected' : '' }}>
                        {{ ucfirst($action) }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Notes (Optional)</label>
                <textarea name="notes" id="notes" class="form-control" rows="4">{{ old('notes', $adminAction->notes) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update Admin Action</button>
            <a href="{{ route('admin-actions.index') }}" class="btn btn-secondary ms-2">Cancel</a>
        </form>
    </div>
</body>

</html>