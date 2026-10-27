-- Migration: Add message_replies table and update messages status
-- Run this to apply the database changes for the new message features

-- Update messages table to add 'replied' status
ALTER TABLE messages MODIFY COLUMN status ENUM('unread', 'read', 'replied') DEFAULT 'unread';

-- Create message_replies table
CREATE TABLE IF NOT EXISTS message_replies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message_id INT NOT NULL,
    reply_text TEXT NOT NULL,
    sent_via ENUM('dashboard', 'email') DEFAULT 'dashboard',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (message_id) REFERENCES messages(id) ON DELETE CASCADE
);
