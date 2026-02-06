                        <div class="tab-pane fade" id="pills-certificates" role="tabpanel" aria-labelledby="pills-certificates-tab" tabindex="0">
                            <div class="d-sm-flex align-items-center justify-content-between mt-3 mb-4">
                                <h3 class="mb-3 mb-sm-0 fw-semibold d-flex align-items-center">Certificates <span class="badge text-bg-secondary fs-2 rounded-4 py-1 px-2 ms-2">{{ ($technician->technicianLinks->count() + $technician->technicianFiles->count()) }}</span></h3>
                                <div class="position-relative">
                                    @if(Auth::user()->is_technician)
                                    <button class="btn btn-sm btn-outline-secondary mt-2" data-bs-toggle="modal"
                                        data-bs-target="#addFilesModal">
                                        <i class="bi bi-pencil-fill me-1"></i>Add Files
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary mt-2" data-bs-toggle="modal" data-bs-target="#addLinkModal">
                                        <i class="bi bi-pencil-fill me-1"></i>Add Link
                                    </button>
                                    @endif
                                    <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y text-dark ms-3"></i>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-12 col-lg-6">
                                    <hr>
                                    <h4 class="pt-4">Files</h4>
                                    @foreach($technician->technicianFiles as $file)
                                    <div class="gallery-item-wrapper" data-item-id="6">
                                        <a href="{{ route('getFile2', $file->file_path) }}"
                                            target="_blank" class="gallery-item d-block shadow-sm">
                                            <div class="overlay">{{ $file->file_name }}</div>
                                            <p><small>{{ $file->file_description }}</small></p>

                                            @if(in_array(pathinfo($file->file_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg']))
                                            <img src="{{ route('getFile2', $file->file_path) }}" alt="{{ $file->file_name }}" class="img-fluid">
                                            @elseif(pathinfo($file->file_path, PATHINFO_EXTENSION) === 'pdf')
                                            <embed src="{{ route('getFile2', $file->file_path) }}" type="application/pdf" width="100%" height="600px" title="{{ $file->file_name }}" />
                                            @else
                                            <p>Unsupported file type. <a href="{{ route('getFile2', $file->file_path) }}" target="_blank">Download</a></p>
                                            @endif
                                        </a>
                                        @if(Auth::user()->is_technician)
                                        <div class="d-flex justify-content-center mt-2">
                                            <!-- <button class="btn btn-sm btn-outline-primary me-2 edit-gallery-btn" data-bs-toggle="modal" data-bs-target="#editPortfolioItemModal" data-item-id="6">Edit</button> -->
                                            <button class="btn btn-sm btn-outline-danger delete-gallery-btn"
                                                data-url="{{ route('technician-files.destroy', $file->id) }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#confirmDeleteModal">
                                                Delete
                                            </button>
                                        </div>
                                        @endif

                                    </div>
                                    @endforeach
                                </div>



                                <div class="col-12 col-lg-6">
                                    <hr>
                                    <h4 class="pt-4">Links</h4>
                                    @foreach($technician->technicianLinks as $link)

                                    <div class="gallery-item-wrapper" data-item-id="{{ $link->id }}">
                                        <a href="{{ $link->url }}" target="_blank" class="gallery-item d-block shadow-sm text-primary">
                                            <div class="overlay">{{ $link->url }}</div>
                                            <p><small>{{ ucfirst($link->type) }}</small></p>
                                        </a>
                                        @if(Auth::user()->is_technician)
                                        <div class="d-flex justify-content-center mt-2">
                                            <!-- <button class="btn btn-sm btn-outline-primary me-2 edit-gallery-btn" data-bs-toggle="modal" data-bs-target="#editLinkModal" data-item-id="{{ $link->id }}">Edit</button> -->
                                            <button class="btn btn-sm btn-outline-danger delete-gallery-btn"
                                                data-url="{{ route('technician-links.destroy', $link->id) }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#confirmDeleteModal">
                                                Delete
                                            </button>


                                        </div>
                                        @endif


                                    </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>

                        @if(Auth::user()->is_technician)
                        <!-- Modal for adding files -->
                        <div class="modal fade" id="addFilesModal" tabindex="-1" aria-labelledby="addFilesModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addFilesModalLabel">Add Profile Information</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('technician-files.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="file_name" class="form-label">File Name</label>
                                                <input type="text" class="form-control" name="file_name" id="file_name" placeholder="e.g., TESDA Certificate, Laptop Repair">
                                            </div>
                                            <div class="mb-3">
                                                <label for="file_description" class="form-label">File Description</label>
                                                <input type="text" class="form-control" name="file_description" id="file_description" placeholder="Description....">
                                            </div>

                                            <div class="mb-3">
                                                <label for="file" class="form-label">File</label>
                                                <input type="file" name="file" class="form-control" id="file">
                                            </div>

                                            <input type="hidden" name="technician_id" id="technician_id" value="{{ $technician->id }}">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                        <!-- Link Modal -->
                        <div class="modal fade" id="addLinkModal" tabindex="-1" aria-labelledby="addLinkModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addLinkModalLabel">Add Technician Link</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('technician-links.store') }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="url" class="form-label">Link URL</label>
                                                <input type="url" name="url" class="form-control" id="url" placeholder="Enter URL" required>
                                            </div>
                                            <input type="hidden" name="technician_id" value="{{ $technician->id }}">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete this item?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form id="deleteForm" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const deleteButtons = document.querySelectorAll('.delete-gallery-btn');
                                const deleteForm = document.getElementById('deleteForm');

                                deleteButtons.forEach(button => {
                                    button.addEventListener('click', function() {
                                        const url = this.getAttribute('data-url');
                                        deleteForm.setAttribute('action', url);
                                    });
                                });
                            });
                        </script>


                        @endif