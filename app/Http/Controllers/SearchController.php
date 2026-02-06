<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use App\Models\ExpertiseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    // Default coordinates for initial load
    private const DEFAULT_LATITUDE = 7.0664;
    private const DEFAULT_LONGITUDE = 125.5991;




    public function search(Request $request)
    {

        $params = $this->getSearchParams($request);

        $expertiseCategories = ExpertiseCategory::select('id', 'name')
            ->where('is_archived', false)
            ->orderBy('name')
            ->get();

        if (!$params['is_valid']) {
            $emptyPaginator = new LengthAwarePaginator(
                collect(),
                0,
                $params['per_page'],
                1,
                ['path' => $request->url()]
            );
            return view('search.search', [
                'technicians' => $emptyPaginator,
                'categories' => $expertiseCategories,
                'params' => $params,
                'message' => 'Please provide client coordinates and search criteria to begin.'
            ]);
        }

        $maxScores = $this->getTechnicianMaxScores();
        $preFilteredQuery = $this->applyInitialDatasetReductionFilters(Technician::query(), $params);
        $technicians = $this->applyWeightedScoringAlgorithmAndPaginate($preFilteredQuery, $params, $maxScores);

        return view('search.search', [
            'categories' => $expertiseCategories,
            'params' => $params,
            // 'technicians' => $technicians,
        ]);
    }


    /**
     * Web search (paginated view)
     */
    public function testSearchTechniciansWeb(Request $request)
    {
        $params = $this->getSearchParams($request);

        $expertiseCategories = ExpertiseCategory::select('id', 'name')
            ->where('is_archived', false)
            ->orderBy('name')
            ->get();

        if (!$params['is_valid']) {
            $emptyPaginator = new LengthAwarePaginator(
                collect(),
                0,
                $params['per_page'],
                1,
                ['path' => $request->url()]
            );
            return view('search.test_results', [
                'technicians' => $emptyPaginator,
                'categories' => $expertiseCategories,
                'params' => $params,
                'message' => 'Please provide client coordinates and search criteria to begin.'
            ]);
        }

        $maxScores = $this->getTechnicianMaxScores();
        $preFilteredQuery = $this->applyInitialDatasetReductionFilters(Technician::query(), $params);
        $technicians = $this->applyWeightedScoringAlgorithmAndPaginate($preFilteredQuery, $params, $maxScores);

        return view('search.test_results', [
            'technicians' => $technicians,
            'categories' => $expertiseCategories,
            'params' => $params,
        ]);
    }



    public function testSearchTechniciansAPI(Request $request)
    {
        $params = $this->getSearchParams($request);

        if (!$params['is_valid']) {
            return response()->json([
                'html' => view('search.partials.technician-results', [
                    'technicians' => new LengthAwarePaginator(collect(), 0, $params['per_page'], 1),
                    'message' => 'Please provide valid client coordinates.'
                ])->render()
            ], 400);
        }

        $maxScores = $this->getTechnicianMaxScores();
        $preFilteredQuery = $this->applyInitialDatasetReductionFilters(Technician::query(), $params);
        $technicians = $this->applyWeightedScoringAlgorithmAndPaginate($preFilteredQuery, $params, $maxScores);

        // Render technicians results HTML (your partial)
        $resultsHtml = view('search.partials.result', compact('technicians'))->render();

        // Generate custom pagination HTML manually
        $paginationHtml = $this->renderCustomPagination($technicians);
        Log::info('technicians', ['data' => $technicians->toArray()]);
        return response()->json([
            'html' => $resultsHtml,
            'pagination' => $paginationHtml,
            'technicians' => $technicians,
        ]);
    }

    /**
     * Render custom pagination HTML based on the $technicians paginator
     */
    protected function renderCustomPagination(LengthAwarePaginator $technicians)
    {
        if ($technicians->lastPage() <= 1) {
            return ''; // No pagination needed
        }

        $currentPage = $technicians->currentPage();
        $lastPage = $technicians->lastPage();

        $pages = [];
        $maxBlock = 3; // Number of fixed links at start/end
        $middlePadding = 1; // Number of links on each side of current page

        // Add fixed start pages
        for ($i = 1; $i <= min($maxBlock, $lastPage); $i++) {
            $pages[] = $i;
        }

        // Add fixed end pages
        for ($i = $lastPage - $maxBlock + 1; $i <= $lastPage; $i++) {
            if ($i > 0) $pages[] = $i;
        }

        // Add current page and neighbors
        for ($i = $currentPage - $middlePadding; $i <= $currentPage + $middlePadding; $i++) {
            if ($i > 0 && $i <= $lastPage) $pages[] = $i;
        }

        // Remove duplicates and sort
        $pages = array_unique($pages);
        sort($pages);

        // Build final links with ellipses
        $finalLinks = [];
        $previousPage = 0;

        foreach ($pages as $page) {
            if ($page > $previousPage + 1) {
                $finalLinks[] = '...';
            }
            $finalLinks[] = $page;
            $previousPage = $page;
        }

        // Build the HTML
        $html = '<nav aria-label="Page navigation"><ul class="pagination shadow">';

        // Previous button
        $prevUrl = $technicians->previousPageUrl();
        $html .= '<li class="page-item ' . ($technicians->onFirstPage() ? 'disabled' : '') . '">';
        $html .= '<a class="page-link" href="' . ($prevUrl ? $prevUrl . '&' . http_build_query(request()->except($technicians->getPageName())) : '#') . '" aria-label="Previous">Previous</a>';
        $html .= '</li>';

        // Page links
        foreach ($finalLinks as $link) {
            if ($link === '...') {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            } else {
                $active = $link == $currentPage ? 'active' : '';
                $url = $technicians->url($link) . '&' . http_build_query(request()->except($technicians->getPageName()));
                $html .= '<li class="page-item ' . $active . '">';
                $html .= '<a class="page-link" href="' . $url . '">' . $link . '</a>';
                $html .= '</li>';
            }
        }

        // Next button
        $nextUrl = $technicians->nextPageUrl();
        $html .= '<li class="page-item ' . (!$technicians->hasMorePages() ? 'disabled' : '') . '">';
        $html .= '<a class="page-link" href="' . ($nextUrl ? $nextUrl . '&' . http_build_query(request()->except($technicians->getPageName())) : '#') . '" aria-label="Next">Next</a>';
        $html .= '</li>';

        $html .= '</ul></nav>';

        return $html;
    }


    /**
     * Get and validate search parameters
     */
    private function getSearchParams(Request $request): array
    {
        $usingDefault = false;

        $clientLatitude = (float) $request->input('latitude');
        $clientLongitude = (float) $request->input('longitude');
        $locationProvided = $request->has('latitude');

        if (!$locationProvided) {
            $clientLatitude = self::DEFAULT_LATITUDE;
            $clientLongitude = self::DEFAULT_LONGITUDE;
            $usingDefault = true;
        }

        $perPage = (int) $request->input('per_page', 15);
        $page = (int) $request->input('page', 1);

        $isValid = ($usingDefault && $clientLatitude !== 0.0) || ($locationProvided && $clientLatitude !== 0.0);

        // fallback non-zero
        $clientLatitude = $clientLatitude ?: self::DEFAULT_LATITUDE;
        $clientLongitude = $clientLongitude ?: self::DEFAULT_LONGITUDE;

        $homeService = filter_var($request->input('home_service', false), FILTER_VALIDATE_BOOLEAN);
        $tesdaVerified = filter_var($request->input('tesda_verified', false), FILTER_VALIDATE_BOOLEAN);
        $hasReviews = filter_var($request->input('has_reviews', false), FILTER_VALIDATE_BOOLEAN);
        $hasJobsCompleted = filter_var($request->input('has_jobs_completed', false), FILTER_VALIDATE_BOOLEAN);

        $searchQuery = $request->input('search_query', '');
        $searchTerms = collect(explode(',', $searchQuery))
            ->map(fn($term) => trim($term))
            ->filter()
            ->unique()
            ->toArray();

        return [
            'is_valid' => $isValid,
            'client_latitude' => $clientLatitude,
            'client_longitude' => $clientLongitude,

            'search_query' => $searchQuery,
            'search_terms' => $searchTerms,

            'address_query' => $request->input('address_query', ''),

            'max_distance_km' => (float) $request->input('distance_max', 1),
            'home_service' => $homeService,
            'tesda_verified' => $tesdaVerified,
            'has_reviews' => $hasReviews,
            'has_jobs_completed' => $hasJobsCompleted,
            'rating_min_score' => (float) $request->input('rating_min_score', 0.0),

            'expertise_ids' => (array) $request->input('expertise', []),
            'available_from' => $request->input('available_from', null),
            'available_to' => $request->input('available_to', null),

            'per_page' => $perPage > 0 ? $perPage : 15,
            'page' => $page > 0 ? $page : 1,
        ];
    }

    /**
     * Retrieve max values for normalization
     */
    private function getTechnicianMaxScores(): array
    {
        return [
            'max_weighted_score_rating' => DB::table('technicians')->max('weighted_score_rating') ?: 1,
            'max_success_rate' => DB::table('technicians')->max('success_rate') ?: 1,
            'max_jobs_completed' => DB::table('technicians')->max('jobs_completed') ?: 1,
        ];
    }

    /**
     * Apply initial filters before scoring
     */
    private function applyInitialDatasetReductionFilters(EloquentBuilder $query, array $params): EloquentBuilder
    {
        $query->when($params['home_service'], fn($q) => $q->where('home_service', true));
        $query->when($params['tesda_verified'], fn($q) => $q->where('tesda_verified', true));
        $query->when($params['has_reviews'], fn($q) => $q->where('weighted_score_rating', '>', 0));
        $query->when($params['has_jobs_completed'], fn($q) => $q->where('jobs_completed', '>', 0));

        $query->when(
            $params['rating_min_score'] > 0.0,
            fn($q) =>
            $q->where('weighted_score_rating', '>=', $params['rating_min_score'])
        );

        $expertiseIds = array_filter($params['expertise_ids']);
        if (!empty($expertiseIds)) {
            $query->whereHas(
                'expertiseCategories',
                fn($q) =>
                $q->whereIn('expertise_categories.id', $expertiseIds)
            );
        }

        if ($params['available_from'] && $params['available_to']) {
            $query->whereRaw('
                TIME(availability_start) <= TIME(?) AND TIME(availability_end) >= TIME(?)
            ', [
                $params['available_to'],
                $params['available_from']
            ]);
        }

        // Distance filter (Haversine)
        $query->whereRaw('
            6371 * ACOS(
                COS(RADIANS(?)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS(?))
                + SIN(RADIANS(?)) * SIN(RADIANS(latitude))
            ) <= ?
        ', [
            $params['client_latitude'],
            $params['client_longitude'],
            $params['client_latitude'],
            $params['max_distance_km']
        ]);

        // General search (name / expertise)
        $searchTerms = array_filter($params['search_terms']);
        if (!empty($searchTerms)) {
            $query->select('technicians.*');
            $query->join('users', 'technicians.technician_user_id', '=', 'users.id');

            $query->where(function (EloquentBuilder $q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $wildcardTerm = '%' . strtolower($term) . '%';

                    $q->orWhere(function ($subQ) use ($wildcardTerm) {
                        $subQ->whereRaw('LOWER(users.first_name) LIKE ?', [$wildcardTerm])
                            ->orWhereRaw('LOWER(users.middle_name) LIKE ?', [$wildcardTerm])
                            ->orWhereRaw('LOWER(users.last_name) LIKE ?', [$wildcardTerm]);
                    })
                        ->orWhereHas('expertiseCategories', function ($subQ) use ($wildcardTerm) {
                            $subQ->whereRaw('LOWER(expertise_categories.name) LIKE ?', [$wildcardTerm]);
                        });
                }
            });
        }




        // Address filter (new)
        $addressQuery = trim($params['address_query'] ?? '');

        if (!empty($addressQuery)) {
            $query->whereRaw('LOWER(technicians.address) LIKE ?', ['%' . strtolower($addressQuery) . '%']);
            Log::info('Applying address filter with query: ' . $addressQuery);
        }



        return $query;
    }

    /**
     * Apply scoring, ranking, and pagination, then attach Expertise
     */
    private function applyWeightedScoringAlgorithmAndPaginate(
        EloquentBuilder $preFilteredQuery,
        array $params,
        array $maxScores
    ): LengthAwarePaginator {
        $perPage = $params['per_page'];
        $currentPage = $params['page'];
        $offset = ($currentPage - 1) * $perPage;

        $bindings = $preFilteredQuery->getBindings();
        $baseSql = $preFilteredQuery->toSql();

        // 1. Get Total Count for Pagination
        $countQuerySql = "SELECT COUNT(t.id) FROM technicians AS t INNER JOIN ({$baseSql}) AS pft ON t.id = pft.id";
        $total = DB::selectOne($countQuerySql, $bindings)->{'COUNT(t.id)'};

        // 2. The Main Scoring Query
        $scoreQuery = "
            WITH PreFilteredTechnicians AS (
                {$baseSql}
            ),
            TechnicianScores AS (
                SELECT
                    t.id,
                    t.technician_user_id,
                    t.technician_code,
                    t.shop_id,
                    t.address,
                    t.latitude,
                    t.longitude,
                    t.tesda_verified,
                    t.home_service,
                    t.weighted_score_rating,
                    t.success_rate,
                    t.jobs_completed,
                    t.availability_start,
                    t.availability_end,

                    u.first_name,
                    u.middle_name,
                    u.last_name,

                    -- Distance Calculation (Haversine/Spherical Law)
                    (
                        CASE
                            WHEN t.latitude IS NULL OR t.longitude IS NULL THEN NULL
                            ELSE
                                6371 * ACOS(
                                    COS(RADIANS(?)) * COS(RADIANS(t.latitude)) * COS(RADIANS(t.longitude) - RADIANS(?))
                                    + SIN(RADIANS(?)) * SIN(RADIANS(t.latitude))
                                )
                        END
                    ) AS distance_km,

                    -- NORMALIZED RATING (Relative to Max in List)
                    COALESCE(t.weighted_score_rating / ?, 0.0) AS normalized_overall_rating_score,

                    -- NORMALIZED PROXIMITY (50km Cap)
                    (
                        CASE
                            WHEN t.latitude IS NULL OR t.longitude IS NULL THEN 0.0
                            ELSE
                                (50.0 - LEAST(
                                    (6371 * ACOS(
                                        COS(RADIANS(?)) * COS(RADIANS(t.latitude)) * COS(RADIANS(t.longitude) - RADIANS(?))
                                        + SIN(RADIANS(?)) * SIN(RADIANS(t.latitude))
                                    )), 50.0
                                )) / 50.0
                        END
                    ) AS normalized_proximity_score,

                    -- NORMALIZED TESDA (Binary)
                    COALESCE(t.tesda_verified, 0) AS normalized_tesda_certification_score,

                    -- NORMALIZED AVAILABILITY (Fixed: Uses 9-hour denominator & Safe Dates)
                    COALESCE(
                          CASE
                             WHEN t.availability_start IS NULL OR t.availability_end IS NULL THEN 0.0
                             ELSE
                             (
                                GREATEST(0, TIMESTAMPDIFF(SECOND,
                                    GREATEST(ADDTIME(CURDATE(), t.availability_start), CONCAT(CURDATE(), ' ', ?)),
                                    LEAST(ADDTIME(CURDATE(), t.availability_end), CONCAT(CURDATE(), ' ', ?))
                                ))
                             )
                             / 32400.0
                          END
                    , 0.0) AS normalized_availability_score,

                    -- NORMALIZED PERFORMANCE
                    COALESCE(t.success_rate / ?, 0.0) AS normalized_success_rate_score,
                    COALESCE(t.jobs_completed / ?, 0.0) AS normalized_jobs_completed_score
                FROM
                    technicians AS t
                INNER JOIN users AS u ON t.technician_user_id = u.id
                INNER JOIN PreFilteredTechnicians AS pft ON t.id = pft.id
            )
            SELECT
                ts.id AS technician_id,
                ts.*,
                CONCAT(ts.first_name, ' ', IFNULL(ts.middle_name, ''), ' ', ts.last_name) AS technician_full_name,
                (
                    ts.normalized_overall_rating_score * 0.24
                    + ts.normalized_proximity_score * 0.18
                    + ts.normalized_tesda_certification_score * 0.19
                    + ts.normalized_availability_score * 0.11
                    + ts.normalized_success_rate_score * 0.17
                    + ts.normalized_jobs_completed_score * 0.11
                ) AS final_weighted_score
            FROM
                TechnicianScores AS ts
            ORDER BY
                final_weighted_score DESC
            LIMIT ? OFFSET ?
        ";

        // 3. Prepare Bindings (Order MUST match the ? placeholders above)
        $scoreBindings = array_merge($bindings, [
            // Distance Calculation params (3)
            $params['client_latitude'],
            $params['client_longitude'],
            $params['client_latitude'],

            // Max Rating param (1)
            $maxScores['max_weighted_score_rating'],

            // Proximity Calculation params (3)
            $params['client_latitude'],
            $params['client_longitude'],
            $params['client_latitude'],

            // Availability params (2)
            $params['available_from'] ?? '00:00:00',
            $params['available_to'] ?? '23:59:59',

            // Max Performance params (2)
            $maxScores['max_success_rate'],
            $maxScores['max_jobs_completed'],

            // Pagination params (2)
            $perPage,
            $offset,
        ]);

        $results = DB::select($scoreQuery, $scoreBindings);

        // ---------------------------------------------------------
        // START: Eager Load Expertise for the Raw Results
        // ---------------------------------------------------------

        // 1. Get the IDs from the current page results
        $technicianIds = array_column($results, 'technician_id');

        if (!empty($technicianIds)) {
            // 2. Fetch the models with the relationship
            $techniciansWithExpertise = Technician::with('activeExpertiseCategories')
                ->whereIn('id', $technicianIds)
                ->get()
                ->keyBy('id'); // Key by ID for easy lookup

            // 3. Attach the expertise collection to the raw result objects
            foreach ($results as $result) {
                if (isset($techniciansWithExpertise[$result->technician_id])) {
                    $result->expertises = $techniciansWithExpertise[$result->technician_id]->activeExpertiseCategories;
                } else {
                    $result->expertises = collect();
                }
            }
        }

        // ---------------------------------------------------------
        // END: Eager Load Expertise
        // ---------------------------------------------------------

        $resultsCollection = collect($results);

        return new LengthAwarePaginator(
            $resultsCollection,
            $total,
            $perPage,
            $currentPage,
            ['path' => Request::capture()->url()]
        );
    }
}
