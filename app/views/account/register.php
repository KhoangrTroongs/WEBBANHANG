<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4">
                        <i class="fas fa-user-plus text-primary mb-3" style="font-size: 3rem;"></i>
                        <div class="mt-2">Đăng ký tài khoản</div>
                    </h2>                      <form action="/webbanhang/Account/save" method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="username" class="form-label">Tên đăng nhập</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control <?php echo isset($_SESSION['errors']['username']) ? 'is-invalid' : ''; ?>" 
                                    id="username" name="username" required 
                                    pattern="[a-zA-Z0-9_]{3,20}" 
                                    value="<?php echo $_SESSION['old']['username'] ?? ''; ?>"
                                    title="3-20 ký tự, chỉ bao gồm chữ cái, số và dấu gạch dưới">
                                <div class="invalid-feedback">
                                    <?php echo $_SESSION['errors']['username'] ?? 'Tên đăng nhập phải có 3-20 ký tự, chỉ bao gồm chữ cái, số và dấu gạch dưới'; ?>
                                </div>
                            </div>
                        </div><div class="mb-3">                            <label for="fullname" class="form-label">Họ và tên</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control <?php echo isset($_SESSION['errors']['fullname']) ? 'is-invalid' : ''; ?>" 
                                    id="fullname" name="fullname" required
                                    value="<?php echo $_SESSION['old']['fullname'] ?? ''; ?>">
                                <div class="invalid-feedback">
                                    <?php echo $_SESSION['errors']['fullname'] ?? 'Vui lòng nhập họ và tên'; ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">                            <label for="password" class="form-label">Mật khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control <?php echo isset($_SESSION['errors']['password']) ? 'is-invalid' : ''; ?>" 
                                    id="password" name="password" required 
                                    pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" 
                                    title="Tối thiểu 8 ký tự, bao gồm chữ và số">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <div class="invalid-feedback">
                                    <?php echo $_SESSION['errors']['password'] ?? 'Mật khẩu phải có tối thiểu 8 ký tự, bao gồm chữ và số'; ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control <?php echo isset($_SESSION['errors']['confirmPass']) ? 'is-invalid' : ''; ?>" 
                                    id="confirm_password" name="confirmpassword" required>
                                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <div class="invalid-feedback">
                                    <?php echo $_SESSION['errors']['confirmPass'] ?? 'Mật khẩu xác nhận không khớp'; ?>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary py-2">
                                <i class="fas fa-user-plus me-2"></i>Đăng ký
                            </button>
                        </div>

                        <div class="text-center">
                            <p class="mb-0">Đã có tài khoản? 
                                <a href="/webbanhang/Account/login" class="text-primary text-decoration-none">
                                    Đăng nhập
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
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');

    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }

        // Check if passwords match
        if (password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Mật khẩu xác nhận không khớp');
            event.preventDefault();
            event.stopPropagation();
        } else {
            confirmPassword.setCustomValidity('');
        }

        form.classList.add('was-validated');
    });

    // Password visibility toggles
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');

    [
        { toggle: togglePassword, input: password },
        { toggle: toggleConfirmPassword, input: confirmPassword }
    ].forEach(({toggle, input}) => {
        toggle.addEventListener('click', function() {
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    });

    // Real-time password confirmation validation
    confirmPassword.addEventListener('input', function() {
        if (password.value !== this.value) {
            this.setCustomValidity('Mật khẩu xác nhận không khớp');
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>

<?php 
// Clean up session data after displaying
unset($_SESSION['errors']);
unset($_SESSION['old']);
?>

<?php include 'app/views/shares/footer.php'; ?>