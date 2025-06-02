<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-edit me-2"></i>Cập nhật thông tin cá nhân
                    </h5>
                </div>
                <div class="card-body">
                    <form action="/webbanhang/Account/updateProfile" method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" id="fullname" name="fullname" 
                                   value="<?php echo htmlspecialchars($user['fullname'] ?? ''); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Giới tính</label>
                            <select class="form-select" id="gender" name="gender">
                                <option value="male" <?php echo (($user['gender'] ?? '') === 'male') ? 'selected' : ''; ?>>Nam</option>
                                <option value="female" <?php echo (($user['gender'] ?? '') === 'female') ? 'selected' : ''; ?>>Nữ</option>
                                <option value="other" <?php echo (($user['gender'] ?? '') === 'other') ? 'selected' : ''; ?>>Khác</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="birthdate" class="form-label">Ngày sinh</label>
                            <input type="date" class="form-control" id="birthdate" name="birthdate" 
                                   value="<?php echo htmlspecialchars($user['birthdate'] ?? ''); ?>">
                        </div>
                        <?php if (isset($_SESSION['profile_success'])): ?>
                        <div class="alert alert-success">
                            <?php 
                            echo $_SESSION['profile_success'];
                            unset($_SESSION['profile_success']);
                            ?>
                        </div>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['profile_error'])): ?>
                        <div class="alert alert-danger">
                            <?php 
                            echo $_SESSION['profile_error'];
                            unset($_SESSION['profile_error']);
                            ?>
                        </div>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Lưu thay đổi
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-key me-2"></i>Đổi mật khẩu
                    </h5>
                </div>
                <div class="card-body">
                    <form action="/webbanhang/Account/changePassword" method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Mật khẩu mới</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required 
                                   minlength="6">
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        <?php if (isset($_SESSION['password_success'])): ?>
                        <div class="alert alert-success">
                            <?php 
                            echo $_SESSION['password_success'];
                            unset($_SESSION['password_success']);
                            ?>
                        </div>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['password_error'])): ?>
                        <div class="alert alert-danger">
                            <?php 
                            echo $_SESSION['password_error'];
                            unset($_SESSION['password_error']);
                            ?>
                        </div>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-key me-2"></i>Đổi mật khẩu
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
