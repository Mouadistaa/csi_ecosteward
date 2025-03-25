-- ===========================================================================
-- 1) (Re)Création de la base
-- ===========================================================================
DROP DATABASE IF EXISTS eco_farm;
CREATE DATABASE eco_farm;
USE eco_farm;

-- ===========================================================================
-- 2) Table: users
-- ===========================================================================
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'woofer') NOT NULL DEFAULT 'woofer',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ===========================================================================
-- 3) Table: products
-- ===========================================================================
DROP TABLE IF EXISTS products;
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    seuil_alerte INT NOT NULL DEFAULT 20,
    price DECIMAL(10,2) NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ===========================================================================
-- 4) Table: sales
-- ===========================================================================
DROP TABLE IF EXISTS sales;
CREATE TABLE sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,        -- Qui a vendu ? (admin/woofer)
    product_id INT NOT NULL,     -- Quel produit
    quantity INT NOT NULL,
    sale_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    prix_unitaire DECIMAL(10,2) NOT NULL DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- ===========================================================================
-- 5) Table: woofers
-- ===========================================================================
DROP TABLE IF EXISTS woofers;
CREATE TABLE woofers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,              -- Lien éventuel vers table users
    name VARCHAR(100) NOT NULL,
    start_date DATE,
    end_date DATE,
    competencies VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ===========================================================================
-- 6) Table: workshops
-- ===========================================================================
DROP TABLE IF EXISTS workshops;
CREATE TABLE workshops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    workshop_date DATE NOT NULL,
    animator_id INT,         -- le woofer animateur
    capacity INT NOT NULL DEFAULT 10,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (animator_id) REFERENCES woofers(id)
);

-- ===========================================================================
-- 7) Table: registrations (inscriptions aux ateliers)
-- ===========================================================================
DROP TABLE IF EXISTS registrations;
CREATE TABLE registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    workshop_id INT NOT NULL,
    participant_name VARCHAR(100) NOT NULL,
    participant_email VARCHAR(100) NOT NULL,
    registered_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (workshop_id) REFERENCES workshops(id)
);

-- ===========================================================================
-- 8) NOUVELLE TABLE: planning
--     Pour stocker des créneaux horaires par woofer (optionnel).
-- ===========================================================================
DROP TABLE IF EXISTS planning;
CREATE TABLE planning (
    id INT AUTO_INCREMENT PRIMARY KEY,
    woofer_id INT,
    plan_date DATE,              -- jour concerné
    start_time TIME,             -- heure début
    end_time TIME,               -- heure fin
    task_name VARCHAR(100),      
    location VARCHAR(100),       
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (woofer_id) REFERENCES woofers(id) ON DELETE SET NULL
);

-- ===========================================================================
-- 9) INSERTS DE TEST
-- ===========================================================================
-- 9.1) Quelques utilisateurs
-- Admin par défaut
INSERT INTO users (email, password_hash, role) VALUES 
('mouad.sahraouidoukkali@farmmail.com', SHA2('admin123', 256), 'admin'),
('aymane.benamar@farmmail.com', SHA2('aymane123', 256), 'woofer'),
('pierre.martin@farmmail.com', SHA2('pierre123', 256), 'woofer'),
('alice.marchand@farmmail.com', SHA2('alice123', 256), 'woofer');

-- 9.2) Des produits
INSERT INTO products (name, category, stock, seuil_alerte, price) VALUES
('Œufs bio',         'Œufs',     150,  20, 0.30),
('Fromage artisanal','Laitiers',  50,  10, 8.00),
('Lait frais',       'Laitiers',  30,  15, 1.20),
('Carottes',         'Légumes',   40,  10, 1.10),
('Tomates',          'Légumes',   25,  10, 2.50);

-- 9.3) Woofers
INSERT INTO woofers (user_id, name, start_date, end_date, competencies)
VALUES 
(2, 'Marie Dupont',   '2025-03-01', '2025-03-31', 'Soins animaux, Vente'),
(3, 'Pierre Martin',  '2025-04-01', '2025-04-15', 'Maraîchage, Accueil'),

-- 9.4) Workshops
INSERT INTO workshops (title, workshop_date, animator_id, capacity)
VALUES
('Fabrication Fromage', '2025-03-15', 1, 12),
('Découverte Maraîchage','2025-04-02',2, 10);

-- 9.5) Sales (ventes)
INSERT INTO sales (user_id, product_id, quantity, sale_date, prix_unitaire)
VALUES
(1, 1, 10, '2025-03-10 09:15:00', 0.30),
(2, 2, 2, '2025-03-22 10:00:00', 8.00),
(2, 1, 6, '2025-03-21 11:00:00', 0.30),
(1, 1, 5, '2025-03-23 14:20:00', 0.30),
(1, 2, 1, '2025-03-23 15:00:00', 8.00),

-- 9.6) Registrations (inscriptions aux ateliers)
INSERT INTO registrations (workshop_id, participant_name, participant_email)
VALUES
(1, 'Alice Martin', 'alice@mail.com'),
(1, 'Jean Dupont',  'jean@example.com'),
(2, 'Laura Petit',  'laura@exemple.com');

-- 9.7) PLANNING: données de démo
INSERT INTO planning (woofer_id, plan_date, start_time, end_time, task_name, location)
VALUES
(1, '2025-03-20', '08:00:00', '09:30:00', 'Soins animaux', 'Étable'),
(1, '2025-03-20', '10:00:00', '12:00:00', 'Vente produits', 'Boutique'),
(1, '2025-03-20', '14:00:00', '15:30:00', 'Atelier fromage', 'Formation'),
(2, '2025-04-03', '08:00:00', '09:00:00', 'Préparation champs', 'Extérieur'),
(2, '2025-04-03', '10:00:00', '12:00:00', 'Récolte carottes', 'Champs');
