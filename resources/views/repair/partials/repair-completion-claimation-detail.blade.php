<!-- Display received notes and file if the device has been marked as received -->

<br><br>
@if ($repair->is_claimed)
<div class="p-3 shadow">
    <div class="card mb-4 shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="bi bi-box2-fill"></i> Client Device Claimation Details
            </h4>
        </div>

        <div class="card-body">

            {{-- 1. Display Claimation --}}
            @if ($repair->claim_date)
            <div class="mb-3">
                <p class="mb-1"><strong>Claim Date:</strong></p>
                <div class="alert bg-white text-primary py-2">
                    {{ $repair->confirmation_date ? \Carbon\Carbon::parse($repair->confirmation_date)->format('F j, Y, g:i A') : 'N/A' }}
                </div>
            </div>
            @endif


            <div class="mb-3">
                <p class="mb-2"><strong>Client Claim Signature:</strong></p>
                <div class="d-flex justify-content-center border p-2 rounded bg-white">
                    @if ($repair->claim_signature_path)
                    <img src="{{ route('getFile2', ['filename' => $repair->claim_signature_path]) }}" alt="Received Image" class="img-fluid rounded" style="max-width: 400px; max-height: 400px; object-fit: cover;">
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endif
<br><br>