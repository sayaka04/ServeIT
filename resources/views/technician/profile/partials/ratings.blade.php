                        <div class="tab-pane fade show active" id="pills-ratings" role="tabpanel" aria-labelledby="pills-ratings-tab" tabindex="0">
                            <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-4">
                                <h3 class="mb-3 mb-sm-0 fw-semibold d-flex align-items-center">
                                    Ratings <span class="badge text-bg-secondary fs-2 rounded-4 py-1 px-2 ms-2">{{ $technician->repairRatings->count() }}
                                    </span>
                                </h3>
                                <form class="position-relative">
                                    <!-- <input type="text" class="form-control search-chat py-2 ps-5" id="text-srh" placeholder="Search reviews"> -->
                                    <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y text-dark ms-3"></i>
                                </form>
                            </div>
                            <div class="row">
                                @foreach($technician->repairRatings as $rating)
                                @if(!is_null($rating->user_weighted_score) && !is_null($rating->technician_weighted_score))
                                @php
                                $user = $rating->user ?? null;
                                $profilePicture = $user && $user->profile_picture
                                ? route('getFile2', $user->profile_picture)
                                : route('getFile2', 'Default_Profile_Picture.png');

                                $score = $rating->user_weighted_score;
                                $fullStars = floor($score / 20);
                                $halfStar = ($score % 20) >= 10;
                                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                @endphp

                                <div class="col-lg-6 col-md-12 review-item mb-4" data-review-id="{{ $rating->id }}">
                                    <div class="card review-card p-3 h-100 shadow-sm">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="d-flex align-items-center">
                                                <img
                                                    src="{{ $profilePicture }}"
                                                    alt="User Avatar"
                                                    class="rounded-circle me-3"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-0 fw-semibold">
                                                        {{ $user->first_name ?? 'Unknown' }}
                                                        {{ $user->middle_name ?? '' }}
                                                        {{ $user->last_name ?? '' }}
                                                    </h6>
                                                    <small class="text-muted">
                                                        {{ $rating->created_at->format('M d, Y') }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="rating-stars text-warning" aria-label="User rating">
                                                @for ($i = 0; $i < $fullStars; $i++)
                                                    <i class="bi bi-star-fill"></i>
                                                    @endfor

                                                    @if ($halfStar)
                                                    <i class="bi bi-star-half"></i>
                                                    @endif

                                                    @for ($i = 0; $i < $emptyStars; $i++)
                                                        <i class="bi bi-star"></i>
                                                        @endfor
                                            </div>
                                        </div>

                                        <p class="card-text text-muted small mb-2">
                                            "{{ $rating->user_comment }}"
                                        </p>

                                        <div class="small">
                                            <strong>Weighted Rating:</strong> {{ $score }}
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>




                        </div>