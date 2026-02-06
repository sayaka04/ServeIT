            @if($repair_progress->first())
            @php
            $rate = $repair_progress->first()->completion_rate;
            $color = match(true) {
            $rate < 25=> 'danger',
                $rate < 50=> 'warning',
                    $rate < 75=> 'success',
                        default => 'info'
                        };
                        @endphp

                        <h5 class="mt-4 mb-3">Progress</h5>
                        <div class="progress mb-3">
                            <div class="progress-bar progress-bar-striped bg-{{ $color }}"
                                role="progressbar"
                                style="width: {{ $rate }}%"
                                aria-valuenow="{{ $rate }}"
                                aria-valuemin="0"
                                aria-valuemax="100">
                                {{ $rate }}%
                            </div>
                        </div>
                        @endif