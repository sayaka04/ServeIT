<!-- Display received notes and file if the device has been marked as received -->

<br><br>
@if ($repair->is_received)
<div class="p-3 shadow">
    <div class="card mb-4 shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="bi bi-box2-fill"></i> Device Reception Details
            </h4>
        </div>

        <div class="card-body">

            {{-- 1. Display Received Notes --}}
            @if ($repair->receive_notes)
            <div class="mb-3">
                <p class="mb-1"><strong>Received Notes:</strong></p>
                <div class="alert bg-white text-primary py-2">
                    {{ $repair->receive_notes }}
                </div>
            </div>
            @endif

            {{-- 2. Display Received File/Media --}}
            @if ($repair->receive_file_path)
            @php
            // NOTE: Double-check the spelling of 'recieve_file_path' (should be 'receive_file_path')
            $extension = pathinfo($repair->receive_file_path, PATHINFO_EXTENSION);
            @endphp

            <div class="mb-3">
                <p class="mb-2"><strong>Received Media/File:</strong></p>
                <div class="d-flex justify-content-center border p-2 rounded bg-white">
                    @if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                    <img src="{{ route('getFile2', ['filename' => $repair->receive_file_path]) }}" alt="Received Image" class="img-fluid rounded" style="max-width: 400px; max-height: 400px; object-fit: cover;">

                    @elseif (in_array(strtolower($extension), ['mp4', 'mov', 'avi', 'wmv', 'mkv', 'flv']))
                    {{-- Set max-width for consistency with image display --}}
                    <video controls class="img-fluid rounded" style="max-width: 400px;">
                        <source src="{{ route('getFile2', ['filename' => $repair->receive_file_path]) }}" type="video/{{ $extension }}">
                        Your browser does not support the video tag.
                    </video>

                    @else
                    {{-- Better button styling for files --}}
                    <a href="{{ route('getFile2', ['filename' => $repair->receive_file_path]) }}" target="_blank" class="btn btn-primary">
                        <i class="fas fa-download me-2"></i> Download File ({{ strtoupper($extension) }})
                    </a>
                    @endif
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endif
<br><br>