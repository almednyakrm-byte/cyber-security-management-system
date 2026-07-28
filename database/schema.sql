CREATE TABLE users (
  id INT AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE reports (
  id INT AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE analyses (
  id INT AUTO_INCREMENT,
  report_id INT,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (report_id) REFERENCES reports(id) ON DELETE CASCADE
);

CREATE TABLE risks (
  id INT AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE patches (
  id INT AUTO_INCREMENT,
  risk_id INT,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (risk_id) REFERENCES risks(id) ON DELETE CASCADE
);

CREATE TABLE user_pages (
  id INT AUTO_INCREMENT,
  user_id INT,
  page_name VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO users (username, email, password, role)
VALUES ('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO user_pages (user_id, page_name)
VALUES (1, 'الصفحة الرئيسية'),
       (1, 'إدارة الإبلاغات'),
       (1, 'إدارة التحليلات'),
       (1, 'إدارة المخاطر'),
       (1, 'إدارة التصحيحات');

INSERT INTO reports (title, description)
VALUES ('Report 1', 'This is a report'),
       ('Report 2', 'This is another report');

INSERT INTO analyses (report_id, title, description)
VALUES (1, 'Analysis 1', 'This is an analysis'),
       (2, 'Analysis 2', 'This is another analysis');

INSERT INTO risks (title, description)
VALUES ('Risk 1', 'This is a risk'),
       ('Risk 2', 'This is another risk');

INSERT INTO patches (risk_id, title, description)
VALUES (1, 'Patch 1', 'This is a patch'),
       (2, 'Patch 2', 'This is another patch');