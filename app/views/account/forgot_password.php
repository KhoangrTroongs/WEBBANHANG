<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4">
                        <i class="fas fa-key text-primary mb-3" style="font-size: 3rem;"></i>
                        <div class="mt-2">Quên mật khẩu</div>
                    </h2>
                    <form action="/webbanhang/Account/verifyUsername" method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="username" class="form-label">Tên đăng nhập</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control <?php echo isset($_SESSION['errors']['username']) ? 'is-invalid' : ''; ?>" 
                                    id="username" name="username" required>
                                <div class="invalid-feedback">
                                    <?php echo $_SESSION['errors']['username'] ?? 'Vui lòng nhập tên đăng nhập'; ?>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary py-2">
                                <i class="fas fa-arrow-right me-2"></i>Tiếp tục
                            </button>
                        </div>

                        <div class="text-center">
                            <a href="/webbanhang/Account/login" class="text-secondary text-decoration-none">
                                <i class="fas fa-arrow-left me-1"></i>Quay lại đăng nhập
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});
</script>

<?php 
unset($_SESSION['errors']);
include 'app/views/shares/footer.php'; 
?>