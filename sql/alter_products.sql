-- Add image field to products table
ALTER TABLE products
ADD COLUMN image VARCHAR(255) DEFAULT NULL AFTER description; 