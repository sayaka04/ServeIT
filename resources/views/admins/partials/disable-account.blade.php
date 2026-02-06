                <!-- Disable/Enable Button -->
                <button class="btn btn-{{ $user->is_disabled ? 'success' : 'danger' }}" data-bs-toggle="modal" data-bs-target="#confirmDisableModal">
                    {{ $user->is_disabled ? 'Enable User' : 'Disable User' }}
                </button>

                <!-- Confirmation Modal -->
                <div class="modal fade" id="confirmDisableModal" tabindex="-1" aria-labelledby="confirmDisableModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('admins.toggleDisable', $user->id) }}">
                                @csrf
                                @method('PATCH')

                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmDisableModalLabel">{{ $user->is_disabled ? 'Enable' : 'Disable' }} User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    Are you sure you want to {{ $user->is_disabled ? 'enable' : 'disable' }} this user?
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-{{ $user->is_disabled ? 'success' : 'danger' }}">
                                        Yes, {{ $user->is_disabled ? 'Enable' : 'Disable' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif