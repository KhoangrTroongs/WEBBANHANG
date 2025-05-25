<?php include 'app/views/shares/header.php'; ?>

<!-- Admin Header -->
<div class="admin-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="admin-title">
                    <i class="fas fa-tags me-2"></i>Quản lý danh mục
                </h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-md-end mb-0">
                        <li class="breadcrumb-item"><a href="/webbanhang/Product/">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="/webbanhang/Product/manage">Quản lý sản phẩm</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Danh mục</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card stats-card primary h-100 py-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="stats-title">Tổng danh mục</div>
                        <div class="stats-value"><?php echo count($categories); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tags stats-icon text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card stats-card success h-100 py-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="stats-title">Tổng sản phẩm</div>
                        <div class="stats-value"><?php echo count($products); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box-open stats-icon text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-12 mb-4">
        <div class="card stats-card info h-100 py-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="stats-title">Trung bình sản phẩm/danh mục</div>
                        <div class="stats-value">
                            <?php 
                            // Tính trung bình số sản phẩm trên mỗi danh mục
                            $avgProducts = count($categories) > 0 ? round(count($products) / count($categories), 1) : 0;
                            echo $avgProducts;
                            ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-pie stats-icon text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Add Category -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <form action="#" method="GET" class="d-flex">
                    <input type="text" class="form-control me-2" name="keyword" placeholder="Tìm kiếm danh mục...">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Tìm kiếm
                    </button>
                </form>
            </div>
            <div class="col-md-6 d-flex justify-content-md-end">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="fas fa-plus-circle me-1"></i>Thêm danh mục
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Categories Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">Danh sách danh mục</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th scope="col" width="60">#</th>
                        <th scope="col">Tên danh mục</th>
                        <th scope="col">Mô tả</th>
                        <th scope="col" width="120">Số sản phẩm</th>
                        <th scope="col" width="150">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($categories) > 0): ?>
                        <?php foreach ($categories as $index => $category): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td>
                                <div class="fw-medium"><?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?></div>
                            </td>
                            <td>
                                <div class="text-muted text-truncate" style="max-width: 300px;">
                                    <?php echo htmlspecialchars($category->description, ENT_QUOTES, 'UTF-8'); ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <?php 
                                // Đếm số sản phẩm trong danh mục này
                                $count = 0;
                                foreach ($products as $product) {
                                    if ($product->category_id == $category->id) {
                                        $count++;
                                    }
                                }
                                echo '<span class="badge bg-light text-dark">' . $count . '</span>';
                                ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary edit-category" data-id="<?php echo $category->id; ?>" data-name="<?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>" data-description="<?php echo htmlspecialchars($category->description, ENT_QUOTES, 'UTF-8'); ?>" data-bs-toggle="modal" data-bs-target="#editCategoryModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="#" class="btn btn-outline-danger delete-category" data-id="<?php echo $category->id; ?>" data-name="<?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-tags fa-3x mb-3"></i>
                                    <p>Không có danh mục nào. Hãy thêm danh mục mới!</p>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                        <i class="fas fa-plus-circle me-1"></i>Thêm danh mục
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="text-muted">Hiển thị <?php echo count($categories); ?> danh mục</span>
            </div>
            <?php if (count($categories) > 0): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination mb-0">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Trước</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Sau</a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Thêm danh mục mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm" action="/webbanhang/Product/addCategory" method="POST">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="categoryName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="categoryDescription" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="categoryDescription" name="description" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" form="addCategoryForm" class="btn btn-primary">Lưu danh mục</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Sửa danh mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCategoryForm" action="/webbanhang/Product/updateCategory" method="POST">
                    <input type="hidden" id="editCategoryId" name="id">
                    <div class="mb-3">
                        <label for="editCategoryName" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editCategoryName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editCategoryDescription" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="editCategoryDescription" name="description" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" form="editCategoryForm" class="btn btn-primary">Lưu thay đổi</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Category Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCategoryModalLabel">Xác nhận xóa danh mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa danh mục <strong id="deleteCategoryName"></strong>?</p>
                <p class="text-danger">Lưu ý: Không thể xóa danh mục đang chứa sản phẩm.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a href="#" id="confirmDeleteCategory" class="btn btn-danger">Xóa danh mục</a>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Category Modals -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Edit Category Modal
        const editCategoryButtons = document.querySelectorAll('.edit-category');
        editCategoryButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const description = this.getAttribute('data-description');
                
                document.getElementById('editCategoryId').value = id;
                document.getElementById('editCategoryName').value = name;
                document.getElementById('editCategoryDescription').value = description;
            });
        });
        
        // Delete Category Modal
        const deleteCategoryButtons = document.querySelectorAll('.delete-category');
        deleteCategoryButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                
                document.getElementById('deleteCategoryName').textContent = name;
                document.getElementById('confirmDeleteCategory').href = '/webbanhang/Product/deleteCategory/' + id;
            });
        });
    });
</script>

<?php include 'app/views/shares/footer.php'; ?>
