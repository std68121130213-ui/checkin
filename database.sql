CREATE DATABASE IF NOT EXISTS checkin_activity CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE checkin_activity;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fullname VARCHAR(150) NOT NULL,
    role ENUM('student','pupil','director','admin') NOT NULL DEFAULT 'student',
    status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    activity_date DATE NOT NULL,
    start_time TIME,
    end_time TIME,
    location VARCHAR(200),
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    activity_id INT NOT NULL,
    checkin_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    detail VARCHAR(255),
    UNIQUE KEY unique_attendance (user_id, activity_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (activity_id) REFERENCES activities(id) ON DELETE CASCADE
);

-- บัญชีแอดมินสำหรับเริ่มต้นระบบ
-- username: admin
-- password: password
INSERT INTO users (username, password, fullname, role, status)
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llCwF4nKQqY7G3n0p2e6u', 'ผู้ดูแลระบบ', 'admin', 'approved');
