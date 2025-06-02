<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4">
                        <i class="fas fa-shield-alt text-primary mb-3" style="font-size: 3rem;"></i>
                        <div class="mt-2">Xác minh tài khoản</div>
                    </h2>                    <p class="text-center mb-4">
                        Để xác minh danh tính của bạn, vui lòng chọn sản phẩm mà bạn đã từng đặt mua:
                    </p>
                    <?php if (isset($_SESSION['verify_attempts'])): ?>
                    <div class="text-center mb-4">
                        <span class="badge bg-info">
                            <i class="fas fa-info-circle me-1"></i>
                            Lần thử thứ <?php echo $_SESSION['verify_attempts']; ?>/3
                        </span>
                    </div>
                    <?php endif; ?>
                    <form action="/webbanhang/Account/verifyPurchase" method="POST" class="needs-validation" novalidate>
                        <div class="row g-4">
                            <?php foreach ($products as $product): ?>
                            <div class="col-md-4">
                                <div class="card h-100">
                                    <img src="/webbanhang/uploads/<?php echo htmlspecialchars($product['image'] ?? 'default.jpg'); ?>" 
                                         class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>"
                                         style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="selected_product" 
                                                   value="<?php echo $product['id']; ?>" required>
                                            <label class="form-check-label">
                                                Đây là sản phẩm tôi đã mua
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>                        <?php if (isset($_SESSION['errors']['verify'])): ?>
                        <div class="alert alert-danger mt-3">
                            <div class="text-center">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?php echo $_SESSION['errors']['verify']; ?>
                            </div>
                            <?php if (isset($_SESSION['verify_attempts']) && $_SESSION['verify_attempts'] < 3): ?>
                            <hr>
                            <div class="text-center small">
                                <i class="fas fa-info-circle me-1"></i>
                                Bạn còn <?php echo 3 - $_SESSION['verify_attempts']; ?> lần thử
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary py-2" <?php echo (isset($_SESSION['verify_attempts']) && $_SESSION['verify_attempts'] >= 3) ? 'disabled' : ''; ?>>
                                <i class="fas fa-check me-2"></i>Xác nhận
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<?php 
// Chỉ xóa session errors sau khi đã render toàn bộ trang
unset($_SESSION['errors']); 
?>