-- FCT College of Nursing Sciences Sample Data
-- Version: 1.0
-- Created: 2024-12-27

-- ============================================================================
-- SAMPLE CAROUSEL SLIDES (3 slides as specified - STAGE 4 CRITICAL)
-- ============================================================================
INSERT INTO carousel_slides (title, subtitle, image_path, button_text, button_link, display_order, is_active) VALUES
(
    'Welcome to FCT College of Nursing Sciences',
    'Empowering Future Healthcare Professionals Since 1989',
    '/assets/images/carousel/slide1.jpg',
    'Explore Programs',
    '/programs',
    1,
    TRUE
),
(
    'Excellence in Nursing Education',
    'State-of-the-art facilities and experienced faculty',
    '/assets/images/carousel/slide2.jpg',
    'Learn More',
    '/about',
    2,
    TRUE
),
(
    'Start Your Journey Today',
    'Applications now open for 2025 admission',
    '/assets/images/carousel/slide3.jpg',
    'Apply Now',
    '/admissions',
    3,
    TRUE
);

-- ============================================================================
-- ADMIN USER (Already created per project brief)
-- Note: Password is pre-hashed as "Admin@123" - DO NOT change this
-- ============================================================================
INSERT INTO users (username, email, password_hash, full_name, role, is_active) VALUES
(
    'admin',
    'admin@fctcns.edu.ng',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- Hashed "Admin@123"
    'Administrator',
    'admin',
    TRUE
);

-- ============================================================================
-- SAMPLE NEWS ARTICLES
-- ============================================================================
INSERT INTO news_articles (title, slug, excerpt, content, featured_image, is_published, published_at, meta_title, meta_description) VALUES
(
    'New Simulation Lab Opening Ceremony',
    'new-simulation-lab-opening-ceremony',
    'FCT College of Nursing Sciences unveils state-of-the-art simulation lab for hands-on training.',
    '<p>The FCT College of Nursing Sciences is proud to announce the grand opening of our new Simulation Laboratory, equipped with the latest medical training technology.</p>
    <p>The lab features high-fidelity patient simulators, virtual reality training modules, and dedicated spaces for various medical scenarios including emergency response, maternal care, and pediatric nursing.</p>
    <p>This facility represents our commitment to providing students with practical, hands-on experience that bridges the gap between classroom learning and real-world clinical practice.</p>
    <p>Dean of Nursing, Dr. Amina Mohammed, stated: "This simulation lab will transform how we train our nursing students, ensuring they graduate with confidence and competence."</p>',
    '/assets/uploads/news/simulation-lab.jpg',
    TRUE,
    '2024-12-20 10:00:00',
    'New Simulation Lab at FCT College of Nursing Sciences',
    'State-of-the-art simulation laboratory opens at FCT College of Nursing Sciences for enhanced nursing education'
),
(
    '2025 Admissions Timeline Announced',
    '2025-admissions-timeline-announced',
    'Important dates and deadlines for prospective students applying for 2025 academic session.',
    '<h3>Admissions Calendar for 2025 Academic Session</h3>
    <p>The FCT College of Nursing Sciences announces the following timeline for the 2025 admissions cycle:</p>
    <ul>
        <li><strong>January 15, 2025:</strong> Online applications open</li>
        <li><strong>March 31, 2025:</strong> Application deadline</li>
        <li><strong>April 20, 2025:</strong> Entrance examination</li>
        <li><strong>May 15, 2025:</strong> Results announcement</li>
        <li><strong>June 1-15, 2025:</strong> Interview sessions</li>
        <li><strong>July 1, 2025:</strong> Final admission list published</li>
        <li><strong>September 15, 2025:</strong> Registration and orientation</li>
        <li><strong>October 1, 2025:</strong> Academic session begins</li>
    </ul>
    <p>Prospective students are advised to complete their applications early to avoid last-minute technical issues.</p>',
    '/assets/uploads/news/admissions-2025.jpg',
    TRUE,
    '2024-12-15 09:00:00',
    '2025 Admissions Timeline - FCT College of Nursing Sciences',
    'Important dates and deadlines for 2025 admissions at FCT College of Nursing Sciences'
);

-- ============================================================================
-- SAMPLE CONTACT MESSAGES
-- ============================================================================
INSERT INTO contact_messages (name, email, phone, subject, message, ip_address, is_read) VALUES
(
    'Chinwe Okoro',
    'chinwe.okoro@example.com',
    '+2348012345678',
    'Inquiry About Basic Nursing Program',
    'Dear Admissions Office, I am interested in the Basic Nursing program for 2025. Could you please send me the detailed curriculum and admission requirements? Also, are there scholarship opportunities available for merit-based students? Thank you.',
    '192.168.1.100',
    TRUE
),
(
    'Musa Ibrahim',
    'musa.ibrahim@example.com',
    '+2348023456789',
    'Clinical Placement Question',
    'Good day, I am a prospective student for the Post Basic Nursing program. I would like to know about the clinical placement opportunities and which hospitals are affiliated with the college for practical training. Are international clinical rotations available?',
    '192.168.1.101',
    FALSE
);

-- ============================================================================
-- SITE SETTINGS
-- ============================================================================
INSERT INTO settings (setting_key, setting_value, setting_type, category, description) VALUES
-- General Settings
('site_name', 'FCT College of Nursing Sciences', 'string', 'general', 'Name of the institution'),
('site_tagline', 'Empowering Future Healthcare Professionals', 'string', 'general', 'Site tagline'),
('contact_email', 'info@fctcns.edu.ng', 'string', 'general', 'Main contact email'),
('contact_phone', '+234 (0) 9 290 0000', 'string', 'general', 'Main contact phone'),
('contact_address', 'Plot 123, Garki District, Abuja, Nigeria', 'string', 'general', 'Physical address'),

-- Carousel Settings
('carousel_auto_rotate', 'true', 'boolean', 'carousel', 'Enable auto-rotation of carousel'),
('carousel_interval', '5000', 'number', 'carousel', 'Rotation interval in milliseconds'),
('carousel_transition', '500', 'number', 'carousel', 'Transition duration in milliseconds'),

-- Contact Form Settings
('contact_form_enabled', 'true', 'boolean', 'contact', 'Enable contact form'),
('contact_notify_email', 'admin@fctcns.edu.ng', 'string', 'contact', 'Email to notify on new messages'),

-- Social Media
('facebook_url', 'https://facebook.com/fctcns', 'string', 'social', 'Facebook page URL'),
('twitter_url', 'https://twitter.com/fctcns', 'string', 'social', 'Twitter profile URL'),
('instagram_url', 'https://instagram.com/fctcns', 'string', 'social', 'Instagram profile URL'),
('linkedin_url', 'https://linkedin.com/school/fctcns', 'string', 'social', 'LinkedIn page URL'),

-- Academic Programs
('programs_national_diploma', 'National Diploma Nursing', 'string', 'programs', 'Program 1 name'),
('programs_basic_nursing', 'Basic Nursing', 'string', 'programs', 'Program 2 name'),
('programs_basic_midwifery', 'Basic Midwifery', 'string', 'programs', 'Program 3 name'),
('programs_post_basic', 'Post Basic Nursing', 'string', 'programs', 'Program 4 name'),
('programs_community_health', 'Community Health Nursing', 'string', 'programs', 'Program 5 name');

-- ============================================================================
-- SAMPLE DATA VERIFICATION QUERIES
-- ============================================================================
SELECT '=== DATABASE VERIFICATION ===' AS '';
SELECT COUNT(*) AS 'Total Carousel Slides' FROM carousel_slides;
SELECT COUNT(*) AS 'Total Users' FROM users;
SELECT COUNT(*) AS 'Total News Articles' FROM news_articles;
SELECT COUNT(*) AS 'Total Contact Messages' FROM contact_messages;
SELECT COUNT(*) AS 'Total Settings' FROM settings;

SELECT '=== ACTIVE CAROUSEL SLIDES ===' AS '';
SELECT id, title, display_order, is_active FROM carousel_slides ORDER BY display_order;

SELECT '=== ADMIN USER ===' AS '';
SELECT username, email, role, is_active FROM users WHERE username = 'admin';