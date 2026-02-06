@if(Auth::user()->is_technician)


<div class="d-flex justify-content-center my-3">
    <!-- Button to trigger Add Breakdown Modal -->
    <button id="add-breakdown-button" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBreakdownModal">
        Add Breakdown
    </button>
</div>

<!-- Modal -->
<div class="modal fade" id="addBreakdownModal" tabindex="-1" aria-labelledby="addBreakdownModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="addBreakdownForm">
                @csrf
                <input type="hidden" id="repair_id" value="{{ $repair->id }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBreakdownModalLabel">Add Breakdown to Repair</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="device" class="form-label">Device</label>
                            <input type="text" class="form-control" id="device" value="{{ $repair->device }}">
                        </div>
                        <div class="mb-3">
                            <label for="device_type" class="form-label">Device Type</label>
                            <input type="text" class="form-control" id="device_type" value="{{ $repair->device_type }}">
                        </div>
                        <div class="mb-3">
                            <label for="serial_number" class="form-label">Serial Number</label>
                            <input type="text" class="form-control" id="serial_number" placeholder="Enter serial number">
                        </div>
                        <div class="mb-3">
                            <label for="completion_date" class="form-label">Completion Date</label>
                            <input type="date" class="form-control" id="completion_date">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Issues</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" id="new_issue" placeholder="Enter an issue">
                            <button type="button" class="btn btn-outline-secondary" onclick="addIssue()">Add</button>
                        </div>
                        <ul class="list-group mb-3" id="issues_list"></ul>

                        <label class="form-label">Breakdown Items</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" id="new_component" placeholder="Item">
                            <input type="number" step="0.01" class="form-control" id="new_price" placeholder="Price">
                            <button type="button" class="btn btn-outline-secondary" onclick="addBreakdown()">Add</button>
                        </div>
                        <ul class="list-group mb-3" id="breakdown_list"></ul>

                        <div class="mt-2">
                            <strong>Estimated Cost: ₱<span id="total_estimated_cost">0.00</span></strong>
                        </div>
                    </div>
                </div>

                <div class="d-none text-center" id="spinner-breakdown">
                    <div class="spinner-border text-light mt-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p>Sending breakdown, please wait...</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="button-breakdown" class="btn btn-success" onclick="showSpinner('spinner-breakdown')">Save Breakdown</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Arrays to hold issues and breakdown items
    let issues = [];
    let breakdown = [];

    // Render issues list
    function renderIssues() {
        const list = document.getElementById('issues_list');
        list.innerHTML = '';
        issues.forEach((item, i) => {
            list.innerHTML += `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    ${item.issue}
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeIssue(${i})">Delete</button>
                </li>`;
        });
    }

    // Render breakdown list and total cost
    function renderBreakdown() {
        const list = document.getElementById('breakdown_list');
        list.innerHTML = '';
        let total = 0;
        breakdown.forEach((item, i) => {
            total += parseFloat(item.price);
            list.innerHTML += `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    ${item.item} - ₱${parseFloat(item.price).toFixed(2)}
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeBreakdown(${i})">Delete</button>
                </li>`;
        });
        document.getElementById('total_estimated_cost').textContent = total.toFixed(2);
    }

    // Add issue
    function addIssue() {
        const input = document.getElementById('new_issue');
        const val = input.value.trim();
        if (val) {
            issues.push({
                issue: val
            });
            input.value = '';
            renderIssues();
        }
    }

    // Remove issue
    function removeIssue(index) {
        issues.splice(index, 1);
        renderIssues();
    }

    // Add breakdown item
    function addBreakdown() {
        const compInput = document.getElementById('new_component');
        const priceInput = document.getElementById('new_price');
        const item = compInput.value.trim();
        const price = parseFloat(priceInput.value);
        if (item && !isNaN(price) && price >= 0) {
            breakdown.push({
                item,
                price
            });
            compInput.value = '';
            priceInput.value = '';
            renderBreakdown();
        }
    }

    // Remove breakdown item
    function removeBreakdown(index) {
        breakdown.splice(index, 1);
        renderBreakdown();
    }

    // Submit form via fetch/AJAX
    document.getElementById('addBreakdownForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const repairId = document.getElementById('repair_id').value;
        const serial_number = document.getElementById('serial_number').value.trim();
        const completion_date = document.getElementById('completion_date').value;

        if (!serial_number) {
            alert('Please enter Serial Number');
            return;
        }
        if (!completion_date) {
            alert('Please enter Completion Date');
            return;
        }
        if (issues.length === 0) {
            alert('Please add at least one issue');
            return;
        }
        if (breakdown.length === 0) {
            alert('Please add at least one breakdown item');
            return;
        }

        const estimated_cost = breakdown.reduce((sum, b) => sum + parseFloat(b.price), 0);

        const data = {
            _token: '{{ csrf_token() }}',
            serial_number,
            completion_date,
            issues,
            breakdown,
            estimated_cost,
        };

        document.getElementById('button-breakdown').disabled = true;

        fetch(`/repairs/${repairId}/add-breakdown`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': data._token
                },
                body: JSON.stringify(data)
            })
            .then(res => {
                if (!res.ok) {
                    document.getElementById('button-breakdown').disabled = false;
                    throw new Error('Failed to add breakdown');
                }
                document.getElementById('button-breakdown').disabled = false;
                return res.json();
            })
            .then(resp => {
                alert(resp.message || 'Breakdown added successfully!');
                const modalEl = document.getElementById('addBreakdownModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();
                location.reload();
            })
            .catch(err => {
                alert(err.message);
            })
            .finally(() => {
                document.getElementById('button-breakdown').disabled = false;
            });
    });
</script>
@endif