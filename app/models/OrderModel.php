<?php
require_once 'app/config/database.php';

class OrderModel
{
    private $conn;
    private $orders_table = "orders";
    private $order_details_table = "order_details";

    public function __construct($db = null)
    {
        if ($db) {
            $this->conn = $db;
        } else {
            $database = new Database();
            $this->conn = $database->getConnection();
        }
    }

    /**
     * Tạo đơn hàng mới
     * @param string $customerName Tên khách hàng
     * @param string $customerPhone Số điện thoại khách hàng
     * @param string $customerEmail Email khách hàng (có thể rỗng)
     * @param string $customerAddress Địa chỉ khách hàng
     * @param string $notes Ghi chú đơn hàng (có thể rỗng)
     * @param float $totalAmount Tổng tiền đơn hàng
     * @return int|false Order ID nếu thành công, false nếu thất bại
     */
    public function createOrder($customerName, $customerPhone, $customerEmail, $customerAddress, $notes, $totalAmount)
    {
        try {
            $query = "INSERT INTO " . $this->orders_table . "
                     (name, phone, email, address, notes, created_at)
                     VALUES (:name, :phone, :email, :address, :notes, NOW())";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $customerName);
            $stmt->bindParam(':phone', $customerPhone);
            $stmt->bindParam(':email', $customerEmail);
            $stmt->bindParam(':address', $customerAddress);
            $stmt->bindParam(':notes', $notes);

            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error creating order: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Tạo chi tiết đơn hàng từ giỏ hàng
     * @param int $orderId ID đơn hàng
     * @param array $cartItems Mảng các sản phẩm trong giỏ hàng
     * @return bool True nếu thành công, false nếu thất bại
     */
    public function createOrderDetails($orderId, $cartItems)
    {
        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO " . $this->order_details_table . "
                     (order_id, product_id, quantity, price)
                     VALUES (:order_id, :product_id, :quantity, :price)";

            $stmt = $this->conn->prepare($query);

            foreach ($cartItems as $productId => $item) {
                $stmt->bindParam(':order_id', $orderId);
                $stmt->bindParam(':product_id', $productId);
                $stmt->bindParam(':quantity', $item['quantity']);
                $stmt->bindParam(':price', $item['price']);

                if (!$stmt->execute()) {
                    $this->conn->rollBack();
                    return false;
                }
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error creating order details: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy thông tin đơn hàng kèm chi tiết theo ID
     * @param int $id ID đơn hàng
     * @return object|false Thông tin đơn hàng nếu tìm thấy, false nếu không
     */
    public function getOrderById($id)
    {
        try {
            // Lấy thông tin đơn hàng
            $query = "SELECT * FROM " . $this->orders_table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $order = $stmt->fetch(PDO::FETCH_OBJ);

            if ($order) {
                // Lấy chi tiết đơn hàng với thông tin sản phẩm
                $detailsQuery = "SELECT od.*, p.name as product_name
                               FROM " . $this->order_details_table . " od
                               LEFT JOIN product p ON od.product_id = p.id
                               WHERE od.order_id = :order_id";
                $detailsStmt = $this->conn->prepare($detailsQuery);
                $detailsStmt->bindParam(':order_id', $id);
                $detailsStmt->execute();

                $order->details = $detailsStmt->fetchAll(PDO::FETCH_OBJ);

                // Tính tổng tiền từ chi tiết đơn hàng
                $order->total_amount = 0;
                foreach ($order->details as $detail) {
                    $detail->subtotal = $detail->price * $detail->quantity;
                    $order->total_amount += $detail->subtotal;
                }

                // Thêm các thuộc tính để tương thích với view
                $order->customer_name = $order->name;
                $order->customer_phone = $order->phone;
                $order->customer_address = $order->address;
                $order->status = $order->status ?? 'pending';
            }

            return $order;
        } catch (PDOException $e) {
            error_log("Error getting order by ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy danh sách đơn hàng với phân trang
     * @param int $page Trang hiện tại
     * @param int $limit Số đơn hàng mỗi trang
     * @return array Mảng chứa danh sách đơn hàng và thông tin phân trang
     */
    public function getAllOrders($page = 1, $limit = 25)
    {
        try {
            // Đếm tổng số đơn hàng
            $countQuery = "SELECT COUNT(*) as total FROM " . $this->orders_table;
            $countStmt = $this->conn->prepare($countQuery);
            $countStmt->execute();
            $totalCount = $countStmt->fetch(PDO::FETCH_OBJ)->total;

            // Lấy danh sách đơn hàng với phân trang
            $offset = ($page - 1) * $limit;
            $query = "SELECT * FROM " . $this->orders_table . "
                     ORDER BY created_at DESC
                     LIMIT :limit OFFSET :offset";

            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $orders = $stmt->fetchAll(PDO::FETCH_OBJ);

            // Tính tổng tiền cho mỗi đơn hàng
            foreach ($orders as $order) {
                $order->total_amount = $this->calculateOrderTotal($order->id);
            }

            return [
                'orders' => $orders,
                'pagination' => [
                    'total' => $totalCount,
                    'page' => $page,
                    'limit' => $limit,
                    'totalPages' => ceil($totalCount / $limit)
                ]
            ];
        } catch (PDOException $e) {
            error_log("Error getting all orders: " . $e->getMessage());
            return [
                'orders' => [],
                'pagination' => [
                    'total' => 0,
                    'page' => $page,
                    'limit' => $limit,
                    'totalPages' => 0
                ]
            ];
        }
    }

    /**
     * Cập nhật trạng thái đơn hàng
     * @param int $orderId ID đơn hàng
     * @param string $status Trạng thái mới
     * @return bool True nếu thành công, false nếu thất bại
     */
    public function updateOrderStatus($orderId, $status)
    {
        try {
            // Validation: Kiểm tra trạng thái có hợp lệ không
            $validStatuses = $this->getValidStatuses();
            if (!in_array($status, array_keys($validStatuses))) {
                error_log("Invalid status: $status");
                return false;
            }

            // Cập nhật trạng thái đơn hàng
            $query = "UPDATE " . $this->orders_table . "
                     SET status = :status
                     WHERE id = :order_id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':order_id', $orderId);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating order status: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Validate thông tin khách hàng
     * @param string $customerName Tên khách hàng
     * @param string $customerPhone Số điện thoại
     * @param string $customerEmail Email khách hàng (tùy chọn)
     * @param string $customerAddress Địa chỉ
     * @return array Mảng lỗi nếu có, rỗng nếu hợp lệ
     */
    public function validateCustomerInfo($customerName, $customerPhone, $customerEmail, $customerAddress)
    {
        $errors = [];

        if (empty(trim($customerName)) || strlen(trim($customerName)) < 2) {
            $errors[] = "Tên khách hàng phải có ít nhất 2 ký tự";
        }

        if (empty(trim($customerPhone))) {
            $errors[] = "Số điện thoại không được để trống";
        } elseif (!preg_match('/^[0-9]{10,11}$/', trim($customerPhone))) {
            $errors[] = "Số điện thoại không hợp lệ (10-11 chữ số)";
        }

        // Email là tùy chọn, chỉ validate nếu có nhập
        if (!empty(trim($customerEmail)) && !filter_var(trim($customerEmail), FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email không hợp lệ";
        }

        if (empty(trim($customerAddress)) || strlen(trim($customerAddress)) < 10) {
            $errors[] = "Địa chỉ phải có ít nhất 10 ký tự";
        }

        return $errors;
    }

    /**
     * Lấy danh sách các trạng thái hợp lệ
     * @return array Mảng các trạng thái với key là mã trạng thái và value là tên hiển thị
     */
    public function getValidStatuses()
    {
        return [
            'pending' => [
                'name' => 'Chờ xử lý',
                'class' => 'warning',
                'icon' => 'fas fa-clock'
            ],
            'confirmed' => [
                'name' => 'Đã xác nhận',
                'class' => 'info',
                'icon' => 'fas fa-check-circle'
            ],
            'shipping' => [
                'name' => 'Đang giao hàng',
                'class' => 'primary',
                'icon' => 'fas fa-truck'
            ],
            'delivered' => [
                'name' => 'Đã giao hàng',
                'class' => 'success',
                'icon' => 'fas fa-check-double'
            ],
            'cancelled' => [
                'name' => 'Đã hủy',
                'class' => 'danger',
                'icon' => 'fas fa-times-circle'
            ]
        ];
    }

    /**
     * Lấy thông tin trạng thái theo mã
     * @param string $status Mã trạng thái
     * @return array|null Thông tin trạng thái hoặc null nếu không tìm thấy
     */
    public function getStatusInfo($status)
    {
        $validStatuses = $this->getValidStatuses();
        return isset($validStatuses[$status]) ? $validStatuses[$status] : null;
    }

    /**
     * Kiểm tra xem có thể chuyển từ trạng thái này sang trạng thái khác không
     * @param string $currentStatus Trạng thái hiện tại
     * @param string $newStatus Trạng thái mới
     * @return bool True nếu có thể chuyển, false nếu không
     */
    public function canChangeStatus($currentStatus, $newStatus)
    {
        // Không thể chuyển từ delivered hoặc cancelled sang trạng thái khác
        if (in_array($currentStatus, ['delivered', 'cancelled'])) {
            return false;
        }

        // Không thể chuyển ngược lại từ trạng thái cao hơn về thấp hơn (trừ cancelled)
        $statusOrder = ['pending', 'confirmed', 'shipping', 'delivered'];
        $currentIndex = array_search($currentStatus, $statusOrder);
        $newIndex = array_search($newStatus, $statusOrder);

        // Cho phép chuyển sang cancelled từ bất kỳ trạng thái nào (trừ delivered)
        if ($newStatus === 'cancelled' && $currentStatus !== 'delivered') {
            return true;
        }

        // Cho phép chuyển tiến hoặc giữ nguyên
        return $newIndex !== false && $currentIndex !== false && $newIndex >= $currentIndex;
    }

    /**
     * Tìm kiếm đơn hàng theo tên khách hàng hoặc số điện thoại
     * @param string $keyword Từ khóa tìm kiếm
     * @param int $page Trang hiện tại
     * @param int $limit Số đơn hàng mỗi trang
     * @return array Mảng chứa danh sách đơn hàng và thông tin phân trang
     */
    public function searchOrders($keyword, $page = 1, $limit = 25)
    {
        try {
            // Đếm tổng số đơn hàng phù hợp
            $countQuery = "SELECT COUNT(*) as total FROM " . $this->orders_table . "
                          WHERE name LIKE :keyword OR phone LIKE :keyword";
            $countStmt = $this->conn->prepare($countQuery);
            $searchKeyword = '%' . $keyword . '%';
            $countStmt->bindParam(':keyword', $searchKeyword);
            $countStmt->execute();
            $totalCount = $countStmt->fetch(PDO::FETCH_OBJ)->total;

            // Lấy danh sách đơn hàng với phân trang
            $offset = ($page - 1) * $limit;
            $query = "SELECT * FROM " . $this->orders_table . "
                     WHERE name LIKE :keyword OR phone LIKE :keyword
                     ORDER BY created_at DESC
                     LIMIT :limit OFFSET :offset";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':keyword', $searchKeyword);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $orders = $stmt->fetchAll(PDO::FETCH_OBJ);

            // Tính tổng tiền cho mỗi đơn hàng
            foreach ($orders as $order) {
                $order->total_amount = $this->calculateOrderTotal($order->id);
            }

            return [
                'orders' => $orders,
                'pagination' => [
                    'total' => $totalCount,
                    'page' => $page,
                    'limit' => $limit,
                    'totalPages' => ceil($totalCount / $limit)
                ]
            ];
        } catch (PDOException $e) {
            error_log("Error searching orders: " . $e->getMessage());
            return [
                'orders' => [],
                'pagination' => [
                    'total' => 0,
                    'page' => $page,
                    'limit' => $limit,
                    'totalPages' => 0
                ]
            ];
        }
    }

    /**
     * Lấy thống kê tổng quan đơn hàng
     * @return object Thống kê đơn hàng
     */
    public function getOrderStatistics()
    {
        try {
            $stats = new stdClass();

            // Tổng số đơn hàng
            $countQuery = "SELECT COUNT(*) as total_orders FROM " . $this->orders_table;
            $countStmt = $this->conn->prepare($countQuery);
            $countStmt->execute();
            $stats->total_orders = $countStmt->fetch(PDO::FETCH_OBJ)->total_orders;

            // Tổng doanh thu
            $revenueQuery = "SELECT SUM(od.price * od.quantity) as total_revenue
                           FROM " . $this->order_details_table . " od";
            $revenueStmt = $this->conn->prepare($revenueQuery);
            $revenueStmt->execute();
            $stats->total_revenue = $revenueStmt->fetch(PDO::FETCH_OBJ)->total_revenue ?? 0;

            // Đơn hàng hôm nay
            $todayQuery = "SELECT COUNT(*) as today_orders FROM " . $this->orders_table . "
                         WHERE DATE(created_at) = CURDATE()";
            $todayStmt = $this->conn->prepare($todayQuery);
            $todayStmt->execute();
            $stats->today_orders = $todayStmt->fetch(PDO::FETCH_OBJ)->today_orders;

            // Doanh thu hôm nay
            $todayRevenueQuery = "SELECT SUM(od.price * od.quantity) as today_revenue
                                FROM " . $this->order_details_table . " od
                                INNER JOIN " . $this->orders_table . " o ON od.order_id = o.id
                                WHERE DATE(o.created_at) = CURDATE()";
            $todayRevenueStmt = $this->conn->prepare($todayRevenueQuery);
            $todayRevenueStmt->execute();
            $stats->today_revenue = $todayRevenueStmt->fetch(PDO::FETCH_OBJ)->today_revenue ?? 0;

            return $stats;
        } catch (PDOException $e) {
            error_log("Error getting order statistics: " . $e->getMessage());
            $stats = new stdClass();
            $stats->total_orders = 0;
            $stats->total_revenue = 0;
            $stats->today_orders = 0;
            $stats->today_revenue = 0;
            return $stats;
        }
    }

    /**
     * Tính tổng tiền của một đơn hàng
     * @param int $orderId ID đơn hàng
     * @return float Tổng tiền
     */
    private function calculateOrderTotal($orderId)
    {
        try {
            $query = "SELECT SUM(price * quantity) as total
                     FROM " . $this->order_details_table . "
                     WHERE order_id = :order_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':order_id', $orderId);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_OBJ);
            return $result->total ?? 0;
        } catch (PDOException $e) {
            error_log("Error calculating order total: " . $e->getMessage());
            return 0;
        }
    }
}
?>
