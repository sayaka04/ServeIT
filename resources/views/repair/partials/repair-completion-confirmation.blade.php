<div class="d-flex justify-content-center my-3">
    <button id="confirm-claimed-button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmReceiptModal">
        Confirm Claimed
    </button>
</div>

<div class="alert alert-info" role="alert">
    Awaiting for client to claim the device!
</div>


<!-- Confirm Receipt and Repair Success Modal -->

<div class="modal fade" id="confirmReceiptModal" tabindex="-1" aria-labelledby="confirmReceiptModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="form-claim-confirm" method="POST" action="{{ route('repairs.claimed', $repair->id) }}">
            @csrf
            @method('PUT')

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmReceiptModalLabel">Confirm Device Receipt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" id="claimedCheck" name="is_claimed" value="1" required>
                        <label class="form-check-label" for="claimedCheck">
                            I confirm that I have claimed the device.
                        </label>
                    </div>

                    @if(!$repair->is_cancelled)
                    <div class="mb-3">
                        <label class="form-label">Was the repair successful?</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="completion_confirmed" id="repairSuccessYes" value="1" required>
                                <label class="form-check-label" for="repairSuccessYes">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="completion_confirmed" id="repairSuccessNo" value="0" required>
                                <label class="form-check-label" for="repairSuccessNo">No</label>
                            </div>
                        </div>
                    </div>

                    {{-- SIGNATURE PAD HTML STRUCTURE --}}
                    <div>
                        {{-- Button to trigger the Signature Modal --}}
                        <button type="button" class="btn btn-info mb-3" data-bs-toggle="modal" data-bs-target="#signatureModal">
                            <i class="fas fa-pen-nib"></i> **Add/Edit Client Signature**
                        </button>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Current Signature Preview</label>
                            <div class="p-2 signature-preview-container d-flex justify-content-center">
                                <img id="signature_preview" src="" alt="Client Signature Preview" style="display: none;">
                                <span id="signature_placeholder" class="text-muted align-self-center">No Signature Added</span>
                            </div>
                        </div>

                        <input type="hidden" name="confirmation_signature_data" id="confirmation_signature_data">
                    </div>
                    {{-- END SIGNATURE PAD HTML STRUCTURE --}}

                    @endif
                </div>

                <div class="d-none text-center" id="spinner-claim-confirm">
                    <div class="spinner-border text-light mt-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p>Processing, please wait...</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button id="button-claim-confirm" type="submit" class="btn btn-success" onclick="showSpinner('spinner-claim-confirm', 'button-claim-confirm', 'form-claim-confirm')">Submit</button>
                </div>
            </div>
        </form>
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
    // --- SIGNATURE PAD VARIABLES & INITIALIZATION ---
    const canvas = document.getElementById('modal_signature_canvas');
    let signaturePad;

    const hiddenInput = document.getElementById('confirmation_signature_data');
    const previewImg = document.getElementById('signature_preview');
    const placeholderText = document.getElementById('signature_placeholder');

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
        // Do not show error on clear
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
        document.getElementById('signature_error').style.display = 'none'; // Clear error on save
    });

    function clearSignatureData() {
        hiddenInput.value = '';
        previewImg.src = '';
        previewImg.style.display = 'none';
        placeholderText.style.display = 'block';
    }
    clearSignatureData(); // Initial call
    // --- END SIGNATURE PAD LOGIC ---






    // Update Form Submission Logic (using standard form submit)
    document.getElementById('repairForm').addEventListener('submit', function(e) {

        const clientConfirmed = document.getElementById('client_confirmation').checked;
        const signatureData = document.getElementById('confirmation_signature_data').value;

        // 1. Final Signature Validation
        if (clientConfirmed && !signatureData) {
            e.preventDefault(); // Stop submission
            document.getElementById('signature_error').style.display = 'block';
            alert('Please sign the breakdown confirmation before submitting.');
            return;
        } else {
            document.getElementById('signature_error').style.display = 'none';
        }

        // The form will submit naturally.
    });
</script>