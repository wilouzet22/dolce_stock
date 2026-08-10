-- Migración: Tablas de Materia Prima y Proveedores para dulce_stock
-- Ejecutar en la base de datos dolce_stock

CREATE TABLE IF NOT EXISTS `proveedores` (
  `id_proveedor` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(150) NOT NULL,
  `contacto` VARCHAR(100) DEFAULT NULL,
  `telefono` VARCHAR(50) DEFAULT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `direccion` VARCHAR(255) DEFAULT NULL,
  `creado_en` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `materia_prima` (
  `id_materia_prima` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(150) NOT NULL,
  `unidad_medida` VARCHAR(50) NOT NULL DEFAULT 'unidades',
  `stock_actual` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `stock_minimo` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `precio_unitario` DECIMAL(10,2) DEFAULT NULL,
  `id_proveedor` INT DEFAULT NULL,
  `creado_en` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_materia_prima_proveedores` 
    FOREIGN KEY (`id_proveedor`) 
    REFERENCES `proveedores` (`id_proveedor`) 
    ON DELETE SET NULL 
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Datos de Prueba para Proveedores
INSERT INTO `proveedores` (`nombre`, `contacto`, `telefono`, `email`, `direccion`) VALUES
('Lácteos del Valle S.A.', 'Carlos Gómez', '3001234567', 'ventas@lacteosdelvalle.com', 'Calle 45 # 12-30, Medellín'),
('Distribuidora Azúcar & Harinas', 'María Rodríguez', '3119876543', 'contacto@azucarharinas.com', 'Carrera 70 # 25-14, Medellín'),
('Empaques & Cajas S.A.S.', 'Pedro Martínez', '3204567890', 'info@empaquesycajas.com', 'Av. Industrial # 88-10, Itagüí');

-- Datos de Prueba para Materia Prima
INSERT INTO `materia_prima` (`nombre`, `unidad_medida`, `stock_actual`, `stock_minimo`, `precio_unitario`, `id_proveedor`) VALUES
('Leche Entera', 'Litros', 50.00, 10.00, 3500.00, 1),
('Harina de Trigo Especial', 'Kg', 100.00, 20.00, 4200.00, 2),
('Azúcar Blanca Refinada', 'Kg', 80.00, 15.00, 3800.00, 2),
('Mantequilla Sin Sal', 'Kg', 15.00, 5.00, 18000.00, 1),
('Cajas de Cartón Repostería', 'Unidades', 200.00, 50.00, 1200.00, 3);
