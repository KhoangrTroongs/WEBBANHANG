<?php include 'app/views/shares/header.php'; ?>

<!-- Page Header -->
<div class="admin-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="admin-title">
                    <i class="fas fa-edit me-2"></i>Chỉnh sửa sản phẩm
                </h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-md-end mb-0">
                        <li class="breadcrumb-item"><a href="/webbanhang/Product/">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="/webbanhang/Product/manage">Quản lý sản phẩm</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa sản phẩm</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold">
                        <i class="fas fa-info-circle me-2 text-primary"></i>Thông tin sản phẩm
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/webbanhang/Product/update" enctype="multipart/form-data" onsubmit="return validateForm();" class="needs-validation" novalidate>
                        <input type="hidden" name="id" value="<?php echo $product->id; ?>">

                        <div class="row g-3">
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" required>
                                <div class="invalid-feedback">Vui lòng nhập tên sản phẩm</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                                <select id="category_id" name="category_id" class="form-select" required>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category->id; ?>" <?php echo $category->id == $product->category_id ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Vui lòng chọn danh mục</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Giá (VND) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" id="price" name="price" class="form-control" step="1000" min="0" value="<?php echo htmlspecialchars($product->price, ENT_QUOTES, 'UTF-8'); ?>" required>
                                    <span class="input-group-text">VND</span>
                                    <div class="invalid-feedback">Vui lòng nhập giá hợp lệ</div>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Mô tả sản phẩm <span class="text-danger">*</span></label>
                                <textarea id="description" name="description" class="form-control" rows="5" required><?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></textarea>
                                <div class="invalid-feedback">Vui lòng nhập mô tả sản phẩm</div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="image" class="form-label">Hình ảnh sản phẩm</label>
                                <input type="file" id="image" name="image" class="form-control" accept="image/*">
                                <input type="hidden" name="existing_image" value="<?php echo $product->image; ?>">
                                <div class="form-text">Chọn hình ảnh mới hoặc giữ nguyên hình ảnh hiện tại</div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="current-image mt-2">
                                    <label class="form-label">Hình ảnh hiện tại:</label>
                                    <div class="d-flex align-items-center">
                                        <?php if ($product->image): ?>
                                            <img src="/webbanhang/<?php echo $product->image; ?>" alt="Current Image" class="img-thumbnail me-3" style="max-height: 150px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="removeImage" name="remove_image" value="1">
                                                <label class="form-check-label" for="removeImage">
                                                    Xóa hình ảnh
                                                </label>
                                            </div>
                                        <?php else: ?>
                                            <div class="alert alert-info mb-0">Sản phẩm chưa có hình ảnh</div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="image-preview mt-3 d-none" id="imagePreview">
                                    <label class="form-label">Hình ảnh mới:</label>
                                    <img src="" alt="Image Preview" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="/webbanhang/Product/manage" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Quay lại
                            </a>
                            <div>
                                <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" class="btn btn-outline-primary me-2">
                                    <i class="fas fa-eye me-1"></i>Xem chi tiết
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i>Lưu thay đổi
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS -->
<style>
    .admin-header {
        background-color: #4e73df;
        color: white;
        padding: 1.5rem 0;
        margin-bottom: 2rem;
    }

    .breadcrumb-item a {
        color: white;
        opacity: 0.8;
        text-decoration: none;
    }

    .breadcrumb-item a:hover {
        opacity: 1;
    }

    .breadcrumb-item.active {
        color: white;
        opacity: 0.6;
    }

    .breadcrumb-item+.breadcrumb-item::before {
        color: white;
        opacity: 0.6;
    }
</style>

<!-- JavaScript for Form Validation and Image Preview -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation
        const form = document.querySelector('.needs-validation');

        function validateForm() {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');
            return form.checkValidity();
        }

        form.addEventListener('submit', validateForm);

        // Image preview
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const previewImage = imagePreview.querySelector('img');

        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    imagePreview.classList.remove('d-none');
                }

                reader.readAsDataURL(this.files[0]);
            } else {
                imagePreview.classList.add('d-none');
            }
        });

        // Remove image checkbox
        const removeImageCheckbox = document.getElementById('removeImage');
        if (removeImageCheckbox) {
            removeImageCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    imageInput.disabled = true;
                } else {
                    imageInput.disabled = false;
                }
            });
        }
    });
</script>

<?php include 'app/views/shares/footer.php'; ?>