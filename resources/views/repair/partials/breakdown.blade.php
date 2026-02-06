<div class="p-3 shadow">
    <div class="card my-4">
        <div class="card-header bg-primary text-white d-flex align-items-center">
            <h4 class="mb-0">
                <i class="bi bi-list-columns-reverse"></i> Repair Breakdown Details
            </h4>

            {{-- Technician/Admin Edit Button --}}
            @if(Auth::user()->is_technician || Auth::user()->is_admin)
            <button class="btn btn-sm btn-light ms-auto"
                onclick='openEditModal("{{ $repair->id }}", `{!! addslashes($repair->issues) !!}`, `{!! addslashes($repair->breakdown) !!}`)'>
                <i class="bi bi-pencil-square"></i> Edit
            </button>
            @endif
        </div>

        <div class="card-body">
            <p><strong>Device:</strong> {{ $repair->device }}</p>
            <p><strong>Device Type:</strong> {{ $repair->device_type }}</p>
            <p><strong>Serial Number:</strong> {{ $repair->serial_number }}</p>
            <p><strong>Completion Date:</strong> {{ $repair->completion_date ? \Carbon\Carbon::parse($repair->completion_date)->format('F j, Y') : 'N/A' }}</p>

            <hr>

            <h5>Issues Reported</h5>
            @php $issuesArray = json_decode($repair->issues, true); @endphp
            @if ($repair->issues && is_array($issuesArray))
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Issues</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($issuesArray as $issue)
                    <tr>
                        <td>• {{ $issue['issue'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-muted">No issues reported.</p>
            @endif

            <h5>Breakdown Items</h5>
            @php $breakdownArray = json_decode($repair->breakdown, true); @endphp
            @if ($repair->breakdown && is_array($breakdownArray))
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="text-end">Price (₱)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($breakdownArray as $item)
                    <tr>
                        <td>{{ $item['item'] }}</td>
                        <td class="text-end">{{ number_format($item['price'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total Estimated Cost</th>
                        <th class="text-end">₱{{ number_format($repair->estimated_cost, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
            <hr>
            <h5>Client Breakdown Confirmation Details</h5>
            <div class="mb-3">
                <p class="mb-1"><strong>Confirmation Date:</strong></p>
                <div class="alert bg-white text-primary py-2">
                    {{ $repair->confirmation_date ? \Carbon\Carbon::parse($repair->confirmation_date)->format('F j, Y, g:i A') : 'N/A' }}
                </div>
            </div>
            <div class="mb-3">
                <p class="mb-2"><strong>Client Signature:</strong></p>
                <div class="d-flex justify-content-center border p-2 rounded bg-white">
                    @if ($repair->confirmation_signature_path)
                    <img src="{{ route('getFile2', ['filename' => $repair->confirmation_signature_path]) }}" alt="Received Image" class="img-fluid rounded" style="max-width: 400px; max-height: 400px; object-fit: cover;">
                    @endif
                </div>
            </div>
            <div class="mb-3">
                <p class="mb-2"><strong>Repair Order Slip File:</strong></p>
                <div class="d-flex justify-content-center border p-2 rounded bg-white">
                    <form action="{{ route('downloadRepairOrder', ['filename' => $repair->order_slip_path]) }}" method="GET">
                        <button class="btn btn-primary" type="submit">Download Repair Order</button>
                    </form>
                </div>
            </div>



            <hr>
            @if(!Auth::user()->is_admin && $repair->client_final_confirmation === null)
            <div class="d-flex justify-content-center mt-3">
                <button id="accept-breakdown-button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#confirmModal" data-action="accept">Accept Breakdown</button>
                <button id="decline-breakdown-button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal" data-action="decline">Decline Breakdown</button>
            </div>
            @elseif(Auth::user()->is_technician && !$repair->client_final_confirmation)
            <div class="alert alert-info" role="alert">
                Awaiting for client final confirmation! </div>
            @endif

            @else
            <p class="text-muted">No breakdown items added.</p>
            @endif
        </div>
    </div>
</div>

{{-- Confirmation Modal --}}
@if(!Auth::user()->is_admin && !$repair->client_final_confirmation)
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="finalConfirmationForm">
            @csrf
            @method('POST')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirm Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="modalText">Are you sure you want to continue?</p>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" id="confirmCheckbox">
                        <label class="form-check-label" for="confirmCheckbox">
                            I confirm that I want to proceed with this action.
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="confirmButton" disabled>Confirm</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

<div class="modal fade" id="editBreakdownModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editRepairForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Edit Repair Breakdown</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Issues</h6>
                    <div id="edit_issues_container" class="mb-3"></div>
                    <button type="button" class="btn btn-sm btn-outline-primary mb-3" onclick="addEditIssue()">+ Add Issue</button>

                    <hr>

                    <h6>Cost Breakdown</h6>
                    <div id="edit_breakdown_container" class="mb-3"></div>
                    <button type="button" class="btn btn-sm btn-outline-primary mb-3" onclick="addEditBreakdown()">+ Add Item</button>

                    <input type="hidden" name="issues_data" id="edit_issues_data">
                    <input type="hidden" name="breakdown_data" id="edit_breakdown_data">
                    <input type="hidden" name="estimated_cost_data" id="edit_estimated_cost_data">

                    <div class="mt-3 text-end">
                        <strong>Total Estimated Cost: ₱<span id="edit_total_display">0.00</span></strong>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    @verbatim
    let editIssues = [];
    let editBreakdown = [];

    function openEditModal(repairId, currentIssues, currentBreakdown) {
        document.getElementById('editRepairForm').action = `/repairs/${repairId}/update-breakdown`;

        try {
            editIssues = typeof currentIssues === 'string' ? JSON.parse(currentIssues) : currentIssues;
            editBreakdown = typeof currentBreakdown === 'string' ? JSON.parse(currentBreakdown) : currentBreakdown;

            if (!Array.isArray(editIssues)) editIssues = [];
            if (!Array.isArray(editBreakdown)) editBreakdown = [];

            renderEditIssues();
            renderEditBreakdown();

            const modal = new bootstrap.Modal(document.getElementById('editBreakdownModal'));
            modal.show();
        } catch (e) {
            console.error("Parsing Error", e);
        }
    }

    function renderEditIssues() {
        const container = document.getElementById('edit_issues_container');
        container.innerHTML = '';
        editIssues.forEach((issueObj, index) => {
            const val = (typeof issueObj === 'object') ? (issueObj.issue || "") : issueObj;
            container.innerHTML += `
            <div class="input-group mb-2">
                <input type="text" class="form-control" value="${val}" onchange="updateEditIssue(${index}, this.value)">
                <button class="btn btn-danger" type="button" onclick="removeEditIssue(${index})"><i class="bi bi-trash"></i></button>
            </div>`;
        });
    }

    function renderEditBreakdown() {
        const container = document.getElementById('edit_breakdown_container');
        container.innerHTML = '';
        let total = 0;
        editBreakdown.forEach((item, index) => {
            total += parseFloat(item.price || 0);
            container.innerHTML += `
            <div class="row g-2 mb-2">
                <div class="col-7">
                    <input type="text" class="form-control" placeholder="Item" value="${item.item || ''}" onchange="updateEditBreakdown(${index}, 'item', this.value)">
                </div>
                <div class="col-4">
                    <input type="number" class="form-control" placeholder="Price" value="${item.price || 0}" onchange="updateEditBreakdown(${index}, 'price', this.value)">
                </div>
                <div class="col-1">
                    <button class="btn btn-danger w-100" type="button" onclick="removeEditBreakdown(${index})"><i class="bi bi-trash"></i></button>
                </div>
            </div>`;
        });
        document.getElementById('edit_total_display').innerText = total.toLocaleString(undefined, {
            minimumFractionDigits: 2
        });
    }

    function addEditIssue() {
        editIssues.push({
            issue: ""
        });
        renderEditIssues();
    }

    function removeEditIssue(index) {
        editIssues.splice(index, 1);
        renderEditIssues();
    }

    function updateEditIssue(index, val) {
        if (typeof editIssues[index] !== 'object') editIssues[index] = {};
        editIssues[index].issue = val;
    }

    function addEditBreakdown() {
        editBreakdown.push({
            item: "",
            price: 0
        });
        renderEditBreakdown();
    }

    function removeEditBreakdown(index) {
        editBreakdown.splice(index, 1);
        renderEditBreakdown();
    }

    function updateEditBreakdown(index, key, val) {
        editBreakdown[index][key] = val;
        renderEditBreakdown();
    }

    document.getElementById('editRepairForm').addEventListener('submit', function(e) {
        document.getElementById('edit_issues_data').value = JSON.stringify(editIssues);
        document.getElementById('edit_breakdown_data').value = JSON.stringify(editBreakdown);
        const total = editBreakdown.reduce((sum, b) => sum + parseFloat(b.price || 0), 0);
        document.getElementById('edit_estimated_cost_data').value = total.toFixed(2);
    });
    @endverbatim

    // Client Confirmation Logic (Needs Blade variables)
    document.addEventListener('DOMContentLoaded', () => {
        const confirmModal = document.getElementById('confirmModal');
        if (confirmModal) {
            const confirmForm = document.getElementById('finalConfirmationForm');
            const confirmButton = document.getElementById('confirmButton');
            const confirmCheckbox = document.getElementById('confirmCheckbox');
            const modalText = document.getElementById('modalText');
            let action = '';

            confirmModal.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                action = button.getAttribute('data-action');
                modalText.textContent = action === 'accept' ?
                    'Do you confirm that you wish to continue and ACCEPT the breakdown of the repair?' :
                    'Are you sure you want to DECLINE the breakdown of the repair?';
                confirmCheckbox.checked = false;
                confirmButton.disabled = true;
                confirmForm.action = `/repairs/{{ $repair->id }}/client-confirm`;
            });

            confirmCheckbox.addEventListener('change', () => {
                confirmButton.disabled = !confirmCheckbox.checked;
            });

            confirmForm.addEventListener('submit', (e) => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'client_final_confirmation';
                hiddenInput.value = action === 'accept' ? '1' : '0';
                confirmForm.appendChild(hiddenInput);
            });
        }
    });
</script>