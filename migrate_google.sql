-- Add Google ID column to users table
ALTER TABLE users ADD COLUMN google_id VARCHAR(255) UNIQUE NULL;
ALTER TABLE users MODIFY password VARCHAR(255) NULL;