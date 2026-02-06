<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Cancel Request</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">
    <div class="container">
        <h2 class="mb-3">Edit Cancel Request #{{ $repairCancelRequest->id }}</h2>

        <form method="POST" action="{{ route('repair-cancel-requests.update', $repairCancelRequest->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Repair</label>
                <select name="repair_id" class="form-select">
                    @foreach($repairs as $r)
                    <option value="{{ $r->id }}" {{ $repairCancelRequest->repair_id == $r->id ? 'selected' : '' }}>
                        {{ $r->issue }} - {{ $r->device }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Requestor</label>
                <select name="requestor_id" class="form-select">
                    @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ $repairCancelRequest->requestor_id == $u->id ? 'selected' : '' }}>
                        {{ $u->first_name }} {{ $u->last_name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Approver</label>
                <select name="approver_id" class="form-select">
                    <option value="">-- none --</option>
                    @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ $repairCancelRequest->approver_id == $u->id ? 'selected' : '' }}>
                        {{ $u->first_name }} {{ $u->last_name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Reason</label>
                <textarea name="reason" rows="4" class="form-control" required>{{ $repairCancelRequest->reason }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="is_accepted" class="form-select">
                    <option value="">Pending</option>
                    <option value="1" {{ $repairCancelRequest->is_accepted === 1 ? 'selected' : '' }}>Accepted</option>
                    <option value="0" {{ $repairCancelRequest->is_accepted === 0 ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <button class="btn btn-primary">Update</button>
            <a href="{{ route('repair-cancel-requests.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</body>

</html>