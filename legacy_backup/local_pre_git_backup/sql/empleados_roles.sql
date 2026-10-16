-- Migración para añadir soporte de Roles y Permisos a Empleados
-- Por favor, ejecuta este script en phpMyAdmin en tu base de datos dolce_stock

ALTER TABLE `empleados`
  ADD COLUMN `rol` ENUM('admin', 'cajero') NOT NULL DEFAULT 'cajero' AFTER `password_hash`;

-- Actualizar a todos los usuarios existentes como 'admin' para que no pierdas acceso
UPDATE `empleados` SET `rol` = 'admin';
