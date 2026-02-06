<div id="layoutSidenav_nav">
    <nav class="sb-sidenav sb-sidenav-dark  d-flex flex-column justify-content-between"
        id="sidenavAccordion" style="height: 100vh;">

        <!-- Sidebar top content: navigation -->
        <div>
            <div class="collapse show" id="sidenavMenu">
                <div class="list-group list-group-flush">

                    <!-- Dashboard -->
                    <a href="{{ route('dashboard.index') }}"
                        class="list-group-item list-group-item-action bg-dark text-white d-flex align-items-center border-0">
                        @if (Route::currentRouteName() == 'dashboard.index')
                        <i class="bi bi-speedometer bg-black text-white rounded me-2"></i> Dashboard
                        @else
                        <i class="bi bi-speedometer me-2"></i> Dashboard
                        @endif
                    </a>

                    <!-- Browse -->
                    @if(!Auth::user()->is_admin && !Auth::user()->is_admin_supervisor && !Auth::user()->is_technician)



                    <a href="{{ route('search') }}"
                        class="list-group-item list-group-item-action bg-dark text-white d-flex align-items-center border-0">
                        @if (Route::currentRouteName() == 'search')
                        <i class="bi bi-search me-2 text-white bg-black rounded"></i> Find Technicians
                        @else
                        <i class=" bi bi-search me-2"></i> Find Technicians
                        @endif
                    </a>
                    @endif


                    <!-- Notifications (collapsible) -->
                    @php
                    use App\Models\Notification;
                    $unreadCount = Notification::where('recipient', auth()->id())
                    ->where('has_seen', false)
                    ->count();
                    @endphp



                    @if(!Auth::user()->is_admin)
                    <a href="{{ route('notifications.index') }}"
                        class="list-group-item list-group-item-action bg-dark text-white d-flex align-items-center border-0">
                        @if (Route::currentRouteName() == 'notifications.index')
                        <i class="bi bi-bell-fill me-2"></i> Notifications
                        @else
                        <i class="bi bi-bell me-2"></i> Notifications
                        @endif
                        @if($unreadCount > 0)
                        <span class="badge bg-danger ms-2">{{ $unreadCount }}</span>
                        @endif
                    </a>
                    @endif


                    <!-- Browse -->
                    @if(Auth::user()->is_admin)
                    <a href="{{ route('admins.index') }}"
                        class="list-group-item list-group-item-action bg-dark text-white d-flex align-items-center border-0">
                        @if (Route::currentRouteName() == 'admins.index')
                        <i class="bi bi-person-fill-gear me-2"></i> Admins
                        @else
                        <i class="bi bi-person-gear me-2"></i> Admins
                        @endif
                    </a>
                    <a href="{{ route('users.index') }}"
                        class="list-group-item list-group-item-action bg-dark text-white d-flex align-items-center border-0">
                        @if (Route::currentRouteName() == 'users.index')
                        <i class="bi bi-person-fill me-2"></i> Users
                        @else
                        <i class="bi bi-person me-2"></i> Users
                        @endif
                    </a>
                    <a href="{{ route('reports.index') }}"
                        class="list-group-item list-group-item-action bg-dark text-white d-flex align-items-center border-0">
                        @if (Route::currentRouteName() == 'reports.index')
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> Reports
                        @else
                        <i class="bi bi-exclamation-triangle me-2"></i> Reports
                        @endif
                    </a>
                    <a href="{{ route('expertise-categories.index') }}"
                        class="list-group-item list-group-item-action bg-dark text-white d-flex align-items-center border-0">
                        @if (Route::currentRouteName() == 'expertise-categories.index')
                        <i class="bi bi-tools bg-black rounded text-white me-2"></i> Expertise Categories
                        @else
                        <i class="bi bi-tools me-2"></i> Expertise Categories
                        @endif
                    </a>
                    @endif




                    @if(!Auth::user()->is_admin)
                    <!-- Messages (collapsible) -->
                    <a href="{{ route('conversations.index') }}"
                        class="list-group-item list-group-item-action bg-dark text-white d-flex align-items-center border-0">
                        @if (Route::currentRouteName() == 'conversations.index')
                        <i class="bi bi-chat-dots-fill me-2"></i> Messages
                        @else
                        <i class="bi bi-chat-dots me-2"></i> Messages
                        @endif
                    </a>
                    @endif









                    <!-- Repairs (collapsible) -->
                    @php
                    if (Auth::user()->is_technician) {
                    $technician = Auth::user()->technician;
                    $onGoingRepair = 0;

                    if ($technician) {
                    $onGoingRepair = \App\Models\Repair::where('technician_id', $technician->id)
                    ->where('is_claimed', null)
                    ->where('is_cancelled', 0)
                    ->where('status', '!=', 'declined')
                    ->count();
                    }
                    } else {
                    $onGoingRepair = \App\Models\Repair::where('user_id', Auth::id())
                    ->where('is_claimed', null)
                    ->where('is_cancelled', 0)
                    ->where('status', '!=', 'declined')
                    ->count();
                    }
                    @endphp


                    @if(!Auth::user()->is_admin && !Auth::user()->is_admin_supervisor)
                    <a href="{{ route('repairs.index') }}"
                        class="list-group-item list-group-item-action bg-dark text-white d-flex align-items-center border-0">
                        @if (Route::currentRouteName() == 'repairs.index')
                        <i class="bi bi-tools bg-black text-white rounded me-2"></i> Repairs
                        @else
                        <i class="bi bi-tools me-2"></i> Repairs
                        @endif
                        @if($onGoingRepair > 0)
                        <span class="badge bg-danger ms-2">{{ $onGoingRepair }}</span>
                        @endif
                    </a>

                    <a href="{{ route('repairs.history') }}"
                        class="list-group-item list-group-item-action bg-dark text-white d-flex align-items-center border-0">
                        @if (Route::currentRouteName() == 'repairs.history')
                        <i class="bi bi-archive-fill me-2"></i> Repairs History
                        @else
                        <i class="bi bi-archive me-2"></i> Repairs History
                        @endif
                    </a>
                    @endif

                    {{--
                    @php
                    use App\Models\RepairCancelRequest;

                    $pendingCancelRequests = \App\Models\RepairCancelRequest::where('approver_id', Auth::id())
                    ->whereNull('is_accepted')
                    ->count();
                    @endphp



                    @if(!Auth::user()->is_admin && !Auth::user()->is_admin_supervisor)
                    <a href="{{ route('repair-cancel-requests.index') }}"
                    class="list-group-item list-group-item-action bg-dark text-white d-flex align-items-center border-0">
                    @if (Route::currentRouteName() == 'repair-cancel-requests.index')
                    <i class="bi bi-x-circle-fill me-2"></i> Request Cancelation
                    @else
                    <i class="bi bi-x-circle me-2"></i> Request Cancelation
                    @endif
                    @if($pendingCancelRequests > 0)
                    <span class="badge bg-danger ms-2">{{ $pendingCancelRequests }}</span>
                    @endif
                    </a>
                    @endif
                    --}}




                </div>
            </div>
        </div>

        <!-- Sidebar Footer pinned at bottom -->
        <div class="sb-sidenav-footer text-black bg-light small ps-2 pt-3 border-top">
            @if(Auth::user()->is_technician)
            Technician:
            @elseif(Auth::user()->is_admin)
            Admin:
            @else
            Client:
            @endif
            <br />
            <div class="text-truncate">
                {{ Auth::user()->first_name }}
                {{ Auth::user()->middle_name }}
                {{ Auth::user()->last_name }}
            </div>
            <br />
            Email:<br />
            <div class="text-truncate">
                {{ Auth::user()->email }}
            </div>
            <!--  -->
            @php
            use App\Models\Technician;
            @endphp

            @php
            $technician = Technician::where('technician_user_id', Auth::id())->first();
            @endphp

            @if ($technician && $technician->tesda_verified)
            <span class="badge bg-success">TESDA Verified</span>
            @elseif($technician)
            <span class="badge bg-danger">TESDA Unverified</span>
            @endif

        </div>
    </nav>
</div>