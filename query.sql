-- Tạo cơ sở dữ liệu và sử dụng nó
CREATE DATABASE IF NOT EXISTS my_store;
USE my_store;
-- Tạo bảng danh mục sản phẩm
CREATE TABLE IF NOT EXISTS category (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 description TEXT
);
-- Tạo bảng sản phẩm
CREATE TABLE IF NOT EXISTS product (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 description TEXT,
 price DECIMAL(10,2) NOT NULL,
 image VARCHAR(255) DEFAULT NULL,
 category_id INT,
 FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE CASCADE
);
-- Chèn dữ liệu vào bảng category
INSERT INTO category (name, description) VALUES
('Điện thoại', 'Danh mục các loại điện thoại'),
('Laptop', 'Danh mục các loại laptop'),
('Máy tính bảng', 'Danh mục các loại máy tính bảng'),
('Phụ kiện', 'Danh mục phụ kiện điện tử'),
('Thiết bị âm thanh', 'Danh mục loa, tai nghe, micro');

-- Tạo bảng orders với cột status
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    status VARCHAR(50) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tạo bảng order_details
CREATE TABLE order_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Query để thêm 100 sản phẩm với danh mục ngẫu nhiên và để trống hình ảnh
-- Sử dụng cơ sở dữ liệu my_store
USE my_store;

-- Thêm 100 sản phẩm với danh mục ngẫu nhiên (từ 1 đến 5)
INSERT INTO product (name, description, price, image, category_id) VALUES
('Điện thoại Samsung Galaxy S21', 'Điện thoại cao cấp với camera chất lượng cao', 18990000, NULL, 1),
('Điện thoại iPhone 13', 'Điện thoại Apple với hiệu năng mạnh mẽ', 24990000, NULL, 1),
('Laptop Dell XPS 13', 'Laptop mỏng nhẹ, hiệu năng cao', 32990000, NULL, 2),
('Laptop MacBook Air M1', 'Laptop Apple với chip M1 mạnh mẽ', 28990000, NULL, 2),
('iPad Pro 2021', 'Máy tính bảng cao cấp của Apple', 22990000, NULL, 3),
('Samsung Galaxy Tab S7', 'Máy tính bảng Android cao cấp', 16990000, NULL, 3),
('Tai nghe AirPods Pro', 'Tai nghe không dây với khả năng chống ồn', 5990000, NULL, 5),
('Loa JBL Flip 5', 'Loa bluetooth chống nước', 2490000, NULL, 5),
('Sạc dự phòng Anker 10000mAh', 'Pin dự phòng dung lượng cao', 790000, NULL, 4),
('Ốp lưng iPhone 13', 'Ốp lưng bảo vệ điện thoại', 290000, NULL, 4),
('Điện thoại Xiaomi Redmi Note 10', 'Điện thoại tầm trung với camera tốt', 4990000, NULL, 1),
('Điện thoại Oppo Reno 6', 'Điện thoại với thiết kế đẹp', 8990000, NULL, 1),
('Laptop Acer Nitro 5', 'Laptop gaming giá rẻ', 19990000, NULL, 2),
('Laptop HP Pavilion 15', 'Laptop đa năng cho công việc và giải trí', 15990000, NULL, 2),
('Samsung Galaxy Tab A8', 'Máy tính bảng giá rẻ', 6990000, NULL, 3),
('Xiaomi Mi Pad 5', 'Máy tính bảng hiệu năng cao', 8990000, NULL, 3),
('Tai nghe Sony WH-1000XM4', 'Tai nghe chống ồn cao cấp', 7990000, NULL, 5),
('Loa Marshall Emberton', 'Loa bluetooth chất lượng cao', 3990000, NULL, 5),
('Cáp sạc USB-C', 'Cáp sạc nhanh cho điện thoại', 190000, NULL, 4),
('Miếng dán cường lực', 'Miếng dán bảo vệ màn hình', 150000, NULL, 4),
('Điện thoại Vivo V23', 'Điện thoại với camera selfie tốt', 9990000, NULL, 1),
('Điện thoại Realme GT Neo 2', 'Điện thoại hiệu năng cao', 7990000, NULL, 1),
('Laptop Lenovo Legion 5', 'Laptop gaming mạnh mẽ', 24990000, NULL, 2),
('Laptop Asus ZenBook 14', 'Laptop mỏng nhẹ cho doanh nhân', 22990000, NULL, 2),
('Huawei MatePad Pro', 'Máy tính bảng cao cấp', 12990000, NULL, 3),
('Lenovo Tab P11', 'Máy tính bảng giá tốt', 7990000, NULL, 3),
('Tai nghe Bose QuietComfort', 'Tai nghe chống ồn tốt', 8990000, NULL, 5),
('Loa Sony SRS-XB43', 'Loa bluetooth công suất lớn', 3990000, NULL, 5),
('Bao da iPad Pro', 'Bao da bảo vệ máy tính bảng', 890000, NULL, 4),
('Bàn phím không dây Logitech', 'Bàn phím bluetooth đa thiết bị', 1290000, NULL, 4),
('Điện thoại Nokia G20', 'Điện thoại pin trâu', 3990000, NULL, 1),
('Điện thoại Poco F3', 'Điện thoại hiệu năng cao giá tốt', 8990000, NULL, 1),
('Laptop MSI GF63', 'Laptop gaming giá rẻ', 18990000, NULL, 2),
('Laptop LG Gram 17', 'Laptop màn hình lớn siêu nhẹ', 32990000, NULL, 2),
('Amazon Fire HD 10', 'Máy tính bảng giá rẻ', 4990000, NULL, 3),
('Microsoft Surface Pro 7', 'Máy tính bảng lai laptop', 23990000, NULL, 3),
('Tai nghe Jabra Elite 75t', 'Tai nghe không dây nhỏ gọn', 3990000, NULL, 5),
('Loa Bose SoundLink Revolve', 'Loa bluetooth âm thanh 360 độ', 4990000, NULL, 5),
('Chuột không dây Logitech', 'Chuột bluetooth đa thiết bị', 790000, NULL, 4),
('Đế sạc không dây', 'Đế sạc không dây cho điện thoại', 590000, NULL, 4),
('Điện thoại OnePlus 9', 'Điện thoại cao cấp hiệu năng mạnh', 14990000, NULL, 1),
('Điện thoại Google Pixel 6', 'Điện thoại với camera AI tốt nhất', 16990000, NULL, 1),
('Laptop Dell Inspiron 15', 'Laptop đa năng giá tốt', 14990000, NULL, 2),
('Laptop Huawei MateBook 14', 'Laptop mỏng nhẹ màn hình đẹp', 21990000, NULL, 2),
('iPad Mini 6', 'Máy tính bảng nhỏ gọn của Apple', 14990000, NULL, 3),
('Samsung Galaxy Tab S6 Lite', 'Máy tính bảng với bút S-Pen', 9990000, NULL, 3),
('Tai nghe Sennheiser Momentum', 'Tai nghe cao cấp chất lượng âm thanh tốt', 6990000, NULL, 5),
('Loa Bang & Olufsen Beosound', 'Loa bluetooth cao cấp', 7990000, NULL, 5),
('Bút cảm ứng Apple Pencil', 'Bút cảm ứng cho iPad', 2990000, NULL, 4),
('Bàn phím Magic Keyboard', 'Bàn phím chính hãng Apple', 3990000, NULL, 4),
('Điện thoại Asus ROG Phone 5', 'Điện thoại gaming cao cấp', 19990000, NULL, 1),
('Điện thoại Sony Xperia 5 III', 'Điện thoại cao cấp màn hình 21:9', 22990000, NULL, 1),
('Laptop Razer Blade 15', 'Laptop gaming cao cấp', 49990000, NULL, 2),
('Laptop Microsoft Surface Laptop 4', 'Laptop cao cấp thiết kế đẹp', 32990000, NULL, 2),
('Lenovo Yoga Tab 13', 'Máy tính bảng màn hình lớn', 12990000, NULL, 3),
('Realme Pad', 'Máy tính bảng giá rẻ', 5990000, NULL, 3),
('Tai nghe Apple AirPods Max', 'Tai nghe chụp tai cao cấp của Apple', 12990000, NULL, 5),
('Loa Ultimate Ears Megaboom 3', 'Loa bluetooth chống nước', 3490000, NULL, 5),
('Bao da Samsung Galaxy Tab', 'Bao da bảo vệ máy tính bảng Samsung', 790000, NULL, 4),
('Chuột gaming Logitech G502', 'Chuột chơi game hiệu năng cao', 1790000, NULL, 4),
('Điện thoại Motorola Edge 20', 'Điện thoại màn hình 144Hz', 9990000, NULL, 1),
('Điện thoại BlackShark 4', 'Điện thoại gaming giá tốt', 11990000, NULL, 1),
('Laptop Gigabyte Aorus 15P', 'Laptop gaming hiệu năng cao', 39990000, NULL, 2),
('Laptop Framework', 'Laptop có thể nâng cấp dễ dàng', 29990000, NULL, 2),
('Xiaomi Pad 5 Pro', 'Máy tính bảng hiệu năng cao', 10990000, NULL, 3),
('TCL Tab 10s', 'Máy tính bảng giá rẻ', 4990000, NULL, 3),
('Tai nghe Beats Studio Buds', 'Tai nghe không dây nhỏ gọn', 3990000, NULL, 5),
('Loa Sonos Roam', 'Loa bluetooth nhỏ gọn chất lượng cao', 4490000, NULL, 5),
('Giá đỡ điện thoại', 'Giá đỡ điện thoại trên xe hơi', 290000, NULL, 4),
('Túi chống sốc laptop', 'Túi bảo vệ laptop', 490000, NULL, 4),
('Điện thoại Nubia Red Magic 6', 'Điện thoại gaming màn hình 165Hz', 13990000, NULL, 1),
('Điện thoại ZTE Axon 30', 'Điện thoại camera ẩn dưới màn hình', 12990000, NULL, 1),
('Laptop EVOO Gaming', 'Laptop gaming giá rẻ', 16990000, NULL, 2),
('Laptop System76 Lemur Pro', 'Laptop chạy Linux', 25990000, NULL, 2),
('Nokia T20', 'Máy tính bảng bền bỉ', 5990000, NULL, 3),
('Alldocube iPlay 40', 'Máy tính bảng giá rẻ hiệu năng tốt', 4490000, NULL, 3),
('Tai nghe Nothing ear (1)', 'Tai nghe không dây thiết kế độc đáo', 2990000, NULL, 5),
('Loa Anker Soundcore Motion+', 'Loa bluetooth chất lượng cao giá tốt', 2490000, NULL, 5),
('Gậy selfie Bluetooth', 'Gậy tự sướng kết nối không dây', 290000, NULL, 4),
('Đế tản nhiệt laptop', 'Đế tản nhiệt cho laptop gaming', 590000, NULL, 4),
('Điện thoại Sharp Aquos R6', 'Điện thoại với cảm biến camera lớn', 16990000, NULL, 1),
('Điện thoại Infinix Note 10 Pro', 'Điện thoại giá rẻ màn hình lớn', 4990000, NULL, 1),
('Laptop Tuxedo InfinityBook Pro', 'Laptop chạy Linux hiệu năng cao', 27990000, NULL, 2),
('Laptop Chuwi CoreBook X', 'Laptop giá rẻ màn hình 2K', 12990000, NULL, 2),
('Teclast M40 Pro', 'Máy tính bảng giá rẻ', 3990000, NULL, 3),
('Blackview Tab 9', 'Máy tính bảng pin trâu', 4490000, NULL, 3),
('Tai nghe Soundpeats TrueFree 2', 'Tai nghe không dây giá rẻ', 790000, NULL, 5),
('Loa Tribit StormBox Micro', 'Loa bluetooth nhỏ gọn', 1490000, NULL, 5),
('Giá đỡ laptop', 'Giá đỡ laptop có thể điều chỉnh độ cao', 490000, NULL, 4),
('Balo laptop chống nước', 'Balo đựng laptop chống nước', 890000, NULL, 4),
('Điện thoại Doogee S96 Pro', 'Điện thoại siêu bền', 7990000, NULL, 1),
('Điện thoại Umidigi A11 Pro', 'Điện thoại giá rẻ pin trâu', 3490000, NULL, 1),
('Laptop Mechrevo Code 01', 'Laptop cho lập trình viên', 19990000, NULL, 2),
('Laptop KUU G3', 'Laptop giá rẻ hiệu năng tốt', 9990000, NULL, 2),
('Vastking KingPad K10', 'Máy tính bảng giá rẻ', 3990000, NULL, 3),
('Pritom P7', 'Máy tính bảng giá rẻ cho trẻ em', 2490000, NULL, 3),
('Tai nghe Mpow M30', 'Tai nghe không dây chống nước', 890000, NULL, 5),
('Loa Tronsmart Element T6 Plus', 'Loa bluetooth công suất lớn', 1990000, NULL, 5),
('Ốp lưng Samsung Galaxy', 'Ốp lưng bảo vệ điện thoại Samsung', 190000, NULL, 4),
('Miếng lót chuột', 'Miếng lót chuột gaming cỡ lớn', 290000, NULL, 4);
