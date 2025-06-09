<?php
class ProductModel
{
    private $conn;
    private $table_name = "product";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getProductById($id, $includeUnavailable = true)
    {
        $query = "SELECT p.*, c.name as category_name
                  FROM " . $this->table_name . " p
                  LEFT JOIN category c ON p.category_id = c.id
                  WHERE p.id = :id";
                  
        // Nếu không includeUnavailable, chỉ lấy sản phẩm đang available
        if (!$includeUnavailable) {
            $query .= " AND p.status = 'available'";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }

    public function addProduct($name, $description, $price, $category_id, $image)
    {
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Tên sản phẩm không được để trống';
        }
        if (empty($description)) {
            $errors['description'] = 'Mô tả không được để trống';
        }
        if (!is_numeric($price) || $price < 0) {
            $errors['price'] = 'Giá sản phẩm không hợp lệ';
        }
        if (count($errors) > 0) {
            return $errors;
        }        $query = "INSERT INTO " . $this->table_name . " (name, description, price, category_id, image, status)
                  VALUES (:name, :description, :price, :category_id, :image, 'available')";
        $stmt = $this->conn->prepare($query);

        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));
        $category_id = htmlspecialchars(strip_tags($category_id));
        $image = htmlspecialchars(strip_tags($image));

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':image', $image);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function updateProduct($id, $name, $description, $price, $category_id, $image)
    {
        $query = "UPDATE " . $this->table_name . "
                  SET name=:name, description=:description, price=:price, category_id=:category_id, image=:image
                  WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));
        $category_id = htmlspecialchars(strip_tags($category_id));
        $image = htmlspecialchars(strip_tags($image));

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':image', $image);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function deleteProduct($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }    public function getProducts($search = null, $category_id = null, $sort = null, $page = 1, $limit = 25, $includeUnavailable = false)
    {
        // Base query for counting
        $countQuery = "SELECT COUNT(*) as total FROM {$this->table_name} p WHERE 1=1";
        if (!$includeUnavailable) {
            $countQuery .= " AND p.status = 'available'";
        }

        $countParams = [];

        if ($search) {
            $countQuery .= " AND (p.name LIKE :search OR p.description LIKE :search)";
            $countParams[':search'] = "%{$search}%";
        }

        if ($category_id) {
            $countQuery .= " AND p.category_id = :category_id";
            $countParams[':category_id'] = $category_id;
        }

        $countStmt = $this->conn->prepare($countQuery);
        foreach ($countParams as $key => $value) {
            $countStmt->bindValue($key, $value);
        }
        $countStmt->execute();
        $totalCount = $countStmt->fetch(PDO::FETCH_OBJ)->total;

        // Base query for selecting products
        $query = "SELECT p.*, c.name as category_name 
                 FROM {$this->table_name} p 
                 LEFT JOIN category c ON p.category_id = c.id 
                 WHERE 1=1";

        if (!$includeUnavailable) {
            $query .= " AND p.status = 'available'";
        }

        $params = [];

        if ($search) {
            $query .= " AND (p.name LIKE :search OR p.description LIKE :search)";
            $params[':search'] = "%{$search}%";
        }

        if ($category_id) {
            $query .= " AND p.category_id = :category_id";
            $params[':category_id'] = $category_id;
        }        // Add sorting
        if ($sort) {
            switch ($sort) {
                case 'price_asc':
                    $query .= " ORDER BY p.price ASC";
                    break;
                case 'price_desc':
                    $query .= " ORDER BY p.price DESC";
                    break;
                case 'name_asc':
                    $query .= " ORDER BY p.name ASC";
                    break;
                case 'name_desc':
                    $query .= " ORDER BY p.name DESC";
                    break;
                case 'status_asc':
                    $query .= " ORDER BY FIELD(p.status, 'available', 'unavailable')";
                    break;
                case 'status_desc':
                    $query .= " ORDER BY FIELD(p.status, 'unavailable', 'available')";
                    break;
                case 'newest':
                    $query .= " ORDER BY p.id DESC";
                    break;
                default:
                    $query .= " ORDER BY p.id DESC";
            }
        } else {
            $query .= " ORDER BY p.id DESC";
        }

        // Add pagination
        $offset = ($page - 1) * $limit;
        $query .= " LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);

        return [
            'products' => $result,
            'pagination' => [
                'total' => $totalCount,
                'page' => $page,
                'limit' => $limit,
                'totalPages' => ceil($totalCount / $limit)
            ]
        ];
    }

    public function getProductsByCategory($category_id, $page = 1, $limit = 25)
    {
        return $this->getProducts(null, $category_id, null, $page, $limit);
    }

    public function searchProducts($keyword, $page = 1, $limit = 25)
    {
        return $this->getProducts($keyword, null, null, $page, $limit);
    }

    public function getAveragePrice()
    {
        $query = "SELECT AVG(price) as avg_price FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->avg_price;
    }

    public function getProductsNotPurchasedByUser($userId) {
        $query = "SELECT DISTINCT p.* FROM " . $this->table_name . " p 
                  WHERE p.id NOT IN (
                    SELECT DISTINCT od.product_id 
                    FROM order_details od 
                    INNER JOIN orders o ON od.order_id = o.id 
                    WHERE o.user_id = :user_id
                  ) 
                  ORDER BY RAND() 
                  LIMIT 10"; // Limit to 10 random products for better performance
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function toggleProductStatus($id)
    {
        // Lấy trạng thái hiện tại
        $query = "SELECT status FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $current = $stmt->fetch(PDO::FETCH_OBJ);
        
        // Đổi trạng thái
        $newStatus = ($current->status == 'available') ? 'unavailable' : 'available';
        
        // Cập nhật trạng thái mới
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $newStatus);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            return ['success' => true, 'new_status' => $newStatus];
        }
        return ['success' => false];
    }
}