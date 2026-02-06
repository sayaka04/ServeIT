{{-- Pagination Links - Custom Design --}}
@if ($technicians->lastPage() > 1)

<div class="col-12">

    <div class="container-fluid d-flex justify-content-center align-items-center">
        <nav aria-label="Page navigation">
            <ul class="pagination shadow">

                {{-- Previous Page Link --}}
                <li class="page-item @if ($technicians->onFirstPage()) disabled @endif">
                    <a class="page-link" href="{{ $technicians->previousPageUrl() ? $technicians->previousPageUrl() . '&' . http_build_query(Request::except($technicians->getPageName())) : '#' }}" aria-label="Previous">
                        Previous
                    </a>
                </li>

                {{-- TRUNCATED PAGINATION ELEMENTS - FIXED START/END WITH ELLIPSES --}}
                @php
                $currentPage = $technicians->currentPage();
                $lastPage = $technicians->lastPage();
                $pages = [];
                $maxBlock = 3; // Number of fixed links to show at the start and end
                $middlePadding = 1; // Number of links to show on either side of the current page

                // 1. Add fixed start pages (1, 2, 3)
                for ($i = 1; $i <= min($maxBlock, $lastPage); $i++) {
                    $pages[]=$i;
                    }

                    // 2. Add fixed end pages (last-2, last-1, last)
                    for ($i=$lastPage - $maxBlock + 1; $i <=$lastPage; $i++) {
                    if ($i> 0) $pages[] = $i;
                    }

                    // 3. Add current page and neighbors (C-1, C, C+1)
                    for ($i = $currentPage - $middlePadding; $i <= $currentPage + $middlePadding; $i++) {
                        if ($i> 0 && $i <= $lastPage) $pages[]=$i;
                            }

                            // 4. Remove duplicates and sort
                            $pages=array_unique($pages);
                            sort($pages);

                            // 5. Build final list, inserting '...' for gaps
                            $finalLinks=[];
                            $previousPage=0;

                            foreach ($pages as $page) {
                            // Insert ellipsis if there is a gap greater than 1
                            if ($page> $previousPage + 1) {
                            $finalLinks[] = '...';
                            }

                            $finalLinks[] = $page;
                            $previousPage = $page;
                            }
                            @endphp

                            @foreach ($finalLinks as $link)
                            @if ($link === '...')
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                            @else
                            <li class="page-item @if ($link == $currentPage) active @endif">
                                {{-- Ensure all search query params are included in the page link --}}
                                <a class="page-link" href="{{ $technicians->url($link) . '&' . http_build_query(Request::except($technicians->getPageName())) }}">{{ $link }}</a>
                            </li>
                            @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            <li class="page-item @if (!$technicians->hasMorePages()) disabled @endif">
                                <a class="page-link" href="{{ $technicians->nextPageUrl() ? $technicians->nextPageUrl() . '&' . http_build_query(Request::except($technicians->getPageName())) : '#' }}" aria-label="Next">
                                    Next
                                </a>
                            </li>

            </ul>
        </nav>
    </div>
    <br><br>

</div>
@endif