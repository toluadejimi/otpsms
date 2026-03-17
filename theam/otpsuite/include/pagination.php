<!-- Responsive Pagination -->
<nav class="mt-4">
    <ul class="pagination pagination-responsive align-items-center flex-wrap">
    <?php
        // Build base query string preserving filters
        $qs = [];
        if (isset($search) && $search !== '') $qs['search'] = urlencode($search);
        if (isset($statusFilter) && $statusFilter !== '') $qs['status'] = $statusFilter;

        function pageLink($p, $qs) {
            $qs['page'] = $p;
            return '?' . http_build_query($qs);
        }
    ?>

    <!-- First & Prev -->
    <li class="page-item <?= ($page <= 1 ? 'disabled' : '') ?>">
        <a class="page-link" href="<?= $page <= 1 ? '#' : pageLink(1, $qs) ?>" aria-label="First">
        <i class="ri-arrow-left-double-line"></i>
        </a>
    </li>
    <li class="page-item <?= ($page <= 1 ? 'disabled' : '') ?>">
        <a class="page-link" href="<?= $page <= 1 ? '#' : pageLink($page - 1, $qs) ?>" aria-label="Previous">
        <i class="ri-arrow-left-s-line"></i>
        </a>
    </li>

    <?php
    // Show page numbers (you can limit range)
    $start = max(1, $page - 2);
    $end = min($totalPages, $page + 2);
    for ($p = $start; $p <= $end; $p++): ?>
        <li class="page-item <?= ($p == $page ? 'active' : '') ?>">
        <a class="page-link" href="<?= pageLink($p, $qs) ?>"><?= $p ?></a>
        </li>
    <?php endfor; ?>

    <!-- Next & Last -->
    <li class="page-item <?= ($page >= $totalPages ? 'disabled' : '') ?>">
        <a class="page-link" href="<?= $page >= $totalPages ? '#' : pageLink($page + 1, $qs) ?>" aria-label="Next">
        <i class="ri-arrow-right-s-line"></i>
        </a>
    </li>
    <li class="page-item <?= ($page >= $totalPages ? 'disabled' : '') ?>">
        <a class="page-link" href="<?= $page >= $totalPages ? '#' : pageLink($totalPages, $qs) ?>" aria-label="Last">
        <i class="ri-arrow-right-double-line"></i>
        </a>
    </li>

    <!-- Mobile page indicator -->
    <li class="page-item d-block d-sm-none ms-auto">
        <span class="page-text">Page <?= $page ?> of <?= $totalPages ?></span>
    </li>
    </ul>
</nav>
<style>
    .pagination-responsive {
    gap: 3px;
    }

    .pagination-responsive .page-link {
        border-radius: 6px;
        min-width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.25rem 0.75rem;
    }

    .pagination-responsive .page-item.disabled .page-link {
        opacity: 0.5;
        pointer-events: none;
    }

    .page-text {
        color: #64748b;
        font-size: 0.875rem;
        padding: 0.5rem 0;
    }

    @media (max-width: 576px) {
    .pagination-responsive .page-link {
        min-width: 32px;
        height: 32px;
        padding: 0.2rem 0.5rem;
    }
    }
</style>