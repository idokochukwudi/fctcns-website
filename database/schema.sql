-- FCT College of Nursing Sciences Database Schema
-- Version: 1.0
-- Created: 2024-12-27

-- Drop existing tables if they exist (for fresh install)
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS carousel_slides;
DROP TABLE IF EXISTS news_articles;
DROP TABLE IF EXISTS contact_messages;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS settings;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================================
-- TABLE: carousel_slides (For homepage carousel - STAGE 4 CRITICAL)
-- ============================================================================
CREATE TABLE carousel_slides (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    subtitle VARCHAR(500),
    image_path VARCHAR(500) NOT NULL,
    button_text VARCHAR(50) DEFAULT 'Learn More',
    button_link VARCHAR(500) DEFAULT '/',
    display_order INT UNSIGNED DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_order (display_order),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: news_articles (For news/blog posts)
-- ============================================================================
CREATE TABLE news_articles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(300) NOT NULL UNIQUE,
    excerpt TEXT,
    content LONGTEXT NOT NULL,
    featured_image VARCHAR(500),
    author_id INT UNSIGNED,
    is_published BOOLEAN DEFAULT FALSE,
    published_at TIMESTAMP NULL,
    view_count INT UNSIGNED DEFAULT 0,
    meta_title VARCHAR(255),
    meta_description VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FULLTEXT idx_search (title, excerpt, content),
    INDEX idx_published (is_published, published_at),
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: contact_messages (For contact form submissions)
-- ============================================================================
CREATE TABLE contact_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    is_read BOOLEAN DEFAULT FALSE,
    is_archived BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_email (email),
    INDEX idx_read (is_read, created_at),
    INDEX idx_archived (is_archived)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: users (For admin and staff access)
-- ============================================================================
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    role ENUM('admin', 'editor', 'viewer') DEFAULT 'editor',
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_role (role, is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABLE: settings (For site configuration)
-- ============================================================================
CREATE TABLE settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('string', 'number', 'boolean', 'json', 'html') DEFAULT 'string',
    category VARCHAR(50) DEFAULT 'general',
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_key (setting_key),
    INDEX idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- INDEXES for performance
-- ============================================================================
CREATE INDEX idx_news_date ON news_articles(published_at DESC);
CREATE INDEX idx_messages_date ON contact_messages(created_at DESC);
CREATE INDEX idx_users_active ON users(is_active, role);

-- ============================================================================
-- STORED PROCEDURES (Optional - for complex operations)
-- ============================================================================
DELIMITER //

CREATE PROCEDURE GetActiveCarouselSlides()
BEGIN
    SELECT * FROM carousel_slides 
    WHERE is_active = TRUE 
    ORDER BY display_order ASC, created_at DESC
    LIMIT 10;
END //

CREATE PROCEDURE IncrementNewsViews(IN news_id INT)
BEGIN
    UPDATE news_articles 
    SET view_count = view_count + 1 
    WHERE id = news_id;
END //

DELIMITER ;

-- ============================================================================
-- VIEWS for common queries
-- ============================================================================
CREATE VIEW vw_latest_news AS
SELECT id, title, slug, excerpt, featured_image, published_at
FROM news_articles
WHERE is_published = TRUE AND published_at <= NOW()
ORDER BY published_at DESC
LIMIT 10;

CREATE VIEW vw_unread_messages AS
SELECT COUNT(*) as count
FROM contact_messages
WHERE is_read = FALSE AND is_archived = FALSE;

-- ============================================================================
-- TRIGGERS for data integrity
-- ============================================================================
DELIMITER //

CREATE TRIGGER before_news_insert
BEFORE INSERT ON news_articles
FOR EACH ROW
BEGIN
    IF NEW.slug IS NULL OR NEW.slug = '' THEN
        SET NEW.slug = LOWER(REPLACE(REPLACE(REPLACE(NEW.title, ' ', '-'), '.', ''), ',', ''));
    END IF;
    
    IF NEW.is_published = TRUE AND NEW.published_at IS NULL THEN
        SET NEW.published_at = NOW();
    END IF;
END //

CREATE TRIGGER before_user_insert
BEFORE INSERT ON users
FOR EACH ROW
BEGIN
    IF NEW.email IS NOT NULL THEN
        SET NEW.email = LOWER(TRIM(NEW.email));
    END IF;
END //

DELIMITER ;

-- ============================================================================
-- COMMENTS for documentation
-- ============================================================================
ALTER TABLE carousel_slides 
COMMENT = 'Homepage carousel slides with order and activation controls';

ALTER TABLE news_articles 
COMMENT = 'News and blog articles with publishing controls';

ALTER TABLE contact_messages 
COMMENT = 'Contact form submissions with read/unread tracking';

ALTER TABLE users 
COMMENT = 'User accounts for admin dashboard access';

ALTER TABLE settings 
COMMENT = 'Site configuration settings (key-value store)';