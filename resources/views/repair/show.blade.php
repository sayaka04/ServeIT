<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Repair Progress</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link href="{{ asset('css/shortened-sidebar.css')}}" rel="stylesheet" />


    {{-- 1. ADD SIGNATURE PAD LIBRARY CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
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
    </style>

    <script defer src="{{ asset('js/scripts.js') }}"></script>
    @include('partials/bootstrap')



</head>

<body class="sb-nav-fixed">

    @include('partials/navigation-bar')

    <div id="layoutSidenav">

        @include('partials/sidebar')

        <div id="layoutSidenav_content">

            <main>
                <div class="container-fluid px-4">

                    <h1 class="">Ongoing Repairs</h1>

                    {{-- Next Step Indicator! --}}
                    @if(Auth::user()->is_technician)

                    @if(Auth::user()->is_technician && !$repair->is_completed && !$repair->is_cancelled)
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>1/3 steps:</strong> Add progress updates until 100% completetion. Upon 100% completion the repair will automatically be marked as completed.
                        <a href="#add-progress-button" class="highlight-trigger">Go to add progress button.</a>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @elseif(!$repair->is_claimed)
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>2/3 steps:</strong> Await for the client to claim the device. Note do not give until the client has confirmed to have received the device in the app.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @elseif($repair->repairRating->user_weighted_score == null || $repair->repairRating->technician_weighted_score == null)
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>3/3 steps:</strong> You may now both leave a review and rating on one another. Note that none of you will see each others review and rating until both has submitted.
                        <a href="#rate-client-button" class="highlight-trigger">Go to rate client button.</a>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @endif



                    @if(!Auth::user()->is_technician)

                    @if(!$repair->is_completed && !$repair->is_cancelled)
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>1/3 steps:</strong> The device is being repaired. For the time being you will be receiving progress until 100% completion. You may view the progress updates here.
                        <a href="#progress-update" class="highlight-trigger">View progress updates.</a>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @elseif(!$repair->is_claimed)
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>2/3 steps:</strong> Claim the device from the technician. Note that the technician will not release the device until you have confirmed to have received it here on the app also note that do not confirm without first checking the device.
                        <a href="#confirm-claimed-button" class="highlight-trigger">Go to confirm claim button.</a>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @elseif($repair->repairRating->user_weighted_score == null || $repair->repairRating->technician_weighted_score == null)
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>3/3 steps:</strong> You may now both leave a review and rating on one another. Note that none of you will see each others review and rating until both has submitted.
                        <a href="#rate-technician-button" class="highlight-trigger">Go to rate techncian button.</a>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @endif






                    <!-- CSS -->
                    <style>
                        /* Brighter + longer highlight pulse */
                        @keyframes highlight-pulse {
                            0% {
                                box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.9), 0 0 10px rgba(255, 193, 7, 0.8);
                                background-color: rgba(255, 243, 205, 0.8);
                            }

                            50% {
                                box-shadow: 0 0 0 15px rgba(255, 193, 7, 0), 0 0 25px rgba(255, 193, 7, 0.9);
                                background-color: rgba(255, 243, 205, 1);
                            }

                            100% {
                                box-shadow: 0 0 0 0 rgba(255, 193, 7, 0), 0 0 0 rgba(255, 193, 7, 0);
                                background-color: inherit;
                            }
                        }


                        .highlight-animate {
                            animation: highlight-pulse 1.5s ease-out 3;
                            /* 5 pulses, slower */
                            position: relative;
                            z-index: 5;
                            /* ensure it appears above neighbors */
                            border-color: #ffc107 !important;
                            /* reinforce with a visible border */
                        }
                    </style>


                    <!-- JS -->
                    <script>
                        // Make highlight reusable for any element by ID
                        document.querySelectorAll('.highlight-trigger').forEach(link => {
                            link.addEventListener('click', function(e) {
                                e.preventDefault();

                                const targetId = this.getAttribute('href').replace('#', '');
                                const target = document.getElementById(targetId);

                                if (target) {
                                    target.scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'center'
                                    });

                                    // Trigger highlight animation
                                    target.classList.remove('highlight-animate'); // reset animation if re-clicked
                                    void target.offsetWidth; // force reflow to restart animation
                                    target.classList.add('highlight-animate');
                                }
                            });
                        });
                    </script>



















                    {{--
                        content.blade.php 
                    --}}

                    {{----------------------------------------------------------------------------------------------------}}
                    @include('repair.partials.repair-info')
                    {{----------------------------------------------------------------------------------------------------}}
                    @include('repair.partials.cancel-requests')
                    {{----------------------------------------------------------------------------------------------------}}
                    @include('repair.partials.repair-reception-details')
                    {{----------------------------------------------------------------------------------------------------}}
                    @if($repair->is_completed || $repair->is_cancelled)
                    @if(!$repair->is_claimed)

                    @include('repair.partials.repair-completion-confirmation')

                    @elseif(!$repair->is_claimed)
                    Awaiting client to receive the device and confirm!
                    @else

                    @include('repair.partials.repair_feedback_and_rating')
                    @include('repair.partials.repair-completion-claimation-detail')

                    @endif
                    @endif
                    {{----------------------------------------------------------------------------------------------------}}
                    @if($repair->is_received && $repair->issues === null && $repair->breakdown === null && !$repair->client_final_confirmation)
                    @include('repair.partials.add-breakdown')
                    <br>
                    @elseif($repair->issues !== null && $repair->breakdown !== null)
                    @include('repair.partials.breakdown')
                    @endif
                    @include('repair.partials.repair-progress')
                    {{----------------------------------------------------------------------------------------------------}}
                </div>
            </main>

            @include('partials/footer')

        </div>
    </div>




</body>


<script>
    function showSpinner(spinnerId, buttonId, formId) {
        var spinner = document.getElementById(spinnerId);
        spinner.classList.remove('d-none'); // Remove 'd-none' to show the spinner

        var button = document.getElementById(buttonId);
        button.disabled = true;

        var form = document.getElementById(formId); // Use the form ID here
        form.submit();
    }
</script>



</html>