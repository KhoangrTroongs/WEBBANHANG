<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');
require_once('app/models/OrderModel.php');
require_once('app/models/ProductModel.php');
require_once('app/helpers/SessionHelper.php');

class AccountController
{
    private $accountModel;
    private $db;    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
    }

    private function checkAdminRole()
    {
        if (!SessionHelper::isAdmin()) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Bạn không có quyền truy cập trang này!'
            ];
            header('Location: /webbanhang/Product/');
            exit();
        }
    }    public function manageUsers()
    {
        // Kiểm tra quyền admin
        $this->checkAdminRole();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $limit = 10;

        try {
            $result = $this->accountModel->getAllUsers($page, $limit, $search);
            $users = $result['users'];
            $pagination = $result['pagination'];
            include 'app/views/account/manage_users.php';
        } catch (Exception $e) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Có lỗi xảy ra khi tải danh sách người dùng!'
            ];
            header('Location: /webbanhang/Product/');
            exit;
        }
    }    public function updateUser()
    {
        $this->checkAdminRole();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /webbanhang/Account/manageUsers');
            exit;
        }

        $id = $_POST['id'] ?? 0;
        $data = [
            'fullname' => $_POST['fullname'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'address' => $_POST['address'] ?? '',
            'gender' => $_POST['gender'] ?? '',
            'birthdate' => $_POST['birthdate'] ?? null,
            'role' => $_POST['role'] ?? 'user'
        ];

        try {
            if ($this->accountModel->updateUser($id, $data)) {
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Cập nhật thông tin người dùng thành công!'
                ];
            } else {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'Không thể cập nhật thông tin người dùng!'
                ];
            }
        } catch (Exception $e) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Có lỗi xảy ra khi cập nhật thông tin người dùng!'
            ];
        }

        header('Location: /webbanhang/Account/manageUsers');
        exit;
    }    public function deleteUser()
    {
        $this->checkAdminRole();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /webbanhang/Account/manageUsers');
            exit;
        }

        $id = $_POST['id'] ?? 0;

        try {
            if ($this->accountModel->deleteUser($id)) {
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Xóa người dùng thành công!'
                ];
            } else {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'Không thể xóa người dùng này!'
                ];
            }
        } catch (Exception $e) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Có lỗi xảy ra khi xóa người dùng!'
            ];
        }

        header('Location: /webbanhang/Account/manageUsers');
        exit;
    }    public function adminResetPassword()
    {
        $this->checkAdminRole();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /webbanhang/Account/manageUsers');
            exit;
        }

        $id = $_POST['id'] ?? 0;
        $newPassword = $_POST['new_password'] ?? '';

        if (empty($newPassword) || strlen($newPassword) < 8) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Mật khẩu mới phải có ít nhất 8 ký tự!'
            ];
            header('Location: /webbanhang/Account/manageUsers');
            exit;
        }

        try {
            if ($this->accountModel->resetPassword($id, $newPassword)) {
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Đặt lại mật khẩu thành công!'
                ];
            } else {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'Không thể đặt lại mật khẩu!'
                ];
            }
        } catch (Exception $e) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Có lỗi xảy ra khi đặt lại mật khẩu!'
            ];
        }

        header('Location: /webbanhang/Account/manageUsers');
        exit;
    }

    function register(){
        include_once 'app/views/account/register.php';
    }

    public function login(){
        include_once 'app/views/account/login.php';
    }    function save(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            error_log("Received POST data: " . print_r($_POST, true));
            
            $username = $_POST['username'] ?? ''; 
            $fullName = $_POST['fullname'] ?? ''; 
            $password = $_POST['password'] ?? ''; 
            $confirmPassword = $_POST['confirmpassword'] ?? ''; 
            
            error_log("Parsed data - username: $username, fullname: $fullName");

            $errors = []; 
            if(empty($username)){ 
                $errors['username'] = "Vui lòng nhập tên đăng nhập!"; 
            } 
            if(empty($fullName)){ 
                $errors['fullname'] = "Vui lòng nhập họ tên!"; 
            } 
            if(empty($password)){ 
                $errors['password'] = "Vui lòng nhập mật khẩu!"; 
            } 
            if($password != $confirmPassword){ 
                $errors['confirmPass'] = "Mật khẩu và xác nhận mật khẩu không khớp"; 
            } 
            
            // Kiểm tra độ dài và định dạng username
            if (strlen($username) < 3 || strlen($username) > 20 || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
                $errors['username'] = "Tên đăng nhập phải từ 3-20 ký tự, chỉ gồm chữ, số và dấu gạch dưới";
            }
            
            // Kiểm tra độ dài và định dạng mật khẩu
            if (strlen($password) < 8 || !preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
                $errors['password'] = "Mật khẩu phải có tối thiểu 8 ký tự, bao gồm chữ và số";
            }

            //kiểm tra username đã được đăng ký chưa
            $account = $this->accountModel->getAccountByUsername($username); 
            if($account){ 
                $errors['username'] = "Tên đăng nhập này đã được sử dụng!"; 
            } 

            if(count($errors) > 0){ 
                $_SESSION['errors'] = $errors;
                $_SESSION['old'] = [
                    'username' => $username,
                    'fullname' => $fullName
                ];
                header('Location: /webbanhang/Account/register');
                exit;
            } else {
                try {
                    $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                    $result = $this->accountModel->save($username, $fullName, $password); 
                    
                    if($result){ 
                        $_SESSION['flash'] = [
                            'type' => 'success',
                            'message' => 'Đăng ký thành công! Vui lòng đăng nhập.'
                        ];
                        header('Location: /webbanhang/Account/login');
                        exit;
                    } else {
                        throw new Exception('Không thể lưu tài khoản');
                    }
                } catch (PDOException $e) {
                    error_log("Database error in registration: " . $e->getMessage());
                    $_SESSION['flash'] = [
                        'type' => 'error',
                        'message' => 'Lỗi cơ sở dữ liệu, vui lòng thử lại sau.'
                    ];
                    header('Location: /webbanhang/Account/register');
                    exit;
                } catch (Exception $e) {
                    error_log("Error in registration: " . $e->getMessage());
                    $_SESSION['flash'] = [
                        'type' => 'error',
                        'message' => 'Có lỗi xảy ra, vui lòng thử lại sau.'
                    ];
                    header('Location: /webbanhang/Account/register');
                    exit;
                }
            }
        }    
    }    function logout(){
        unset($_SESSION['user']);
        unset($_SESSION['username']);
        unset($_SESSION['user_role']);
        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Đăng xuất thành công!'
        ];
        header('Location: /webbanhang/Product/list');
        exit;
    }function checkLogin(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($username) || empty($password)) {
                $_SESSION['errors']['login'] = 'Vui lòng nhập đầy đủ thông tin đăng nhập';
                header('Location: /webbanhang/Account/login');
                exit;
            }
            
            $account = $this->accountModel->getAccountByUserName($username);
            if ($account) {
                $pwd_hashed = $account->password;
                if (password_verify($password, $pwd_hashed)) {
                    $_SESSION['user'] = [
                        'id' => $account->id,
                        'username' => $account->username,
                        'fullname' => $account->fullname,
                        'role' => $account->role
                    ];
                    $_SESSION['username'] = $account->username;
                    $_SESSION['user_role'] = $account->role;
                    $_SESSION['flash'] = [
                        'type' => 'success',
                        'message' => 'Đăng nhập thành công!'
                    ];
                    header('Location: /webbanhang/Product/list');
                    exit;
                } else {
                    $_SESSION['errors']['login'] = 'Mật khẩu không chính xác';
                    header('Location: /webbanhang/Account/login');
                    exit;
                }
            } else {
                $_SESSION['errors']['login'] = 'Tài khoản không tồn tại';
                header('Location: /webbanhang/Account/login');
                exit;
            }
        }
    }
    public function forgotPassword() {
        require 'app/views/account/forgot_password.php';
    }

    public function verifyUsername() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            
            if (empty($username)) {
                $_SESSION['errors']['username'] = 'Vui lòng nhập tên đăng nhập';
                header('Location: /webbanhang/Account/forgotPassword');
                exit;
            }

            $user = $this->accountModel->findByUsername($username);
            
            if (!$user) {
                $_SESSION['errors']['username'] = 'Tên đăng nhập không tồn tại';
                header('Location: /webbanhang/Account/forgotPassword');
                exit;
            }

            // Store username in session for verification process
            $_SESSION['reset_username'] = $username;
            $_SESSION['reset_user_id'] = $user['id'];

            // Check if user has any orders
            $orderModel = new OrderModel($this->db);
            $userOrders = $orderModel->getUserOrders($user['id']);

            if (empty($userOrders)) {
                // User has no orders, redirect directly to reset password
                header('Location: /webbanhang/Account/resetPassword');
                exit;
            }

            // Get purchased products
            $purchasedProducts = $orderModel->getUserPurchasedProducts($user['id']);
            if (empty($purchasedProducts)) {
                // If no purchased products found, redirect to reset password
                header('Location: /webbanhang/Account/resetPassword');
                exit;
            }

            // Get one random purchased product
            $randomPurchased = $purchasedProducts[array_rand($purchasedProducts)];
            
            // Get products not purchased by user
            $productModel = new ProductModel($this->db);
            $otherProducts = $productModel->getProductsNotPurchasedByUser($user['id']);
            shuffle($otherProducts);
            $randomOthers = array_slice($otherProducts, 0, 2);

            // Combine and shuffle products
            $products = array_merge([$randomPurchased], $randomOthers);
            shuffle($products);

            // Store correct product ID in session
            $_SESSION['verify_product_id'] = $randomPurchased['id'];

            include 'app/views/account/verify_purchase.php';
        } else {
            header('Location: /webbanhang/Account/forgotPassword');
            exit;
        }
    }

    public function verifyPurchase() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['reset_username']) || !isset($_SESSION['verify_product_id']) || !isset($_SESSION['reset_user_id'])) {
                header('Location: /webbanhang/Account/forgotPassword');
                exit;
            }

            $selected_product = $_POST['selected_product'] ?? '';

            if (empty($selected_product)) {
                $_SESSION['errors']['verify'] = 'Vui lòng chọn một sản phẩm';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }

            // Tăng số lần thử
            $_SESSION['verify_attempts'] = ($_SESSION['verify_attempts'] ?? 0) + 1;

            if ($selected_product == $_SESSION['verify_product_id']) {
                // Correct product selected
                unset($_SESSION['verify_product_id']); // Clear verification product
                unset($_SESSION['verify_attempts']); // Clear attempts counter
                header('Location: /webbanhang/Account/resetPassword');
                exit;
            } else {
                if ($_SESSION['verify_attempts'] >= 3) {
                    // Quá 3 lần thử
                    $_SESSION['errors']['verify'] = 'Bạn đã thử quá 3 lần không thành công. Vui lòng thử lại từ đầu.';
                    unset($_SESSION['verify_attempts']);
                    unset($_SESSION['reset_username']);
                    unset($_SESSION['reset_user_id']);
                    unset($_SESSION['verify_product_id']);
                    header('Location: /webbanhang/Account/forgotPassword');
                    exit;
                }

                // Còn cơ hội thử, tải sản phẩm mới
                $orderModel = new OrderModel($this->db);
                $productModel = new ProductModel($this->db);
                
                // Lấy sản phẩm đã mua
                $purchasedProducts = $orderModel->getUserPurchasedProducts($_SESSION['reset_user_id']);
                if (empty($purchasedProducts)) {
                    header('Location: /webbanhang/Account/resetPassword');
                    exit;
                }

                // Chọn ngẫu nhiên một sản phẩm đã mua
                $randomPurchased = $purchasedProducts[array_rand($purchasedProducts)];
                
                // Lấy sản phẩm chưa mua
                $otherProducts = $productModel->getProductsNotPurchasedByUser($_SESSION['reset_user_id']);
                shuffle($otherProducts);
                $randomOthers = array_slice($otherProducts, 0, 2);

                // Kết hợp và xáo trộn sản phẩm
                $products = array_merge([$randomPurchased], $randomOthers);
                shuffle($products);

                // Lưu ID sản phẩm đúng mới
                $_SESSION['verify_product_id'] = $randomPurchased['id'];

                // Hiển thị thông báo lỗi
                $remaining = 3 - $_SESSION['verify_attempts'];
                $_SESSION['errors']['verify'] = "Sản phẩm được chọn không chính xác. Bạn còn {$remaining} lần thử.";
                
                // Hiển thị trang xác minh với sản phẩm mới
                include 'app/views/account/verify_purchase.php';
                exit;
            }
        } else {
            header('Location: /webbanhang/Account/forgotPassword');
            exit;
        }
    }

    public function resetPassword() {
        if (!isset($_SESSION['reset_username'])) {
            header('Location: /webbanhang/Account/login');
            exit;
        }
        include 'app/views/account/reset_password.php';
    }

    public function updatePassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['reset_username'])) {
                header('Location: /webbanhang/Account/login');
                exit;
            }

            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if (empty($password) || strlen($password) < 6) {
                $_SESSION['errors']['password'] = 'Mật khẩu phải có ít nhất 6 ký tự';
                header('Location: /webbanhang/Account/resetPassword');
                exit;
            }

            if ($password !== $confirm_password) {
                $_SESSION['errors']['password'] = 'Mật khẩu xác nhận không khớp';
                header('Location: /webbanhang/Account/resetPassword');
                exit;
            }

            $username = $_SESSION['reset_username'];
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            if ($this->accountModel->updatePassword($username, $hashed_password)) {
                // Clear all reset-related session variables
                unset($_SESSION['reset_username']);
                unset($_SESSION['reset_user_id']);
                
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Mật khẩu đã được cập nhật thành công!'
                ];
                header('Location: /webbanhang/Account/login');
                exit;
            } else {
                $_SESSION['errors']['password'] = 'Có lỗi xảy ra, vui lòng thử lại';
                header('Location: /webbanhang/Account/resetPassword');
                exit;
            }
        } else {
            header('Location: /webbanhang/Account/forgotPassword');
            exit;
        }
    }
    public function profile() {
        if (!isset($_SESSION['user'])) {
            header('Location: /webbanhang/Account/login');
            exit;
        }

        // Lấy thông tin chi tiết người dùng
        $user = $this->accountModel->getAccountById($_SESSION['user']['id']);
        include 'app/views/account/profile.php';
    }    public function updateProfile() {
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Vui lòng đăng nhập để thực hiện thao tác này'
            ];
            header('Location: /webbanhang/Account/login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /webbanhang/Account/profile');
            exit;
        }

        $data = [
            'fullname' => $_POST['fullname'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'address' => $_POST['address'] ?? '',
            'gender' => $_POST['gender'] ?? '',
            'birthdate' => $_POST['birthdate'] ?? null
        ];

        // Basic validation
        if (empty($data['fullname'])) {
            $_SESSION['profile_error'] = 'Họ tên không được để trống';
            header('Location: /webbanhang/Account/profile');
            exit;
        }        // Use the existing accountModel instance with database connection
        if ($this->accountModel->updateProfile($_SESSION['user']['id'], $data)) {
            $_SESSION['profile_success'] = 'Cập nhật thông tin thành công';
            // Update session data
            $_SESSION['user']['fullname'] = $data['fullname'];
        } else {
            $_SESSION['profile_error'] = 'Có lỗi xảy ra khi cập nhật thông tin';
        }

        header('Location: /webbanhang/Account/profile');
        exit;
    }

    public function changePassword() {
        if (!isset($_SESSION['user'])) {
            header('Location: /webbanhang/Account/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            $errors = [];

            // Validate mật khẩu hiện tại
            $user = $this->accountModel->getAccountById($_SESSION['user']['id']);
            if (!password_verify($currentPassword, $user['password'])) {
                $errors['current_password'] = 'Mật khẩu hiện tại không chính xác';
            }

            // Validate mật khẩu mới
            if (strlen($newPassword) < 6) {
                $errors['new_password'] = 'Mật khẩu mới phải có ít nhất 6 ký tự';
            }

            if ($newPassword !== $confirmPassword) {
                $errors['new_password'] = 'Mật khẩu xác nhận không khớp';
            }

            if (count($errors) > 0) {
                $_SESSION['errors'] = $errors;
                header('Location: /webbanhang/Account/profile');
                exit;
            }

            // Cập nhật mật khẩu mới
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            if ($this->accountModel->updatePassword($_SESSION['user']['username'], $hashedPassword)) {
                $_SESSION['password_success'] = 'Đổi mật khẩu thành công!';
            } else {
                $_SESSION['errors']['general'] = 'Có lỗi xảy ra, vui lòng thử lại';
            }

            header('Location: /webbanhang/Account/profile');
            exit;
        }
    }    public function getUserDetails() {
        $this->checkAdminRole();
        
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID người dùng không hợp lệ']);
            exit;
        }
        
        try {
            $user = $this->accountModel->getAccountById($id);
            if ($user) {
                header('Content-Type: application/json');
                echo json_encode($user);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Không tìm thấy người dùng']);
            }
        } catch (Exception $e) {
            error_log("Error in getUserDetails: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Có lỗi xảy ra khi lấy thông tin người dùng']);
        }
        exit;
    }
}