{{--
################################################################################################
Image Modal                        Image Modal                        Image Modal
################################################################################################
--}}

<div class="modal fade" id="modalAttachImage" tabindex="-1" aria-labelledby="modalAttachImageLabel" aria-hidden="true" data-bs-backdrop="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAttachImageLabel">Attach Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label for="imageUpload" class="form-label">Select image(s) to upload</label>
                <input class="form-control" type="file" id="imageUpload" accept="image/*">
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



{{--
################################################################################################
File Modal                        File Modal                        File Modal
################################################################################################
--}}

<div class="modal fade" id="modalAttachFile" tabindex="-1" aria-labelledby="modalAttachFileLabel" aria-hidden="true" data-bs-backdrop="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAttachFileLabel">Attach File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label for="fileUpload" class="form-label">Select file to upload (PDF only)</label>
                <input class="form-control" type="file" id="fileUpload" accept=".pdf,application/pdf">
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




{{--
################################################################################################
Link Modal                        Link Modal                        Link Modal
################################################################################################
--}}

<div class="modal fade" id="modalInsertLink" tabindex="-1" aria-labelledby="modalInsertLinkLabel" aria-hidden="true" data-bs-backdrop="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInsertLinkLabel">Insert Link</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="linkText" id="url_name" class="form-label">Link Text</label>
                    <input type="text" class="form-control" id="linkText" placeholder="e.g. Google">
                </div>
                <div class="mb-3">
                    <label for="linkURL" class="form-label">URL</label>
                    <input type="url" class="form-control" id="linkURL" placeholder="https://example.com">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


{{--
################################################################################################
Repair Modal                    Repair Modal                    Repair Modal
------------------------------------------------------------------------------------------------
This is only for Technician
################################################################################################
--}}
@if(Auth::user() && Auth::user()->is_technician)

<div class="modal fade" id="modalRepair" tabindex="-1" aria-labelledby="modalRepairLabel" aria-hidden="true" data-bs-backdrop="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRepairLabel">Initiate Repair</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="repairForm" class="needs-validation" novalidate>
                    <!-- Device -->
                    <div class="mb-3">
                        <label for="device" class="form-label">Device</label>
                        <input type="text" class="form-control" id="device" name="device" placeholder="e.g. iPhone 12" required>
                        <div class="invalid-feedback">
                            Please enter the device name.
                        </div>
                    </div>
                    {{--
                    <!-- Issue -->
                    <div class="mb-3">
                        <label for="issue" class="form-label">Issue</label>
                        <input type="text" class="form-control" id="issue" name="issue" placeholder="e.g. Broken LCD" required>
                        <div class="invalid-feedback">
                            Please enter the issue.
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Breakdown</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Details about the repair and estimated cost..."></textarea>
                    </div>
                    --}}

                    <!-- Device Type -->
                    <div class="mb-3">
                        <label for="device_type" class="form-label">Device Type</label>
                        <input type="text" class="form-control" id="device_type" name="device_type" placeholder="e.g. Smartphone" required>
                        <div class="invalid-feedback">
                            Please enter the device type.
                        </div>
                    </div>
                    {{--
                    <!-- Estimated Price -->
                    <div class="mb-3">
                        <label for="estimated_cost" class="form-label">Estimated Price</label>
                        <input type="number" class="form-control" id="estimated_cost" name="estimated_cost" placeholder="e.g. 299.99" step="0.01" min="0">
                        <div class="invalid-feedback">
                            Please enter a valid estimated price.
                        </div>
                    </div>

                    <!-- Completion Date -->
                    <div class="mb-3">
                        <label for="completion_date" class="form-label">Completion Date</label>
                        <input type="datetime-local" class="form-control" id="completion_date" name="completion_date">
                        <div class="invalid-feedback">
                            Please enter a valid completion date.
                        </div>
                    </div>
                    --}}

                    <!-- Hidden Fields (optional if used in JS/backend) -->
                    <input type="hidden" id="user_id" name="user_id" value="{{ Auth::id() }}">
                    <input type="hidden" id="technician_id" name="technician_id" value="{{ Auth::user()->technician_id ?? '' }}">
                    <input type="hidden" id="conversation_id" name="conversation_id" value="">
                    <input type="hidden" id="status" name="status" value="pending">
                    <input type="hidden" id="is_cancelled" name="is_cancelled" value="0">
                    <input type="hidden" id="is_completed" name="is_completed" value="0">

                    <input type="hidden" id="completion_date" name="completion_date">
                    <input type="hidden" id="estimated_cost" name="estimated_cost">
                    <input type="hidden" id="description" name="description">
                    <input type="hidden" id="issue" name="issue">


                    <div class="d-none text-center" id="spinner-repair-initiation">
                        <div class="spinner-border text-light mt-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p>Sending Repair Initiation, please wait...</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="submitRepair" onclick="showSpinner('spinner-repair-initiation')">Confirm Repair</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    function showSpinner(spinnerId) {
        var spinner = document.getElementById(spinnerId);
        spinner.classList.remove('d-none'); // Remove 'd-none' to show the spinner
    }
</script>

@endif


{{--
################################################################################################
JavaScript                        JavaScript                        JavaScript
################################################################################################
--}}
@if(Auth::user()->is_technician)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('repairForm');
        const submitBtn = document.getElementById('submitRepair');

        form.addEventListener('submit', async function(event) {
            event.preventDefault();

            // Clear previous validation state
            form.classList.remove('was-validated');

            // Bootstrap validation check
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            submitBtn.disabled = true;

            try {
                // Prepare form data to send
                const formData = new FormData(form);

                // Convert FormData to plain object if needed by ajaxHandler
                const dataObject = {};
                formData.forEach((value, key) => {
                    dataObject[key] = value;
                });

                const url = "{{ route('repairs.store') }}";
                const data = await ajaxHandler.requestCreateRepair(url, dataObject);

                if (window.APP_DEBUG === "true") {
                    if (data && data.id) {
                        alert('Repair successfully created with ID: ' + data.id);
                    }
                }



                message.value = '*';
                await sendMessageRepair("{{ route('messages.store') }}", data.id, '*');

                // Hide modal
                const modalEl = document.getElementById('modalRepair');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();

                // Reset form and validation state
                form.reset();
                form.classList.remove('was-validated');

            } catch (error) {
                console.error('Repair creation failed:', error);
                alert('Failed to create repair. See console for details.');
            } finally {
                submitBtn.disabled = false;
            }
        });
    });
</script>
@endif


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mainBadge = document.querySelector('#dropdownActionsClient .badge');

        const imageInput = document.getElementById('imageUpload');
        const fileInput = document.getElementById('fileUpload');
        const linkTextInput = document.getElementById('linkText');
        const linkURLInput = document.getElementById('linkURL');

        // Get dropdown badges inside each item
        const imageBadge = document.querySelector('button[data-bs-target="#modalAttachImage"] .badge');
        const fileBadge = document.querySelector('button[data-bs-target="#modalAttachFile"] .badge');
        const linkBadge = document.querySelector('button[data-bs-target="#modalInsertLink"] .badge');

        function updateBadgeCount() {
            let totalCount = 0;

            // Image count
            let imageCount = imageInput.files.length;
            if (imageCount > 0) {
                imageBadge.textContent = imageCount;
                imageBadge.style.display = 'inline-block';
                totalCount++;
            } else {
                imageBadge.style.display = 'none';
            }

            // File count
            let fileCount = fileInput.files.length;
            if (fileCount > 0) {
                fileBadge.textContent = fileCount;
                fileBadge.style.display = 'inline-block';
                totalCount++;
            } else {
                fileBadge.style.display = 'none';
            }

            // Link count (just 1 if both fields filled)
            let linkCount = (linkTextInput.value.trim() && linkURLInput.value.trim()) ? 1 : 0;
            if (linkCount > 0) {
                linkBadge.textContent = linkCount;
                linkBadge.style.display = 'inline-block';
                totalCount++;
            } else {
                linkBadge.style.display = 'none';
            }

            // Update main badge on the button
            if (totalCount > 0) {
                mainBadge.textContent = totalCount;
                mainBadge.style.display = 'inline-block';
            } else {
                mainBadge.style.display = 'none';
            }
        }

        imageInput.addEventListener('change', updateBadgeCount);
        fileInput.addEventListener('change', updateBadgeCount);
        linkTextInput.addEventListener('input', updateBadgeCount);
        linkURLInput.addEventListener('input', updateBadgeCount);

        updateBadgeCount(); // initialize on page load
    });
</script>