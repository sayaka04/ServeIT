<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Client Messaging Interface" />
    <meta name="author" content="" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <title>Chat - {{ $client->first_name }} {{ $client->middle_name }} {{ $client->last_name }}</title>

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <link href="{{ asset('css/shortened-sidebar.css')}}" rel="stylesheet" />
    <script defer src="{{ asset('js/scripts.js') }}"></script>


    <script defer src="{{ asset('js/chat/AjaxHandler.js') }}"></script>

    <script defer src="{{ asset('js/chat/Main.js') }}"></script>

    @if(Auth::user()->is_technician)
    <script defer src="{{ asset('js/chat/append-handler-technician.js') }}"></script>
    @else
    <script defer src="{{ asset('js/chat/append-handler-client.js') }}"></script>
    @endif


    @vite(['resources/js/app.js'])

    @include('partials/bootstrap')


</head>

<body class="sb-nav-fixed">

    @include('partials/navigation-bar')

    <div id="layoutSidenav">

        @include('partials/sidebar')

        <div id="layoutSidenav_content" class="d-flex flex-column min-vh-100">

            <main class="flex-grow-1 d-flex flex-column">


                <div class="fixed-top border-top mt-5 pt-3 bg-primary shadow-lg">
                    <div class="container">
                        @if(Auth::user()->is_technician)
                        <h6><i class="bi bi-person-circle text-white"></i> <a href="{{ route('users.show', $client->id) }}">{{ $client->first_name }} {{ $client->middle_name }} {{ $client->last_name }} | (Client)</a></h6>
                        @else
                        <h6><i class="bi bi-person-circle text-white"></i> <a href="{{ route('technicians.show', $technician->technician->technician_code) }}">{{ $technician->first_name }} {{ $technician->middle_name }} {{ $technician->last_name }} (Technician)</a></h6>
                        @endif

                    </div>
                </div>

                <input type="hidden" id="conversasation_id_input" name="custId" value="{{ $conversation->id }}">


                <br><br><br><br>
                <div class="container-fluid flex-grow-1 overflow-auto pb-5 mb-2">
                    <div class="card shadow-sm h-100">
                        <div id="conversation" class="card-body d-flex flex-column justify-content-end">

                            @if(!$is_all)
                            <a href="{{ route('conversations.showAll', $conversation) }}" class="btn btn-primary">See all</a>
                            @endif

                            @foreach ($messages as $message)
                            @php
                            $isCurrentUser = $message->sender_id == Auth::id();
                            $alignmentClass = $isCurrentUser ? 'justify-content-end text-start' : 'justify-content-start text-start';
                            $bgColor = $isCurrentUser ? 'bg-primary' : 'bg-secondary';

                            if ($message->repair) {
                            $alignmentClass = 'justify-content-center text-end';
                            $bgColor = 'bg-info w-50 text-center';
                            }
                            @endphp

                            <div class="d-flex {{ $alignmentClass }} p-2">

                                @php
                                try {
                                $decryptedMessage = Crypt::decryptString($message->message);
                                } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                                // If decryption fails, assume the message is not encrypted
                                // and display the original content.
                                $decryptedMessage = $message->message;
                                }
                                @endphp
                                <div class="card d-inline-block text-wrap text-break p-2 text-white {{ $bgColor }}" style="max-width: 70%;">
                                    {{-- Message Content --}}
                                    <pre style="white-space: pre-wrap; word-break: break-word;">{{ $decryptedMessage }}</pre>
                                    {{-- Timestamp --}}
                                    <small class="text-white opacity-75">
                                        {{ $message->created_at->format('h:i A - d M Y') }}
                                    </small>

                                    {{-- Image (if any) --}}
                                    @if ($message->image_path)
                                    <hr class="border-light">
                                    <div>
                                        <img src="{{ route('getFile2', ['filename' => $message->image_path]) }}" alt="{{ $message->image_name ?? 'Image' }}" class="img-thumbnail mb-1">
                                        @if($message->image_name)
                                        <div class="small text-white-50">📷 {{ $message->image_name }}</div>
                                        @endif
                                    </div>
                                    @endif

                                    {{-- File (if any) --}}
                                    @if ($message->file_path)
                                    <hr class="border-light">
                                    <div class="small">
                                        📎 <a href="{{ route('getFile2', ['filename' => $message->file_path]) }}" class="text-white text-decoration-underline" download>
                                            {{ $message->file_name ?? 'Download file' }}
                                        </a>
                                        @if($message->file_type)
                                        <span class="text-white-50">({{ strtoupper($message->file_type) }})</span>
                                        @endif
                                    </div>
                                    @endif

                                    {{-- URL (if any) --}}
                                    @if ($message->url)
                                    <hr class="border-light">
                                    <div class="small">
                                        🔗 <a href="{{ $message->url }}" target="_blank" rel="noopener" class="text-white text-decoration-underline">
                                            {{ $message->url_name ?? parse_url($message->url, PHP_URL_HOST) }}
                                        </a>
                                        @if($message->url_domain)
                                        <div class="text-white-50">🌐 {{ $message->url_domain }}</div>
                                        @endif
                                    </div>
                                    @endif

                                    {{-- Repair Info --}}
                                    @if ($message->repair)
                                    <hr class="border-light">
                                    <div class="card bg-dark text-white p-2 text-center">
                                        <div class="card-body p-2">
                                            <h6 class="card-title mb-2">🔧 Repair Info</h6>
                                            <!-- <p class="mb-1"><strong>Issue:</strong> {{ $message->repair->issue }}</p>


                                            <p class="mb-1"><strong>Description:</strong>
                                            <pre style="white-space: pre-wrap; word-break: break-word; text-align: justify;">{{ $message->repair->description }}</pre>
                                            </p> -->

                                            <p class="mb-1"><strong>Device:</strong> {{ $message->repair->device }} {{--({{ $message->repair->device_type }})--}}</p>
                                            <p class="mb-1"><strong>Device Type:</strong> {{ $message->repair->device_type }}</p>
                                            <p class="mb-1"><strong>Status:</strong> <span id="repair-status-{{ $message->repair->id }}">{{ $message->repair->status }}</span></p>
                                            <!-- <p class="mb-1"><strong>Estimated Cost:</strong> {{ $message->repair->estimated_cost ? '$' . number_format($message->repair->estimated_cost, 2) : 'N/A' }}</p>
                                            @if ($message->repair->completion_date)
                                            <p class="mb-1"><strong>Completed:</strong> {{ \Carbon\Carbon::parse($message->repair->completion_date)->format('d M Y') }}</p>
                                            @endif
                                            <p class="mb-1"><strong>Technician ID:</strong> {{ $message->repair->technician_id ?? 'N/A' }}</p> -->
                                            <a class="btn btn-primary" href="{{ route('repairs.show', $message->repair->id) }}">View Repair</a>
                                            @if (!Auth::user()->is_technician && $message->repair->status === 'pending')
                                            <div class="mt-2" id="repair-buttons-{{ $message->repair->id }}">
                                                <!-- Accept Button -->
                                                <button class="btn btn-sm btn-success me-1" data-bs-toggle="modal" data-bs-target="#confirmAcceptModal{{ $message->repair->id }}">
                                                    ✅ Accept
                                                </button>

                                                <!-- Decline Button -->
                                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeclineModal{{ $message->repair->id }}">
                                                    ❌ Decline
                                                </button>

                                                <!-- Accept Modal -->
                                                <!-- Accept Modal -->
                                                <div class="modal fade" id="confirmAcceptModal{{ $message->repair->id }}" tabindex="-1" aria-labelledby="confirmAcceptLabel" aria-hidden="true" data-bs-backdrop="false">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content bg-dark text-white">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Confirm Accept</h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to <strong>ACCEPT</strong> this repair?
                                                                <!-- Status Message -->
                                                                <div id="statusMessage-accept-{{ $message->repair->id }}" class="mt-2 text-warning small"></div>
                                                                <div class="spinner-border text-light mt-2 d-none mx-auto" role="status" id="spinner-accept-{{ $message->repair->id }}">
                                                                    <span class="visually-hidden">Loading...</span>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer flex-column align-items-between">
                                                                <div class="w-100 d-flex justify-content-between">
                                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="button" class="btn btn-primary" id="acceptButton-{{ $message->repair->id }}"
                                                                        onclick="repairAction(`{{ route('repairs.accept', $message->repair->id) }}`, '{{ $message->repair->id }}', 'accept')">
                                                                        Yes, Accept
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <!-- Decline Modal -->
                                                <!-- Decline Modal -->
                                                <div class="modal fade" id="confirmDeclineModal{{ $message->repair->id }}" tabindex="-1" aria-labelledby="confirmDeclineLabel" aria-hidden="true" data-bs-backdrop="false">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content bg-dark text-white">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Confirm Decline</h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to <strong>DECLINE</strong> this repair?
                                                                <!-- Status Message -->
                                                                <div id="statusMessage-decline-{{ $message->repair->id }}" class="mt-2 text-warning small"></div>
                                                                <div class="spinner-border text-light mt-2 d-none mx-auto" role="status" id="spinner-decline-{{ $message->repair->id }}">
                                                                    <span class="visually-hidden">Loading...</span>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer flex-column align-items-between">
                                                                <div class="w-100 d-flex justify-content-between">
                                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="button" class="btn btn-danger" id="declineButton-{{ $message->repair->id }}"
                                                                        onclick="repairAction(`{{ route('repairs.decline', $message->repair->id) }}`, '{{ $message->repair->id }}', 'decline')">
                                                                        Yes, Decline
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif

                                </div>
                            </div>
                            @endforeach

                        </div>
                    </div>
                </div>

                <script>
                    function repairAction(url, repairId, action) {
                        const btnId = action === 'accept' ? `acceptButton-${repairId}` : `declineButton-${repairId}`;
                        const spinnerId = `spinner-${action}-${repairId}`;
                        const messageId = `statusMessage-${action}-${repairId}`;
                        const modalId = action === 'accept' ? `#confirmAcceptModal${repairId}` : `#confirmDeclineModal${repairId}`;

                        const button = document.getElementById(btnId);
                        const spinner = document.getElementById(spinnerId);
                        const message = document.getElementById(messageId);

                        // UI: disable button, show spinner, reset message
                        if (button) button.disabled = true;
                        if (spinner) spinner.classList.remove('d-none');
                        if (message) {
                            message.innerText = 'Processing... Please wait.';
                            message.classList.remove('text-danger', 'text-success');
                            message.classList.add('text-warning');
                        }

                        fetch(url, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => {
                                if (!response.ok) throw new Error('Failed request');
                                return response.json();
                            })
                            .then(data => {
                                if (message) {
                                    message.innerText = action === 'accept' ?
                                        'Repair successfully accepted!' :
                                        'Repair successfully declined!';
                                    message.classList.remove('text-warning', 'text-danger');
                                    message.classList.add('text-success');
                                }

                                // Close modal after delay
                                setTimeout(() => {
                                    const modalEl = document.querySelector(modalId);
                                    const modal = bootstrap.Modal.getInstance(modalEl);
                                    if (modal) modal.hide();

                                    // Optionally remove action buttons
                                    const buttonWrapper = document.querySelector(`#repair-buttons-${repairId}`);
                                    if (buttonWrapper) buttonWrapper.remove();

                                    if (spinner) spinner.classList.add('d-none');
                                    if (button) button.disabled = false;
                                    if (message) message.innerText = '';
                                }, 1000);
                            })
                            .catch(error => {
                                console.error(error);

                                if (spinner) spinner.classList.add('d-none');
                                if (button) button.disabled = false;

                                if (message) {
                                    message.innerText = 'An error occurred. Please try again.';
                                    message.classList.remove('text-warning', 'text-success');
                                    message.classList.add('text-danger');
                                }
                            });
                    }
                </script>






                <div class="fixed-bottom bg-white border border-light border-order-1 p-3 shadow-lg">
                    <div class="container">
                        <div class="row gx-2 align-items-end flex-nowrap">
                            <div class="col-auto dropup dropdown">
                                <button class="btn btn-outline-secondary rounded-circle p-2 position-relative"
                                    id="dropdownActionsClient" data-bs-toggle="dropdown" aria-expanded="false" title="Attach File or More">
                                    <i class="bi bi-plus-lg fs-5 d-flex align-items-center justify-content-center"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display:none;"></span>
                                </button>

                                <ul class="dropdown-menu mb-2" aria-labelledby="dropdownActionsClient">
                                    <li>
                                        <button type="button" class="dropdown-item position-relative" data-bs-toggle="modal" data-bs-target="#modalAttachImage">
                                            <i class="bi bi-image me-2"></i>Attach Image
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display:none;"></span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item position-relative" data-bs-toggle="modal" data-bs-target="#modalAttachFile">
                                            <i class="bi bi-paperclip me-2"></i>Attach File
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display:none;"></span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item position-relative" data-bs-toggle="modal" data-bs-target="#modalInsertLink">
                                            <i class="bi bi-link-45deg me-2"></i>Insert Link
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display:none;"></span>
                                        </button>
                                    </li>
                                    {{--
                                    @if(Auth::user() && Auth::user()->is_technician)
                                    <li>
                                        <hr class="dropdown-divider d-block m-2">
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item position-relative" data-bs-toggle="modal" data-bs-target="#modalRepair">
                                            <i class="bi bi-tools me-2"></i>Send Repair Proposal
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display:none;"></span>
                                        </button>
                                    </li>
                                    @endif
                                    --}}
                                    <li>
                                </ul>
                            </div>



                            <div class="col">
                                <textarea id="message" class="form-control" rows="1" placeholder="Type a message..."></textarea>
                            </div>

                            <div class="col-auto">
                                <button id="sendButton" type="button" class="btn btn-primary rounded-circle p-2">
                                    <i class="bi bi-send-fill fs-5 d-flex align-items-center justify-content-center"></i>
                                </button>

                            </div>
                        </div>
                    </div>
                </div>

            </main>

            @include('conversation/partials/modals')


            @include('partials/footer')

        </div>
    </div>



    <!-- <div x-init="
            Echo.private('converse.{{$conversation->id}}')
                .listen('Converse', (event) => {
                    alert('Private Converse event received!');
                    console.log('Event:', event);
                });
"></div> -->


    <!-- <div x-init="



    Echo.private('converse.{{$conversation->id}}')
        .listen('Converse', (event) => {
            console.log('📩 Full message:', event.message);
            console.log('📩 Full repair:', event.repair);

            const messageObj = event.message;  // full message object
            const repairObj = event.repair;  // full repair object
            const userId = messageObj.sender_id;
            const timestamp = messageObj.created_at;
            const currentUserId = {{ Auth::id() }};
            const isCurrentUser = currentUserId === userId;

            appendMessage(messageObj, repairObj, userId, timestamp, isCurrentUser);


            // code here
        });
"></div> -->


    <div x-data="{ onlineMembers: [], isOtherUserOnline: false }" x-init="
    Echo.join('converse.{{ $conversation->id }}')
        .here((users) => {
            this.onlineMembers = users;
            this.isOtherUserOnline = this.onlineMembers.length === 2;
            console.log('Online members on join:', this.onlineMembers);
        })
        .joining((user) => {
            this.onlineMembers.push(user);
            this.isOtherUserOnline = true;
            console.log(user.name + ' has joined.');
        })
        .leaving((user) => {
            this.onlineMembers = this.onlineMembers.filter(u => u.id !== user.id);
            this.isOtherUserOnline = false;
            console.log(user.name + ' has left.');
        });

    Echo.private('converse.{{$conversation->id}}')
        .listen('Converse', (event) => {
            console.log('📩 Full message:', event.message);
            console.log('📩 Full repair:', event.repair);

            const messageObj = event.message;
            const repairObj = event.repair;
            const userId = messageObj.sender_id;
            const timestamp = messageObj.created_at;
            const currentUserId = {{ Auth::id() }};
            const isCurrentUser = currentUserId === userId;

            appendMessage(messageObj, repairObj, userId, timestamp, isCurrentUser);

            if (!this.isOtherUserOnline) {
                updateLastSeen();
            } else {
                console.log('Other user is online, message is considered seen.');
            }
        });
"></div>



    <script>
        // Separate function to perform the last seen update
        function updateLastSeen() {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const url = '/conversations/{{ $conversation->id }}/update-last-seen';

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({}) // Sending an empty JSON object
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Last seen updated:', data);
                })
                .catch(error => {
                    console.error('Error updating last seen:', error);
                });
        }
    </script>

    <!-- <div x-data="{ onlineMembers: [] }" x-init="

    Echo.join('converse.{{ $conversation->id }}')
        .here((users) => {
            // This event fires immediately upon joining and gives you all users currently in the channel.
            this.onlineMembers = users;
            console.log('Online members:', this.onlineMembers);
            
            // This is how you check if the other user is present
            const otherUserId = {{ Auth::user()->id === $conversation->user_id ? $conversation->technician_user_id : $conversation->user_id }};
            const isOtherUserOnline = this.onlineMembers.some(user => user.id === otherUserId);
            
            if (!isOtherUserOnline) {
                console.log('The other user is not currently present in the chat.');
            }
        })
        .joining((user) => {
            // Fires when a new user joins
            this.onlineMembers.push(user);
        })
        .leaving((user) => {
            // Fires when a user leaves
            this.onlineMembers = this.onlineMembers.filter(u => u.id !== user.id);
        })
        .listen('Converse', (event) => {
            // Your existing message handling logic
            console.log('📩 Full message:', event.message);
            // ...
        });
"></div> -->




    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sendBtn = document.getElementById('sendButton');
            const messageInput = document.getElementById('message');
            const url = "{{ route('messages.store') }}";

            window.conversationCode = "{{ $conversation->conversation_code }}";


            let isSending = false;

            // Send message function wrapper with button disable/enable
            async function handleSend() {
                if (isSending) return;

                isSending = true;
                sendBtn.disabled = true;

                try {
                    await sendMessage(url); // your existing async sendMessage function
                    messageInput.value = ''; // clear on success
                } catch (error) {
                    console.error('Send failed', error);
                    // optionally show error to user
                } finally {
                    isSending = false;
                    sendBtn.disabled = false;
                    messageInput.focus();
                }
            }

            // Click event on send button
            sendBtn.addEventListener('click', () => {
                handleSend();
            });

            // Keyboard event for Enter and Shift+Enter handling
            messageInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault(); // prevent newline on enter
                    handleSend();
                }
            });
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Scrolls the entire viewport to the maximum height possible
            window.scrollTo(0, document.body.scrollHeight);
        });
    </script>


    <script defer src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="anonymous"></script>


    @php
    $debug = config('app.debug');
    @endphp

    <script>
        window.APP_DEBUG = "{{ config('app.debug') ? 'true' : 'false' }}";
    </script>





</body>

</html>