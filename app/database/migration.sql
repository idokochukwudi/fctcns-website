-- Applications table (for admissions)
CREATE TABLE IF NOT EXISTS applications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    application_number VARCHAR(50) UNIQUE NOT NULL,
    program_applied VARCHAR(100) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('male', 'female', 'other') NOT NULL,
    nationality VARCHAR(50) NOT NULL,
    state_of_origin VARCHAR(50),
    lga VARCHAR(100),
    address TEXT NOT NULL,
    qualification_type VARCHAR(50) NOT NULL,
    qualification_details JSON,
    work_experience TEXT,
    referee_details JSON,
    passport_photo_path VARCHAR(500),
    documents_path VARCHAR(500),
    status ENUM('pending', 'under_review', 'approved', 'rejected', 'waitlisted') DEFAULT 'pending',
    admission_status ENUM('not_admitted', 'provisional', 'fully_admitted') DEFAULT 'not_admitted',
    payment_status ENUM('pending', 'partial', 'completed') DEFAULT 'pending',
    payment_reference VARCHAR(100),
    total_amount DECIMAL(10, 2) DEFAULT 0.00,
    amount_paid DECIMAL(10, 2) DEFAULT 0.00,
    application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reviewed_by INT UNSIGNED,
    review_date TIMESTAMP NULL,
    review_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_program (program_applied),
    INDEX idx_email (email),
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Research publications table
CREATE TABLE IF NOT EXISTS research_publications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(500) NOT NULL,
    authors TEXT NOT NULL,
    abstract TEXT,
    publication_type ENUM('journal', 'conference', 'book', 'thesis', 'report') DEFAULT 'journal',
    journal_name VARCHAR(300),
    volume VARCHAR(50),
    issue VARCHAR(50),
    pages VARCHAR(50),
    publisher VARCHAR(200),
    publication_date DATE,
    doi VARCHAR(100),
    url VARCHAR(500),
    keywords TEXT,
    research_area VARCHAR(100),
    citations INT DEFAULT 0,
    impact_factor DECIMAL(5,3),
    file_path VARCHAR(500),
    file_size INT,
    file_type VARCHAR(50),
    thumbnail_path VARCHAR(500),
    is_featured BOOLEAN DEFAULT FALSE,
    is_published BOOLEAN DEFAULT TRUE,
    views_count INT DEFAULT 0,
    downloads_count INT DEFAULT 0,
    created_by INT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    published_at TIMESTAMP NULL,
    INDEX idx_type (publication_type),
    INDEX idx_area (research_area),
    INDEX idx_featured (is_featured),
    INDEX idx_published (is_published),
    FULLTEXT idx_search (title, authors, abstract, keywords),
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- News table
CREATE TABLE IF NOT EXISTS news (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(300) NOT NULL,
    slug VARCHAR(300) UNIQUE NOT NULL,
    excerpt TEXT,
    content LONGTEXT NOT NULL,
    author_id INT UNSIGNED,
    category VARCHAR(100),
    tags JSON,
    featured_image VARCHAR(500),
    gallery JSON,
    is_published BOOLEAN DEFAULT TRUE,
    is_featured BOOLEAN DEFAULT FALSE,
    is_breaking BOOLEAN DEFAULT FALSE,
    views_count INT DEFAULT 0,
    likes_count INT DEFAULT 0,
    shares_count INT DEFAULT 0,
    comments_count INT DEFAULT 0,
    meta_title VARCHAR(300),
    meta_description TEXT,
    meta_keywords TEXT,
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_published (is_published),
    INDEX idx_featured (is_featured),
    INDEX idx_category (category),
    FULLTEXT idx_news_search (title, excerpt, content),
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Application payments table
CREATE TABLE IF NOT EXISTS application_payments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    application_id INT UNSIGNED NOT NULL,
    reference VARCHAR(100) UNIQUE NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_method VARCHAR(50),
    transaction_id VARCHAR(200),
    payer_email VARCHAR(255),
    payer_name VARCHAR(255),
    status ENUM('pending', 'success', 'failed', 'cancelled') DEFAULT 'pending',
    payment_date TIMESTAMP NULL,
    verified_at TIMESTAMP NULL,
    verified_by INT UNSIGNED,
    payment_details JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_reference (reference),
    INDEX idx_status (status),
    INDEX idx_application (application_id),
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE,
    FOREIGN KEY (verified_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Research categories table
CREATE TABLE IF NOT EXISTS research_categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    parent_id INT UNSIGNED,
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_parent (parent_id),
    INDEX idx_active (is_active),
    FOREIGN KEY (parent_id) REFERENCES research_categories(id) ON DELETE CASCADE
);

-- User permissions table
CREATE TABLE IF NOT EXISTS user_permissions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    permission VARCHAR(100) NOT NULL,
    is_allowed BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_permission (user_id, permission),
    INDEX idx_user (user_id),
    INDEX idx_permission (permission),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Activity logs table
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED,
    action VARCHAR(100) NOT NULL,
    description TEXT,
    table_name VARCHAR(100),
    record_id VARCHAR(100),
    ip_address VARCHAR(45),
    user_agent TEXT,
    metadata JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert initial research categories
INSERT INTO research_categories (name, slug, description, sort_order) VALUES
('Clinical Nursing', 'clinical-nursing', 'Evidence-based clinical practice research', 1),
('Community Health', 'community-health', 'Community-based health interventions', 2),
('Nursing Education', 'nursing-education', 'Teaching methodologies and curriculum development', 3),
('Mental Health Nursing', 'mental-health', 'Psychiatric and mental health nursing', 4),
('Maternal & Child Health', 'maternal-child-health', 'Reproductive and child health research', 5),
('Health Policy & Systems', 'health-policy', 'Healthcare policy and systems research', 6),
('Geriatric Nursing', 'geriatric-nursing', 'Elderly care and gerontology', 7),
('Emergency Nursing', 'emergency-nursing', 'Emergency and critical care nursing', 8),
('Oncology Nursing', 'oncology-nursing', 'Cancer care and oncology nursing', 9),
('Digital Health', 'digital-health', 'Technology in healthcare delivery', 10);

-- Insert default permissions
INSERT INTO user_permissions (user_id, permission) 
SELECT id, 'manage_users' FROM users WHERE role = 'admin'
UNION ALL
SELECT id, 'manage_applications' FROM users WHERE role IN ('admin', 'editor')
UNION ALL
SELECT id, 'manage_research' FROM users WHERE role IN ('admin', 'editor')
UNION ALL
SELECT id, 'manage_news' FROM users WHERE role IN ('admin', 'editor')
UNION ALL
SELECT id, 'view_reports' FROM users WHERE role IN ('admin', 'editor')
UNION ALL
SELECT id, 'view_dashboard' FROM users WHERE role IN ('admin', 'editor', 'viewer');

-- Add some sample news
INSERT INTO news (title, slug, excerpt, content, category, is_published, is_featured, published_at) VALUES
('New Nursing Program Launch', 'new-nursing-program-launch', 'Announcing our new Advanced Nursing Practice program', '<p>The FCT College of Nursing Sciences is proud to announce the launch of our new Advanced Nursing Practice program...</p>', 'Announcements', TRUE, TRUE, NOW()),
('Research Grant Awarded', 'research-grant-awarded', 'College receives major research grant for community health study', '<p>We are excited to announce that our research team has been awarded a ₦25 million grant...</p>', 'Research', TRUE, FALSE, NOW());