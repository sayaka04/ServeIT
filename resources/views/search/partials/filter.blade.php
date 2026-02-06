<style>
    /* ========================================================
    BOOTSTRAP OVERRIDES (For Accordion in light mode)
    ========================================================
    */
    .accordion-button,
    .accordion-item,
    .accordion-body {
        background-color: #ffffff !important;
        color: #212529 !important;
    }

    .accordion-button:not(.collapsed) {
        background-color: #f8f9fa !important;
        color: #212529 !important;
        box-shadow: none !important;
    }

    .accordion-item {
        border: 1px solid #dee2e6 !important;
    }

    /* Keep dark mode styles light if necessary (Based on previous code) */
    [data-bs-theme="dark"] .accordion-button,
    [data-bs-theme="dark"] .accordion-item,
    [data-bs-theme="dark"] .accordion-body {
        background-color: #ffffff !important;
        color: #212529 !important;
    }

    /* ========================================================
    SIDEBAR COLLAPSE STYLES
    ========================================================
    */

    /* Button Styling */
    .filter-toggle-btn {
        width: 100%;
        margin-bottom: 1rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* RESULTS CONTAINER DYNAMIC WIDTH (Added for smooth transition) */
    @media (min-width: 992px) {
        #technicianResults {
            transition: width 0.35s ease, flex 0.35s ease;
        }
    }


    /* DESKTOP STYLES (>= 992px) */
    @media (min-width: 992px) {

        /* Default (Expanded) state: Use Bootstrap's col-lg-3 width (25%) */
        .filter-sidebar {
            width: 25% !important;
            /* Explicitly set width of col-lg-3 */
            transition: width 0.35s ease, padding 0.35s ease;
            overflow: hidden;
        }

        /* Collapsed state: Simulate col-lg-1 (approx 8.33% width) */
        .filter-collapsed {
            width: 8.33333% !important;
            /* Setting the width to col-lg-1 equivalent */
            padding: 1rem 0.5rem !important;
        }

        /* Hide ALL form content when collapsed (form is inside .filter-sidebar) */
        .filter-collapsed form {
            display: none !important;
        }
    }

    /* RESPONSIVE: small screens (< 992px) */
    @media (max-width: 991.98px) {

        /* On mobile, the sidebar should always be full width */
        .filter-sidebar {
            width: 100% !important;
            /* Use padding for mobile */
            padding: 1rem !important;
            transition: height 0.35s ease, padding 0.35s ease;
            overflow: hidden;
            /* Ensure initial state is responsive */
            height: auto !important;
        }

        /* Ensure the button is visible on mobile */
        #toggleBtnContainer {
            display: block !important;
        }

        /* FIX: When collapsed on mobile, shrink the height to fit just the button. */
        .filter-collapsed {
            /* Reset width to 100% and reduce padding/height */
            width: 100% !important;
            padding-top: 1rem !important;
            padding-bottom: 0.5rem !important;
            /* Use a height property to visually collapse the whole area */
            height: 4.5rem !important;
        }

        /* Hide ALL form content when collapsed */
        .filter-collapsed form {
            display: none !important;
        }
    }
</style>

<div id="filterSidebar" class="col-lg-3 p-5 px-3 shadow filter-sidebar filter-collapsed">

    <div id="toggleBtnContainer">
        <button id="toggleFilters" class="btn btn-outline-primary filter-toggle-btn">
        </button>
    </div>

    <form id="technicianSearchForm">
        @csrf

        <div class="accordion" id="filterAccordion">

            <div class="accordion-item mb-3 border-0">
                <h2 class="accordion-header" id="headingGeneral">
                    <button class="accordion-button" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapseGeneral"
                        aria-expanded="true" aria-controls="collapseGeneral">
                        General Search
                    </button>
                </h2>
                <div id="collapseGeneral" class="accordion-collapse collapse show"
                    aria-labelledby="headingGeneral" data-bs-parent="#filterAccordion">
                    <div class="card p-3 shadow">
                        <div class="mb-3">
                            <label for="search_query" class="form-label">Search Name</label>
                            <input type="text" name="search_query" id="search_query" class="form-control"
                                value="{{ $params['search_query'] ?? '' }}" placeholder="e.g., John Paul">
                            <div class="form-text">Name (comma-separated supported).</div>
                        </div>
                        <div class="mb-0">
                            <label for="address_query" class="form-label">Filter by Technician Address</label>
                            <input type="text" name="address_query" id="address_query" class="form-control"
                                value="{{ $params['address_query'] ?? '' }}" placeholder="e.g., City, Street Name">
                            <div class="form-text">Technicians whose address contains this text.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item mb-3 border-0">
                <h2 class="accordion-header" id="headingLocation">
                    <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapseLocation"
                        aria-expanded="false" aria-controls="collapseLocation">
                        Location & Radius
                    </button>
                </h2>
                <div id="collapseLocation" class="accordion-collapse collapse"
                    aria-labelledby="headingLocation" data-bs-parent="#filterAccordion">
                    <div class="card p-3 shadow">
                        <div id="locationAlertPlaceholder"></div>
                        <div class="mb-3">
                            <label for="latitude" class="form-label">Client Latitude</label>
                            <input type="number" step="0.00000001" name="latitude" id="latitude" class="form-control"
                                value="{{ $params['client_latitude'] ?? '' }}" required placeholder="e.g., 7.06000064">
                        </div>
                        <div class="mb-3">
                            <label for="longitude" class="form-label">Client Longitude</label>
                            <input type="number" step="0.00000001" name="longitude" id="longitude" class="form-control"
                                value="{{ $params['client_longitude'] ?? '' }}" required placeholder="e.g., 125.59000091">
                        </div>
                        <div class="mb-3">
                            <button type="button" class="btn btn-secondary w-100" id="useLocationBtn">Use My Location</button>
                        </div>
                        <div>
                            <label for="distance_max" class="form-label">Max Distance (km)</label>
                            <input type="number" step="1" name="distance_max" id="distance_max" class="form-control"
                                value="{{ $params['max_distance_km'] }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item mb-3 border-0">
                <h2 class="accordion-header" id="headingSkill">
                    <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapseSkill"
                        aria-expanded="false" aria-controls="collapseSkill">
                        Skill & Score
                    </button>
                </h2>
                <div id="collapseSkill" class="accordion-collapse collapse"
                    aria-labelledby="headingSkill" data-bs-parent="#filterAccordion">
                    <div class="card p-3 shadow">
                        <div class="mb-3">
                            <label for="expertise" class="form-label">Expertise Category</label>
                            <select name="expertise[]" id="expertise" class="form-select" multiple>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ in_array($category->id, $params['expertise_ids']) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="rating_min_score" class="form-label">Min Rating Score</label>
                            <input type="number" step="0.01" name="rating_min_score" id="rating_min_score"
                                class="form-control" value="{{ $params['rating_min_score'] ?? '' }}" placeholder="e.g., 54.5" min="0" max="100">
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item mb-3 border-0">
                <h2 class="accordion-header" id="headingAvailability">
                    <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapseAvailability"
                        aria-expanded="false" aria-controls="collapseAvailability">
                        Availability Time
                    </button>
                </h2>
                <div id="collapseAvailability" class="accordion-collapse collapse"
                    aria-labelledby="headingAvailability" data-bs-parent="#filterAccordion">
                    <div class="card p-3 shadow">
                        <h6 class="form-label text-secondary mb-3">Time (HH:MM:SS)</h6>
                        <div class="row">
                            <div class="col-6">
                                <label for="available_from" class="form-label">From</label>
                                <input type="text" name="available_from" id="available_from"
                                    class="form-control" placeholder="08:00:00" value="{{ $params['available_from'] }}"
                                    pattern="^([01]\d|2[0-3]):([0-5]\d):([0-5]\d)$">
                            </div>
                            <div class="col-6">
                                <label for="available_to" class="form-label">To</label>
                                <input type="text" name="available_to" id="available_to"
                                    class="form-control" placeholder="17:00:00" value="{{ $params['available_to'] }}"
                                    pattern="^([01]\d|2[0-3]):([0-5]\d):([0-5]\d)$">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item mb-3 border-0">
                <h2 class="accordion-header" id="headingBoolean">
                    <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapseBoolean"
                        aria-expanded="false" aria-controls="collapseBoolean">
                        Boolean Filters
                    </button>
                </h2>
                <div id="collapseBoolean" class="accordion-collapse collapse"
                    aria-labelledby="headingBoolean" data-bs-parent="#filterAccordion">
                    <div class="card p-3 shadow">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="home_service"
                                id="home_service" value="true"
                                {{ $params['home_service'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="home_service">Offers Home Service</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="tesda_verified"
                                id="tesda_verified" value="true"
                                {{ $params['tesda_verified'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="tesda_verified">TESDA Verified</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="has_reviews"
                                id="has_reviews" value="true"
                                {{ $params['has_reviews'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_reviews">Has Reviews</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="has_jobs_completed"
                                id="has_jobs_completed" value="true"
                                {{ $params['has_jobs_completed'] ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_jobs_completed">Has Completed Jobs</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="per_page" value="{{ $params['per_page'] }}">
        <input type="hidden" name="page" value="{{ $params['page'] }}">

        <button type="submit" class="btn btn-primary w-100 mt-4">
            <i class="fas fa-search"></i> Apply Filters
        </button>
        <button type="button" id="resetFilters" class="btn btn-outline-secondary w-100 mt-2">
            <i class="fas fa-sync-alt"></i> Reset Filters
        </button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // --- All Original Form Scripts (Validation, Time Formatting, Geolocation) are kept here for completeness ---
        function attachCoordinateValidation(input, min, max) {
            let lastValid = input.value;
            input.addEventListener('input', function() {
                const val = parseFloat(input.value);
                if (isNaN(val)) {
                    lastValid = '';
                    return;
                }
                if (val < min || val > max) {
                    input.value = lastValid;
                } else {
                    lastValid = input.value;
                }
            });
        }
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        if (latInput) attachCoordinateValidation(latInput, -90, 90);
        if (lngInput) attachCoordinateValidation(lngInput, -180, 180);

        const useLocationBtn = document.getElementById('useLocationBtn');
        if (useLocationBtn) {
            useLocationBtn.addEventListener('click', function() {
                if (!navigator.geolocation) {
                    alert('Geolocation is not supported by your browser.');
                    return;
                }
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        document.getElementById('latitude').value = position.coords.latitude.toFixed(8);
                        document.getElementById('longitude').value = position.coords.longitude.toFixed(8);
                    },
                    function(error) {
                        alert('Unable to retrieve your location. Make sure location services are enabled.');
                    }
                );
            });
        }

        const alertPlaceholder = document.getElementById('locationAlertPlaceholder');
        if (navigator.geolocation && latInput && lngInput && latInput.value === '' && lngInput.value === '') {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    latInput.value = position.coords.latitude.toFixed(8);
                    lngInput.value = position.coords.longitude.toFixed(8);
                    if (alertPlaceholder) {
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success alert-dismissible fade show';
                        alertDiv.role = 'alert';
                        alertDiv.innerHTML = `Coordinates automatically set from your location.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
                        alertPlaceholder.appendChild(alertDiv);
                    }
                },
                function(error) {
                    console.warn('Unable to retrieve your location. Error code: ', error.code, error.message);
                    if (alertPlaceholder) {
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                        alertDiv.role = 'alert';
                        alertDiv.innerHTML = `Unable to retrieve your location. Location access blocked!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
                        alertPlaceholder.appendChild(alertDiv);
                    }
                }
            );
        } else if (!navigator.geolocation && alertPlaceholder) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-warning alert-dismissible fade show';
            alertDiv.role = 'alert';
            alertDiv.innerHTML = `Geolocation is not supported by your browser.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
            alertPlaceholder.appendChild(alertDiv);
        }

        const ratingInput = document.getElementById('rating_min_score');
        if (ratingInput) {
            ratingInput.addEventListener('input', function() {
                const val = parseFloat(this.value);
                if (!isNaN(val)) {
                    if (val > 100) this.value = 100;
                    if (val < 0) this.value = 0;
                }
            });
        }

        const timeInputs = ['available_from', 'available_to'];
        timeInputs.forEach(id => {
            const input = document.getElementById(id);
            if (!input) return;
            input.addEventListener('input', function(e) {
                let val = input.value.replace(/\D/g, '');
                let formatted = '';
                if (val.length > 0) formatted += val.substring(0, 2);
                if (val.length >= 3) formatted += ':' + val.substring(2, 4);
                if (val.length >= 5) formatted += ':' + val.substring(4, 6);
                input.value = formatted;
            });
        });


        // --- 6. COLLAPSE LOGIC (Updated to toggle results column) ---
        const toggleBtn = document.getElementById("toggleFilters");
        const sidebar = document.getElementById("filterSidebar");
        const resultsContainer = document.getElementById("technicianResults"); // NEW: Get results container
        const toggleBtnContainer = document.getElementById("toggleBtnContainer");

        // Initial setup for button text on load
        if (sidebar.classList.contains("filter-collapsed")) {
            toggleBtn.innerHTML = '<i class="fas fa-angle-right"></i> FILTERS';
            // NEW: Ensure results start at col-lg-11 if sidebar starts collapsed
            if (resultsContainer && window.innerWidth >= 992) {
                resultsContainer.classList.remove("col-lg-9");
                resultsContainer.classList.add("col-lg-11");
            }
        } else {
            toggleBtn.innerHTML = '<i class="fas fa-angle-left"></i> Hide Filters';
        }

        sidebar.prepend(toggleBtnContainer); // Ensure button is at the top

        toggleBtn.addEventListener("click", function() {
            const isCollapsed = sidebar.classList.contains("filter-collapsed");

            // Only perform column swap logic on desktop screens (>= 992px)
            if (window.innerWidth >= 992 && resultsContainer) {
                if (isCollapsed) {
                    // EXPAND: Sidebar goes 1 -> 3 (8.33% -> 25%)
                    sidebar.classList.remove("filter-collapsed");
                    toggleBtn.innerHTML = '<i class="fas fa-angle-left"></i> Hide Filters';

                    // Results go 11 -> 9
                    resultsContainer.classList.remove("col-lg-11");
                    resultsContainer.classList.add("col-lg-9");
                } else {
                    // COLLAPSE: Sidebar goes 3 -> 1 (25% -> 8.33%)
                    sidebar.classList.add("filter-collapsed");
                    toggleBtn.innerHTML = '<i class="fas fa-angle-right"></i> FILTERS';

                    // Results go 9 -> 11
                    resultsContainer.classList.remove("col-lg-9");
                    resultsContainer.classList.add("col-lg-11");
                }
            } else {
                // Mobile/Tablet: Just toggle the content visibility/height via the filter-collapsed class
                if (isCollapsed) {
                    sidebar.classList.remove("filter-collapsed");
                    toggleBtn.innerHTML = '<i class="fas fa-angle-left"></i> Hide Filters';
                } else {
                    sidebar.classList.add("filter-collapsed");
                    toggleBtn.innerHTML = '<i class="fas fa-angle-right"></i> FILTERS';
                }
            }
        });
    });
</script>