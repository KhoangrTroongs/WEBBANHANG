<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4">
                        <i class="fas fa-lock text-primary mb-3" style="font-size: 3rem;"></i>
                        <div class="mt-2">Đặt lại mật khẩu</div>
                    </h2>
                    <form action="/webbanhang/Account/updatePassword" method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu mới</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control <?php echo isset($_SESSION['errors']['password']) ? 'is-invalid' : ''; ?>" 
                                    id="password" name="password" required minlength="6">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <div class="invalid-feedback">
                                    <?php echo $_SESSION['errors']['password'] ?? 'Vui lòng nhập mật khẩu ít nhất 6 ký tự'; ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" 
                                    id="confirm_password" name="confirm_password" required>
                                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <div class="invalid-feedback">
                                    Vui lòng xác nhận mật khẩu
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary py-2">
                                <i class="fas fa-save me-2"></i>Lưu mật khẩu mới
                            </button>
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

        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');
        
        if (password.value !== confirmPassword.value) {
            event.preventDefault();
            confirmPassword.setCustomValidity('Mật khẩu xác nhận không khớp');
        } else {
            confirmPassword.setCustomValidity('');
        }
        
        form.classList.add('was-validated');
    });

    // Password visibility toggles
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmPassword = document.getElementById('confirm_password');
    toggleConfirmPassword.addEventListener('click', function() {
        const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPassword.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
});
</script>

<?php 
unset($_SESSION['errors']);
include 'app/views/shares/footer.php'; 
?>
