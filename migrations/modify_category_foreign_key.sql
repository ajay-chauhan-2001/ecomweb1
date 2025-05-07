-- First, drop the existing foreign key constraint
ALTER TABLE products DROP FOREIGN KEY products_ibfk_1;

-- Then, add the new foreign key constraint with ON DELETE CASCADE
ALTER TABLE products 
ADD CONSTRAINT products_ibfk_1 
FOREIGN KEY (category_id) 
REFERENCES categories(id) 
ON DELETE CASCADE 
ON UPDATE CASCADE; 