-- Create testimonials table
CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(100) NOT NULL,
    client_title VARCHAR(100),
    client_company VARCHAR(100),
    practice_area VARCHAR(50),
    rating INT DEFAULT 5,
    review_text TEXT NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert 9 professional testimonials
INSERT INTO testimonials (client_name, client_title, client_company, practice_area, rating, review_text, display_order) VALUES
(
    'Robert Chen',
    'CEO',
    'TechVentures Inc.',
    'Corporate Law',
    5,
    'The corporate legal team at Lawyerson guided us through a complex merger with exceptional expertise and professionalism. Their attention to detail and strategic insights were invaluable. I highly recommend their services to any business seeking top-tier legal counsel.',
    1
),
(
    'Jennifer Martinez',
    'Managing Director',
    'Horizon Real Estate Group',
    'Real Estate',
    5,
    'After working with several law firms over the years, I can confidently say that Lawyerson provides unmatched service in real estate law. They handled our commercial property acquisition seamlessly, navigating complex zoning issues with ease. Their team is responsive, knowledgeable, and truly client-focused.',
    2
),
(
    'David Thompson',
    'Private Client',
    NULL,
    'Family Law',
    5,
    'Going through a divorce was one of the most difficult times in my life, but Sarah Jenkins and her team made the process as smooth as possible. They were compassionate, understanding, and fought tirelessly for my children''s best interests. I am forever grateful for their support and expertise.',
    3
),
(
    'Marcus Williams',
    'Private Client',
    NULL,
    'Criminal Defense',
    5,
    'When I was wrongfully accused, Elena Rostova took my case and never gave up. Her dedication, thorough investigation, and courtroom presence led to my complete exoneration. She is an exceptional attorney who truly cares about justice. I cannot thank her enough.',
    4
),
(
    'Amanda Foster',
    'Founder & CEO',
    'Bloom Boutiques',
    'Corporate Law',
    5,
    'As a small business owner, I needed legal guidance I could trust without breaking the bank. Lawyerson provided clear, practical advice that helped me navigate trademark registration, contracts, and employment law. Their team treats every client with the same level of care and professionalism.',
    5
),
(
    'James Patterson',
    'VP of Operations',
    'Gateway Logistics',
    'Real Estate',
    5,
    'The team handled our multi-state warehouse lease negotiations with remarkable efficiency. Their deep understanding of commercial real estate law and strong negotiation skills saved us significant costs. Michael Chang and his colleagues are absolute professionals.',
    6
),
(
    'Lisa Henderson',
    'Private Client',
    NULL,
    'Family Law',
    5,
    'The prenuptial agreement process was handled with such discretion and professionalism. The attorneys explained every clause clearly and ensured both parties felt heard and protected. Their expertise gave us peace of mind as we started our marriage on a solid legal foundation.',
    7
),
(
    'Richard Chambers',
    'Private Client',
    NULL,
    'Criminal Defense',
    5,
    'Facing criminal charges was terrifying, but my defense attorney''s expertise and aggressive representation made all the difference. The charges were reduced significantly thanks to their negotiation skills and thorough case preparation. I walked away with my freedom and reputation intact.',
    8
),
(
    'Sophia Rodriguez',
    'COO',
    'Meridian Financial Partners',
    'Corporate Law',
    5,
    'Jonathan Doe and the corporate law team provided outstanding counsel during our IPO process. Their experience with securities law and regulatory compliance was evident at every stage. They anticipated issues before they arose and kept our timeline on track. Exceptional work all around.',
    9
);

-- Verify insertion
SELECT COUNT(*) as total_testimonials FROM testimonials WHERE status = 'active';
SELECT client_name, practice_area, rating FROM testimonials ORDER BY display_order;
