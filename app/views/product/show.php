<?php include 'app/views/shares/header.php'; ?>
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h2 class="mb-0">Chi tiết sản phẩm</h2>
        </div>
        <div class="card-body">
            <?php if ($product): ?>
                <div class="row">
                    <div class="col-md-6">
                        <?php if ($product->image): ?>
                            <img src="/webbanhang/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>"
                                 class="img-fluid rounded"
                                 alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php else: ?>
                            <img src="/webbanhang/images/no-image.png"
                                 class="img-fluid rounded"
                                 alt="Không có ảnh">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <h3 class="card-title text-dark font-weight-bold">
                            <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                        </h3>
                        <p class="card-text">
                            <?php echo nl2br(htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8')); ?>
                        </p>
                        <p class="text-danger font-weight-bold h4">
                            <?php echo number_format($product->price, 0, ',', '.'); ?> VND
                        </p>
                        <p>
                            <strong>Danh mục:</strong>
                            <span class="badge bg-info text-white">
                                <?php echo !empty($product->category_name)
                                    ? htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8')
                                    : 'Chưa có danh mục'; ?>
                            </span>
                        </p>
                        <div class="mt-4">
                            <div class="d-grid gap-2 d-md-flex">
                                <a href="/webbanhang/Product/addToCart/<?php echo $product->id; ?>"
                                   class="btn btn-success btn-lg flex-md-fill">
                                    <i class="fas fa-cart-plus me-2"></i>Thêm vào giỏ hàng
                                </a>
                                <a href="/webbanhang/Product/list" class="btn btn-outline-secondary btn-lg flex-md-fill">
                                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-danger text-center">
                    <h4>Không tìm thấy sản phẩm!</h4>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>