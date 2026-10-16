-- Migración para añadir campos de Identificación, Apellidos, Correo y Dirección a Clientes

ALTER TABLE `clientes`
  ADD COLUMN `identificacion` varchar(50) DEFAULT NULL AFTER `id_cliente`,
  ADD COLUMN `apellidos` varchar(150) DEFAULT NULL AFTER `nombre`,
  ADD COLUMN `email` varchar(150) DEFAULT NULL AFTER `apellidos`,
  ADD COLUMN `direccion` varchar(255) DEFAULT NULL AFTER `email`;
