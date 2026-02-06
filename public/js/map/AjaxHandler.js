
//Global Handler




class AjaxHandler {

    async requestCoordinateBoundaries(lat, lon, radius, url) {
        let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        return new Promise((resolve, reject) => {

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    lat: lat,
                    lon: lon,
                    radius_km: radius
                })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch boundary data');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Boundary Coordinates:', data);
                    if (window.APP_DEBUG === "true") {
                        alert('Success! Check console for boundary data.');
                    }
                    resolve(data);  //return promise
                })
                .catch(error => {
                    console.error(error);
                    alert('Error fetching boundary data.');
                    reject('Ajax Error'); //return promise error
                });
        });
    }



    async requestResults(url) {
        let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        let distance = document.getElementById('distance_max').value;
        let latitude = document.getElementById('lat').value;
        let longitude = document.getElementById('long').value;
        let available_from = document.getElementById('available_from').value;
        let available_to = document.getElementById('available_to').value;
        let home_service = document.getElementById('home_service').checked || document.getElementById('home_service').value === 'true';

        // Convert strings to appropriate types
        const technicianSearchData = {
            latitude: parseFloat(latitude) || 0,
            longitude: parseFloat(longitude) || 0,
            distance_max: parseFloat(distance) || 0,
            available_from: available_from || '00:00:00',
            available_to: available_to || '23:59:59',
            home_service: !!home_service
        };



        return new Promise((resolve, reject) => {
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify(technicianSearchData)
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch technician data from server');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('✅ Technician data received:', data);

                    if (window.APP_DEBUG === "true") {
                        if (data.length === 0) {
                            alert('No technicians found within the specified parameters.');

                        } else {
                            alert(`Success! ${data.length} technician(s) found.`);
                        }
                    }

                    leafletHandler.renderTechnicianMarkers(data.technicians); // ✅ Map dots go here

                    this.renderTechnicians(data.technicians); // ✅ FIXED
                    resolve(data);
                })
                .catch(error => {
                    console.error('❌ Error:', error);
                    alert('An error occurred while fetching technician data. Please try again.');
                    reject('Ajax Error');
                });

        });
    }



    renderTechnicians(technicians) {
        const container = document.getElementById('technician-list');
        container.innerHTML = ''; // Clear previous results

        if (!technicians || technicians.length === 0) {
            container.innerHTML = `
            <div class="alert alert-warning text-center" role="alert">
                No technicians found matching your criteria.
            </div>`;
            return;
        }

        technicians.forEach(tech => {
            const card = document.createElement('div');
            card.className = 'list-group-item list-group-item-action flex-column align-items-start shadow-sm mb-3 rounded';

            card.innerHTML = `
            <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                <h5 class="mb-0">${tech.technician_full_name}</h5>
                <small class="text-muted">${parseFloat(tech.final_weighted_score).toFixed(4)} Weighted Score</small>
            </div>
            <p class="mb-1 text-muted">Specializes in <strong>Smartphones & Tablets</strong>. Highly rated and experienced technician dedicated to quality repairs.</p>
            <div class="d-flex flex-wrap align-items-center mt-2 mb-2">
                <span class="badge bg-warning text-dark me-2 mb-1">${parseFloat(tech.existing_overall_rating).toFixed(2)} Rating</span>
                <span class="badge bg-secondary me-2 mb-1">📍 ${tech.distance_km ? parseFloat(tech.distance_km).toFixed(2) : 'N/A'} km away</span>
                <span class="badge bg-success me-2 mb-1">✅ ${tech.jobs_completed} Finished Tasks</span>
                <span class="badge bg-info text-dark mb-1">${parseFloat(tech.success_rate).toFixed(2)}% Successful</span>
                <span class="badge ${tech.tesda_verified ? 'bg-success' : 'bg-danger'} mb-1">
                    ${tech.tesda_verified ? 'Tesda Verified' : 'No Tesda verification'}
                </span>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted">Last active: 10 mins ago</small>
                <a href="/technicians/${tech.technician_code}" class="btn btn-sm btn-outline-primary">
                    View Profile
                </a>
            </div>
        `;

            container.appendChild(card);
        });
    }


}



