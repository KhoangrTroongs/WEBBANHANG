<?php

require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');

class ProductController
{    private $productModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }    private function checkAdminRole()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Bạn không có quyền truy cập trang này!'
            ];
            header('Location: /webbanhang/Product/');
            exit();
        }
    }

    public function index()
    {
        // Kiểm tra xem có tham số tìm kiếm, lọc hoặc sắp xếp không
        $search = isset($_GET['search']) ? $_GET['search'] : null;
        $category_id = isset($_GET['category']) ? $_GET['category'] : null;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : null;

        // Nếu có tham số, hiển thị trang danh sách sản phẩm
        if ($search || $category_id || $sort || isset($_GET['page'])) {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = 25; // Hiển thị 25 sản phẩm mỗi trang

            // Đảm bảo page không nhỏ hơn 1
            if ($page < 1) $page = 1;

            // Lấy danh sách sản phẩm với các điều kiện và phân trang
            $result = $this->productModel->getProducts($search, $category_id, $sort, $page, $limit);
            $products = $result['products'];
            $pagination = $result['pagination'];

            // Lấy danh sách danh mục để hiển thị bộ lọc
            $categoryModel = new CategoryModel($this->db);
            $categories = $categoryModel->getCategories();

            // Hiển thị trang danh sách sản phẩm
            include 'app/views/product/list.php';
        } else {
            // Không có tham số, hiển thị trang chủ mới
            // Lấy sản phẩm mới nhất để hiển thị trên trang chủ
            $result = $this->productModel->getProducts(null, null, 'newest', 1, 12);
            $products = $result['products'];

            // Lấy danh sách danh mục
            $categoryModel = new CategoryModel($this->db);
            $categories = $categoryModel->getCategories();

            // Hiển thị trang chủ mới
            include 'app/views/product/home.php';
        }
    }    public function manage()
    {
        // Kiểm tra quyền admin
        $this->checkAdminRole();

        // Lấy tham số tìm kiếm, lọc và sắp xếp từ URL
        $search = isset($_GET['search']) ? $_GET['search'] : null;
        $category_id = isset($_GET['category']) ? $_GET['category'] : null;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 25; // Hiển thị 25 sản phẩm mỗi trang

        // Đảm bảo page không nhỏ hơn 1
        if ($page < 1) $page = 1;

        // Lấy danh sách sản phẩm với các điều kiện và phân trang
        $result = $this->productModel->getProducts($search, $category_id, $sort, $page, $limit);
        $products = $result['products'];
        $pagination = $result['pagination'];

        // Tính giá trung bình của tất cả sản phẩm
        $avgPrice = $this->productModel->getAveragePrice();

        // Lấy danh sách danh mục
        $categoryModel = new CategoryModel($this->db);
        $categories = $categoryModel->getCategories();

        // Hiển thị trang quản lý sản phẩm
        include 'app/views/product/manage.php';
    }

    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function add()
    {
        // Kiểm tra quyền admin
        $this->checkAdminRole();

        $categories = (new CategoryModel($this->db))->getCategories();
        include_once 'app/views/product/add.php';
    }

    public function save()
    {
        // Kiểm tra quyền admin
        $this->checkAdminRole();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $this->uploadImage($_FILES['image']);
            } else {
                $image = "";
            }
            $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image);
            if (is_array($result)) {
                $errors = $result;
                $categories = (new CategoryModel($this->db))->getCategories();
                include 'app/views/product/add.php';
            } else {
                header('Location: /webbanhang/Product');
            }
        }
    }

    public function edit($id)
    {
        // Kiểm tra quyền admin
        $this->checkAdminRole();

        $product = $this->productModel->getProductById($id);
        $categories = (new CategoryModel($this->db))->getCategories();
        if ($product) {
            include 'app/views/product/edit.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function update()
    {
        // Kiểm tra quyền admin
        $this->checkAdminRole();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category_id = $_POST['category_id'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $this->uploadImage($_FILES['image']);
            } else {
                $image = $_POST['existing_image'];
            }
            $edit = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image);
            if ($edit) {
                header('Location: /webbanhang/Product');
            } else {
                echo "Đã xảy ra lỗi khi lưu sản phẩm.";
            }
        }
    }

    public function delete($id)
    {
        // Kiểm tra quyền admin
        $this->checkAdminRole();

        if ($this->productModel->deleteProduct($id)) {
            header('Location: /webbanhang/Product');
        } else {
            echo "Đã xảy ra lỗi khi xóa sản phẩm.";
        }
    }

    private function uploadImage($file)
    {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            throw new Exception("File không phải là hình ảnh.");
        }
        if ($file["size"] > 10 * 1024 * 1024) {
            throw new Exception("Hình ảnh có kích thước quá lớn.");
        }
        if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            throw new Exception("Chỉ cho phép các định dạng JPG, JPEG, PNG và GIF.");
        }
        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new Exception("Có lỗi xảy ra khi tải lên hình ảnh.");
        }
        return $target_file;
    }

    public function addToCart($id)
    {
        // Validation: Kiểm tra product tồn tại
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Không tìm thấy sản phẩm.'
            ];
            // Redirect về trang trước đó hoặc trang chủ
            $referer = $_SERVER['HTTP_REFERER'] ?? '/webbanhang/Product/';
            header('Location: ' . $referer);
            return;
        }

        // Khởi tạo giỏ hàng nếu chưa có
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Thêm hoặc cập nhật số lượng sản phẩm trong giỏ hàng
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
            $message = 'Đã cập nhật số lượng sản phẩm trong giỏ hàng.';
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image
            ];
            $message = 'Đã thêm sản phẩm vào giỏ hàng.';
        }

        // Thông báo thành công
        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => $message
        ];

        // Redirect về trang trước đó hoặc giỏ hàng
        $referer = $_SERVER['HTTP_REFERER'] ?? '/webbanhang/Product/cart';
        header('Location: ' . $referer);
    }

    public function list()
    {
        // Lấy tham số tìm kiếm, lọc và sắp xếp từ URL
        $search = isset($_GET['search']) ? $_GET['search'] : null;
        $category_id = isset($_GET['category']) ? $_GET['category'] : null;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 25; // Hiển thị 25 sản phẩm mỗi trang

        // Đảm bảo page không nhỏ hơn 1
        if ($page < 1) $page = 1;

        // Lấy danh sách sản phẩm với các điều kiện và phân trang
        $result = $this->productModel->getProducts($search, $category_id, $sort, $page, $limit);
        $products = $result['products'];
        $pagination = $result['pagination'];

        // Lấy danh sách danh mục để hiển thị bộ lọc
        $categoryModel = new CategoryModel($this->db);
        $categories = $categoryModel->getCategories();

        // Hiển thị trang danh sách sản phẩm
        include 'app/views/product/list.php';
    }

    public function search()
    {
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : null;
        $limit = 25; // Hiển thị 25 sản phẩm mỗi trang

        // Đảm bảo page không nhỏ hơn 1
        if ($page < 1) $page = 1;

        $result = $this->productModel->searchProducts($keyword, $page, $limit);
        $products = $result['products'];
        $pagination = $result['pagination'];

        $categoryModel = new CategoryModel($this->db);
        $categories = $categoryModel->getCategories();

        // Luôn hiển thị trang quản lý sản phẩm nếu URL chứa 'manage'
        $currentUrl = $_SERVER['REQUEST_URI'];
        if (strpos($currentUrl, 'manage') !== false) {
            include 'app/views/product/manage.php';
        } else {
            include 'app/views/product/list.php';
        }
    }

    public function categories()
    {
        // Kiểm tra quyền admin
        $this->checkAdminRole();

        // Lấy danh sách danh mục
        $categoryModel = new CategoryModel($this->db);
        $categories = $categoryModel->getCategories();

        // Lấy số lượng sản phẩm trong mỗi danh mục
        $result = $this->productModel->getProducts(null, null, null, 1, 1000); // Lấy tất cả sản phẩm
        $products = $result['products'];
        $pagination = $result['pagination'];

        // Hiển thị trang quản lý danh mục
        include 'app/views/product/categories.php';
    }

    public function addCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';

            $categoryModel = new CategoryModel($this->db);
            $result = $categoryModel->addCategory($name, $description);

            if ($result === true) {
                header('Location: /webbanhang/Product/categories');
            } else {
                $errors = $result;
                $categories = $categoryModel->getCategories();
                include 'app/views/product/categories.php';
            }
        }
    }

    public function updateCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];

            $categoryModel = new CategoryModel($this->db);
            $edit = $categoryModel->updateCategory($id, $name, $description);

            if ($edit) {
                header('Location: /webbanhang/Product/categories');
            } else {
                echo "Đã xảy ra lỗi khi lưu danh mục.";
            }
        }
    }

    public function deleteCategory($id)
    {
        $categoryModel = new CategoryModel($this->db);

        // Kiểm tra xem có sản phẩm nào thuộc danh mục này không
        $products = $this->productModel->getProductsByCategory($id);

        if (count($products) > 0) {
            // Có sản phẩm thuộc danh mục này, không thể xóa
            echo "<script>alert('Không thể xóa danh mục đang chứa sản phẩm.'); window.location.href='/webbanhang/Product/categories';</script>";
        } else {
            // Không có sản phẩm thuộc danh mục này, có thể xóa
            if ($categoryModel->deleteCategory($id)) {
                header('Location: /webbanhang/Product/categories');
            } else {
                echo "Đã xảy ra lỗi khi xóa danh mục.";
            }
        }
    }

    /**
     * Hiển thị giỏ hàng
     */
    public function cart()
    {
        // Khởi tạo giỏ hàng nếu chưa có
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $cartItems = $_SESSION['cart'];
        $totalAmount = 0;

        // Tính tổng tiền
        foreach ($cartItems as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        // Hiển thị trang giỏ hàng
        include 'app/views/product/cart.php';
    }

    /**
     * Cập nhật giỏ hàng
     */
    public function updateCart()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $quantities = $_POST['quantities'] ?? [];

            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            $hasError = false;
            foreach ($quantities as $productId => $quantity) {
                // Validation: số lượng phải là integer > 0
                $quantity = (int)$quantity;
                if ($quantity <= 0) {
                    $hasError = true;
                    break;
                }

                if (isset($_SESSION['cart'][$productId])) {
                    $_SESSION['cart'][$productId]['quantity'] = $quantity;
                }
            }

            if ($hasError) {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'Số lượng sản phẩm phải lớn hơn 0.'
                ];
            } else {
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Đã cập nhật giỏ hàng thành công.'
                ];
            }
        }

        header('Location: /webbanhang/Product/cart');
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function removeFromCart($productId)
    {
        if (isset($_SESSION['cart'][$productId])) {
            $productName = $_SESSION['cart'][$productId]['name'];
            unset($_SESSION['cart'][$productId]);

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => "Đã xóa sản phẩm '{$productName}' khỏi giỏ hàng."
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Không tìm thấy sản phẩm trong giỏ hàng.'
            ];
        }

        header('Location: /webbanhang/Product/cart');
    }

    /**
     * Hiển thị trang thanh toán
     */
    public function checkout()
    {
        // Validation: giỏ hàng không rỗng
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Giỏ hàng của bạn đang trống.'
            ];
            header('Location: /webbanhang/Product/cart');
            return;
        }

        $cartItems = $_SESSION['cart'];
        $totalAmount = 0;

        // Tính tổng tiền
        foreach ($cartItems as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        // Hiển thị trang thanh toán
        include 'app/views/product/checkout.php';
    }

    /**
     * Xử lý thanh toán
     */
    public function processCheckout()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation: giỏ hàng không rỗng
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'Giỏ hàng của bạn đang trống.'
                ];
                header('Location: /webbanhang/Product/cart');
                return;
            }

            // Lấy thông tin từ form
            $customerName = trim($_POST['customer_name'] ?? '');
            $customerPhone = trim($_POST['customer_phone'] ?? '');
            $customerEmail = trim($_POST['customer_email'] ?? '');
            $customerAddress = trim($_POST['customer_address'] ?? '');
            $orderNotes = trim($_POST['order_notes'] ?? '');

            // Validation form
            $orderModel = new OrderModel($this->db);
            $errors = $orderModel->validateCustomerInfo($customerName, $customerPhone, $customerEmail, $customerAddress);

            if (!empty($errors)) {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => implode('<br>', $errors)
                ];
                header('Location: /webbanhang/Product/checkout');
                return;
            }

            // Tính tổng tiền
            $totalAmount = 0;
            foreach ($_SESSION['cart'] as $item) {
                $totalAmount += $item['price'] * $item['quantity'];
            }            // Lấy user_id từ session nếu đã đăng nhập
            $userId = isset($_SESSION['user']) ? $_SESSION['user']['id'] : null;

            // Tạo đơn hàng
            $orderId = $orderModel->createOrder($customerName, $customerPhone, $customerEmail, $customerAddress, $orderNotes, $totalAmount, $userId);

            if ($orderId) {
                // Tạo chi tiết đơn hàng
                if ($orderModel->createOrderDetails($orderId, $_SESSION['cart'])) {
                    // Clear giỏ hàng
                    unset($_SESSION['cart']);

                    $_SESSION['flash'] = [
                        'type' => 'success',
                        'message' => 'Đặt hàng thành công!'
                    ];

                    header('Location: /webbanhang/Product/orderSuccess/' . $orderId);
                    return;
                } else {
                    error_log("Failed to create order details for order ID: $orderId");
                    $_SESSION['flash'] = [
                        'type' => 'error',
                        'message' => 'Có lỗi xảy ra khi tạo chi tiết đơn hàng. Vui lòng thử lại.'
                    ];
                }
            } else {
                error_log("Failed to create order for customer: $customerName");
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'Có lỗi xảy ra khi tạo đơn hàng. Vui lòng thử lại.'
                ];
            }
            header('Location: /webbanhang/Product/checkout');
        } else {
            header('Location: /webbanhang/Product/checkout');
        }
    }

    /**
     * Hiển thị trang đặt hàng thành công
     */
    public function orderSuccess($orderId)
    {
        $orderModel = new OrderModel($this->db);
        $order = $orderModel->getOrderById($orderId);

        if (!$order) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Không tìm thấy thông tin đơn hàng.'
            ];
            header('Location: /webbanhang/Product/');
            return;
        }

        // Hiển thị trang thành công
        include 'app/views/product/order_success.php';
    }

    /**
     * Quản lý đơn hàng (Admin)
     */
    public function manageOrders()
    {
        // Kiểm tra quyền admin
        $this->checkAdminRole(); 

        // Lấy tham số phân trang và tìm kiếm
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $limit = 25; // Hiển thị 25 đơn hàng mỗi trang

        // Đảm bảo page không nhỏ hơn 1
        if ($page < 1) $page = 1;

        $orderModel = new OrderModel($this->db);

        // Lấy danh sách đơn hàng
        if (!empty($search)) {
            $result = $orderModel->searchOrders($search, $page, $limit);
        } else {
            $result = $orderModel->getAllOrders($page, $limit);
        }

        $orders = $result['orders'];
        $pagination = $result['pagination'];

        // Lấy thống kê tổng quan
        $statistics = $orderModel->getOrderStatistics();

        // Hiển thị trang quản lý đơn hàng
        include 'app/views/product/manage_orders.php';
    }

    /**
     * Xem chi tiết đơn hàng
     */
    public function viewOrder($orderId)
    {
        $orderModel = new OrderModel($this->db);
        $order = $orderModel->getOrderById($orderId);

        if (!$order) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Không tìm thấy đơn hàng.'
            ];
            header('Location: /webbanhang/Product/manageOrders');
            return;
        }

        // Hiển thị trang chi tiết đơn hàng
        include 'app/views/product/order_detail.php';
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateOrderStatus()
    {
        // Kiểm tra quyền admin
        $this->checkAdminRole();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = (int)($_POST['order_id'] ?? 0);
            $status = trim($_POST['status'] ?? '');
            $redirectTo = $_POST['redirect_to'] ?? 'manageOrders';

            // Validation cơ bản
            if (!$orderId || !$status) {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'Thông tin không hợp lệ.'
                ];
                $this->redirectAfterStatusUpdate($redirectTo, $orderId);
                return;
            }

            $orderModel = new OrderModel($this->db);

            // Kiểm tra đơn hàng tồn tại
            $order = $orderModel->getOrderById($orderId);
            if (!$order) {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'Không tìm thấy đơn hàng.'
                ];
                $this->redirectAfterStatusUpdate($redirectTo, $orderId);
                return;
            }

            // Kiểm tra trạng thái hợp lệ
            $validStatuses = $orderModel->getValidStatuses();
            if (!isset($validStatuses[$status])) {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'Trạng thái không hợp lệ.'
                ];
                $this->redirectAfterStatusUpdate($redirectTo, $orderId);
                return;
            }

            // Kiểm tra có thể chuyển trạng thái không
            $currentStatus = $order->status ?? 'pending';
            if (!$orderModel->canChangeStatus($currentStatus, $status)) {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'Không thể chuyển từ trạng thái "' . $orderModel->getStatusInfo($currentStatus)['name'] . '" sang "' . $validStatuses[$status]['name'] . '".'
                ];
                $this->redirectAfterStatusUpdate($redirectTo, $orderId);
                return;
            }

            // Cập nhật trạng thái
            if ($orderModel->updateOrderStatus($orderId, $status)) {
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Đã cập nhật trạng thái đơn hàng #' . str_pad($orderId, 6, '0', STR_PAD_LEFT) . ' thành "' . $validStatuses[$status]['name'] . '" thành công.'
                ];
            } else {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'Có lỗi xảy ra khi cập nhật trạng thái đơn hàng.'
                ];
            }
        } else {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Phương thức không được hỗ trợ.'
            ];
        }

        $this->redirectAfterStatusUpdate($redirectTo ?? 'manageOrders', $orderId ?? 0);
    }

    /**
     * Chuyển hướng sau khi cập nhật trạng thái
     */
    private function redirectAfterStatusUpdate($redirectTo, $orderId)
    {
        switch ($redirectTo) {
            case 'viewOrder':
                header('Location: /webbanhang/Product/viewOrder/' . $orderId);
                break;
            case 'manageOrders':
            default:
                header('Location: /webbanhang/Product/manageOrders');
                break;
        }
    }

    /**
     * Hiển thị danh sách đơn hàng của người dùng
     */
    public function orders()
    {
        // Kiểm tra người dùng đã đăng nhập chưa
        if (!isset($_SESSION['user'])) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Vui lòng đăng nhập để xem đơn hàng của bạn.'
            ];
            header('Location: /webbanhang/Account/login');
            return;
        }

        $userId = $_SESSION['user']['id'];
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10; // Số đơn hàng hiển thị trên mỗi trang

        // Đảm bảo page không nhỏ hơn 1
        if ($page < 1) $page = 1;

        $orderModel = new OrderModel($this->db);
        $result = $orderModel->getOrdersByUserId($userId, $page, $limit);
        
        $orders = $result['orders'];
        $pagination = $result['pagination'];

        // Hiển thị trang danh sách đơn hàng
        include 'app/views/product/user_orders.php';
    }
}
?>