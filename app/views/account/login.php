<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4">
                        <i class="fas fa-user-circle text-primary mb-3" style="font-size: 3rem;"></i>
                        <div class="mt-2">Đăng nhập</div>
                    </h2>
                      <form action="/webbanhang/Account/checkLogin" method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="username" class="form-label">Tên đăng nhập</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>                                <input type="text" class="form-control <?php echo isset($_SESSION['errors']['login']) ? 'is-invalid' : ''; ?>" 
                                    id="username" name="username" required>
                                <div class="invalid-feedback">
                                    <?php echo $_SESSION['errors']['login'] ?? 'Vui lòng nhập tên đăng nhập'; ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>                                <input type="password" class="form-control <?php echo isset($_SESSION['errors']['login']) ? 'is-invalid' : ''; ?>" 
                                    id="password" name="password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <div class="invalid-feedback">
                                    <?php echo $_SESSION['errors']['login'] ?? 'Vui lòng nhập mật khẩu'; ?>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary py-2">
                                <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                            </button>
                        </div>                        <div class="text-center">
                            <p class="mb-0">Quên mật khẩu? 
                                <a href="/webbanhang/Account/forgotPassword" class="text-primary text-decoration-none">
                                    Khôi phục ngay
                                </a>
                            </p>
                            <p class="mt-2 mb-0">Chưa có tài khoản? 
                                <a href="/webbanhang/Account/register" class="text-primary text-decoration-none">
                                    Đăng ký ngay
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });

    // Password visibility toggle
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
});
</script>

<?php 
// Clean up session data after displaying
unset($_SESSION['errors']);
?>

<?php include 'app/views/shares/footer.php'; ?>