<!DOCTYPE html>
<html>

<head>
    <title>Create Notification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
</head>

<body>
    <div class="container mt-4">
        <nav class="mb-3">
            <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary">Back to Notifications</a>
        </nav>

        <h2>Create Notification</h2>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('notifications.store') }}">
            @csrf

            <div class="mb-3">
                <label for="subject" class="form-label">Subject *</label>
                <input type="text" name="subject" id="subject" class="form-control" value="{{ old('subject') }}" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="notifiable_type" class="form-label">Notifiable Type *</label>
                <input type="text" name="notifiable_type" id="notifiable_type" class="form-control" value="{{ old('notifiable_type') }}" placeholder="e.g. App\Models\Post" required>
            </div>

            <div class="mb-3">
                <label for="notifiable_id" class="form-label">Notifiable ID *</label>
                <input type="number" name="notifiable_id" id="notifiable_id" class="form-control" value="{{ old('notifiable_id') }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Create Notification</button>
            <a href="{{ route('notifications.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>