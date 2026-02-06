<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HaversineFormulaController extends Controller
{
    /**
     * Radius of the Earth in kilometers.
     * @var float
     */
    private const EARTH_RADIUS_KM = 6371.0;

    /**
     * Calculate the distance between two geographical points using the Haversine formula.
     *
     * @param float $lat1 Latitude of the first point (in degrees).
     * @param float $lon1 Longitude of the first point (in degrees).
     * @param float $lat2 Latitude of the second point (in degrees).
     * @param float $lon2 Longitude of the second point (in degrees).
     * @return float The distance between the two points in kilometers.
     */
    public function calculateDistance(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'lat1' => 'required|numeric',
            'lon1' => 'required|numeric',
            'lat2' => 'required|numeric',
            'lon2' => 'required|numeric',
        ]);

        // Get coordinates from the request input
        $lat1 = (float) $request->input('lat1');
        $lon1 = (float) $request->input('lon1');
        $lat2 = (float) $request->input('lat2');
        $lon2 = (float) $request->input('lon2');

        $distance = $this->haversine($lat1, $lon1, $lat2, $lon2);

        // Return a view with the calculated distance
        return view('prototype.haversine', [
            'result' => "Distance: " . round($distance, 3) . " km",
            'lat1' => $lat1,
            'lon1' => $lon1,
            'lat2' => $lat2,
            'lon2' => $lon2,
        ]);
    }














    /**
     * Finds the boundary coordinates (North, South, East, West, NE, NW, SE, SW)
     * from a given center point and radius.
     *
     * @param \Illuminate\Http\Request $request The incoming HTTP request.
     * @param float $lon1 Longitude of the first point (in degrees).
     * @param float $lat2 Latitude of the second point (in degrees).
     * Expects 'lat', 'lon', and 'radius_km'.
     * @return \Illuminate\Http\JsonResponse Returns JSON with the calculated boundary points.
     */
    public function findBoundaryCoordinates(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
            'radius_km' => 'required|numeric',
        ]);


        $centerLat = (float) $request->input('lat');
        $centerLon = (float) $request->input('lon');
        $radiusKm = (float) $request->input('radius_km');

        $boundaryPoints = [];

        // Define bearings for cardinal and intercardinal directions
        $directions = [
            'North' => 0,
            'Northeast' => 45,
            'East' => 90,
            'Southeast' => 135,
            'South' => 180,
            'Southwest' => 225,
            'West' => 270,
            'Northwest' => 315,
        ];

        foreach ($directions as $name => $bearingDegrees) {
            $point = $this->getDestinationPoint($centerLat, $centerLon, $radiusKm, $bearingDegrees);
            $boundaryPoints[$name] = [
                'latitude' => round($point['lat'], 6), // Round for precision
                'longitude' => round($point['lon'], 6),
            ];
        }

        return response()->json([
            'center_point' => ['latitude' => $centerLat, 'longitude' => $centerLon],
            'radius_km' => $radiusKm,
            'boundary_coordinates' => $boundaryPoints,
        ]);
    }

    /**
     * Calculates a destination point given a start point, bearing, and distance.
     * Uses the direct geodetic formula.
     *
     * @param float $startLat Latitude of the starting point (in degrees).
     * @param float $startLon Longitude of the starting point (in degrees).
     * @param float $distanceKm Distance to travel (in kilometers).
     * @param float $bearingDegrees Bearing in degrees (0 = North, 90 = East, etc.).
     * @return array An associative array with 'lat' and 'lon' of the destination point in degrees.
     */
    private function getDestinationPoint(float $startLat, float $startLon, float $distanceKm, float $bearingDegrees): array
    {
        $latRad = deg2rad($startLat);
        $lonRad = deg2rad($startLon);
        $bearingRad = deg2rad($bearingDegrees);

        $angularDistance = $distanceKm / self::EARTH_RADIUS_KM; // d/R

        $latDestRad = asin(
            sin($latRad) * cos($angularDistance) +
                cos($latRad) * sin($angularDistance) * cos($bearingRad)
        );

        $lonDestRad = $lonRad + atan2(
            sin($bearingRad) * sin($angularDistance) * cos($latRad),
            cos($angularDistance) - sin($latRad) * sin($latDestRad)
        );

        // Convert back to degrees
        $latDest = rad2deg($latDestRad);
        $lonDest = rad2deg($lonDestRad);

        // Normalize longitude to -180 to +180
        $lonDest = fmod($lonDest + 540, 360) - 180;

        return ['lat' => $latDest, 'lon' => $lonDest];
    }















    /**
     * Core Haversine calculation method.
     *
     * @param float $lat1 Latitude of the first point (in degrees).
     * @param float $lon1 Longitude of the first point (in degrees).
     * @param float $lat2 Latitude of the second point (in degrees).
     * @param float $lon2 Longitude of the second point (in degrees).
     * @return float The calculated distance in kilometers.
     */
    private function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        // Convert degrees to radians
        $lat1Rad = deg2rad($lat1);
        $lon1Rad = deg2rad($lon1);
        $lat2Rad = deg2rad($lat2);
        $lon2Rad = deg2rad($lon2);

        // Difference in coordinates
        $dLat = $lat2Rad - $lat1Rad;
        $dLon = $lon2Rad - $lon1Rad;

        // Haversine formula
        $a = pow(sin($dLat / 2), 2) + cos($lat1Rad) * cos($lat2Rad) * pow(sin($dLon / 2), 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // Distance in kilometers
        return self::EARTH_RADIUS_KM * $c;
    }
}
