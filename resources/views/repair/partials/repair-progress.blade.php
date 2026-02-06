<br><br>


@if($repair->is_received && $repair->client_final_confirmation)

<div class="p-3 shadow">
    <div class="card my-4">
        <div id="progress-update" class="card-header bg-primary text-white d-flex justify-content-between">
            <h4>
                <svg class="svg-inline--fa fa-clock-rotate-left me-1" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="clock-rotate-left" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                    <path fill="currentColor" d="M75 75L41 41C25.9 25.9 0 36.6 0 57.9V168c0 13.3 10.7 24 24 24H134.1c21.4 0 32.1-25.9 17-41l-30.8-30.8C155 85.5 203 64 256 64c106 0 192 86 192 192s-86 192-192 192c-40.8 0-78.6-12.7-109.7-34.4c-14.5-10.1-34.4-6.6-44.6 7.9s-6.6 34.4 7.9 44.6C151.2 495 201.7 512 256 512c141.4 0 256-114.6 256-256S397.4 0 256 0C185.3 0 121.3 28.7 75 75zm181 53c-13.3 0-24 10.7-24 24V256c0 6.4 2.5 12.5 7 17l72 72c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-65-65V152c0-13.3-10.7-24-24-24z"></path>
                </svg>
            </h4>
            <h4>Progress Updates</h4>
            @if(Auth::user()->is_technician && !$repair->is_completed && !$repair->is_cancelled)
            <button id="add-progress-button" type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#addProgressModal">
                Add Progress
            </button>
            @else
            <h4>
                <svg class="svg-inline--fa fa-clock-rotate-left me-1" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="clock-rotate-left" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                    <path fill="currentColor" d="M75 75L41 41C25.9 25.9 0 36.6 0 57.9V168c0 13.3 10.7 24 24 24H134.1c21.4 0 32.1-25.9 17-41l-30.8-30.8C155 85.5 203 64 256 64c106 0 192 86 192 192s-86 192-192 192c-40.8 0-78.6-12.7-109.7-34.4c-14.5-10.1-34.4-6.6-44.6 7.9s-6.6 34.4 7.9 44.6C151.2 495 201.7 512 256 512c141.4 0 256-114.6 256-256S397.4 0 256 0C185.3 0 121.3 28.7 75 75zm181 53c-13.3 0-24 10.7-24 24V256c0 6.4 2.5 12.5 7 17l72 72c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-65-65V152c0-13.3-10.7-24-24-24z"></path>
                </svg>
            </h4>
            @endif
        </div>
        <div class="card-body">

            @if(is_null($repair_progress) || $repair_progress->isEmpty())
            <div class="alert alert-info">
                There is no progress information available for this repair.
            </div>
            @endif

            <ul class="list-group list-group-flush bg-secondary rounded-lg">
                @foreach($repair_progress as $progress)
                <li class="list-group-item d-flex align-items-start border-0 py-3 px-0 bg-light">
                    <div class="flex-shrink-0 me-3 text-primary pt-1">
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">{{$progress->progress_status}}
                            <span class="badge bg-secondary ms-2">
                                {{ $progress->created_at ? \Carbon\Carbon::parse($progress->created_at)->format('F j, Y, g:i A') : 'N/A' }}
                            </span>
                        </h6>
                        <p class="mb-1">{{$progress->description}}</p>
                        @if($progress->progress_file_path)
                        <div class="mb-2">
                            <div class="d-flex justify-content-center border p-2 rounded bg-white">
                                @if ($repair->confirmation_signature_path)
                                <img src="{{ route('getFile2', ['filename' => $progress->progress_file_path]) }}" alt="Received Image" class="img-fluid rounded" style="max-width: 400px; max-height: 400px; object-fit: cover;">
                                @endif
                            </div>
                        </div>
                        @endif
                        <small class="text-muted">{{$progress->completion_rate}}% Complete</small>
                    </div>
                    @if(Auth::user()->is_technician && !$repair->is_cancelled && !$repair->is_completed)
                    <button class="btn btn-info m-2" onclick="openProgressUpdateModal2('{{$progress->id}}', '{{$progress->progress_status}}', '{{$progress->description}}', '{{$progress->completion_rate}}')">edit</button>
                    @endif
                </li>
                @endforeach
            </ul>
        </div>
    </div>

</div>

<br><br>

@endif

@if(Auth::user()->is_technician)

<div class="modal fade" id="addProgressModal" tabindex="-1" aria-labelledby="addProgressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form
            id="form-add-progress"
            action="{{ route('repair-progress.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="modal-content">
            @csrf
            <input type="hidden" name="repair_id" value="{{ $repair->id }}" />

            <div class="modal-header">
                <h5 class="modal-title" id="addProgressModalLabel">Add Repair Progress</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label for="progress_status" class="form-label">Status Update</label>
                    <input type="text" name="progress_status" id="progress_status" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Comment</label>
                    <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label for="completion_rate" class="form-label">Completion Rate (%)</label>
                    <input
                        type="number"
                        name="completion_rate"
                        id="completion_rate"
                        class="form-control"
                        min="0"
                        max="100"
                        step="1"
                        required
                        oninput="if(this.value > 100) this.value = 100; if(this.value < 0) this.value = 0;">
                </div>

                {{-- progress_file --}}
                <div class="mb-3">
                    <label for="progress_file" class="form-label">Upload File (Image) <small class="text-muted">(Optional)</small></label>
                    <input type="file" class="form-control" name="progress_file" id="progress_file" accept="image/*">
                    <small class="text-muted">For documentation of the device's current state</small>
                </div>
            </div>

            <div class="d-none text-center" id="spinner-add-progress">
                <div class="spinner-border text-light mt-2" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p>Adding progress, please wait...</p>
            </div>

            <div class="modal-footer">
                <button id="button-add-progress" type="submit" class="btn btn-success" onclick="showSpinner('spinner-add-progress', 'button-add-progress', 'form-add-progress')">Save Progress</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="updateProgressModal" tabindex="-1" aria-labelledby="updateProgressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="updateProgressForm" action="" method="POST"
            enctype="multipart/form-data"
            class="modal-content">
            @csrf
            @method('PUT') <input type="hidden" name="repair_id" value="{{ $repair->id }}" />

            <div class="modal-header">
                <h5 class="modal-title" id="updateProgressModalLabel">Update Repair Progress</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label for="update_progress_status" class="form-label">Status Update</label>
                    <input type="text" name="update_progress_status" id="update_progress_status" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="update_progress_description" class="form-label">Comment</label>
                    <textarea name="update_description" id="update_progress_description" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label for="update_completion_rate" class="form-label">Completion Rate (%)</label>
                    <input type="number" name="update_completion_rate" id="update_completion_rate" class="form-control" min="0" max="100" step="1" required>
                </div>

                {{-- progress_file --}}
                <div class="mb-3">
                    <label for="update_progress_file" class="form-label">Upload File (Image) <small class="text-muted">(Optional)</small></label>
                    <input type="file" class="form-control" name="update_progress_file" id="update_progress_file" accept="image/*">
                    <small class="text-muted">For documentation of the device's current state</small>
                </div>

            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Save Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="updateRepairForm" action="" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Repair Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="update_issue" class="form-label">Issue</label>
                    <input type="text" name="issue" id="update_issue" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="update_description" class="form-label">Description</label>
                    <textarea name="description" id="update_description" class="form-control" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="update_device" class="form-label">Device</label>
                    <input type="text" name="device" id="update_device" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="update_device_type" class="form-label">Device Type</label>
                    <input type="text" name="device_type" id="update_device_type" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="update_status" class="form-label">Status</label>
                    <select name="status" id="update_status" class="form-select" required>
                        <option value="pending">Pending</option>
                        <option value="in progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
</div>


<script>
    function openProgressUpdateModal2(id, progressStatus, description, completionRate) {
        const form = document.getElementById('updateProgressForm');
        document.getElementById('update_progress_status').value = progressStatus;
        document.getElementById('update_progress_description').value = description;
        document.getElementById('update_completion_rate').value = completionRate;
        form.action = '/repair-progress/' + id;
        const updateProgressModal = new bootstrap.Modal(document.getElementById('updateProgressModal'));
        updateProgressModal.show();
    }

    function openRepairUpdateModal(id, issue, description, device, deviceType, status) {
        const row = document.querySelector(`[data-repair-id="${id}"]`);

        if (row) {
            const issue = row.cells[1].textContent;
            const description = row.dataset.description;
            const device = row.cells[3].textContent;
            const deviceType = row.cells[4].textContent;
            const status = row.cells[5].querySelector('.badge').textContent.trim().toLowerCase();

            const form = document.getElementById('updateRepairForm');

            document.getElementById('update_issue').value = issue;
            document.getElementById('update_description').value = description;
            document.getElementById('update_device').value = device;
            document.getElementById('update_device_type').value = deviceType;
            document.getElementById('update_status').value = status;

            form.action = '/repairs/' + id;

            const updateModal = new bootstrap.Modal(document.getElementById('updateModal'));
            updateModal.show();
        }
    }
</script>
@endif