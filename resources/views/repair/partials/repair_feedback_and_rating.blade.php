                                        @if(!$repair->is_cancelled)


                                        <div class="p-3 shadow">
                                            <div class="card mb-4 shadow">
                                                <div class="card-header d-flex justify-content-between bg-primary text-white">
                                                    <div class="d-flex align-items-center">
                                                        <h4><i class="bi bi-star-fill"></i>Rating and Feedback</h4>
                                                    </div>
                                                </div>

                                                <div class="card-body">
                                                    <div class="row">

                                                        <!-- CLIENT → TECHNICIAN -->
                                                        <div class="col-md-6 border-end">
                                                            <h5>Client Rating to Technician</h5>
                                                            <hr class="mt-1">

                                                            {{-- BOTH SUBMITTED --}}
                                                            @if($repair->repairRating->user_weighted_score !== null && $repair->repairRating->technician_weighted_score !== null)

                                                            <p class="h4 text-success mb-1">
                                                                Score: <strong>{{ number_format($repair->repairRating->user_weighted_score, 1) }}</strong>
                                                            </p>
                                                            <p>
                                                                <strong>Comment:</strong>
                                                                <span class="text-muted">{{ $repair->repairRating->user_comment ?: 'No comment provided.' }}</span>
                                                            </p>

                                                            {{-- CLIENT ONLY SUBMITTED --}}
                                                            @elseif($repair->repairRating->user_weighted_score !== null)

                                                            @if(!Auth::user()->is_technician)
                                                            {{-- Show own rating to client --}}
                                                            <p class="h4 text-success mb-1">
                                                                Score: <strong>{{ number_format($repair->repairRating->user_weighted_score, 1) }}</strong>
                                                            </p>
                                                            <p>
                                                                <strong>Comment:</strong>
                                                                <span class="text-muted">{{ $repair->repairRating->user_comment ?: 'No comment provided.' }}</span>
                                                            </p>
                                                            @else
                                                            <p class="text-info">The client has submitted a rating!</p>
                                                            @endif

                                                            {{-- CLIENT MUST SUBMIT --}}
                                                            @elseif(!Auth::user()->is_technician)
                                                            <div class="alert alert-info d-inline-block p-2">
                                                                Repair completed! Please share your feedback.
                                                            </div>
                                                            <button id="rate-technician-button" class="btn btn-primary w-100 mt-2"
                                                                data-bs-toggle="modal" data-bs-target="#rateRepairModalClient">
                                                                Rate Technician
                                                            </button>

                                                            {{-- TECHNICIAN WAITING --}}
                                                            @else
                                                            <p class="text-muted">Awaiting client rating...</p>
                                                            @endif
                                                        </div>

                                                        <!-- TECHNICIAN → CLIENT -->
                                                        <div class="col-md-6">
                                                            <h5>Technician Rating to Client</h5>
                                                            <hr class="mt-1">

                                                            {{-- BOTH SUBMITTED --}}
                                                            @if($repair->repairRating->user_weighted_score !== null && $repair->repairRating->technician_weighted_score !== null)

                                                            <p class="h4 text-success mb-1">
                                                                Score: <strong>{{ number_format($repair->repairRating->technician_weighted_score, 1) }}</strong>
                                                            </p>
                                                            <p>
                                                                <strong>Comment:</strong>
                                                                <span class="text-muted">{{ $repair->repairRating->technician_comment ?: 'No comment provided.' }}</span>
                                                            </p>

                                                            {{-- TECHNICIAN ONLY SUBMITTED --}}
                                                            @elseif($repair->repairRating->technician_weighted_score !== null)

                                                            @if(Auth::user()->is_technician)
                                                            {{-- Show own rating to technician --}}
                                                            <p class="h4 text-success mb-1">
                                                                Score: <strong>{{ number_format($repair->repairRating->technician_weighted_score, 1) }}</strong>
                                                            </p>
                                                            <p>
                                                                <strong>Comment:</strong>
                                                                <span class="text-muted">{{ $repair->repairRating->technician_comment ?: 'No comment provided.' }}</span>
                                                            </p>
                                                            @else
                                                            <p class="text-info">The technician has submitted a rating!</p>
                                                            @endif

                                                            {{-- TECHNICIAN MUST SUBMIT --}}
                                                            @elseif(Auth::user()->is_technician)
                                                            <div class="alert alert-info d-inline-block p-2">
                                                                Please provide feedback on the client.
                                                            </div>
                                                            <button id="rate-client-button" class="btn btn-primary w-100 mt-2"
                                                                data-bs-toggle="modal" data-bs-target="#rateRepairModalTechnician">
                                                                Rate Client
                                                            </button>

                                                            {{-- CLIENT WAITING --}}
                                                            @else
                                                            <p class="text-muted">Awaiting technician rating...</p>
                                                            @endif
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br><br>



                                        <!-- Rate Technician Modal (Client) -->
                                        <div class="modal fade" id="rateRepairModalClient" tabindex="-1" aria-labelledby="rateRepairModalClientLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form id="form-rating-client" method="POST" action="{{ route('repair_ratings.update', $repair->repairRating->id) }}">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="rateRepairModalClientLabel">Rate Technician</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="user_weighted_score" class="form-label">Score (0-100)</label>
                                                                <input
                                                                    type="number"
                                                                    name="user_weighted_score"
                                                                    class="form-control"
                                                                    step="0.01"
                                                                    min="0"
                                                                    max="100"
                                                                    value=""
                                                                    oninput="if(this.value > 100) this.value = 100; if(this.value < 0) this.value = 0;">
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="user_comment" class="form-label">Comment</label>
                                                                <textarea name="user_comment" class="form-control" rows="4"></textarea>
                                                            </div>
                                                        </div>

                                                        <div class="d-none text-center" id="spinner-rating-client">
                                                            <div class="spinner-border text-light mt-2" role="status">
                                                                <span class="visually-hidden">Loading...</span>
                                                            </div>
                                                            <p>Submitting, please wait...</p>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button id="button-rating-client" type="submit" class="btn btn-primary" onclick="showSpinner('spinner-rating-client', 'button-rating-client', 'form-rating-client')">Submit Rating</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>





                                        <!-- Rate Client Modal (Technician) -->
                                        <div class="modal fade" id="rateRepairModalTechnician" tabindex="-1" aria-labelledby="rateRepairModalTechnicianLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form id="form-rating-technician" method="POST" action="{{ route('repair_ratings.update', $repair->repairRating->id) }}">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="rateRepairModalTechnicianLabel">Rate Client</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="technician_weighted_score" class="form-label">Score (0-100)</label>
                                                                <input
                                                                    type="number"
                                                                    name="technician_weighted_score"
                                                                    class="form-control"
                                                                    step="0.01"
                                                                    min="0"
                                                                    max="100"
                                                                    value=""
                                                                    oninput="if(this.value > 100) this.value = 100; if(this.value < 0) this.value = 0;">
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="technician_comment" class="form-label">Comment</label>
                                                                <textarea name="technician_comment" class="form-control" rows="4"></textarea>
                                                            </div>
                                                        </div>

                                                        <div class="d-none text-center" id="spinner-rating-technician">
                                                            <div class="spinner-border text-light mt-2" role="status">
                                                                <span class="visually-hidden">Loading...</span>
                                                            </div>
                                                            <p>Submitting, please wait...</p>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button id="button-rating-technician" type="submit" class="btn btn-primary" onclick="showSpinner('spinner-rating-technician', 'button-rating-technician', 'form-rating-technician')">Submit Rating</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        @endif