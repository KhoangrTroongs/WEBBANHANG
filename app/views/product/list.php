<?php include 'app/views/shares/header.php'; ?>

<!-- Page Header -->
<div class="admin-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="admin-title">
                    <i class="fas fa-box-open me-2"></i>Danh sách sản phẩm
                </h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-md-end mb-0">
                        <li class="breadcrumb-item"><a href="/webbanhang/Product/">Trang chủ</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Danh sách sản phẩm</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
    <!-- Search and Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <form action="/webbanhang/Product/search" method="GET" class="d-flex">
                        <input type="text" class="form-control me-2" name="keyword" placeholder="Tìm kiếm sản phẩm..." value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Tìm kiếm
                        </button>
                    </form>
                </div>
                <div class="col-md-6 d-flex justify-content-md-end">
                    <div class="d-flex">
                        <select class="form-select me-2" id="categoryFilter">
                            <option value="">Tất cả danh mục</option>
                            <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category->id; ?>" <?php echo (isset($_GET['category']) && $_GET['category'] == $category->id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <a href="/webbanhang/Product/add" class="btn btn-success">
                            <i class="fas fa-plus-circle me-1"></i>Thêm sản phẩm
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sort Options -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0">Hiển thị <?php echo count($products); ?> sản phẩm (tổng số: <?php echo $pagination['total']; ?>)</h5>
        </div>
        <div class="d-flex align-items-center">
            <label class="me-2 text-nowrap">Sắp xếp theo:</label>
            <select class="form-select form-select-sm" style="width: 200px;" id="sortOptions">
                <option value="newest" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'newest') ? 'selected' : ''; ?>>Mới nhất</option>
                <option value="price_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : ''; ?>>Giá: Thấp đến cao</option>
                <option value="price_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : ''; ?>>Giá: Cao đến thấp</option>
                <option value="name_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'name_asc') ? 'selected' : ''; ?>>Tên: A-Z</option>
                <option value="name_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'name_desc') ? 'selected' : ''; ?>>Tên: Z-A</option>
            </select>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-4">
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
            <div class="col">
                <div class="card h-100 product-card border-0 shadow-sm">
                    <div class="position-relative">
                        <?php if ($product->image): ?>
                            <img src="/webbanhang/<?php echo $product->image; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top" alt="No Image" style="height: 200px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="position-absolute top-0 end-0 p-2">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="/webbanhang/Product/show/<?php echo $product->id; ?>">
                                            <i class="fas fa-eye me-2 text-primary"></i>Xem chi tiết
                                        </a>
                                    </li>                                    
                                    <li>
                                        <a class="dropdown-item text-danger" href="/webbanhang/Product/delete/<?php echo $product->id; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                            <i class="fas fa-trash-alt me-2"></i>Xóa
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <span class="position-absolute top-0 start-0 m-2 badge bg-<?php echo $product->category_name ? 'primary' : 'secondary'; ?>">
                            <?php echo htmlspecialchars($product->category_name ?: 'Chưa phân loại', ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-2">
                            <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" class="text-decoration-none text-dark product-link">
                                <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </h5>
                        <p class="card-text text-muted small flex-grow-1">
                            <?php echo substr(htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'), 0, 100) . (strlen($product->description) > 100 ? '...' : ''); ?>
                        </p>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="price fw-bold"><?php echo number_format($product->price, 0, ',', '.'); ?> VND</span>
                <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye me-1"></i>Chi tiết
                                </a>
                            </div>
                            <div class="d-grid">
                                <a href="/webbanhang/Product/addToCart/<?php echo $product->id; ?>" class="btn btn-success btn-sm">
                                    <i class="fas fa-cart-plus me-1"></i>Thêm vào giỏ hàng
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-info-circle fa-3x mb-3"></i>
                    <h4>Không tìm thấy sản phẩm nào</h4>
                    <p>Không có sản phẩm nào phù hợp với tiêu chí tìm kiếm của bạn.</p>
                    <a href="/webbanhang/Product/add" class="btn btn-primary mt-3">
                        <i class="fas fa-plus-circle me-1"></i>Thêm sản phẩm mới
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if (count($products) > 0 && $pagination['totalPages'] > 1): ?>
    <nav aria-label="Page navigation" class="my-4">
        <ul class="pagination justify-content-center">
            <?php
            // Tạo URL cơ sở cho phân trang
            $currentUrl = $_SERVER['REQUEST_URI'];
            $currentPage = $pagination['page'];
            $totalPages = $pagination['totalPages'];

            // Hàm tạo URL phân trang
            function buildPaginationUrl($url, $page) {
                // Phân tích URL hiện tại
                $parsedUrl = parse_url($url);
                $query = [];

                // Phân tích query string nếu có
                if (isset($parsedUrl['query'])) {
                    parse_str($parsedUrl['query'], $query);
                }

                // Cập nhật tham số page
                $query['page'] = $page;

                // Tạo lại query string
                $newQuery = http_build_query($query);

                // Tạo URL mới
                $path = $parsedUrl['path'];
                return $path . '?' . $newQuery;
            }

            // Nút Trước
            $prevDisabled = ($currentPage <= 1) ? 'disabled' : '';
            $prevUrl = ($currentPage > 1) ? buildPaginationUrl($currentUrl, $currentPage - 1) : '#';
            ?>

            <li class="page-item <?php echo $prevDisabled; ?>">
                <a class="page-link" href="<?php echo $prevUrl; ?>" tabindex="-1" aria-disabled="<?php echo ($prevDisabled) ? 'true' : 'false'; ?>">Trước</a>
            </li>

            <?php
            // Hiển thị các trang
            $startPage = max(1, $currentPage - 2);
            $endPage = min($totalPages, $startPage + 4);

            if ($endPage - $startPage < 4 && $startPage > 1) {
                $startPage = max(1, $endPage - 4);
            }

            for ($i = $startPage; $i <= $endPage; $i++) {
                $active = ($i == $currentPage) ? 'active' : '';
                $pageUrl = buildPaginationUrl($currentUrl, $i);
            ?>
                <li class="page-item <?php echo $active; ?>">
                    <a class="page-link" href="<?php echo $pageUrl; ?>"><?php echo $i; ?></a>
                </li>
            <?php } ?>

            <?php
            // Nút Sau
            $nextDisabled = ($currentPage >= $totalPages) ? 'disabled' : '';
            $nextUrl = ($currentPage < $totalPages) ? buildPaginationUrl($currentUrl, $currentPage + 1) : '#';
            ?>

            <li class="page-item <?php echo $nextDisabled; ?>">
                <a class="page-link" href="<?php echo $nextUrl; ?>">Sau</a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>
</div>

<!-- Custom CSS for Product Cards -->
<style>
    .product-card {
        transition: transform 0.3s, box-shadow 0.3s;
        overflow: hidden;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .product-card .card-img-top {
        transition: transform 0.5s;
    }

    .product-card:hover .card-img-top {
        transform: scale(1.05);
    }

    .product-link {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        height: 2.5rem;
    }

    .price {
        color: #e74a3b;
    }

    .admin-header {
        background-color: #4e73df;
        color: white;
        padding: 1.5rem 0;
        margin-bottom: 2rem;
    }

    .breadcrumb-item a {
        color: white;
        opacity: 0.8;
        text-decoration: none;
    }

    .breadcrumb-item a:hover {
        opacity: 1;
    }

    .breadcrumb-item.active {
        color: white;
        opacity: 0.6;
    }

    .breadcrumb-item+.breadcrumb-item::before {
        color: white;
        opacity: 0.6;
    }
</style>

<!-- JavaScript for Filters -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Category filter
        const categoryFilter = document.getElementById('categoryFilter');
        categoryFilter.addEventListener('change', function() {
            const categoryId = this.value;
            const currentUrl = new URL(window.location.href);

            if (categoryId) {
                currentUrl.searchParams.set('category', categoryId);
            } else {
                currentUrl.searchParams.delete('category');
            }

            window.location.href = currentUrl.toString();
        });

        // Sort options
        const sortOptions = document.getElementById('sortOptions');
        sortOptions.addEventListener('change', function() {
            const sortValue = this.value;
            const currentUrl = new URL(window.location.href);

            if (sortValue) {
                currentUrl.searchParams.set('sort', sortValue);
            } else {
                currentUrl.searchParams.delete('sort');
            }

            window.location.href = currentUrl.toString();
        });
    });
</script>

<?php include 'app/views/shares/footer.php'; ?>