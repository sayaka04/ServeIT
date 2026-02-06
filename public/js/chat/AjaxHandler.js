
//Global Handler




class AjaxHandler {

    async requestSendMessage(url, formData) {

        let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        return new Promise((resolve, reject) => {
            if (window.APP_DEBUG === "true") {
                alert(url);
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token                 // ✅ keep CSRF
                    // ✅ Do NOT set 'Content-Type' manually when using FormData
                },
                body: formData                            // ✅ this is correct
            })

                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to send message!');
                    }
                    return response.json();
                })
                .then(data => {
                    if (window.APP_DEBUG === "true") {
                        alert('Success! Check console for message data.');
                    }
                    resolve(data);  //return promise
                })
                .catch(error => {
                    console.error(error);
                    alert('Error fetching sending message.');
                    reject('Ajax Error'); //return promise error
                });
        });
    }



    async requestNewMessage(url) {
        let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        return new Promise((resolve, reject) => {

            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch message!');
                    }
                    return response.json();
                })
                .then(data => {
                    if (window.APP_DEBUG === "true") {
                        alert('Success! Check console for fetched message data.');
                    }
                    resolve(data);  //return promise
                })
                .catch(error => {
                    console.error(error);
                    alert('Error fetching message data.');
                    reject('Ajax Error'); //return promise error
                });
        });
    }



    async requestCreateRepair(url) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Collect form data
        const repairData = {
            conversation_id: document.getElementById('conversasation_id_input').value,
            issue: document.getElementById('issue').value,
            description: document.getElementById('description').value,
            device: document.getElementById('device').value,
            device_type: document.getElementById('device_type').value,
            estimated_cost: document.getElementById('estimated_cost').value,
            completion_date: document.getElementById('completion_date').value,
        };

        return new Promise((resolve, reject) => {
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify(repairData)
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch message!');
                    }
                    return response.json();
                })
                .then(data => {
                    resolve(data);  // return promise
                })
                .catch(error => {
                    console.error(error);
                    reject('Ajax Error'); // return promise error
                });
        });
    }

}


