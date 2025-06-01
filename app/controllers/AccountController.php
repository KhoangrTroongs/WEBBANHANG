<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');

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
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
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
        $fullname = $_POST['fullname'] ?? '';
        $role = $_POST['role'] ?? 'user';

        try {
            if ($this->accountModel->updateUser($id, $fullname, $role)) {
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
    }    public function resetPassword()
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
}