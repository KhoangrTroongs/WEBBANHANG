    </div> <!-- Đóng container mở ở header.php -->

    <!-- Footer -->
    <footer class="footer mt-5 py-4 bg-white border-top">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="mb-0 text-muted">&copy; HUTECH University. Thực hành phát triển phần mềm mã nguồn mở.</p>
                </div>
                <!-- <div class="col-md-6 text-center text-md-end">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item">
                            <a href="/webbanhang/Product/" class="text-muted text-decoration-none">Trang chủ</a>
                        </li>
                        <li class="list-inline-item">
                            <a href="/webbanhang/Product/manage" class="text-muted text-decoration-none">Quản lý sản phẩm</a>
                        </li>
                        <li class="list-inline-item">
                            <a href="/webbanhang/Product/add" class="text-muted text-decoration-none">Thêm sản phẩm</a>
                        </li>
                        <li class="list-inline-item">
                            <a href="/webbanhang/Product/categories" class="text-muted text-decoration-none">Danh mục</a>
                        </li>
                    </ul>
                </div> -->
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Thêm hiệu ứng active cho menu
        document.addEventListener('DOMContentLoaded', function() {
            const currentLocation = location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');

            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentLocation ||
                    currentLocation.includes(link.getAttribute('href')) && link.getAttribute('href') !== '/webbanhang/Product/') {
                    link.classList.add('active');
                    link.style.color = 'var(--primary-color) !important';
                    link.style.fontWeight = '700';
                }
            });

            // Xử lý sự kiện click trên liên kết danh mục
            const categoriesLink = document.getElementById('categoriesLink');
            if (categoriesLink) {
                categoriesLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.location.href = '/webbanhang/Product/categories';
                });
            }

            // Xử lý sự kiện click trên liên kết quản lý danh mục
            const manageCategoriesLink = document.getElementById('manageCategoriesLink');
            if (manageCategoriesLink) {
                manageCategoriesLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.location.href = '/webbanhang/Product/categories';
                });
            }
        });
    </script>
</body>
</html>