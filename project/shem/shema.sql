CREATE DATABASE IF NOT EXISTS student;
CREATE USER 'blog_user'@'localhost' IDENTIFIED BY '';
GRANT ALL PRIVILEGES ON student.* TO 'root'@'localhost';

USE student;


CREATE TABLE posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


CREATE TABLE comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    post_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    body TEXT NOT NULL,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
) ENGINE=InnoDB;


ALTER TABLE posts ADD FULLTEXT INDEX ft_search (title, content);
ALTER TABLE comments ADD FULLTEXT INDEX ft_comment_search (body);