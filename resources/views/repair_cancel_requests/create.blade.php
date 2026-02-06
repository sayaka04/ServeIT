<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Cancel Request</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">
    <div class="container">
        <h2 class="mb-3">Create Cancel Request</h2>

        <form method="POST" action="{{ route('repair-cancel-requests.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Repair</label>
                <select name="repair_id" class="form-select">
                    @foreach($repairs as $r)
                    <option value="{{ $r->id }}">{{ $r->issue }} - {{ $r->device }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Requestor</label>
                <select name="requestor_id" class="form-select">
                    @foreach($users as $u)
                    <option value="{{ $u->id }}">{{ $u->first_name }} {{ $u->last_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Approver (optional)</label>
                <select name="approver_id" class="form-select">
                    <option value="">-- none --</option>
                    @foreach($users as $u)
                    <option value="{{ $u->id }}">{{ $u->first_name }} {{ $u->last_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Reason</label>
                <textarea name="reason" rows="4" class="form-control" required></textarea>
            </div>

            <button class="btn btn-primary">Submit</button>
            <a href="{{ route('repair-cancel-requests.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</body>

</html>