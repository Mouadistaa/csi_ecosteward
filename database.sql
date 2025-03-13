DROP DATABASE IF EXISTS eco_farm;
CREATE DATABASE eco_farm;
USE eco_farm;

-- =========================
-- Table : users
-- =========================
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'woofer') NOT NULL DEFAULT 'woofer',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- Table : products
-- =========================
DROP TABLE IF EXISTS products;
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- Table : sales
-- =========================
DROP TABLE IF EXISTS sales;
CREATE TABLE sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,            -- Qui a vendu ? (admin/woofer)
    product_id INT NOT NULL,         -- Quel produit ?
    quantity INT NOT NULL,
    sale_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- =========================
-- Table : woofers
-- =========================
DROP TABLE IF EXISTS woofers;
CREATE TABLE woofers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,                     -- Optionnel : lien vers table users
    name VARCHAR(100) NOT NULL,
    start_date DATE,                 -- début de séjour
    end_date DATE,                   -- fin de séjour (peut être NULL)
    competencies VARCHAR(255),       -- ex: "Soins animaux, Vente"
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- =========================
-- Table : workshops
-- =========================
DROP TABLE IF EXISTS workshops;
CREATE TABLE workshops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    workshop_date DATE NOT NULL,
    animator_id INT,                 -- le woofer animateur
    capacity INT NOT NULL DEFAULT 10,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (animator_id) REFERENCES woofers(id)
);

-- =========================
-- Table : registrations (Inscriptions aux ateliers)
-- =========================
DROP TABLE IF EXISTS registrations;
CREATE TABLE registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    workshop_id INT NOT NULL,
    participant_name VARCHAR(100) NOT NULL,
    participant_email VARCHAR(100) NOT NULL,
    registered_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (workshop_id) REFERENCES workshops(id)
);

-- =========================
-- INSERT DE TEST
-- =========================

-- 2. Quelques produits
INSERT INTO products (name, category, stock) VALUES
('Œufs bio', 'Œufs', 150),
('Fromage artisanal', 'Laitiers', 50),
('Lait frais', 'Laitiers', 30);

-- 3. Un woofer
INSERT INTO woofers (name, start_date, end_date, competencies)
VALUES ('Marie Dupont', '2025-03-01', '2025-03-31', 'Soins animaux, Vente');

-- 4. Un atelier
INSERT INTO workshops (title, workshop_date, animator_id, capacity)
VALUES ('Fabrication Fromage', '2025-03-15', 1, 12);
