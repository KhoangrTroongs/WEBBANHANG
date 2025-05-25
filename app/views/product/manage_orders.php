<?php include 'app/views/shares/header.php'; ?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/webbanhang/Product/">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="/webbanhang/Product/manage">Quản lý</a></li>
        <li class="breadcrumb-item active" aria-current="page">Quản lý đơn hàng</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-clipboard-list me-2"></i>Quản lý đơn hàng</h2>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0"><?php echo number_format($statistics->total_orders); ?></h4>
                        <p class="mb-0">Tổng đơn hàng</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-shopping-cart fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0"><?php echo number_format($statistics->total_revenue, 0, ',', '.'); ?> VND</h4>
                        <p class="mb-0">Tổng doanh thu</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0"><?php echo number_format($statistics->today_orders); ?></h4>
                        <p class="mb-0">Đơn hàng hôm nay</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-day fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0"><?php echo number_format($statistics->today_revenue, 0, ',', '.'); ?> VND</h4>
                        <p class="mb-0">Doanh thu hôm nay</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/webbanhang/Product/manageOrders" class="row g-3">
            <div class="col-md-8">
                <label for="search" class="form-label">Tìm kiếm đơn hàng</label>
                <input type="text"
                       class="form-control"
                       id="search"
                       name="search"
                       placeholder="Nhập tên khách hàng hoặc số điện thoại..."
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8') : ''; ?>">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search me-1"></i>Tìm kiếm
                </button>
                <a href="/webbanhang/Product/manageOrders" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Xóa bộ lọc
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>Danh sách đơn hàng
            <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                <small class="text-muted">(Kết quả tìm kiếm cho: "<?php echo htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8'); ?>")</small>
            <?php endif; ?>
        </h5>
    </div>
    <div class="card-body">
        <?php if (empty($orders)): ?>
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Không có đơn hàng nào</h4>
                <p class="text-muted">
                    <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                        Không tìm thấy đơn hàng phù hợp với từ khóa tìm kiếm.
                    <?php else: ?>
                        Chưa có đơn hàng nào được tạo.
                    <?php endif; ?>
                </p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Tên khách hàng</th>
                            <th>Số điện thoại</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $orderModel = new OrderModel($db ?? null);
                        foreach ($orders as $order):
                            $currentStatus = $order->status ?? 'pending';
                            $statusInfo = $orderModel->getStatusInfo($currentStatus);
                            $validStatuses = $orderModel->getValidStatuses();
                        ?>
                            <tr>
                                <td>
                                    <span class="fw-bold text-primary">#<?php echo str_pad($order->id, 6, '0', STR_PAD_LEFT); ?></span>
                                </td>
                                <td>
                                    <div>
                                        <strong><?php echo htmlspecialchars($order->name, ENT_QUOTES, 'UTF-8'); ?></strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted"><?php echo htmlspecialchars($order->phone, ENT_QUOTES, 'UTF-8'); ?></span>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">
                                        <?php echo number_format($order->total_amount, 0, ',', '.'); ?> VND
                                    </span>
                                </td>
                                <td>
                                    <?php if ($statusInfo): ?>
                                        <span class="badge bg-<?php echo $statusInfo['class']; ?>">
                                            <i class="<?php echo $statusInfo['icon']; ?> me-1"></i>
                                            <?php echo $statusInfo['name']; ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Không xác định</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div>
                                        <small class="text-muted">
                                            <?php echo date('d/m/Y', strtotime($order->created_at)); ?>
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            <?php echo date('H:i', strtotime($order->created_at)); ?>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="/webbanhang/Product/viewOrder/<?php echo $order->id; ?>"
                                           class="btn btn-sm btn-primary"
                                           title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                    type="button"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false"
                                                    title="Cập nhật trạng thái">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <?php foreach ($validStatuses as $statusKey => $statusData): ?>
                                                    <?php if ($statusKey !== $currentStatus && $orderModel->canChangeStatus($currentStatus, $statusKey)): ?>
                                                        <li>
                                                            <a class="dropdown-item"
                                                               href="#"
                                                               onclick="updateOrderStatus(<?php echo $order->id; ?>, '<?php echo $statusKey; ?>', '<?php echo $statusData['name']; ?>')">
                                                                <i class="<?php echo $statusData['icon']; ?> me-2"></i>
                                                                <?php echo $statusData['name']; ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($pagination['totalPages'] > 1): ?>
                <nav aria-label="Phân trang đơn hàng" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <!-- Previous Page -->
                        <?php if ($pagination['page'] > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $pagination['page'] - 1; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        <?php endif; ?>

                        <!-- Page Numbers -->
                        <?php
                        $startPage = max(1, $pagination['page'] - 2);
                        $endPage = min($pagination['totalPages'], $pagination['page'] + 2);

                        for ($i = $startPage; $i <= $endPage; $i++):
                        ?>
                            <li class="page-item <?php echo $i == $pagination['page'] ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <!-- Next Page -->
                        <?php if ($pagination['page'] < $pagination['totalPages']): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $pagination['page'] + 1; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>

                <!-- Pagination Info -->
                <div class="text-center text-muted">
                    Hiển thị <?php echo (($pagination['page'] - 1) * $pagination['limit']) + 1; ?> -
                    <?php echo min($pagination['page'] * $pagination['limit'], $pagination['total']); ?>
                    trong tổng số <?php echo $pagination['total']; ?> đơn hàng
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- JavaScript for status update -->
<script>
function updateOrderStatus(orderId, newStatus, statusName) {
    if (confirm(`Bạn có chắc chắn muốn cập nhật trạng thái đơn hàng #${String(orderId).padStart(6, '0')} thành "${statusName}"?`)) {
        // Tạo form ẩn để submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/webbanhang/Product/updateOrderStatus';

        // Thêm order_id
        const orderIdInput = document.createElement('input');
        orderIdInput.type = 'hidden';
        orderIdInput.name = 'order_id';
        orderIdInput.value = orderId;
        form.appendChild(orderIdInput);

        // Thêm status
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = newStatus;
        form.appendChild(statusInput);

        // Submit form
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php include 'app/views/shares/footer.php'; ?>
