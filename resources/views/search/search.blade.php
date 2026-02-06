<!DOCTYPE html>
<html lang="en">

<!------------------------------>
<!------------Head------------>
<!------------------------------>
@include('search/partials/head')

<body class="sb-nav-fixed" data-boundary-url="{{ route('haversine.boundary') }}" data-result-url="{{ route('technicians.search.result') }}">

    <!------------------------------>
    <!------------NavBar------------>
    <!------------------------------>
    @include('partials/navigation-bar')

    <div id="layoutSidenav">

        <!------------------------------>
        <!-----------SideBar------------>
        <!------------------------------>
        @include('partials/sidebar')

        <div id="layoutSidenav_content">

            <main>
                <div class="container-fluid">

                    <div class="container-fluid d-flex justify-content-center align-items-center">
                        <div id="map" class="d-flex justify-content-center align-items-center"
                            style="height: 500px; width: 100%;">
                        </div>
                    </div>
                    <br><br>
                    <div class="row d-none">
                        <div class="col-12 p-3 px-3 shadow">
                            <div class="card p-3 shadow mb-2">
                                <input type="hidden" id="general_search" class="form-control search-box"
                                    placeholder="Search..." />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!------------------------------>
                        <!-----------Filter------------>
                        <!------------------------------>
                        @include('search/partials/filter')

                        <div id="technicianResults" class="col-lg-9 px-4">
                            @include('search.partials.result')
                        </div>
                    </div>
                </div>
            </main>

            <!------------------------------>
            <!-----------Footer------------>
            <!------------------------------>
            @include('partials/footer')
        </div>
    </div>

    <script>
        // 🌍 Global map & marker references
        let map;
        let userMarker = null;
        let boundaryCircle = null;
        let technicianLayer = null;

        document.addEventListener("DOMContentLoaded", () => {

            //------------------------------------------------------------------------------
            // Function that scrolls to the #search-result div
            function goToSearchResult() {
                const element = document.getElementById("search-result");
                if (element) {
                    element.scrollIntoView({
                        behavior: "smooth",
                        block: "start"
                    });
                }
            }
            window.addEventListener("load", goToSearchResult);
            //------------------------------------------------------------------------------



            // ✅ Initialize Leaflet Map
            map = L.map('map').setView([7.0647, 125.6088], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // 🟦 Handle map clicks
            map.on('click', function(e) {
                const {
                    lat,
                    lng
                } = e.latlng;

                if (userMarker) map.removeLayer(userMarker);

                userMarker = L.marker([lat, lng], {
                        icon: L.divIcon({
                            html: '<div style="width:10px;height:10px;background-color:blue;border:2px solid white;border-radius:50%;"></div>',
                            iconSize: [10, 10],
                            iconAnchor: [5, 5]
                        })
                    }).addTo(map)
                    .bindPopup(`You selected:<br>Lat: ${lat.toFixed(4)}<br>Lng: ${lng.toFixed(4)}`)
                    .openPopup();

                const latInput = document.getElementById('latitude');
                const lngInput = document.getElementById('longitude');
                if (latInput && lngInput) {
                    latInput.value = lat.toFixed(6);
                    lngInput.value = lng.toFixed(6);
                }
            });

            const form = document.getElementById("technicianSearchForm");
            const resultContainer = document.getElementById("technicianResults");
            const resetBtn = document.getElementById("resetFilters");

            // 🟨 AJAX Search Submit
            form.addEventListener("submit", async function(e) {
                e.preventDefault();


                performSearch();

            });

            async function performSearch() {


                const formData = new FormData(form);
                const query = new URLSearchParams(formData).toString();
                const latitude = parseFloat(formData.get('latitude'));
                const longitude = parseFloat(formData.get('longitude'));
                const distance = parseFloat(formData.get('distance_max')) || 5;

                resultContainer.innerHTML = `
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-3">Searching technicians...</p>
                    </div>
                `;

                try {
                    const response = await fetch("{{ route('technicians.test_search.api') }}?" + query);
                    const data = await response.json();
                    if (!response.ok) throw data;

                    renderTechnicians(data.html);
                    renderPagination(data.pagination);
                    //Jumps to the search results [renderTechnicians(data.html);]
                    goToSearchResult();

                    // 🟦 Draw boundary
                    drawBoundaryManual(latitude, longitude, distance);

                    // 🟥 Show technician markers
                    if (data.technicians && data.technicians.data) {
                        renderTechnicianMarkers(data.technicians.data);
                    }

                    // 🟦 Re-center map on user's location
                    addUserMarker(latitude, longitude);

                } catch (error) {
                    console.error(error);
                    resultContainer.innerHTML = `
                        <div class="alert alert-danger text-center">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            ${error.message || 'An error occurred during the search.'}
                        </div>
                    `;
                }
            }




            async function initialPerformSearch() {
                // 🟦 Wait for geolocation (or fallback)
                const position = await new Promise((resolve) => {
                    navigator.geolocation.getCurrentPosition(
                        (pos) => resolve(pos),
                        (err) => {
                            console.warn("Geolocation not available or denied:", err.message);
                            resolve(null); // continue even if denied
                        }
                    );
                });

                // 🟩 Form data
                const formData = new FormData(form);
                const query = new URLSearchParams(formData).toString();

                // 🟨 Use geolocation if available, else fallback to form
                const latitude = position?.coords?.latitude ?
                    position.coords.latitude.toFixed(8) :
                    parseFloat(formData.get("latitude"));

                const longitude = position?.coords?.longitude ?
                    position.coords.longitude.toFixed(8) :
                    parseFloat(formData.get("longitude"));

                const distance = parseFloat(formData.get('distance_max')) || 5;

                resultContainer.innerHTML = `
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-3">Searching technicians...</p>
                    </div>
                `;

                try {
                    const response = await fetch("{{ route('technicians.test_search.api') }}?" + query);
                    const data = await response.json();
                    if (!response.ok) throw data;

                    renderTechnicians(data.html);
                    renderPagination(data.pagination);
                    //Jumps to the search results [renderTechnicians(data.html);]
                    goToSearchResult();

                    // 🟦 Draw boundary
                    drawBoundaryManual(latitude, longitude, distance);

                    // 🟥 Show technician markers
                    if (data.technicians && data.technicians.data) {
                        renderTechnicianMarkers(data.technicians.data);
                    }

                    // 🟦 Re-center map on user's location
                    addUserMarker(latitude, longitude);

                } catch (error) {
                    console.error(error);
                    resultContainer.innerHTML = `
                        <div class="alert alert-danger text-center">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            ${error.message || 'An error occurred during the search.'}
                        </div>
                    `;
                }
            }
            initialPerformSearch();

            // 🟧 Handle Reset
            resetBtn.addEventListener("click", () => {
                form.reset();
                resultContainer.innerHTML = `
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-info-circle me-2"></i> Filters reset. Please apply filters again.
                    </div>
                `;
                if (boundaryCircle) map.removeLayer(boundaryCircle);
                if (technicianLayer) map.removeLayer(technicianLayer);
                if (userMarker) map.removeLayer(userMarker);
            });
        });

        // 🟩 Render technicians (HTML)
        function renderTechnicians(html) {
            const resultContainer = document.getElementById("technicianResults");
            resultContainer.innerHTML = html;
        }

        // 🟩 Render pagination
        function renderPagination(html) {
            let paginationContainer = document.getElementById('paginationContainer');
            if (!paginationContainer) {
                paginationContainer = document.createElement('div');
                paginationContainer.id = 'paginationContainer';
                paginationContainer.classList.add('col-12', 'mt-3');
                document.getElementById("technicianSearchForm").after(paginationContainer);
            }
            paginationContainer.innerHTML = html || '';
        }

        // 🟦 Draw circular boundary around the user's location
        function drawBoundaryManual(lat, lng, radiusKm) {
            if (!lat || !lng || !radiusKm) return;

            if (boundaryCircle) map.removeLayer(boundaryCircle);

            const radiusMeters = radiusKm * 1000;
            boundaryCircle = L.circle([lat, lng], {
                color: 'blue',
                fillColor: '#007bff',
                fillOpacity: 0.2,
                radius: radiusMeters
            }).addTo(map);
        }

        // 🟦 Add user marker
        function addUserMarker(lat, lng) {
            if (!lat || !lng) return;

            if (userMarker) map.removeLayer(userMarker);

            userMarker = L.marker([lat, lng], {
                    icon: L.divIcon({
                        html: '<div style="width:10px;height:10px;background-color:blue;border:2px solid white;border-radius:50%;"></div>',
                        iconSize: [10, 10],
                        iconAnchor: [5, 5]
                    })
                })
                .addTo(map)
                .bindPopup("<b>Your Location</b>")
                .openPopup();

            map.setView([lat, lng], 13);
        }

        // 🟥 Plot technician markers
        function renderTechnicianMarkers(technicians) {
            // Create the marker group if it doesn't exist
            if (!window.technicianMarkerGroup) {
                window.technicianMarkerGroup = L.layerGroup().addTo(map);
            } else {
                window.technicianMarkerGroup.clearLayers();
            }

            technicians.forEach(tech => {
                const lat = parseFloat(tech.latitude || tech.technician_latitude);
                const lng = parseFloat(tech.longitude || tech.technician_longitude);
                if (!lat || !lng) return;

                const redDot = L.marker([lat, lng], {
                    icon: L.divIcon({
                        className: '',
                        html: '<div style="width:8px;height:8px;background-color:red;border:1px solid white;border-radius:50%;z-index:999;"></div>',
                        iconSize: [8, 8],
                        iconAnchor: [4, 4]
                    })
                });

                const popupContent = `
            <b>${tech.technician_full_name}</b><br>
            ${parseFloat(tech.distance_km || 0).toFixed(2)} km away<br>
            <a href="/technicians/${tech.technician_code}" target="_blank">View Profile</a>
        `;

                redDot.bindPopup(popupContent);

                // Track if mouse is over marker or popup
                let isMouseOverMarker = false;
                let isMouseOverPopup = false;

                // Open popup on marker mouseover
                redDot.on('mouseover', function() {
                    isMouseOverMarker = true;
                    this.openPopup();
                });

                // On marker mouseout, close popup only if mouse not over popup
                redDot.on('mouseout', function() {
                    isMouseOverMarker = false;
                    setTimeout(() => {
                        if (!isMouseOverPopup && !isMouseOverMarker) {
                            this.closePopup();
                        }
                    }, 100);
                });

                // When popup opens, add event listeners to popup container
                redDot.on('popupopen', function() {
                    const popupNode = this.getPopup().getElement();
                    if (popupNode) {
                        popupNode.addEventListener('mouseenter', () => {
                            isMouseOverPopup = true;
                        });

                        popupNode.addEventListener('mouseleave', () => {
                            isMouseOverPopup = false;
                            setTimeout(() => {
                                if (!isMouseOverMarker && !isMouseOverPopup) {
                                    redDot.closePopup();
                                }
                            }, 100);
                        });

                        // 🟩 Prevent map clicks when clicking popup links
                        popupNode.querySelectorAll('a').forEach(a => {
                            a.addEventListener('click', e => {
                                e.stopPropagation(); // stops Leaflet from closing popup
                            });
                        });
                    }
                });

                window.technicianMarkerGroup.addLayer(redDot);
            });
        }
    </script>
</body>

</html>