-- Insert default admin user (password: admin123)
-- Hash généré avec bcryptjs : bcrypt.hashSync('admin123', 10)
INSERT INTO users (email, password, first_name, last_name, role)
VALUES (
    'admin@maraicher.com',
    '$2a$10$2AtytVYGBd10VCw7QXgpIOCTW/fNk5lhW3EFC1hNc5pyrTOZVfk0.',
    'Admin',
    'Maraicher',
    'admin'
);

-- Insert sample customer (password: customer123)
INSERT INTO users (email, password, first_name, last_name, phone, address, city, postal_code, role)
VALUES (
    'customer@example.com',
    '$2a$10$htG/3uNp731cvpWKDmsnCeBVOqkG7toe6yn0Et7adS5Uz3rVvtGqa',
    'Jean',
    'Dupont',
    '0612345678',
    '123 Rue de la Paix',
    'Paris',
    '75001',
    'customer'
);

-- Insert sample products
INSERT INTO products (name, description, price, stock_quantity, unit, category) VALUES
('Tomates Bio', 'Tomates fraîches cultivées localement', 4.50, 50, 'kg', 'Légumes'),
('Carottes', 'Carottes croquantes et sucrées', 3.00, 80, 'kg', 'Légumes'),
('Laitue', 'Laitue fraîche du jour', 2.50, 30, 'pièce', 'Salades'),
('Courgettes', 'Courgettes vertes tendres', 3.50, 40, 'kg', 'Légumes'),
('Pommes de terre', 'Pommes de terre locales', 2.00, 100, 'kg', 'Légumes'),
('Fraises', 'Fraises fraîches et juteuses', 8.00, 20, 'barquette', 'Fruits'),
('Concombres', 'Concombres croquants', 2.80, 35, 'kg', 'Légumes'),
('Poivrons', 'Mélange de poivrons colorés', 5.00, 25, 'kg', 'Légumes'),
('Aubergines', 'Aubergines fraîches', 4.20, 30, 'kg', 'Légumes'),
('Radis', 'Radis croquants', 1.80, 40, 'botte', 'Légumes'),
('Épinards', 'Épinards frais', 3.80, 25, 'kg', 'Légumes'),
('Haricots verts', 'Haricots verts extra-fins', 5.50, 35, 'kg', 'Légumes');
