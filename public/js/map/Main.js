/*
--------------------------------------------------------------------------
This basically serves as "linear" or "sequencial" programming for JS
This is where the main functionalities the JS will be runned
--------------------------------------------------------------------------
*/



//Global Classes
let locationHandler;
let leafletHandler;
let ajaxHandler;


//HTML Documents Declarations
let distance = null;
let latitude = null;
let longitude = null;

//Initialization
document.addEventListener('DOMContentLoaded', function () {

    //HTML Documents Declarations
    distance = document.getElementById('distance_max');
    latitude = document.getElementById('latitude');
    longitude = document.getElementById('longitude');


    //Class Initialization
    locationHandler = new LocationHandler();
    leafletHandler = new LeafletHandler();
    ajaxHandler = new AjaxHandler();

    leafletHandler.initializeLeaflet();

    let url = document.body.dataset.boundaryUrl;
    let url2 = document.body.dataset.resultUrl;

    runInitialFunction(url, url2);
});



async function runInitialFunction(url, url2) {
    let user_coordinates = await locationHandler.fetchUserCoordinates();

    if (user_coordinates[3]) {
        latitude.value = user_coordinates[0];
        longitude.value = user_coordinates[1];
    }


    if (!latitude.value || !longitude.value || !distance.value) {
        alert("Please fill in all fields: latitude, longitude, and distance.");
    } else {
        let coordinate_boundaries = await ajaxHandler.requestCoordinateBoundaries(latitude.value, longitude.value, distance.value, url);
        alert(JSON.stringify(coordinate_boundaries, null, 2));

        leafletHandler.drawBoundary(coordinate_boundaries);

        await ajaxHandler.requestResults(url2);

    }
}

