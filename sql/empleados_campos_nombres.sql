-- Migración: Añadir campos de nombres y apellidos separados a la tabla empleados
ALTER TABLE `empleados`
  ADD COLUMN IF NOT EXISTS `primer_nombre` varchar(70) DEFAULT NULL AFTER `id_empleado`,
  ADD COLUMN IF NOT EXISTS `segundo_nombre` varchar(70) DEFAULT NULL AFTER `primer_nombre`,
  ADD COLUMN IF NOT EXISTS `primer_apellido` varchar(70) DEFAULT NULL AFTER `segundo_nombre`,
  ADD COLUMN IF NOT EXISTS `segundo_apellido` varchar(70) DEFAULT NULL AFTER `primer_apellido`;
