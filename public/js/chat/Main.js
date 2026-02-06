/*
--------------------------------------------------------------------------
This basically serves as "linear" or "sequential" programming for JS
This is where the main functionalities the JS will be run
--------------------------------------------------------------------------
*/

// Global Classes
let ajaxHandler;

// HTML Documents Declarations
let message = null;

// Initialization
document.addEventListener('DOMContentLoaded', function () {

    // Initialize message element and AJAX handler
    message = document.getElementById('message');
    ajaxHandler = new AjaxHandler();

    let url = document.body.dataset.newUrl;

});





function appendMessage2(message, userId, timestamp, isCurrentUser) {
    const messageContainer = document.getElementById('messages'); // Make sure you have a container with this ID
    const msgDiv = document.createElement('div');

    msgDiv.classList.add('message');
    msgDiv.classList.add(isCurrentUser ? 'my-message' : 'their-message');

    msgDiv.innerHTML = `
            <div class="message-content">
                <strong>User ${userId}</strong>: ${message}
                <br>
                <small>${timestamp}</small>
            </div>
        `;

    messageContainer.appendChild(msgDiv);
}






// Function to send a message
async function sendMessage(url) {
    let messageContent = message.value;
    const imageInput = document.getElementById('imageUpload');
    const fileInput = document.getElementById('fileUpload');
    const linkText = document.getElementById('linkText').value.trim();
    const linkURL = document.getElementById('linkURL').value.trim();

    if (imageInput.files.length === 0 && fileInput.files.length === 0 && (!linkText || !linkURL)) {
        if (window.APP_DEBUG === "true") {
            alert("Please add image, file, or link before sending.");
        }
    } else if (messageContent.trim() === "") {
        messageContent = "*";
    }


    if (!messageContent.trim()) {
        if (window.APP_DEBUG === "true") {
            alert("Please add a message");
        }
        return;
    }


    const formData = new FormData();
    formData.append('message', messageContent);

    // Append images
    for (let i = 0; i < imageInput.files.length; i++) {
        formData.append('images[]', imageInput.files[i]);
    }

    // Append files
    for (let i = 0; i < fileInput.files.length; i++) {
        formData.append('files[]', fileInput.files[i]);
    }

    // Append link if present
    if (linkText && linkURL) {
        formData.append('link_text', linkText);
        formData.append('link_url', linkURL);
    }

    formData.append('conversation_code', window.conversationCode);

    try {
        // Send the message via AJAX
        await ajaxHandler.requestSendMessage(url, formData);

        // After sending, clear the input
        message.value = '';
        document.getElementById('linkText').value = '';
        document.getElementById('linkURL').value = '';
        imageInput.value = '';
        fileInput.value = '';

        document.querySelector('#dropdownActionsClient .badge').style.display = 'none'
        document.querySelector('button[data-bs-target="#modalAttachImage"] .badge').style.display = 'none';
        document.querySelector('button[data-bs-target="#modalAttachFile"] .badge').style.display = 'none';
        document.querySelector('button[data-bs-target="#modalInsertLink"] .badge').style.display = 'none';

        console.log('Message sent successfully!');
    } catch (error) {
        console.error('Error sending message:', error);
    }
}


async function sendMessageRepair(url, repair_id, msg) {
    const messageContent = msg;
    const imageInput = document.getElementById('imageUpload');
    const fileInput = document.getElementById('fileUpload');
    const linkText = document.getElementById('linkText').value.trim();
    const linkURL = document.getElementById('linkURL').value.trim();

    if (!messageContent.trim() && imageInput.files.length === 0 && fileInput.files.length === 0 && (!linkText || !linkURL)) {
        if (window.APP_DEBUG === "true") {
            alert("Please add a message, image, file, or link before sending.");
        }
        return;
    }

    const formData = new FormData();
    formData.append('message', messageContent);

    formData.append('repair_id', repair_id);

    // Append images
    for (let i = 0; i < imageInput.files.length; i++) {
        formData.append('images[]', imageInput.files[i]);
    }

    // Append files
    for (let i = 0; i < fileInput.files.length; i++) {
        formData.append('files[]', fileInput.files[i]);
    }

    // Append link if present
    if (linkText && linkURL) {
        formData.append('link_text', linkText);
        formData.append('link_url', linkURL);
    }

    formData.append('conversation_code', window.conversationCode);


    try {
        // Send the message via AJAX
        await ajaxHandler.requestSendMessage(url, formData);

        // After sending, clear the input
        message.value = '';
        linkText.value = '';
        linkURL.value = '';
        imageInput.value = '';
        fileInput.value = '';

        document.querySelector('#dropdownActionsClient .badge').style.display = 'none'
        document.querySelector('button[data-bs-target="#modalAttachImage"] .badge').style.display = 'none';
        document.querySelector('button[data-bs-target="#modalAttachFile"] .badge').style.display = 'none';
        document.querySelector('button[data-bs-target="#modalInsertLink"] .badge').style.display = 'none';

        console.log('Message sent successfully11111!');
    } catch (error) {
        console.error('Error sending message:', error);
    }
}