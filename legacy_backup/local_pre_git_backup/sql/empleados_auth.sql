-- Corre en phpMyAdmin sobre la base `dolce_stock` (una sola vez)
-- Credenciales para que los empleados inicien sesión / se registren

ALTER TABLE `empleados`
  ADD COLUMN `email` varchar(150) DEFAULT NULL AFTER `salario`,
  ADD COLUMN `password_hash` varchar(255) DEFAULT NULL AFTER `email`,
  ADD UNIQUE KEY `uq_empleados_email` (`email`);
