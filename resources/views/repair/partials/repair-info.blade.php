{{--

                                        @if(is_null($repair))
                                        <div class="alert alert-info">
                                            You currently have no ongoing repairs.
                                        </div>
                                        @else
                                        <div class="p-3 shadow">
                                            <div class="card mb-4 shadow">
                                                <div class="card-header d-flex justify-content-between bg-light">
                                                    <svg class="svg-inline--fa fa-clipboard-list me-1" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="clipboard-list" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" data-fa-i2svg="">
                                                        <path fill="currentColor" d="M192 0c-41.8 0-77.4 26.7-90.5 64H64C28.7 64 0 92.7 0 128V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V128c0-35.3-28.7-64-64-64H282.5C269.4 26.7 233.8 0 192 0zm0 64a32 32 0 1 1 0 64 32 32 0 1 1 0-64zM72 272a24 24 0 1 1 48 0 24 24 0 1 1 -48 0zm104-16H304c8.8 0 16 7.2 16 16s-7.2 16-16 16H176c-8.8 0-16-7.2-16-16s7.2-16 16-16zM72 368a24 24 0 1 1 48 0 24 24 0 1 1 -48 0zm88 0c0-8.8 7.2-16 16-16H304c8.8 0 16 7.2 16 16s-7.2 16-16 16H176c-8.8 0-16-7.2-16-16z"></path>
                                                    </svg>
                                                    Repair ID: {{ $repair->id }} - ( {{ $repair->issue }} )
@if(Auth::user()->is_technician)
<button class="btn btn-info btn-sm" onclick="openRepairUpdateModal('{{$repair->id}}', '{{$repair->issue}}', '{{$repair->description}}', '{{$repair->device}}', '{{$repair->device_type}}', '{{$repair->status}}')">edit</button>
@else
<svg class="svg-inline--fa fa-clipboard-list me-1" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="clipboard-list" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" data-fa-i2svg="">
    <path fill="currentColor" d="M192 0c-41.8 0-77.4 26.7-90.5 64H64C28.7 64 0 92.7 0 128V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V128c0-35.3-28.7-64-64-64H282.5C269.4 26.7 233.8 0 192 0zm0 64a32 32 0 1 1 0 64 32 32 0 1 1 0-64zM72 272a24 24 0 1 1 48 0 24 24 0 1 1 -48 0zm104-16H304c8.8 0 16 7.2 16 16s-7.2 16-16 16H176c-8.8 0-16-7.2-16-16s7.2-16 16-16zM72 368a24 24 0 1 1 48 0 24 24 0 1 1 -48 0zm88 0c0-8.8 7.2-16 16-16H304c8.8 0 16 7.2 16 16s-7.2 16-16 16H176c-8.8 0-16-7.2-16-16z"></path>
</svg>
@endif
</div>
<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <p class="mb-0">
                <strong>Technician:</strong>
                <a href="{{ route('technicians.show', $technician_user->technician->technician_code) }}">
                    {{$technician_user->first_name}} {{$technician_user->middle_name}} {{$technician_user->last_name}}
                </a>
            </p>
            <p class="mb-2">
                <strong>Customer:</strong>
                <a href="{{ route('users.show', $client_user->id) }}">
                    {{$client_user->first_name}} {{$client_user->middle_name}} {{$client_user->last_name}}
                </a>
            </p>
            <p class="mb-2"><strong>Device:</strong> {{ $repair->device }}</p>
            <p class="mb-2"><strong>Issue:</strong> {{ $repair->issue }}</p>
        </div>
        <div class="col-md-6">
            <p class="mb-2"><strong>Date claimed:</strong> {{ $repair->created_at }}</p>
            <p class="mb-2"><strong>Estimated Completion:</strong> {{ $repair->completion_date }}</p>
            <p class="mb-2">
                <strong>Status:</strong>
                <strong class="badge text-bg-{{ $repair->status === 'completed' ? 'success' : ($repair->status === 'accepted' ? 'primary' : ($repair->status === 'declined' ? 'danger' : 'secondary')) }}">
                    {{ ucfirst($repair->status) }}
                </strong>
            </p>
            <p class="mb-0"><strong>Service Type:</strong> Walk-In (TO BE ADDED!)</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="mb-0"><strong>Description:</strong> {{ $repair->description }}</p>

        </div>
    </div>

    @if($repair_progress->first())
    @php
    $rate = $repair_progress->first()->completion_rate;
    $color = match(true) {$rate < 25=> 'danger', $rate < 50=> 'warning', $rate < 75=> 'success', default => 'info'};
                @endphp

                <h5 class="mt-4 mb-3">Progress</h5>
                <div class="progress mb-3">
                    <div class="progress-bar progress-bar-striped bg-{{ $color }}"
                        role="progressbar"
                        style="width: {{ $rate }}%"
                        aria-valuenow="{{ $rate }}"
                        aria-valuemin="0"
                        aria-valuemax="100">
                        {{ $rate }}%
                    </div>
                </div>
                @endif



                <p class="mb-0">Current Stage: **Awaiting part delivery for screen replacement.** (SHOULD I KEEP THIS?)</p>
</div>
</div>
</div>
@endif

--}}














{{--

    @if(is_null($repair))
<div class="alert alert-info">
    You currently have no ongoing repairs.
</div>
@else
<div class="p-3 shadow">
    <div class="card mb-4 shadow">
        <div class="card-header d-flex justify-content-between bg-light align-items-center">
            <div>
                <svg class="svg-inline--fa fa-clipboard-list me-1" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="clipboard-list" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                    <path fill="currentColor" d="M192 0c-41.8 0-77.4 26.7-90.5 64H64C28.7 64 0 92.7 0 128V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V128c0-35.3-28.7-64-64-64H282.5C269.4 26.7 233.8 0 192 0zm0 64a32 32 0 1 1 0 64 32 32 0 1 1 0-64zM72 272a24 24 0 1 1 48 0 24 24 0 1 1 -48 0zm104-16H304c8.8 0 16 7.2 16 16s-7.2 16-16 16H176c-8.8 0-16-7.2-16-16s7.2-16 16-16zM72 368a24 24 0 1 1 48 0 24 24 0 1 1 -48 0zm88 0c0-8.8 7.2-16 16-16H304c8.8 0 16 7.2 16 16s-7.2 16-16 16H176c-8.8 0-16-7.2-16-16z"></path>
                </svg>
                Repair ID: {{ $repair->id }} - ({{ $repair->issue }})
</div>

@if(!$repair->is_technician && !$repair->is_cancelled)
<button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cancelRepairModal">
    Cancel Repair
</button>
@endif
</div>

<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <p class="mb-0"><strong>Technician:</strong>
                <a href="{{ route('technicians.show', $technician_user->technician->technician_code) }}">
                    {{ $technician_user->first_name }} {{ $technician_user->middle_name }} {{ $technician_user->last_name }}
                </a>
            </p>
            <p class="mb-2"><strong>Customer:</strong>
                <a href="{{ route('users.show', $client_user->id) }}">
                    {{ $client_user->first_name }} {{ $client_user->middle_name }} {{ $client_user->last_name }}
                </a>
            </p>
            <p class="mb-2"><strong>Device:</strong> {{ $repair->device }}</p>
            <p class="mb-2"><strong>Issue:</strong> {{ $repair->issue }}</p>
        </div>
        <div class="col-md-6">
            <p class="mb-2"><strong>Date claimed:</strong> {{ $repair->created_at }}</p>
            <p class="mb-2"><strong>Estimated Completion:</strong> {{ $repair->completion_date }}</p>
            <p class="mb-2">
                <strong>Status:</strong>
                <strong class="badge text-bg-{{ $repair->status === 'completed' ? 'success' : ($repair->status === 'accepted' ? 'primary' : ($repair->status === 'declined' ? 'danger' : 'secondary')) }}">
                    {{ ucfirst($repair->status) }}
                </strong>
            </p>
            <p class="mb-0"><strong>Service Type:</strong> Walk-In (TO BE ADDED!)</p>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <p class="mb-0"><strong>Description:</strong> {{ $repair->description }}</p>
        </div>
    </div>

    @if($repair_progress->first())
    @php
    $rate = $repair_progress->first()->completion_rate;
    $color = match(true) {
    $rate < 25=> 'danger',
        $rate < 50=> 'warning',
            $rate < 75=> 'success',
                default => 'info'
                };
                @endphp

                <h5 class="mt-4 mb-3">Progress</h5>
                <div class="progress mb-3">
                    <div class="progress-bar progress-bar-striped bg-{{ $color }}"
                        role="progressbar"
                        style="width: {{ $rate }}%"
                        aria-valuenow="{{ $rate }}"
                        aria-valuemin="0"
                        aria-valuemax="100">
                        {{ $rate }}%
                    </div>
                </div>
                @endif

                <p class="mb-0"><strong>Current Stage:</strong> Awaiting part delivery for screen replacement.</p>
</div>
</div>
</div>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelRepairModal" tabindex="-1" aria-labelledby="cancelRepairModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="cancelRepairForm" method="POST" action="{{ route('repairs.cancel', $repair->id) }}">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelRepairModalLabel">Cancel Repair</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to request a cancellation for this repair?</p>
                    <div class="mb-3">
                        <label for="cancel_reason" class="form-label">Reason (optional)</label>
                        <textarea name="cancel_reason" id="cancel_reason" rows="3" class="form-control" placeholder="Enter your reason here..."></textarea>
                    </div>
                    <div id="cancelSpinner" class="text-center d-none">
                        <div class="spinner-border text-danger" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Submitting request...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="submitCancelBtn" class="btn btn-danger">Submit Cancellation</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif



--}}












@if(is_null($repair))
<div class="alert alert-info">
    You currently have no ongoing repairs.
</div>
@else
<div class="p-3 shadow">

    <div class="card my-4">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between">
                <h4><i class="bi bi-info-circle-fill"></i> Repair Basic Info</h4>
                @if(!$repair->is_technician && !$repair->is_cancelled && !$repair->is_completed)
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cancelRepairModal">
                    Cancel Repair
                </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col col-12 col-lg-6">
                    <p><strong>Client:</strong>
                        <a class="text-primary" href="{{ route('users.show', $client_user->id) }}">
                            {{ $client_user->first_name }} {{ $client_user->middle_name }} {{ $client_user->last_name }}
                        </a>
                    </p>
                    <p><strong>Technician:</strong>
                        <a class="text-primary" href="{{ route('technicians.show', $technician_user->technician->technician_code) }}">
                            {{ $technician_user->first_name }} {{ $technician_user->middle_name }} {{ $technician_user->last_name }}
                        </a>
                    </p>
                </div>
                <div class="col col-12 col-lg-6">
                    <p><strong>Status:</strong>
                        <strong class="badge text-bg-{{ $repair->status === 'completed' ? 'success' : ($repair->status === 'accepted' ? 'primary' : ($repair->status === 'declined' ? 'danger' : 'secondary')) }}">
                            {{ ucfirst($repair->status) }}
                        </strong>
                    </p>
                </div>
                @if($repair->issues === null && $repair->breakdown === null && !$repair->client_final_confirmation)
                <div class="card">
                    <p><strong>Device:</strong> {{ $repair->device }}</p>
                    <p><strong>Device Type:</strong> {{ $repair->device_type }}</p>
                </div>
                @endif
                @if($repair->issues !== null && $repair->breakdown !== null && $repair->client_final_confirmation)
                <div>
                    {{-- Progress Bar --}}
                    @include('repair/partials/progress-bar')
                </div>
                @endif
            </div>
        </div>
    </div>



    <!-- Cancel Modal -->
    <div class="modal fade" id="cancelRepairModal" tabindex="-1" aria-labelledby="cancelRepairModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="cancelRepairForm" method="POST" action="{{ route('repairs.cancel', $repair->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelRepairModalLabel">Cancel Repair</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to request a cancellation for this repair?</p>
                        <div class="mb-3">
                            <label for="cancel_reason" class="form-label">Reason (optional)</label>
                            <textarea name="cancel_reason" id="cancel_reason" rows="3" class="form-control" placeholder="Enter your reason here..."></textarea>
                        </div>
                        <div id="cancelSpinner" class="text-center d-none">
                            <div class="spinner-border text-danger" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Submitting request...</p>
                        </div>
                    </div>

                    <div class="d-none text-center" id="spinner-cancel">
                        <div class="spinner-border text-light mt-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p>Sending request, please wait...</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="submitCancelBtn" onclick="showSpinner('spinner-cancel', 'submitCancelBtn', 'cancelRepairForm')" class="btn btn-danger">Submit Cancellation</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endif