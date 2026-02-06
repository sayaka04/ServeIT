<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Create Repair Request</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link href="{{ asset('css/shortened-sidebar.css')}}" rel="stylesheet" />

    {{-- 1. ADD SIGNATURE PAD LIBRARY CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>


    <script defer src="{{ asset('js/scripts.js') }}"></script>
    @include('partials/bootstrap')

    <style>
        /* CSS for the Signature Pad in the Modal */
        #modal_signature_canvas {
            border: 2px solid #000;
            width: 350px;
            height: 250px;
            /* Prevents the browser from scrolling/zooming when drawing on touch devices */
            touch-action: none;
        }

        /* Style for the preview image container */
        .signature-preview-container {
            min-height: 100px;
            border: 1px dashed #ccc;
            background-color: #f8f9fa;
            /* light gray background */
        }

        /* Style for the actual preview image */
        #signature_preview {
            max-width: 100%;
            max-height: 200px;
            object-fit: contain;
            /* ensures image fits without cropping */
        }

        /* Optional: Add a max height for the lists to prevent them from taking up too much vertical space */
        .issues-list-scroll,
        .breakdown-list-scroll {
            max-height: 200px;
            /* Adjust as needed */
            overflow-y: auto;
        }

        .signature-preview-container {
            border-width: 2px !important;
            border-style: dashed !important;
        }

        /* Highlight validation errors for custom fields */
        .is-invalid-list {
            border: 1px solid #dc3545 !important;
            border-radius: .25rem;
            padding: 0.5rem;
        }
    </style>

</head>

<body class="sb-nav-fixed">

    @include('partials/navigation-bar')

    <div id="layoutSidenav">

        {{-- NOTE: Assuming 'partials/sidebar' is correct for your user roles --}}
        @include('partials/sidebar')

        <div id="layoutSidenav_content">

            <main>
                <div class="container-fluid px-4">

                    <h1 class="mt-4">Create Repair Request</h1>

                    {{-- Set enctype="multipart/form-data" to handle file uploads (receive_file) --}}
                    <form action="{{ route('repairs.store') }}" method="POST" id="repairForm" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf

                        {{-- Hidden inputs for dynamic data (must remain for JS/backend functionality) --}}
                        <input type="hidden" name="issues_data" id="issues_data">
                        <input type="hidden" name="breakdown_data" id="breakdown_data">
                        <input type="hidden" name="estimated_cost_data" id="estimated_cost_data">
                        <input type="hidden" name="confirmation_signature_data" id="confirmation_signature_data">


                        <div class="card shadow mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-mobile-alt"></i> Device Basic Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">




                                    <!-- Walk-in checkbox -->
                                    <div class="mb-3">
                                        <input type="checkbox" name="walk_in" id="walk-in-checkbox" value="1">
                                        <label for=" walk-in-checkbox">Walk-in</label>
                                    </div>


                                    {{-- Client Dropdown (conversation_id) --}}
                                    <div class="col-md-12 mb-3" id="client-select">
                                        <label for="conversation_id" class="form-label">Client</label>
                                        <select class="form-select" id="conversation_id" name="conversation_id">
                                            <option selected disabled value="">Select Client</option>
                                            @foreach($conversations as $conversation)
                                            <option value="{{ $conversation->id }}">
                                                {{ $conversation->user->first_name }}
                                                {{ $conversation->user->middle_name }}
                                                {{ $conversation->user->last_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">Please select a client.</div>
                                    </div>



                                    <!-- Walk-in fields (hidden by default) -->
                                    <div class="row d-none" id="walk-in-field">
                                        <div class="col col-md-12 col-lg-4 mb-3">
                                            <label for="first-name" class="form-label">First Name</label>
                                            <input type="text" class="form-control" id="first-name" name="first_name"
                                                placeholder="Juan" value="{{ old('first_name') }}">
                                        </div>

                                        <div class="col col-md-12 col-lg-4 mb-3">
                                            <label for="middle-name" class="form-label">Middle Name</label>
                                            <input type="text" class="form-control" id="middle-name" name="middle_name"
                                                placeholder="S." value="{{ old('middle_name') }}">
                                        </div>

                                        <div class="col col-md-12 col-lg-4 mb-3">
                                            <label for="last-name" class="form-label">Last Name</label>
                                            <input type="text" class="form-control" id="last-name" name="last_name"
                                                placeholder="Dela Cruz" value="{{ old('last_name') }}">
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email address</label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" value="{{old('email')}}">
                                        </div>

                                        <div class="mb-3">
                                            <label for="phone_number" class="form-label">Phone Number</label>
                                            <input
                                                type="tel"
                                                class="form-control"
                                                id="phone_number"
                                                name="phone_number"
                                                placeholder="09171234567"
                                                pattern="[0-9]{11}"
                                                title="Please enter an 11-digit phone number"
                                                value="{{old('phone_number')}}">
                                        </div>
                                    </div>

                                    <script>
                                        document.getElementById('walk-in-checkbox').addEventListener('change', function() {
                                            document.getElementById('walk-in-field').classList.toggle('d-none', !this.checked);
                                            document.getElementById('client-select').classList.toggle('d-none', this.checked);
                                        });
                                    </script>











































                                    {{-- device (Model/Name) --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="device" class="form-label">Device (Model/Name)</label>
                                        <input type="text" class="form-control" id="device" name="device" placeholder="e.g. iPhone 12, Dell XPS 13" required>
                                        <div class="invalid-feedback">
                                            Please enter the device name.
                                        </div>
                                    </div>

                                    {{-- device_type --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="device_type" class="form-label">Device Type</label>
                                        <input type="text" class="form-control" id="device_type" name="device_type" placeholder="e.g. Smartphone, Laptop" required>
                                        <div class="invalid-feedback">
                                            Please enter the device type.
                                        </div>
                                    </div>

                                    {{-- serial_number --}}
                                    <div class="col-md-12 mb-3">
                                        <label for="serial_number" class="form-label">Serial Number <small class="text-muted">(Optional)</small></label>
                                        <input type="text" class="form-control" id="serial_number" name="serial_number" placeholder="Enter serial number">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br><br>

                        <div class="card shadow mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-hand-holding-box"></i> Device Reception Details</h5>
                            </div>
                            <div class="card-body">
                                {{-- is_received checkbox (REQUIRED) --}}
                                <div class="mb-3 form-check">
                                    <input class="form-check-input" type="checkbox" id="receivedCheck" name="is_received" value="1" required>
                                    <label class="form-check-label fw-bold" for="receivedCheck">
                                        Device Received Confirmation <span class="text-danger">*</span>
                                    </label>
                                    <p class="small text-muted mb-0">Check this box to confirm that you have physically received the device.</p>
                                    <div class="invalid-feedback">
                                        You must confirm receipt of the device to proceed.
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- receive_notes --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="receive_notes" class="form-label">Reception Notes <small class="text-muted">(Optional)</small></label>
                                        <textarea class="form-control" name="receive_notes" id="receive_notes" rows="3" placeholder="Condition upon arrival, included accessories, etc."></textarea>
                                    </div>

                                    {{-- receive_file_path --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="receive_file" class="form-label">Upload File (Image/Video) <small class="text-muted">(Optional)</small></label>
                                        <input type="file" class="form-control" name="receive_file" id="receive_file" accept="image/*,video/*">
                                        <small class="text-muted">For documentation of the device's current state.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br><br>

                        <div class="card shadow mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-tools"></i> Repair Breakdown & Cost Estimation</h5>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    {{-- Issues Section --}}
                                    <div class="col-md-6 border-end">
                                        <h6 class="text-secondary mb-3">Reported Issues <span class="text-danger">*</span></h6>

                                        {{-- issues input --}}
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" id="new_issue" placeholder="Describe a specific issue (e.g., Broken Screen)">
                                            <button type="button" class="btn btn-outline-secondary" onclick="addIssue()"><i class="fas fa-plus"></i> Add Issue</button>
                                        </div>
                                        <ul class="list-group mb-1 issues-list-scroll" id="issues_list">
                                            <li class="list-group-item text-muted small py-2">No issues added yet.</li>
                                        </ul>
                                        <p class="small text-danger" id="issues_error" style="display: none;">
                                            <i class="fas fa-exclamation-triangle"></i> At least one issue is required.
                                        </p>
                                    </div>

                                    {{-- Breakdown/Cost Section --}}
                                    <div class="col-md-6">
                                        <h6 class="text-secondary mb-3">Component Breakdown (Parts & Labor) <span class="text-danger">*</span></h6>

                                        {{-- breakdown inputs --}}
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" id="new_component" placeholder="Item (e.g., Replacement LCD)">
                                            <span class="input-group-text">₱</span>
                                            <input type="number" step="0.01" class="form-control" id="new_price" placeholder="Price">
                                            <button type="button" class="btn btn-outline-secondary" onclick="addBreakdown()"><i class="fas fa-plus"></i> Add Item</button>
                                        </div>
                                        <ul class="list-group mb-1 breakdown-list-scroll" id="breakdown_list">
                                            <li class="list-group-item text-muted small py-2">No breakdown items added yet.</li>
                                        </ul>
                                        <p class="small text-danger" id="breakdown_error" style="display: none;">
                                            <i class="fas fa-exclamation-triangle"></i> At least one breakdown item (part or labor) is required.
                                        </p>

                                        {{-- Total Estimated Cost --}}
                                        <div class="alert alert-success mt-2" role="alert">
                                            <h5 class="mb-0">Total Estimated Cost: ₱<span id="total_estimated_cost">0.00</span></h5>
                                        </div>
                                    </div>
                                </div>


                                <br>
                                {{-- Add Disclaimer Section here please as text area acting as more like waiver or agreement --}}
                                {{-- Optional Disclaimer Section --}}
                                <div class="col-md-12 border-end">

                                    <h6 class="text-secondary mb-3 d-flex justify-content-between align-items-center">
                                        Optional Disclaimer / Agreement
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="toggleDisclaimerBtn">
                                            <i class="fas fa-file-contract"></i> Add Disclaimer
                                        </button>
                                    </h6>

                                    <div id="disclaimerSection" style="display: none;">
                                        <div class="mb-2">
                                            <textarea class="form-control" id="repair_disclaimer" name="repair_disclaimer" rows=" 7" readonly>
By submitting this repair request, I acknowledge and agree that:

• Repair costs are estimates and may change after further inspection.
• Additional parts or labor may be required.
• The repair center is not responsible for data loss or pre-existing damage.
• Completion dates are estimates and not guaranteed.
• The device owner authorizes the technician to perform necessary repairs.

I understand and accept these terms.
            </textarea>
                                        </div>

                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                id="disclaimer_agree"
                                                name="disclaimer_agree"
                                                value="1">

                                            <input type="hidden" name="disclaimer_enabled" id="disclaimer_enabled" value="0">

                                            <label class="form-check-label small" for="disclaimer_agree">
                                                I agree to the disclaimer above
                                            </label>
                                        </div>
                                    </div>

                                </div>
                                <br>
                                <script>
                                    document.getElementById('toggleDisclaimerBtn').addEventListener('click', function() {
                                        const section = document.getElementById('disclaimerSection');
                                        const btn = this;
                                        const enabled = document.getElementById('disclaimer_enabled');

                                        if (section.style.display === 'none') {
                                            section.style.display = 'block';
                                            btn.innerHTML = '<i class="fas fa-times"></i> Remove Disclaimer';
                                            btn.classList.remove('btn-outline-primary');
                                            btn.classList.add('btn-outline-danger');
                                            enabled.value = 1;
                                        } else {
                                            section.style.display = 'none';
                                            btn.innerHTML = '<i class="fas fa-file-contract"></i> Add Disclaimer';
                                            btn.classList.remove('btn-outline-danger');
                                            btn.classList.add('btn-outline-primary');
                                            document.getElementById('disclaimer_agree').checked = false;
                                            enabled.value = 0;
                                        }
                                    });
                                </script>


                                {{-- completion_date (Optional) --}}
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <label for="completion_date" class="form-label">Estimated Completion Date <small class="text-muted">(Optional)</small></label>
                                        <input type="date" class="form-control" id="completion_date" name="completion_date">
                                    </div>
                                </div>

                            </div>
                        </div>

                        <br><br>

                        <div class="card shadow mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-signature"></i> Client Confirmation</h5>
                            </div>
                            <div class="card-body">
                                {{-- client_final_confirmation checkbox (NOW REQUIRED) --}}
                                <div class="mb-4 form-check">
                                    <input class="form-check-input" type="checkbox" id="client_confirmation" name="client_final_confirmation" value="1" required>
                                    <label class="form-check-label fw-bold" for="client_confirmation">
                                        Client Acceptance <span class="text-danger">*</span>
                                    </label>
                                    <p class="text-muted small">By checking this box, the client accepts the estimated cost breakdown and authorizes the repair to proceed.</p>
                                    <div class="invalid-feedback">
                                        Client acceptance is required to proceed with the repair.
                                    </div>
                                </div>

                                <hr>

                                {{-- Signature Pad Area --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Client Signature <span class="text-danger" id="signature_required_star">*</span></label>
                                    <p class="small text-danger m-0 mb-2" id="signature_error" style="display: none;">
                                        <i class="fas fa-exclamation-triangle"></i> **A client signature is required for acceptance.**
                                    </p>

                                    {{-- Button to trigger the Signature Modal --}}
                                    <button type="button" class="btn btn-info btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#signatureModal">
                                        <i class="fas fa-pen-nib"></i> **Add/Edit Client Signature**
                                    </button>

                                    <div class="p-3 border rounded signature-preview-container d-flex justify-content-center bg-light" style="min-height: 100px;">
                                        <img id="signature_preview" src="" alt="Client Signature Preview" style="max-width: 100%; height: auto; display: none;">
                                        <span id="signature_placeholder" class="text-muted align-self-center">No Signature Added</span>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="mb-5 text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5" id="submitButton">
                                <i class="fas fa-save"></i> Submit Repair Request
                            </button>
                        </div>
                    </form>


                </div>
            </main>

            @include('partials/footer')

        </div>
    </div>

    {{-- SIGNATURE PAD MODAL HTML --}}
    <div class="modal fade" id="signatureModal" tabindex="-1" aria-labelledby="signatureModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="signatureModalLabel">Client E-Signature</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-danger fw-bold">Draw your signature in the box below:</p>
                    <div class="container-fluid d-flex justify-content-center">
                        <canvas id="modal_signature_canvas"></canvas>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-warning" id="clearSignatureBtn"><i class="fas fa-eraser"></i> Clear</button>
                    <button type="button" class="btn btn-primary" id="saveSignatureBtn" data-bs-dismiss="modal"><i class="fas fa-check"></i> **Save Signature**</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END SIGNATURE PAD MODAL HTML --}}

    <script>
        // Arrays to hold issues and breakdown items
        let issues = [];
        let breakdown = [];

        // --- SIGNATURE PAD VARIABLES & INITIALIZATION ---
        const canvas = document.getElementById('modal_signature_canvas');
        let signaturePad;

        const hiddenInput = document.getElementById('confirmation_signature_data');
        const previewImg = document.getElementById('signature_preview');
        const placeholderText = document.getElementById('signature_placeholder');
        const clientConfirmation = document.getElementById('client_confirmation');
        const signatureError = document.getElementById('signature_error');


        // Function to set canvas resolution correctly for high-DPI screens
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);

            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;

            canvas.getContext('2d').scale(ratio, ratio);

            // Re-load the signature if it exists, otherwise clear
            if (hiddenInput.value && signaturePad) {
                signaturePad.fromDataURL(hiddenInput.value);
            } else if (signaturePad) {
                signaturePad.clear();
            }
        }

        // Initialize the SignaturePad after the DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            if (canvas) {
                signaturePad = new SignaturePad(canvas, {
                    backgroundColor: 'rgb(255, 255, 255)',
                    penColor: 'rgb(0, 0, 0)'
                });

                resizeCanvas();
            }
        });

        window.addEventListener('resize', resizeCanvas);

        // Re-size and load existing signature when the modal is shown
        document.getElementById('signatureModal').addEventListener('shown.bs.modal', function() {
            resizeCanvas();
        });

        // Handler for the Clear button inside the modal
        document.getElementById('clearSignatureBtn').addEventListener('click', () => {
            signaturePad.clear();
            clearSignatureData();
        });

        // Handler for the Save button inside the modal
        document.getElementById('saveSignatureBtn').addEventListener('click', () => {
            if (signaturePad.isEmpty()) {
                clearSignatureData();
                return;
            }

            const dataURL = signaturePad.toDataURL('image/jpeg', 0.8);

            hiddenInput.value = dataURL;
            previewImg.src = dataURL;
            previewImg.style.display = 'block';
            placeholderText.style.display = 'none';
            signatureError.style.display = 'none'; // Clear error on save
        });

        function clearSignatureData() {
            hiddenInput.value = '';
            previewImg.src = '';
            previewImg.style.display = 'none';
            placeholderText.style.display = 'block';
        }
        clearSignatureData(); // Initial call

        // --- END SIGNATURE PAD LOGIC ---


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

            // Add placeholder if list is empty
            if (issues.length === 0) {
                list.innerHTML = '<li class="list-group-item text-muted small py-2">No issues added yet.</li>';
            }

            // Remove/Add validation indicator class
            list.classList.toggle('is-invalid-list', issues.length === 0);
            document.getElementById('issues_error').style.display = issues.length === 0 ? 'block' : 'none';
        }

        // Render breakdown list and total cost
        function renderBreakdown() {
            const list = document.getElementById('breakdown_list');
            list.innerHTML = '';
            let total = 0;
            breakdown.forEach((item, i) => {
                const price = parseFloat(item.price);
                total += price;
                list.innerHTML += `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    ${item.item} - ₱${price.toFixed(2)}
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeBreakdown(${i})">Delete</button>
                </li>`;
            });
            document.getElementById('total_estimated_cost').textContent = total.toFixed(2);

            // Add placeholder if list is empty
            if (breakdown.length === 0) {
                list.innerHTML = '<li class="list-group-item text-muted small py-2">No breakdown items added yet.</li>';
            }

            // Remove/Add validation indicator class
            list.classList.toggle('is-invalid-list', breakdown.length === 0);
            document.getElementById('breakdown_error').style.display = breakdown.length === 0 ? 'block' : 'none';
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
            if (item && !isNaN(price) && price > 0) {
                breakdown.push({
                    item,
                    price: price.toFixed(2)
                });
                compInput.value = '';
                priceInput.value = '';
                renderBreakdown();
            } else if (item && (!priceInput.value || price === 0)) {
                alert("Please enter a valid price greater than 0 for the breakdown item.");
            }
        }

        // Remove breakdown item
        function removeBreakdown(index) {
            breakdown.splice(index, 1);
            renderBreakdown();
        }

        // Initial render calls
        document.addEventListener('DOMContentLoaded', () => {
            renderIssues();
            renderBreakdown();
        });


        // Update Form Submission Logic (using standard form submit)
        document.getElementById('repairForm').addEventListener('submit', function(e) {
            const submitButton = document.getElementById('submitButton');
            const originalButtonHtml = submitButton.innerHTML;
            let isValid = true;


            // 1. Disable the button and show loading state immediately
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...';


            // --- STANDARD HTML VALIDATION CHECK ---
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                // If HTML validation fails, re-enable and reset button
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonHtml;
                this.classList.add('was-validated');
                return;
            }

            // --- CUSTOM JAVASCRIPT VALIDATION ---
            const signatureData = document.getElementById('confirmation_signature_data').value;

            // 2. Issues List Validation
            if (issues.length === 0) {
                document.getElementById('issues_error').style.display = 'block';
                document.getElementById('issues_list').classList.add('is-invalid-list');
                isValid = false;
            } else {
                document.getElementById('issues_error').style.display = 'none';
                document.getElementById('issues_list').classList.remove('is-invalid-list');
            }

            // 3. Breakdown List Validation
            if (breakdown.length === 0) {
                document.getElementById('breakdown_error').style.display = 'block';
                document.getElementById('breakdown_list').classList.add('is-invalid-list');
                isValid = false;
            } else {
                document.getElementById('breakdown_error').style.display = 'none';
                document.getElementById('breakdown_list').classList.remove('is-invalid-list');
            }


            // 4. Final Signature Validation (REQUIRED because client_confirmation is required)
            if (!signatureData) {
                signatureError.style.display = 'block';
                // Scroll to the error message for better user experience
                document.getElementById('signature_error').scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                isValid = false;
            } else {
                signatureError.style.display = 'none';
            }


            if (!isValid) {
                e.preventDefault(); // Stop submission if any custom validation fails
                this.classList.add('was-validated'); // Apply Bootstrap validation styles

                // Re-enable and reset button on custom validation failure
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonHtml;
                return;
            }


            // 5. If all is valid, prepare data and let the form submit
            const totalCost = breakdown.reduce((sum, b) => sum + parseFloat(b.price), 0).toFixed(2);

            // Populate hidden fields (These are JSON strings sent to the backend)
            document.getElementById('issues_data').value = JSON.stringify(issues);
            document.getElementById('breakdown_data').value = JSON.stringify(breakdown);
            document.getElementById('estimated_cost_data').value = totalCost;

            // The form will submit naturally here. The button remains disabled.
        });
    </script>


</body>

</html>