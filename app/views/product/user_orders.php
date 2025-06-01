<?php require_once 'app/views/shares/header.php'; ?>

<div class="container py-4">
    <h2 class="mb-4">Đơn hàng của tôi</h2>
    
    <?php if (empty($orders)): ?>
    <div class="alert alert-info">
        Bạn chưa có đơn hàng nào.
    </div>
    <?php else: ?>
    <div class="row">
        <?php foreach ($orders as $order): ?>
        <div class="col-12 mb-4">
            <div class="card">                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="mb-0">Đơn hàng #<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></h5>
                            <span class="badge bg-<?= $order['status_info']['class'] ?>">
                                <i class="<?= $order['status_info']['icon'] ?> me-1"></i>
                                <?= $order['status_info']['name'] ?>
                            </span>
                        </div>
                        <small class="text-muted">Ngày đặt: <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></small>
                    </div>
                    <div class="text-end">
                        <div class="h5 mb-0">Tổng tiền: <?= number_format($order['total_amount'], 0, ',', '.') ?>đ</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Người nhận:</strong> <?= htmlspecialchars($order['name']) ?></p>
                            <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($order['phone']) ?></p>
                            <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['address']) ?></p>
                            <?php if (!empty($order['notes'])): ?>
                            <p><strong>Ghi chú:</strong> <?= htmlspecialchars($order['notes']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <h6 class="mt-3">Chi tiết đơn hàng:</h6>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Hình ảnh</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order['details'] as $item): ?>
                                <tr>                                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                                    <td>
                                        <?php if ($item['image']): ?>
                                            <img src="/webbanhang/<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" style="width: 50px; height: 50px; object-fit: cover;" class="img-thumbnail">
                                        <?php else: ?>
                                            <img src="https://via.placeholder.com/50x50?text=No+Image" alt="No Image" class="img-thumbnail">
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?= $item['quantity'] ?></td>
                                    <td class="text-end"><?= number_format($item['price'], 0, ',', '.') ?>đ</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php if ($pagination['total_pages'] > 1): ?>
    <nav aria-label="Phân trang đơn hàng">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
            <li class="page-item <?= $i == $pagination['current_page'] ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once 'app/views/shares/footer.php'; ?>
