-- Crear base de datos
CREATE DATABASE IF NOT EXISTS `pancontrol` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `pancontrol`;

-- =====================================================
-- CREACIÓN DE TABLAS
-- =====================================================

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS `user` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    passwd VARCHAR(255) NOT NULL,
    tipo TINYINT NOT NULL DEFAULT 2 COMMENT '1=Admin, 2=Usuario',
    activo TINYINT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de productos/inventario
CREATE TABLE IF NOT EXISTS `product` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    quantity DECIMAL(10,2) NOT NULL DEFAULT 0,
    unit VARCHAR(20) NOT NULL,
    min_quantity DECIMAL(10,2) NOT NULL DEFAULT 0,
    active TINYINT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de clientes
CREATE TABLE IF NOT EXISTS `client` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    active TINYINT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de pedidos
CREATE TABLE IF NOT EXISTS `order` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    total DECIMAL(10,2) NOT NULL DEFAULT 0,
    status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    delivery_date DATE,
    active TINYINT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES client(id)
);

-- Tabla de detalles de pedido
CREATE TABLE IF NOT EXISTS `order_detail` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES `order`(id),
    FOREIGN KEY (product_id) REFERENCES product(id)
);

-- =====================================================
-- DATOS DE PRUEBA
-- =====================================================

-- Limpiar datos existentes
DELETE FROM order_detail;
DELETE FROM `order`;
DELETE FROM client;
DELETE FROM product;
DELETE FROM user;

-- Reiniciar AUTO_INCREMENT
ALTER TABLE order_detail AUTO_INCREMENT = 1;
ALTER TABLE `order` AUTO_INCREMENT = 1;
ALTER TABLE client AUTO_INCREMENT = 1;
ALTER TABLE product AUTO_INCREMENT = 1;
ALTER TABLE user AUTO_INCREMENT = 1;

-- =====================================================
-- USUARIOS DE PRUEBA
-- =====================================================

INSERT INTO `user` (name, username, email, passwd, tipo, activo) VALUES
('Administrador del Sistema', 'admin', 'admin@pancontrol.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 1), -- Contraseña: admin123
('Gerente de Panadería', 'gerente', 'gerente@pancontrol.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 1), -- Contraseña: gerente123
('Usuario Regular', 'usuario', 'usuario@pancontrol.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 1), -- Contraseña: usuario123
('María González', 'maria', 'maria@pancontrol.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 1); -- Contraseña: maria123

-- =====================================================
-- PRODUCTOS DE INVENTARIO
-- =====================================================

INSERT INTO `product` (name, quantity, unit, min_quantity, active) VALUES
-- Ingredientes básicos
('Harina de Trigo', 50.00, 'kg', 10.00, 1),
('Azúcar Blanca', 25.00, 'kg', 5.00, 1),
('Levadura Fresca', 2.50, 'kg', 0.50, 1),
('Mantequilla', 15.00, 'kg', 3.00, 1),
('Huevos', 120, 'unidades', 24, 1),
('Leche Entera', 30.00, 'litros', 5.00, 1),
('Sal', 8.00, 'kg', 2.00, 1),
('Aceite Vegetal', 12.00, 'litros', 2.00, 1),

-- Ingredientes especiales
('Chocolate en Polvo', 5.00, 'kg', 1.00, 1),
('Vainilla Líquida', 1.50, 'litros', 0.25, 1),
('Canela en Polvo', 0.80, 'kg', 0.20, 1),
('Nueces', 3.00, 'kg', 0.50, 1),
('Pasas', 2.50, 'kg', 0.50, 1),
('Crema de Leche', 8.00, 'litros', 2.00, 1),
('Mermelada de Fresa', 4.00, 'kg', 1.00, 1),

-- Productos con stock bajo (para pruebas de alertas)
('Azúcar Glass', 0.30, 'kg', 0.50, 1), -- Stock bajo
('Levadura Seca', 0.20, 'kg', 0.30, 1), -- Stock bajo
('Colorante Rojo', 0.05, 'litros', 0.10, 1), -- Stock bajo

-- Productos adicionales
('Harina Integral', 20.00, 'kg', 5.00, 1),
('Miel Natural', 6.00, 'kg', 1.50, 1),
('Coco Rallado', 2.00, 'kg', 0.50, 1);

-- =====================================================
-- CLIENTES EMPRESARIALES
-- =====================================================

INSERT INTO `client` (name, email, phone, address, active) VALUES
('Panadería El Sol Dorado', 'pedidos@soldorado.com', '+34 912 345 678', 'Calle Mayor 123, Madrid, España', 1),
('Café y Pastelería Central', 'compras@cafecentral.es', '+34 913 456 789', 'Plaza del Carmen 45, Madrid, España', 1),
('Restaurante La Mesa Redonda', 'cocina@mesaredonda.com', '+34 914 567 890', 'Avenida de la Castellana 200, Madrid, España', 1),
('Hotel Majestic - Servicio de Catering', 'catering@hotelmajestic.es', '+34 915 678 901', 'Gran Vía 78, Madrid, España', 1),
('Supermercado Fresh Market', 'panaderia@freshmarket.com', '+34 916 789 012', 'Calle de Alcalá 350, Madrid, España', 1),
('Escuela Culinaria Madrid', 'suministros@escuelamadrid.edu', '+34 917 890 123', 'Calle de la Princesa 89, Madrid, España', 1),
('Cafetería Universidad Complutense', 'compras@cafeteriacomplutense.edu', '+34 918 901 234', 'Ciudad Universitaria, Madrid, España', 1),
('Pastelería Artesanal Dulce Hogar', 'info@dulcehogar.com', '+34 919 012 345', 'Calle de Serrano 156, Madrid, España', 1);

-- =====================================================
-- PEDIDOS DE EJEMPLO
-- =====================================================

-- Pedido 1: Panadería El Sol Dorado (COMPLETADO)
INSERT INTO `order` (client_id, total, status, delivery_date, created_at) VALUES 
(1, 145.50, 'completed', '2025-05-27', '2025-05-25 09:30:00');

INSERT INTO `order_detail` (order_id, product_id, quantity, price) VALUES
(1, 1, 20.00, 1.20), -- Harina 20kg a 1.20€/kg = 24.00€
(1, 2, 10.00, 0.80), -- Azúcar 10kg a 0.80€/kg = 8.00€
(1, 3, 1.00, 15.50), -- Levadura 1kg a 15.50€/kg = 15.50€
(1, 4, 5.00, 6.50),  -- Mantequilla 5kg a 6.50€/kg = 32.50€
(1, 5, 48, 0.25),    -- Huevos 48 unidades a 0.25€/u = 12.00€
(1, 6, 10.00, 1.10), -- Leche 10L a 1.10€/L = 11.00€
(1, 7, 2.00, 0.90),  -- Sal 2kg a 0.90€/kg = 1.80€
(1, 8, 3.00, 2.80);  -- Aceite 3L a 2.80€/L = 8.40€

-- Pedido 2: Café Central (EN PROCESO)
INSERT INTO `order` (client_id, total, status, delivery_date, created_at) VALUES 
(2, 167.25, 'processing', '2025-05-28', '2025-05-26 14:15:00');

INSERT INTO `order_detail` (order_id, product_id, quantity, price) VALUES
(2, 1, 15.00, 1.20),  -- Harina 15kg = 18.00€
(2, 9, 3.00, 8.50),   -- Chocolate 3kg a 8.50€/kg = 25.50€
(2, 10, 0.75, 12.00), -- Vainilla 0.75L a 12€/L = 9.00€
(2, 14, 5.00, 4.50),  -- Crema 5L a 4.50€/L = 22.50€
(2, 15, 3.00, 5.25),  -- Mermelada 3kg a 5.25€/kg = 15.75€
(2, 4, 8.00, 6.50),   -- Mantequilla 8kg = 52.00€
(2, 5, 96, 0.25);     -- Huevos 96 unidades = 24.00€

-- Pedido 3: Restaurante La Mesa Redonda (PENDIENTE)
INSERT INTO `order` (client_id, total, status, delivery_date, created_at) VALUES 
(3, 312.85, 'pending', '2025-05-29', '2025-05-27 16:45:00');

INSERT INTO `order_detail` (order_id, product_id, quantity, price) VALUES
(3, 1, 30.00, 1.20),  -- Harina 30kg = 36.00€
(3, 2, 15.00, 0.80),  -- Azúcar 15kg = 12.00€
(3, 4, 10.00, 6.50),  -- Mantequilla 10kg = 65.00€
(3, 5, 144, 0.25),    -- Huevos 144 unidades = 36.00€
(3, 6, 25.00, 1.10),  -- Leche 25L = 27.50€
(3, 12, 2.00, 12.00), -- Nueces 2kg a 12€/kg = 24.00€
(3, 13, 1.50, 8.00),  -- Pasas 1.5kg a 8€/kg = 12.00€
(3, 19, 8.00, 1.85),  -- Harina Integral 8kg a 1.85€/kg = 14.80€
(3, 20, 3.00, 8.50),  -- Miel 3kg a 8.50€/kg = 25.50€
(3, 21, 1.00, 12.50), -- Coco 1kg a 12.50€/kg = 12.50€
(3, 11, 0.50, 15.00), -- Canela 0.5kg a 15€/kg = 7.50€
(3, 8, 5.00, 2.80);   -- Aceite 5L = 14.00€

-- Pedido 4: Hotel Majestic (PENDIENTE - Pedido Grande)
INSERT INTO `order` (client_id, total, status, delivery_date, created_at) VALUES 
(4, 756.80, 'pending', '2025-05-30', '2025-05-28 10:20:00');

INSERT INTO `order_detail` (order_id, product_id, quantity, price) VALUES
(4, 1, 60.00, 1.15),  -- Harina 60kg (precio por volumen) = 69.00€
(4, 2, 30.00, 0.75),  -- Azúcar 30kg = 22.50€
(4, 3, 4.00, 15.50),  -- Levadura 4kg = 62.00€
(4, 4, 20.00, 6.50),  -- Mantequilla 20kg = 130.00€
(4, 5, 240, 0.25),    -- Huevos 240 unidades = 60.00€
(4, 6, 40.00, 1.10),  -- Leche 40L = 44.00€
(4, 8, 8.00, 2.80),   -- Aceite 8L = 22.40€
(4, 14, 12.00, 4.50), -- Crema 12L = 54.00€
(4, 9, 5.00, 8.50),   -- Chocolate 5kg = 42.50€
(4, 10, 2.00, 12.00), -- Vainilla 2L = 24.00€
(4, 15, 8.00, 5.25),  -- Mermelada 8kg = 42.00€
(4, 12, 3.00, 12.00), -- Nueces 3kg = 36.00€
(4, 13, 2.00, 8.00),  -- Pasas 2kg = 16.00€
(4, 20, 5.00, 8.50),  -- Miel 5kg = 42.50€
(4, 21, 2.00, 12.50), -- Coco 2kg = 25.00€
(4, 7, 5.00, 0.90),   -- Sal 5kg = 4.50€
(4, 11, 1.00, 15.00); -- Canela 1kg = 15.00€

-- Pedido 5: Supermercado Fresh Market (CANCELADO)
INSERT INTO `order` (client_id, total, status, delivery_date, created_at) VALUES 
(5, 89.50, 'cancelled', '2025-05-26', '2025-05-24 11:30:00');

INSERT INTO `order_detail` (order_id, product_id, quantity, price) VALUES
(5, 1, 25.00, 1.20),  -- Harina 25kg = 30.00€
(5, 2, 20.00, 0.80),  -- Azúcar 20kg = 16.00€
(5, 4, 5.00, 6.50),   -- Mantequilla 5kg = 32.50€
(5, 15, 2.00, 5.25);  -- Mermelada 2kg = 10.50€

-- Pedido 6: Escuela Culinaria (EN PROCESO)
INSERT INTO `order` (client_id, total, status, delivery_date, created_at) VALUES 
(6, 445.75, 'processing', '2025-05-31', '2025-05-28 13:45:00');

INSERT INTO `order_detail` (order_id, product_id, quantity, price) VALUES
(6, 1, 40.00, 1.20),  -- Harina 40kg = 48.00€
(6, 19, 15.00, 1.85), -- Harina Integral 15kg = 27.75€
(6, 2, 20.00, 0.80),  -- Azúcar 20kg = 16.00€
(6, 3, 3.00, 15.50),  -- Levadura 3kg = 46.50€
(6, 4, 15.00, 6.50),  -- Mantequilla 15kg = 97.50€
(6, 5, 180, 0.25),    -- Huevos 180 unidades = 45.00€
(6, 6, 20.00, 1.10),  -- Leche 20L = 22.00€
(6, 8, 6.00, 2.80),   -- Aceite 6L = 16.80€
(6, 9, 4.00, 8.50),   -- Chocolate 4kg = 34.00€
(6, 10, 1.50, 12.00), -- Vainilla 1.5L = 18.00€
(6, 11, 0.80, 15.00), -- Canela 0.8kg = 12.00€
(6, 12, 2.50, 12.00), -- Nueces 2.5kg = 30.00€
(6, 13, 1.50, 8.00),  -- Pasas 1.5kg = 12.00€
(6, 14, 4.00, 4.50),  -- Crema 4L = 18.00€
(6, 20, 2.00, 8.50);  -- Miel 2kg = 17.00€

-- Actualizar totales de pedidos (por si acaso)
UPDATE `order` SET total = (
    SELECT SUM(quantity * price) 
    FROM order_detail 
    WHERE order_id = `order`.id
);

-- =====================================================
-- VERIFICACIÓN DE DATOS
-- =====================================================

-- Mostrar resumen de datos insertados
SELECT 'RESUMEN DE DATOS INSERTADOS' as info;
SELECT 'Usuarios:', COUNT(*) as total FROM user;
SELECT 'Productos:', COUNT(*) as total FROM product;
SELECT 'Clientes:', COUNT(*) as total FROM client;
SELECT 'Pedidos:', COUNT(*) as total FROM `order`;
SELECT 'Detalles de pedido:', COUNT(*) as total FROM order_detail;

-- Mostrar productos con stock bajo (para alertas)
SELECT 'PRODUCTOS CON STOCK BAJO:' as info;
SELECT name, quantity, min_quantity, unit 
FROM product 
WHERE quantity <= min_quantity 
ORDER BY (quantity - min_quantity) ASC;

-- Mostrar pedidos por estado
SELECT 'PEDIDOS POR ESTADO:' as info;
SELECT status, COUNT(*) as cantidad, SUM(total) as total_euros
FROM `order` 
GROUP BY status;

-