

//Global Variables
let polygonLayer = null;
let currentMarker = null;
let map = null;

class LeafletHandler {

    initializeLeaflet() {
        // Create map and set view to Davao City
        map = L.map('map').setView([7.0647, 125.6088], 13); // Davao City coordinates

        // Add OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);


        // Handle map clicks
        map.on('click', function (e) {
            const {
                lat,
                lng
            } = e.latlng;

            // Remove the current marker (default or previously clicked)
            if (currentMarker) {
                map.removeLayer(currentMarker);
            }

            // Add the new marker
            currentMarker = L.marker([lat, lng], { opacity: 0.8 })
                .addTo(map)
                .bindPopup(`You selected:<br>Lat: ${lat.toFixed(4)}<br>Lng: ${lng.toFixed(4)}`)
                .openPopup();


            document.getElementById('latitude').value = lat.toFixed(6);
            document.getElementById('longitude').value = lng.toFixed(6);
        });
    }



    async drawBoundary(data) {
        return new Promise((resolve, reject) => {

            if (polygonLayer) {
                map.removeLayer(polygonLayer);
            }

            const center = data.center_point;

            // Remove any existing marker
            if (currentMarker) {
                map.removeLayer(currentMarker);
            }

            map.setView([center.latitude, center.longitude], 13);

            // Use currentMarker to store the new one
            currentMarker = L.marker([center.latitude, center.longitude], { opacity: 0.80 })
                .addTo(map)
                .bindPopup(`<b>Center Point</b><br>Lat: ${center.latitude}<br>Lon: ${center.longitude}<br>Radius: ${data.radius_km} km`)
                .openPopup();


            const polygonPoints = [];
            const directions = ['North', 'Northeast', 'East', 'Southeast', 'South', 'Southwest', 'West', 'Northwest'];

            directions.forEach(dir => {
                const coords = data.boundary_coordinates[dir];
                if (coords && coords.latitude && coords.longitude) {
                    polygonPoints.push([coords.latitude, coords.longitude]);
                } else {
                    console.warn(`Missing coordinates for ${dir}:`, coords);
                }
            });

            console.log("Polygon points:", polygonPoints); // <- Check this in console!

            if (polygonPoints.length < 3) {
                console.error("Not enough valid points to form a polygon.");
                return;
            }

            polygonLayer = L.polygon(polygonPoints, {
                color: 'blue',
                fillColor: '#007bff',
                fillOpacity: 0.2
            }).addTo(map);


        });
    }


    renderTechnicianMarkers(technicians) {

        // Create the marker group if it doesn't exist
        if (!this.technicianMarkerGroup) {
            this.technicianMarkerGroup = L.layerGroup().addTo(map);
        } else {
            this.technicianMarkerGroup.clearLayers();
        }
        technicians.forEach(tech => {
            if (tech.technician_latitude && tech.technician_longitude) {
                const redDot = L.marker([tech.technician_latitude, tech.technician_longitude], {
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
            <a href="/technicians/${tech.technician_code}" >View</a>
        `;

                redDot.bindPopup(popupContent);

                // Track if mouse is over marker or popup
                let isMouseOverMarker = false;
                let isMouseOverPopup = false;

                // Open popup on marker mouseover
                redDot.on('mouseover', function () {
                    isMouseOverMarker = true;
                    this.openPopup();
                });

                // On marker mouseout, close popup only if mouse not over popup
                redDot.on('mouseout', function () {
                    isMouseOverMarker = false;
                    setTimeout(() => {
                        if (!isMouseOverPopup && !isMouseOverMarker) {
                            this.closePopup();
                        }
                    }, 100); // small delay to allow mouse entering popup
                });

                // When popup opens, add event listeners to popup container
                redDot.on('popupopen', function () {
                    const popupNode = this.getPopup().getElement();
                    if (popupNode) {
                        // When mouse enters popup
                        popupNode.addEventListener('mouseenter', () => {
                            isMouseOverPopup = true;
                        });

                        // When mouse leaves popup
                        popupNode.addEventListener('mouseleave', () => {
                            isMouseOverPopup = false;
                            // Close popup if mouse also not on marker
                            setTimeout(() => {
                                if (!isMouseOverMarker && !isMouseOverPopup) {
                                    redDot.closePopup();
                                }
                            }, 100);
                        });

                        // Prevent map clicks when clicking links inside popup
                        popupNode.querySelectorAll('a').forEach(a => {
                            a.addEventListener('click', e => {
                                e.stopPropagation();
                            });
                        });
                    }
                });

                this.technicianMarkerGroup.addLayer(redDot);
            }
        });


    }



}