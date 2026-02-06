function appendMessage(messageObj, repairObj, userId, timestamp, isCurrentUser) {

    if (repairObj) {
        if (repairObj.status != 'pending' && repairObj.status == "accepted" || repairObj.status == "declined") {
            const statusElement = document.querySelector(`#repair-status-${repairObj.id}`);
            if (statusElement) {
                statusElement.textContent = repairObj.status;
            } else {
                console.warn('Repair status element not found for:', repairObj.repair_id);
            }
            return;
        }
    }



    const conversation = document.getElementById('conversation');

    let alignment = isCurrentUser ? 'justify-content-end text-start' : 'justify-content-start text-start';
    let bgColor = isCurrentUser ? 'bg-primary' : 'bg-secondary';

    if (messageObj.repair_id) {
        alignment = 'justify-content-center text-end';
        bgColor = 'bg-info w-50 text-center';
    }

    // Message text
    const content = messageObj.message ?? 'No content';

    // Extras: image, file, url
    let extras = '';

    // Image (if any)
    if (messageObj.image_path) {
        extras += `
    <hr class="border-light">
    <div>
        <img src="/file/file/${messageObj.image_path}" class="img-thumbnail mb-1" alt="${messageObj.image_name ?? 'Image'}">`;
        if (messageObj.image_name) {
            extras += `<div class="small text-white-50">📷 ${messageObj.image_name}</div>`;
        }
        extras += `</div>`;
    }

    // File (if any)
    if (messageObj.file_path) {
        extras += `
    <hr class="border-light">
    <div class="small">
        📎 <a href="/file/file/${messageObj.file_path}" class="text-white text-decoration-underline" download>
            ${messageObj.file_name ?? 'Download file'}
        </a>
        ${messageObj.file_type ? `<span class="text-white-50">(${messageObj.file_type.toUpperCase()})</span>` : ''}
    </div>`;
    }

    // URL (if any)
    if (messageObj.url) {
        extras += `
    <hr class="border-light">
    <div class="small">
        🔗 <a href="${messageObj.url}" target="_blank" rel="noopener" class="text-white text-decoration-underline">
            ${messageObj.url_name || new URL(messageObj.url).hostname}
        </a>
        ${messageObj.url_domain ? `<div class="text-white-50">🌐 ${messageObj.url_domain}</div>` : ''}
    </div>`;
    }


    if (repairObj) {

        console.log('Estimated Cost Raw:', repairObj.estimated_cost);
        console.log('Estimated Cost Parsed:', parseFloat(repairObj.estimated_cost));
        console.log('isNaN:', isNaN(parseFloat(repairObj.estimated_cost)));


        extras += `
        <hr class="border-light">
        <div class="card bg-dark text-white p-2 text-center">
            <div class="card-body p-2">
                <h6 class="card-title mb-2">🔧 Repair Info</h6>

                <p class="mb-1"><strong>Device:</strong> ${repairObj.device}</p>
                <p class="mb-1"><strong>Device Type:</strong> ${repairObj.device_type}</p>
                <p class="mb-1"><strong>Status:</strong> <span id="repair-status-${repairObj.id}">${repairObj.status}</span></p>


                ${repairObj.status === 'pending' ? `
                    <div class="mt-2" id="repair-buttons-${repairObj.id}">
                        <button class="btn btn-sm btn-success me-1" data-bs-toggle="modal" data-bs-target="#confirmAcceptModal${repairObj.id}">
                            ✅ Accept
                        </button>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeclineModal${repairObj.id}">
                            ❌ Decline
                        </button>

<!-- Accept Modal -->
<div class="modal fade" id="confirmAcceptModal${repairObj.id}" tabindex="-1" aria-labelledby="confirmAcceptLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Accept</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to <strong>ACCEPT</strong> this repair?
                <!-- Status Message -->
                <div id="statusMessage-accept-${repairObj.id}" class="mt-2 text-warning small"></div>
            </div>
            <div class="modal-footer flex-column align-items-start">
                <div class="w-100 d-flex justify-content-between">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="acceptButton-${repairObj.id}"
                        onclick="repairAction('/repairs/${repairObj.id}/accept', '${repairObj.id}', 'accept')">
                        Yes, Accept
                    </button>
                </div>
                <div class="spinner-border text-light mt-2 d-none mx-auto" role="status" id="spinner-accept-${repairObj.id}">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Decline Modal -->
<div class="modal fade" id="confirmDeclineModal${repairObj.id}" tabindex="-1" aria-labelledby="confirmDeclineLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Decline</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to <strong>DECLINE</strong> this repair?
                <!-- Status Message -->
                <div id="statusMessage-decline-${repairObj.id}" class="mt-2 text-warning small"></div>
            </div>
            <div class="modal-footer flex-column align-items-start">
                <div class="w-100 d-flex justify-content-between">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="declineButton-${repairObj.id}"
                        onclick="repairAction('/repairs/${repairObj.id}/decline', '${repairObj.id}', 'decline')">
                        Yes, Decline
                    </button>
                </div>
                <div class="spinner-border text-light mt-2 d-none" role="status" id="spinner-decline-${repairObj.id}">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>

                    </div>
                ` : ''}
            </div>
        </div>
    `;
    }



    const html = `
        <div class="d-flex ${alignment} p-2">
            <div class="card d-inline-block text-wrap text-break p-2 text-white ${bgColor}" style="max-width: 70%;">
                <pre style="white-space: pre-wrap; word-break: break-word;">${content}</pre>
<small class="text-white opacity-75">
                    ${formatMessageTimestamp(timestamp)}
                </small>
                ${extras}
            </div>
        </div>
    `;

    conversation.insertAdjacentHTML('beforeend', html);
    conversation.scrollTop = conversation.scrollHeight;
    window.scrollTo(0, document.body.scrollHeight);
}

/**
 * Formats a timestamp into 'h:i A - d M Y' format.
 * Example: 04:37 PM - 02 Dec 2025
 */
function formatMessageTimestamp(timestamp) {
    const date = new Date(timestamp);

    // Time part (04:37 PM). Use 'en-US' for 12-hour clock.
    const time = new Intl.DateTimeFormat('en-US', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    }).format(date).replace(' ', ''); // Remove space for PM

    // Date part (02 Dec 2025). Use 'en-GB' for Day-Month-Year order.
    const datePart = new Intl.DateTimeFormat('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    }).format(date).replace(/[\.,]/g, ''); // Remove periods/commas

    // Combine them with the required format
    return `${time.toUpperCase()} - ${datePart}`;
}