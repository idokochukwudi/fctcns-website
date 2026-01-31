<?php
/**
 * Pagination Middleware
 * Ensures proper pagination parameters
 */
class PaginationMiddleware {
    
    public static function validatePagination($page, $limit, $totalRecords) {
        $page = max(1, (int)$page);
        $limit = max(1, (int)$limit);
        
        $totalPages = ceil($totalRecords / $limit);
        
        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
        }
        
        return [
            'page' => $page,
            'limit' => $limit,
            'total_pages' => $totalPages,
            'offset' => ($page - 1) * $limit
        ];
    }
    
    public static function buildPaginationLinks($currentPage, $totalPages, $baseUrl, $queryParams = []) {
        $links = [];
        
        // First page
        if ($currentPage > 1) {
            $queryParams['page'] = 1;
            $links['first'] = $baseUrl . '?' . http_build_query($queryParams);
        }
        
        // Previous page
        if ($currentPage > 1) {
            $queryParams['page'] = $currentPage - 1;
            $links['prev'] = $baseUrl . '?' . http_build_query($queryParams);
        }
        
        // Next page
        if ($currentPage < $totalPages) {
            $queryParams['page'] = $currentPage + 1;
            $links['next'] = $baseUrl . '?' . http_build_query($queryParams);
        }
        
        // Last page
        if ($currentPage < $totalPages) {
            $queryParams['page'] = $totalPages;
            $links['last'] = $baseUrl . '?' . http_build_query($queryParams);
        }
        
        // Page numbers
        $startPage = max(1, $currentPage - 2);
        $endPage = min($totalPages, $currentPage + 2);
        
        for ($i = $startPage; $i <= $endPage; $i++) {
            $queryParams['page'] = $i;
            $links['pages'][$i] = $baseUrl . '?' . http_build_query($queryParams);
        }
        
        return $links;
    }
}