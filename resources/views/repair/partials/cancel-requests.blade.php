@if ($repair->pendingCancelRequests->isNotEmpty())
<div class="alert alert-warning mt-4">
    <h5>Pending Cancel Requests</h5>
    <ul class="list-group">
        @foreach ($repair->pendingCancelRequests as $cancel)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
                <strong>Reason:</strong> {{ $cancel->reason }} <br>
                <small>Requested by: {{ $cancel->requestor->first_name }} {{ $cancel->requestor->middle_name }} {{ $cancel->requestor->last_name }}</small>
            </div>
            @if($cancel->approver_id === Auth::id())
            <div>
                <form method="POST" action="{{ route('cancel_requests.accept', $cancel->id) }}" style="display:inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Accept cancellation request? This will mark the repair as cancelled.')">Accept</button>
                </form>

                <form method="POST" action="{{ route('cancel_requests.decline', $cancel->id) }}" style="display:inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Decline cancellation request?')">Decline</button>
                </form>
            </div>
            @endif
        </li>
        @endforeach
    </ul>
</div>
@endif