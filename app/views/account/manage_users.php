<?php include 'app/views/shares/header.php'; ?>

<!-- Admin Header -->
<div class="admin-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="admin-title">
                    <i class="fas fa-users me-2"></i>Quản lý người dùng
                </h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-md-end mb-0">
                        <li class="breadcrumb-item"><a href="/webbanhang/Product/">Trang chủ</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Quản lý người dùng</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
    <!-- Search Box -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <form method="GET" action="/webbanhang/Account/manageUsers" class="row g-3">
                <div class="col-md-8">
                    <label for="search" class="form-label">Tìm kiếm người dùng</label>
                    <input type="text" 
                           class="form-control" 
                           id="search" 
                           name="search" 
                           placeholder="Nhập tên người dùng hoặc tên đăng nhập..."
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i>Tìm kiếm
                    </button>
                    <a href="/webbanhang/Account/manageUsers" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Xóa bộ lọc
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0 fw-bold">
                <i class="fas fa-list me-2"></i>Danh sách người dùng
                <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                    <small class="text-muted">(Kết quả tìm kiếm cho: "<?php echo htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8'); ?>")</small>
                <?php endif; ?>
            </h5>
        </div>
        <div class="card-body">
            <?php if (empty($users)): ?>
                <div class="text-center py-5">
                    <div class="text-muted">
                        <i class="fas fa-users fa-3x mb-3"></i>
                        <p>Không tìm thấy người dùng nào.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên đăng nhập</th>
                                <th>Họ tên</th>
                                <th>Vai trò</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo $user->id; ?></td>
                                    <td><?php echo htmlspecialchars($user->username, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($user->fullname, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $user->role === 'admin' ? 'danger' : 'primary'; ?>">
                                            <?php echo $user->role === 'admin' ? 'Quản trị viên' : 'Người dùng'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo date('d/m/Y H:i', strtotime($user->created_at)); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" 
                                                    class="btn btn-outline-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editUserModal"
                                                    data-id="<?php echo $user->id; ?>"
                                                    data-fullname="<?php echo htmlspecialchars($user->fullname, ENT_QUOTES, 'UTF-8'); ?>"
                                                    data-role="<?php echo $user->role; ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-outline-warning"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#resetPasswordModal"
                                                    data-id="<?php echo $user->id; ?>"
                                                    data-username="<?php echo htmlspecialchars($user->username, ENT_QUOTES, 'UTF-8'); ?>">
                                                <i class="fas fa-key"></i>
                                            </button>
                                            <?php if ($user->role !== 'admin'): ?>
                                                <button type="button"
                                                        class="btn btn-outline-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteUserModal"
                                                        data-id="<?php echo $user->id; ?>"
                                                        data-username="<?php echo htmlspecialchars($user->username, ENT_QUOTES, 'UTF-8'); ?>">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($pagination['totalPages'] > 1): ?>
                    <nav aria-label="User pagination" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($pagination['page'] > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $pagination['page'] - 1; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>

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

                            <?php if ($pagination['page'] < $pagination['totalPages']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $pagination['page'] + 1; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Chỉnh sửa thông tin người dùng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>            <div class="modal-body">
                <form id="editUserForm" action="/webbanhang/Account/updateUser" method="POST">
                    <input type="hidden" id="editUserId" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editUsername" class="form-label">Tên đăng nhập</label>
                                <input type="text" class="form-control" id="editUsername" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editFullName" class="form-label">Họ tên</label>
                                <input type="text" class="form-control" id="editFullName" name="fullname" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editEmail" name="email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editPhone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control" id="editPhone" name="phone">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editAddress" class="form-label">Địa chỉ</label>
                        <textarea class="form-control" id="editAddress" name="address" rows="2"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editGender" class="form-label">Giới tính</label>
                                <select class="form-select" id="editGender" name="gender">
                                    <option value="male">Nam</option>
                                    <option value="female">Nữ</option>
                                    <option value="other">Khác</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editBirthdate" class="form-label">Ngày sinh</label>
                                <input type="date" class="form-control" id="editBirthdate" name="birthdate">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editRole" class="form-label">Vai trò</label>
                        <select class="form-select" id="editRole" name="role">
                            <option value="user">Người dùng</option>
                            <option value="admin">Quản trị viên</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" form="editUserForm" class="btn btn-primary">Lưu thay đổi</button>
            </div>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetPasswordModalLabel">Đặt lại mật khẩu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">                <form id="resetPasswordForm" action="/webbanhang/Account/adminResetPassword" method="POST">
                    <input type="hidden" id="resetPasswordUserId" name="id">
                    <p>Bạn đang đặt lại mật khẩu cho tài khoản: <strong id="resetPasswordUsername"></strong></p>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">Mật khẩu mới</label>
                        <input type="password" class="form-control" id="newPassword" name="new_password" required minlength="8">
                        <div class="form-text">Mật khẩu phải có ít nhất 8 ký tự</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" form="resetPasswordForm" class="btn btn-warning">Đặt lại mật khẩu</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Xác nhận xóa người dùng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="deleteUserForm" action="/webbanhang/Account/deleteUser" method="POST">
                    <input type="hidden" id="deleteUserId" name="id">
                    <p>Bạn có chắc chắn muốn xóa tài khoản: <strong id="deleteUsername"></strong>?</p>
                    <p class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Hành động này không thể hoàn tác!</p>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" form="deleteUserForm" class="btn btn-danger">Xóa người dùng</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for User Management -->
<script>
document.addEventListener('DOMContentLoaded', function() {    // Edit User Modal
    const editUserModal = document.getElementById('editUserModal');
    editUserModal.addEventListener('show.bs.modal', async function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        
        try {
            // Gọi API để lấy thông tin chi tiết người dùng
            const response = await fetch(`/webbanhang/Account/getUserDetails?id=${id}`);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const user = await response.json();
            
            // Điền thông tin vào form
            document.getElementById('editUserId').value = user.id;
            document.getElementById('editUsername').value = user.username;
            document.getElementById('editFullName').value = user.fullname;
            document.getElementById('editEmail').value = user.email || '';
            document.getElementById('editPhone').value = user.phone || '';
            document.getElementById('editAddress').value = user.address || '';
            document.getElementById('editGender').value = user.gender || 'other';
            document.getElementById('editBirthdate').value = user.birthdate || '';
            document.getElementById('editRole').value = user.role;
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi khi tải thông tin người dùng');
        }
    });

    // Reset Password Modal
    const resetPasswordModal = document.getElementById('resetPasswordModal');
    resetPasswordModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const username = button.getAttribute('data-username');
        
        document.getElementById('resetPasswordUserId').value = id;
        document.getElementById('resetPasswordUsername').textContent = username;
    });

    // Delete User Modal
    const deleteUserModal = document.getElementById('deleteUserModal');
    deleteUserModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const username = button.getAttribute('data-username');
        
        document.getElementById('deleteUserId').value = id;
        document.getElementById('deleteUsername').textContent = username;
    });
});
</script>

<?php include 'app/views/shares/footer.php'; ?>