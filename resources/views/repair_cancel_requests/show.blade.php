<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cancel Request Details</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">
    <div class="container">
        <h2 class="mb-3">Cancel Request #{{ $repairCancelRequest->id }}</h2>

        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <td>{{ $repairCancelRequest->id }}</td>
            </tr>
            <tr>
                <th>Repair Issue</th>
                <td>{{ $repairCancelRequest->repair->issue ?? '-' }}</td>
            </tr>
            <tr>
                <th>Device</th>
                <td>{{ $repairCancelRequest->repair->device ?? '-' }}</td>
            </tr>
            <tr>
                <th>Requestor</th>
                <td>{{ $repairCancelRequest->requestor->first_name ?? '' }} {{ $repairCancelRequest->requestor->last_name ?? '' }}</td>
            </tr>
            <tr>
                <th>Approver</th>
                <td>{{ $repairCancelRequest->approver->first_name ?? '' }} {{ $repairCancelRequest->approver->last_name ?? '' }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @if(is_null($repairCancelRequest->is_accepted))
                    <span class="badge bg-secondary">Pending</span>
                    @elseif($repairCancelRequest->is_accepted)
                    <span class="badge bg-success">Accepted</span>
                    @else
                    <span class="badge bg-danger">Rejected</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Reason</th>
                <td>{{ $repairCancelRequest->reason }}</td>
            </tr>
            <tr>
                <th>Date Requested</th>
                <td>{{ $repairCancelRequest->created_at->format('Y-m-d H:i') }}</td>
            </tr>
        </table>

        <a href="{{ route('repair-cancel-requests.edit', $repairCancelRequest->id) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('repair-cancel-requests.index') }}" class="btn btn-secondary">Back</a>
    </div>
</body>

</html>