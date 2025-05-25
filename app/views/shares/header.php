<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TECH Shop</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --accent-color: #e74a3b;
            --text-color: #5a5c69;
            --light-color: #f8f9fc;
            --dark-color: #2e3148;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
        }

        body {
            font-family: 'Roboto', sans-serif;
            color: var(--text-color);
            background-color: #f8f9fc;
        }

        .navbar {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .nav-link {
            font-weight: 500;
            color: var(--text-color) !important;
            transition: all 0.3s;
            position: relative;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: var(--primary-color);
            transition: width 0.3s;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #3a5ccc;
            border-color: #3a5ccc;
        }

        .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.15);
        }

        .table th {
            font-weight: 600;
            color: var(--dark-color);
        }

        .table td {
            vertical-align: middle;
        }

        .badge {
            font-weight: 500;
            padding: 0.5em 0.8em;
        }

        .search-form {
            position: relative;
        }

        .search-form .form-control {
            padding-right: 40px;
            border-radius: 20px;
        }

        .search-form .btn {
            position: absolute;
            right: 5px;
            top: 5px;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admin-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1.5rem 0;
            margin-bottom: 2rem;
        }

        .admin-title {
            font-weight: 700;
            margin-bottom: 0;
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

        .stats-card {
            border-left: 4px solid;
            border-radius: 0.25rem;
        }

        .stats-card.primary {
            border-left-color: var(--primary-color);
        }

        .stats-card.success {
            border-left-color: var(--success-color);
        }

        .stats-card.info {
            border-left-color: var(--info-color);
        }

        .stats-card.warning {
            border-left-color: var(--warning-color);
        }

        .stats-card .stats-icon {
            font-size: 2rem;
            opacity: 0.3;
        }

        .stats-card .stats-title {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.1rem;
            font-weight: 700;
            color: var(--text-color);
            opacity: 0.7;
        }

        .stats-card .stats-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
        }

        /* Carousel styles */
        .carousel {
            margin-bottom: 4rem;
        }
        .carousel-caption {
            bottom: 3rem;
            z-index: 10;
        }
        .carousel-item {
            height: 32rem;
        }
        .carousel-item > .overlay-image {
            position: absolute;
            top: 0;
            left: 0;
            min-width: 100%;
            height: 32rem;
            background-size: cover;
            background-position: center;
        }
        .carousel-item::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }
        .marketing .col-lg-4 {
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .marketing .col-lg-4 p {
            margin-right: .75rem;
            margin-left: .75rem;
        }
        .featurette-divider {
            margin: 5rem 0;
        }
        .featurette-heading {
            letter-spacing: -.05rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 sticky-top">
        <div class="container">
            <a class="navbar-brand text-primary" href="/webbanhang/Product/">
                <i class="fas fa-boxes me-2"></i>NHD Tech Shop
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link px-3" href="/webbanhang/Product/">
                            <i class="fas fa-home me-1"></i>Trang chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="/webbanhang/Product/list">
                            <i class="fa-brands fa-product-hunt me-1"></i>Sản phẩm
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="/webbanhang/Product/manage">
                            <i class="fas fa-tasks me-1"></i>Quản lý sản phẩm
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="/webbanhang/Product/manageOrders">
                            <i class="fas fa-clipboard-list me-1"></i>Đơn hàng
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <form class="search-form d-flex me-3" action="/webbanhang/Product/search" method="GET">
                        <input class="form-control" type="search" name="keyword" placeholder="Tìm kiếm sản phẩm..." aria-label="Search">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    </form>

                    <!-- Cart Icon -->
                    <a href="/webbanhang/Product/cart" class="btn btn-outline-primary position-relative">
                        <i class="fas fa-shopping-cart"></i>
                        <?php
                        $cartCount = 0;
                        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $item) {
                                $cartCount += $item['quantity'];
                            }
                        }
                        ?>
                        <?php if ($cartCount > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?php echo $cartCount > 99 ? '99+' : $cartCount; ?>
                                <span class="visually-hidden">items in cart</span>
                            </span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">

        <!-- Flash Messages -->
        <?php if (isset($_SESSION['flash'])): ?>
            <div class="alert alert-<?php echo $_SESSION['flash']['type'] === 'error' ? 'danger' : 'success'; ?> alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['flash']['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>