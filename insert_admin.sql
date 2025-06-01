-- Insert admin account
INSERT INTO account (username, password, fullname, role)
VALUES ('admin', '$2y$10$VQYaPlxux7CLNC8mdJ0LeuWxP8cqviVVP4KD0VFaB6VgvBgGRMq5e', 'Administrator', 'admin');

-- Note: The password hash above corresponds to 'admin123'
-- Make sure to change this password after first login for security reasons
