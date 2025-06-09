<?php include 'app/views/shares/header.php'; ?>

<!-- Admin Header -->
<div class="admin-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="admin-title">
                    <i class="fas fa-tasks me-2"></i>Quản lý sản phẩm
                </h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-md-end mb-0">
                        <li class="breadcrumb-item"><a href="/webbanhang/Product/">Trang chủ</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Quản lý sản phẩm</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-md-6 mb-4 mb-md-0">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Thao tác nhanh</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-4">
                        <a href="/webbanhang/Product/add" class="btn btn-primary w-100">
                            <i class="fas fa-plus-circle me-1"></i>Thêm sản phẩm
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a href="/webbanhang/Product/categories" class="btn btn-outline-primary w-100" id="manageCategoriesLink">
                            <i class="fas fa-tags me-1"></i>Quản lý danh mục
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a href="/webbanhang/Product/manageOrders" class="btn btn-outline-success w-100">
                            <i class="fas fa-clipboard-list me-1"></i>Quản lý đơn hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Thống kê nhanh</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3 text-primary">
                                <i class="fas fa-box-open fa-2x"></i>
                            </div>
                            <div>
                                <div class="small text-muted">Tổng sản phẩm</div>
                                <div class="fw-bold"><?php echo $pagination['total']; ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3 text-success">
                                <i class="fas fa-tags fa-2x"></i>
                            </div>
                            <div>
                                <div class="small text-muted">Tổng danh mục</div>
                                <div class="fw-bold"><?php echo count($categories); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3 text-warning">
                                <i class="fas fa-money-bill-wave fa-2x"></i>
                            </div>
                            <div>
                                <div class="small text-muted">Giá trung bình</div>
                                <div class="fw-bold"><?php echo number_format($avgPrice, 0, ',', '.'); ?> VND</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="card mb-4 border-0 shadow-sm">
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

<!-- Products Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Danh sách sản phẩm</h5>
            <div class="d-flex align-items-center">
                <label class="me-2 text-nowrap">Sắp xếp theo:</label>                <select class="form-select form-select-sm" style="width: 200px;" id="sortOptions">
                    <option value="newest" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'newest') ? 'selected' : ''; ?>>Mới nhất</option>
                    <option value="price_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : ''; ?>>Giá: Thấp đến cao</option>
                    <option value="price_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : ''; ?>>Giá: Cao đến thấp</option>
                    <option value="name_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'name_asc') ? 'selected' : ''; ?>>Tên: A-Z</option>
                    <option value="name_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'name_desc') ? 'selected' : ''; ?>>Tên: Z-A</option>
                    <option value="status_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'status_asc') ? 'selected' : ''; ?>>Trạng thái: Đang hiện trước</option>
                    <option value="status_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'status_desc') ? 'selected' : ''; ?>>Trạng thái: Đã ẩn trước</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>                        <th scope="col" width="60">#</th>                        <th scope="col" width="80">Hình ảnh</th>
                        <th scope="col">Tên sản phẩm</th>
                        <th scope="col">Danh mục</th>
                        <th scope="col">Giá</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col" width="150">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($products) > 0): ?>
                        <?php foreach ($products as $index => $product): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td>
                                <?php if ($product->image): ?>
                                    <img src="/webbanhang/<?php echo $product->image; ?>" alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/50x50?text=No+Image" alt="No Image" class="img-fluid rounded">
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-medium"><?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></div>
                                <div class="small text-muted text-truncate" style="max-width: 300px;">
                                    <?php echo substr(htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'), 0, 50) . '...'; ?>
                                </div>
                            </td>                            <td>
                                <span class="badge bg-light text-dark">
                                    <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>                            <td><?php echo number_format($product->price, 0, ',', '.'); ?> VND</td>
                            <td>
                                <span class="badge <?php echo $product->status == 'available' ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo $product->status == 'available' ? 'Đang hiện' : 'Đã ẩn'; ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" class="btn btn-outline-primary" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>                                    
                                    <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-outline-success" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>                                    
                                    <button type="button" 
                                            class="btn <?php echo $product->status == 'available' ? 'btn-outline-danger' : 'btn-outline-success'; ?> toggle-status" 
                                            data-product-id="<?php echo $product->id; ?>"
                                            data-current-status="<?php echo $product->status; ?>"
                                            title="<?php echo $product->status == 'available' ? 'Ẩn sản phẩm' : 'Hiện sản phẩm'; ?>">
                                        <i class="fas <?php echo $product->status == 'available' ? 'fa-eye-slash' : 'fa-eye'; ?>"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-box fa-3x mb-3"></i>
                                    <p>Không có sản phẩm nào. Hãy thêm sản phẩm mới!</p>
                                    <a href="/webbanhang/Product/add" class="btn btn-primary">
                                        <i class="fas fa-plus-circle me-1"></i>Thêm sản phẩm
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="text-muted">Hiển thị <?php echo count($products); ?> sản phẩm (tổng số: <?php echo $pagination['total']; ?>)</span>
            </div>
            <?php if (count($products) > 0 && $pagination['totalPages'] > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination mb-0">
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
    </div>
</div>

<style>
    /* Tùy chỉnh badge trạng thái */
    .badge.bg-success, .badge.bg-danger {
        font-size: 0.875rem;
        padding: 0.4em 0.8em;
    }
    
    /* Badge cho danh mục */
    .badge.bg-light {
        font-size: 0.875rem;
        padding: 0.4em 0.8em;
        border: 1px solid #dee2e6;
    }

    /* Thêm icon cho trạng thái */
    .badge.bg-success::before {
        content: "•";
        margin-right: 5px;
    }
    
    .badge.bg-danger::before {
        content: "•";
        margin-right: 5px;
    }

    .product-thumbnail {
        width: 50px;
        height: 50px;
        object-fit: cover;
    }

    .btn-group .btn {
        margin: 0 2px;
    }

    .toggle-status:focus {
        box-shadow: none;
    }

    .toggle-status:hover {
        opacity: 0.8;
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

            // Đảm bảo luôn giữ ở trang quản lý
            if (currentUrl.pathname.indexOf('manage') === -1) {
                currentUrl.pathname = '/webbanhang/Product/manage';
            }

            window.location.href = currentUrl.toString();
        });

        // Sort options
        const sortOptions = document.getElementById('sortOptions');
        if (sortOptions) {
            sortOptions.addEventListener('change', function() {
                const sortValue = this.value;
                const currentUrl = new URL(window.location.href);

                if (sortValue) {
                    currentUrl.searchParams.set('sort', sortValue);
                } else {
                    currentUrl.searchParams.delete('sort');
                }

                // Đảm bảo luôn giữ ở trang quản lý
                if (currentUrl.pathname.indexOf('manage') === -1) {
                    currentUrl.pathname = '/webbanhang/Product/manage';
                }                window.location.href = currentUrl.toString();
            });
        }

        // Xử lý toggle status bằng AJAX
        document.querySelectorAll('.toggle-status').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.productId;
                const currentStatus = this.dataset.currentStatus;                const button = this;
                // Sửa lại cách chọn badge trạng thái - chỉ chọn badge trong cột trạng thái
                const tr = button.closest('tr');
                const statusCell = tr.querySelector('td:nth-child(6)'); // Cột thứ 6 là cột trạng thái
                const statusBadge = statusCell.querySelector('.badge');
                
                if (!confirm(`Bạn có chắc chắn muốn ${currentStatus === 'available' ? 'ẩn' : 'hiện'} sản phẩm này?`)) {
                    return;
                }                // Thêm console.log để debug
                console.log('Sending request:', {
                    product_id: productId,
                    status: currentStatus === 'available' ? 'unavailable' : 'available'
                });
                
                fetch(`/webbanhang/Product/toggleStatus/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `status=${currentStatus === 'available' ? 'unavailable' : 'available'}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Cập nhật giao diện
                        const newStatus = data.new_status;
                        
                        // Cập nhật nút
                        button.dataset.currentStatus = newStatus;
                        button.classList.toggle('btn-outline-danger');
                        button.classList.toggle('btn-outline-success');
                        button.title = newStatus === 'available' ? 'Ẩn sản phẩm' : 'Hiện sản phẩm';
                        button.querySelector('i').classList.remove('fa-eye', 'fa-eye-slash');
                        button.querySelector('i').classList.add(newStatus === 'available' ? 'fa-eye-slash' : 'fa-eye');
                        
                        // Cập nhật badge trạng thái
                        statusBadge.classList.remove('bg-success', 'bg-danger');
                        statusBadge.classList.add(newStatus === 'available' ? 'bg-success' : 'bg-danger');
                        statusBadge.textContent = newStatus === 'available' ? 'Đang hiện' : 'Đã ẩn';
                    } else {
                        alert('Không thể thay đổi trạng thái sản phẩm!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Đã xảy ra lỗi khi thay đổi trạng thái sản phẩm!');
                });
            });
        });
    });
</script>

<?php include 'app/views/shares/footer.php'; ?>
