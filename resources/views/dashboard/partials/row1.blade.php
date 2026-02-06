                                    @if(Auth::user()->is_technician)
                                    <!-- 1st Row: Information Cards -->
                                    <div class="row mb-4">
                                        <!-- Ongoing Repairs Card -->
                                        <div class="col-md-6 col-lg-4 mb-4">
                                            <div class="card text-dark h-100 shadow-sm border border-info bg-info-subtle">
                                                <div class="card-body">
                                                    <h5 class="card-title fw-semibold">Ongoing Repairs</h5>
                                                    <p class="card-text display-4 fw-bold text-end">{{ $ongoing_repairs_count }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Unclaimed Repairs Card -->
                                        <div class="col-md-6 col-lg-4 mb-4">
                                            <div class="card text-dark h-100 shadow-sm border border-warning bg-warning-subtle">
                                                <div class="card-body">
                                                    <h5 class="card-title fw-semibold">Unclaimed Repairs</h5>
                                                    <p class="card-text display-4 fw-bold text-end">{{ $unclaimed_repairs_count }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        {{--

                                        <!-- Inquiries Card -->
                                        <div class="col-md-6 col-lg-3 mb-4">
                                            <div class="card text-dark h-100 shadow-sm border border-success bg-success-subtle">
                                                <div class="card-body">
                                                    <h5 class="card-title fw-semibold">Inquiries</h5>
                                                    <p class="card-text display-4 fw-bold">3(?)</p>
                                                </div>
                                            </div>
                                        </div>
    --}}

                                        <!-- Overdue Repairs Card -->
                                        <div class="col-md-6 col-lg-4 mb-4">
                                            <div class="card text-dark h-100 shadow-sm border border-danger bg-danger-subtle">
                                                <div class="card-body">
                                                    <h5 class="card-title fw-semibold">Overdue Repairs</h5>
                                                    <p class="card-text display-4 fw-bold text-end">{{ $overdue_repairs_count }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @else

                                    <!-- 1st Row: Information Cards -->
                                    <div class="row mb-4">
                                        <!-- Ongoing Repairs Card -->
                                        <div class="col col-12 col-lg-6 p-2">
                                            <div class="card text-dark h-100 shadow-sm border border-info bg-info-subtle">
                                                <div class="card-body">
                                                    <h5 class="card-title fw-semibold">Ongoing Repairs</h5>
                                                    <p class="card-text display-4 fw-bold text-end">{{ $ongoing_repairs_count }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Unclaimed Repairs Card -->
                                        <div class="col col-12 col-lg-6 p-2">
                                            <div class="card text-dark h-100 shadow-sm border border-warning bg-warning-subtle">
                                                <div class="card-body">
                                                    <h5 class="card-title fw-semibold">Unclaimed Repairs</h5>
                                                    <p class="card-text display-4 fw-bold text-end">{{ $unclaimed_repairs_count }}</p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    @endif