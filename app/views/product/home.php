<?php include 'app/views/shares/header.php'; ?>

<!-- Carousel -->
<div id="myCarousel" class="carousel slide mb-6" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="overlay-image" style="background-image: url('/webbanhang/uploads/carousel/carousel1.jpg');"></div>
            <div class="container">
                <div class="carousel-caption text-start">
                    <h1>Sản phẩm công nghệ hàng đầu</h1>
                    <p class="opacity-75">Khám phá các sản phẩm điện tử mới nhất với chất lượng tốt nhất.</p>
                    <p><a class="btn btn-lg btn-primary" href="/webbanhang/Product/?category=1">Xem ngay</a></p>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="overlay-image" style="background-image: url('/webbanhang/uploads/carousel/carousel2.jpg');"></div>
            <div class="container">
                <div class="carousel-caption">
                    <h1>Laptop chính hãng</h1>
                    <p>Đa dạng mẫu mã, cấu hình mạnh mẽ, đáp ứng mọi nhu cầu.</p>
                    <p><a class="btn btn-lg btn-primary" href="/webbanhang/Product/?category=2">Tìm hiểu thêm</a></p>
                </div>
            </div>
        </div>
        <div class="carousel-item">
            <div class="overlay-image" style="background-image: url('/webbanhang/uploads/carousel/carousel3.jpg');"></div>
            <div class="container">
                <div class="carousel-caption text-end">
                    <h1>Phụ kiện chất lượng cao</h1>
                    <p>Đa dạng phụ kiện cho các thiết bị của bạn với giá cả hợp lý.</p>
                    <p><a class="btn btn-lg btn-primary" href="/webbanhang/Product/?category=4">Mua ngay</a></p>
                </div>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<!-- Marketing messaging and featurettes -->
<div class="container marketing">
    <!-- Three columns of text below the carousel -->
    <div class="row">
        <div class="col-lg-4 text-center">
            <div class="rounded-circle overflow-hidden mx-auto" style="width: 140px; height: 140px;">
                <img src="/webbanhang/uploads/Điện thoại Sharp Aquos R6.jpg" class="img-fluid" alt="Smartphone">
            </div>
            <h2 class="fw-normal">Điện thoại</h2>
            <p>Khám phá các mẫu điện thoại thông minh mới nhất với công nghệ tiên tiến và thiết kế hiện đại.</p>
            <p><a class="btn btn-secondary" href="/webbanhang/Product/?category=1">Xem chi tiết &raquo;</a></p>
        </div>
        <div class="col-lg-4 text-center">
            <div class="rounded-circle overflow-hidden mx-auto" style="width: 140px; height: 140px;">
                <img src="/webbanhang/uploads/Laptop KUU G3.jpg" class="img-fluid" alt="Laptop">
            </div>
            <h2 class="fw-normal">Laptop</h2>
            <p>Các dòng laptop mạnh mẽ, phù hợp cho công việc, học tập và giải trí với hiệu năng vượt trội.</p>
            <p><a class="btn btn-secondary" href="/webbanhang/Product/?category=2">Xem chi tiết &raquo;</a></p>
        </div>
        <div class="col-lg-4 text-center">
            <div class="rounded-circle overflow-hidden mx-auto" style="width: 140px; height: 140px;">
                <img src="/webbanhang/uploads/Blackview Tab 9.jpg" class="img-fluid" alt="Tablet">
            </div>
            <h2 class="fw-normal">Máy tính bảng</h2>
            <p>Máy tính bảng đa dạng kích thước, phù hợp cho mọi nhu cầu sử dụng với màn hình sắc nét.</p>
            <p><a class="btn btn-secondary" href="/webbanhang/Product/?category=3">Xem chi tiết &raquo;</a></p>
        </div>
    </div>

    <hr class="featurette-divider">

    <!-- Featured Products -->
    <h2 class="text-center mb-4">Sản phẩm nổi bật</h2>
    <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
        <?php foreach (array_slice($products, 0, 6) as $product): ?>
        <div class="col">
            <div class="card h-100 shadow-sm">
                <?php if ($product->image): ?>
                    <img src="/webbanhang/<?php echo $product->image; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" style="height: 200px; object-fit: cover;">
                <?php else: ?>
                    <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top" alt="No Image">
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></h5>
                    <p class="card-text text-truncate"><?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></p>
                    <div class="mb-2">
                        <span class="text-primary fw-bold"><?php echo number_format($product->price, 0, ',', '.'); ?> VND</span>
                    </div>
                    <div class="d-flex gap-1">
                        <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" class="btn btn-sm btn-outline-primary flex-fill">Chi tiết</a>
                        <a href="/webbanhang/Product/addToCart/<?php echo $product->id; ?>" class="btn btn-sm btn-success flex-fill">
                            <i class="fas fa-cart-plus me-1"></i>Thêm
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- START THE FEATURETTES -->
    <hr class="featurette-divider">

    <div class="row featurette">
        <div class="col-md-7">
            <h2 class="featurette-heading fw-normal lh-1">Công nghệ tiên tiến. <span class="text-body-secondary">Trải nghiệm tuyệt vời.</span></h2>
            <p class="lead">Chúng tôi cung cấp các sản phẩm công nghệ hàng đầu với chất lượng đảm bảo và dịch vụ chăm sóc khách hàng tận tâm.</p>
        </div>
        <div class="col-md-5">
            <img src="/webbanhang/uploads/Điện thoại Infinix Note 10 Pro.jpg" class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto rounded" width="500" height="500" alt="Technology">
        </div>
    </div>

    <hr class="featurette-divider">

    <div class="row featurette">
        <div class="col-md-7 order-md-2">
            <h2 class="featurette-heading fw-normal lh-1">Đa dạng sản phẩm. <span class="text-body-secondary">Đáp ứng mọi nhu cầu.</span></h2>
            <p class="lead">Từ điện thoại, laptop đến các phụ kiện công nghệ, chúng tôi có đầy đủ sản phẩm để đáp ứng nhu cầu của bạn.</p>
        </div>
        <div class="col-md-5 order-md-1">
            <img src="/webbanhang/uploads/Laptop Mechrevo Code 01.jpg" class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto rounded" width="500" height="500" alt="Gadgets">
        </div>
    </div>

    <hr class="featurette-divider">

    <div class="row featurette">
        <div class="col-md-7">
            <h2 class="featurette-heading fw-normal lh-1">Chất lượng hàng đầu. <span class="text-body-secondary">Giá cả hợp lý.</span></h2>
            <p class="lead">Chúng tôi cam kết mang đến cho khách hàng những sản phẩm chất lượng với mức giá cạnh tranh nhất trên thị trường.</p>
        </div>
        <div class="col-md-5">
            <img src="/webbanhang/uploads/Tai nghe Soundpeats TrueFree 2.jpg" class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto rounded" width="500" height="500" alt="Quality">
        </div>
    </div>

    <hr class="featurette-divider">
    <!-- /END THE FEATURETTES -->
</div>



<?php include 'app/views/shares/footer.php'; ?>
