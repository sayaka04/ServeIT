class LocationHandler {

    async fetchUserCoordinates() {
        if (!navigator.geolocation) {
            throw new Error("Geolocation is not supported by your browser.");
        }

        const options = {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        };

        return new Promise((resolve, reject) => {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    const acc = position.coords.accuracy;

                    resolve([lat, lon, acc, true]);
                },
                (error) => {
                    let message = "Error getting location: ";
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            message += "User denied the request for Geolocation.";
                            break;
                        case error.POSITION_UNAVAILABLE:
                            message += "Location information is unavailable.";
                            break;
                        case error.TIMEOUT:
                            message += "The request to get user location timed out.";
                            break;
                        default:
                            message += "An unknown error occurred.";
                    }
                    resolve([-1, -1, -1, false]); //denied

                    reject(new Error(message));
                },
                options
            );
        });
    }



}
