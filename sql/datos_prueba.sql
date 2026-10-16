USE dolce_stock;
SET NAMES utf8mb4;

-- ══════════════════════════════════════════
-- 1. CLIENTES (15 clientes de prueba)
-- ══════════════════════════════════════════
INSERT INTO clientes (identificacion, nombre, apellidos, email, direccion, `tipo de documento`, documento, `direcci?n`) VALUES
('1045678901','Sofia','Ramirez Montoya','sofia.ramirez@gmail.com','Cra 45 #12-30 Laureles','CC','1045678901','Cra 45 #12-30'),
('1067234510','Valentina','Gomez Rios','vgomez@hotmail.com','Cl 33 #76-10 El Poblado','CC','1067234510','Cl 33 #76-10'),
('1023456789','Camila','Torres Herrera','camila.torres@outlook.com','Av El Ferrocarril #38-20','CC','1023456789','Av Ferrocarril'),
('900123456','Cafe Delicias SAS','','contacto@cafedelicias.co','Cl 80 #52-15 Robledo','NIT','900123456','Cl 80 #52-15'),
('1052341100','Andres','Marin Ospina','andres.marin@gmail.com','Cra 70 #41-05 Belen','CC','1052341100','Cra 70 #41-05'),
('1044789012','Mariana','Perea Cano','mariana.perea@gmail.com','Cl 10 #43D-12 El Centro','CC','1044789012','Cl 10 #43D-12'),
('1038456712','Juan','Cardona Silva','jcardona@gmail.com','Cra 80 #34-67 Castilla','CC','1038456712','Cra 80 #34-67'),
('71234567','Carlos','Perez Lopera','carlos.perez@empresarial.co','Cl 19 #45-23 La America','CC','71234567','Cl 19 #45-23'),
('1060987654','Isabella','Vargas Duque','isabella.v@gmail.com','Cra 65 #50A-10 Laureles','CC','1060987654','Cra 65 #50A-10'),
('43876543','Maria','Restrepo Calle','mrestrepo@outlook.com','Cl 44 #78-90 Florida Nueva','CC','43876543','Cl 44 #78-90'),
('1029876543','Santiago','Gutierrez Mora','sgutierrez@gmail.com','Av 33 #65-11 Manrique','CC','1029876543','Av 33 #65-11'),
('900234567','Pasteleria La Dulce Vida','','admin@ladulcevida.co','Cl 70 #45-20 Aranjuez','NIT','900234567','Cl 70 #45-20'),
('1071234561','Laura','Jimenez Castro','laura.jimenez@gmail.com','Cra 48 #22-33 Boston','CC','1071234561','Cra 48 #22-33'),
('1080456123','Felipe','Saldarriaga Rios','felipe.s@hotmail.com','Cl 77 #80-54 Robledo','CC','1080456123','Cl 77 #80-54'),
('1066123456','Alejandra','Zapata Naranjo','ale.zapata@gmail.com','Cra 43A #1-50 El Poblado','CC','1066123456','Cra 43A #1-50');

-- ══════════════════════════════════════════
-- 2. FACTURAS + DETALLE (25 facturas de prueba)
-- ══════════════════════════════════════════

-- Factura 1
INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-01 08:32:00',1,1,22300.00);
SET @f1=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES
(@f1,35,2,5200.00,10400.00),(@f1,34,1,8300.00,8300.00),(@f1,46,1,3600.00,3600.00);

-- Factura 2
INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-01 09:15:00',2,2,46000.00);
SET @f2=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES
(@f2,38,2,14000.00,28000.00),(@f2,43,1,9000.00,9000.00),(@f2,46,1,3600.00,3600.00),(@f2,50,1,5400.00,5400.00);

-- Factura 3
INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-01 10:00:00',3,1,37000.00);
SET @f3=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES
(@f3,34,2,8300.00,16600.00),(@f3,36,1,19000.00,19000.00),(@f3,48,1,1400.00,1400.00);

-- Factura 4
INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-02 08:45:00',4,3,58300.00);
SET @f4=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES
(@f4,37,1,18000.00,18000.00),(@f4,38,1,14000.00,14000.00),(@f4,39,1,14000.00,14000.00),(@f4,45,2,5200.00,10400.00),(@f4,46,1,1900.00,1900.00);

-- Factura 5
INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-02 11:30:00',5,2,24600.00);
SET @f5=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES
(@f5,44,2,5500.00,11000.00),(@f5,34,1,8300.00,8300.00),(@f5,46,1,5300.00,5300.00);

-- Factura 6
INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-03 09:00:00',6,1,45000.00);
SET @f6=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES
(@f6,36,1,19000.00,19000.00),(@f6,37,1,18000.00,18000.00),(@f6,45,1,5200.00,5200.00),(@f6,47,1,2800.00,2800.00);

-- Factura 7
INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-03 14:20:00',7,3,31500.00);
SET @f7=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES
(@f7,40,1,13000.00,13000.00),(@f7,41,1,9000.00,9000.00),(@f7,34,1,8300.00,8300.00),(@f7,46,1,1200.00,1200.00);

-- Factura 8
INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-04 08:10:00',8,2,19700.00);
SET @f8=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES
(@f8,46,2,3600.00,7200.00),(@f8,44,1,5500.00,5500.00),(@f8,34,1,5200.00,5200.00),(@f8,31,1,1800.00,1800.00);

-- Factura 9
INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-04 10:45:00',9,1,42000.00);
SET @f9=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES
(@f9,38,1,14000.00,14000.00),(@f9,39,1,14000.00,14000.00),(@f9,35,1,5200.00,5200.00),(@f9,42,1,6500.00,6500.00),(@f9,46,1,2300.00,2300.00);

-- Factura 10
INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-05 09:30:00',10,3,27800.00);
SET @f10=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES
(@f10,36,1,19000.00,19000.00),(@f10,46,2,3600.00,7200.00),(@f10,31,1,1600.00,1600.00);

-- Factura 11
INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-06 08:00:00',11,1,55000.00);
SET @f11=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES
(@f11,38,1,14000.00,14000.00),(@f11,40,1,13000.00,13000.00),(@f11,37,1,18000.00,18000.00),(@f11,35,2,5200.00,10000.00);

-- Factura 12
INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-06 11:00:00',12,2,33100.00);
SET @f12=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES
(@f12,34,2,8300.00,16600.00),(@f12,43,1,9000.00,9000.00),(@f12,44,1,5500.00,5500.00),(@f12,31,1,2000.00,2000.00);

-- Factura 13
INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-07 09:10:00',13,1,20000.00);
SET @f13=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES
(@f13,36,1,19000.00,19000.00),(@f13,46,1,1000.00,1000.00);

-- Factura 14
INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-07 13:00:00',14,3,48200.00);
SET @f14=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES
(@f14,38,2,14000.00,28000.00),(@f14,41,1,9000.00,9000.00),(@f14,34,1,8300.00,8300.00),(@f14,46,1,2900.00,2900.00);

-- Factura 15
INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-08 08:30:00',15,2,14300.00);
SET @f15=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES
(@f15,35,1,5200.00,5200.00),(@f15,46,2,3600.00,7200.00),(@f15,47,1,1900.00,1900.00);

-- Factura 16
INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-08 10:15:00',1,1,63000.00);
SET @f16=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES
(@f16,38,3,14000.00,42000.00),(@f16,40,1,13000.00,13000.00),(@f16,45,1,5200.00,5200.00),(@f16,31,1,2800.00,2800.00);

-- Factura 17
INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-09 09:00:00',3,3,35500.00);
SET @f17=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES
(@f17,37,1,18000.00,18000.00),(@f17,34,1,8300.00,8300.00),(@f17,42,1,6500.00,6500.00),(@f17,46,1,2700.00,2700.00);

-- Factura 18
INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-09 12:45:00',5,2,22000.00);
SET @f18=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES
(@f18,36,1,19000.00,19000.00),(@f18,46,1,3000.00,3000.00);

-- Factura 19
INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-10 08:20:00',7,1,41800.00);
SET @f19=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES
(@f19,38,1,14000.00,14000.00),(@f19,39,1,14000.00,14000.00),(@f19,34,1,8300.00,8300.00),(@f19,44,1,5500.00,5500.00);

-- Factura 20
INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-10 15:30:00',9,3,29300.00);
SET @f20=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES
(@f20,36,1,19000.00,19000.00),(@f20,45,2,5200.00,10400.00),(@f20,47,1,1900.00,1900.00),(@f20,31,1,1000.00,1000.00);

-- Facturas de HOY para el dashboard
INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES (NOW(),2,1,28300.00);
SET @f21=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES
(@f21,34,1,8300.00,8300.00),(@f21,37,1,18000.00,18000.00),(@f21,46,1,2000.00,2000.00);

INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES (NOW(),4,2,47000.00);
SET @f22=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES
(@f22,38,2,14000.00,28000.00),(@f22,40,1,13000.00,13000.00),(@f22,45,1,5200.00,5200.00),(@f22,46,1,800.00,800.00);

INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES (NOW(),6,3,33500.00);
SET @f23=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES
(@f23,36,1,19000.00,19000.00),(@f23,34,1,8300.00,8300.00),(@f23,44,1,5500.00,5500.00),(@f23,46,1,700.00,700.00);

INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES (NOW(),8,1,14000.00);
SET @f24=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES
(@f24,38,1,14000.00,14000.00);

INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES (NOW(),10,2,22700.00);
SET @f25=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES
(@f25,34,1,8300.00,8300.00),(@f25,41,1,9000.00,9000.00),(@f25,35,1,5200.00,5200.00),(@f25,46,1,200.00,200.00);

-- ══════════════════════════════════════════
-- 3. MOVIMIENTOS DE INVENTARIO (entradas de stock)
-- ══════════════════════════════════════════
INSERT INTO movimientos_inventario (id_producto,tipo,cantidad,descripcion,fecha) VALUES
(34,'ENTRADA',100,'Apertura de inventario inicial - Espresso','2026-09-28 07:00:00'),
(35,'ENTRADA',100,'Apertura de inventario inicial - Chocolate Caliente','2026-09-28 07:00:00'),
(36,'ENTRADA',80,'Apertura de inventario inicial - Crepe Salado','2026-09-28 07:00:00'),
(37,'ENTRADA',80,'Apertura de inventario inicial - Waffles','2026-09-28 07:00:00'),
(38,'ENTRADA',60,'Apertura de inventario inicial - Capricho de Avellanas','2026-09-28 07:00:00'),
(39,'ENTRADA',60,'Apertura de inventario inicial - Arabigo','2026-09-28 07:00:00'),
(40,'ENTRADA',60,'Apertura de inventario inicial - Affogato','2026-09-28 07:00:00'),
(41,'ENTRADA',60,'Apertura de inventario inicial - Carajillo','2026-09-28 07:00:00'),
(42,'ENTRADA',80,'Apertura de inventario inicial - Limonada Natural','2026-09-28 07:00:00'),
(43,'ENTRADA',80,'Apertura de inventario inicial - Limonada de Cereza','2026-09-28 07:00:00'),
(44,'ENTRADA',80,'Apertura de inventario inicial - Soda Italiana','2026-09-28 07:00:00'),
(45,'ENTRADA',100,'Apertura de inventario inicial - Milo Caliente','2026-09-28 07:00:00'),
(46,'ENTRADA',120,'Apertura de inventario inicial - Tinto','2026-09-28 07:00:00'),
(47,'ENTRADA',100,'Apertura de inventario inicial - Aromatica Sencilla','2026-09-28 07:00:00'),
(31,'ENTRADA',80,'Apertura de inventario inicial - Kumis','2026-09-28 07:00:00'),
(32,'ENTRADA',80,'Apertura de inventario inicial - Cereal','2026-09-28 07:00:00'),
(34,'ENTRADA',50,'Reposicion semanal - Espresso','2026-10-05 07:30:00'),
(36,'ENTRADA',30,'Reposicion semanal - Crepe Salado','2026-10-05 07:30:00'),
(38,'ENTRADA',20,'Reposicion especiales - Capricho de Avellanas','2026-10-07 08:00:00'),
(46,'ENTRADA',50,'Reposicion diaria - Tinto','2026-10-10 07:00:00'),
(34,'SALIDA',5,'Muestra cortesia clientes','2026-10-08 14:00:00'),
(37,'SALIDA',2,'Merma - Waffles quemados','2026-10-06 11:00:00'),
(36,'SALIDA',1,'Merma - Crepe dañado','2026-10-07 10:30:00');

-- ══════════════════════════════════════════
-- VERIFICACION FINAL
-- ══════════════════════════════════════════
SELECT 'CLIENTES' AS Tabla, COUNT(*) AS Total FROM clientes
UNION ALL SELECT 'FACTURAS', COUNT(*) FROM facturas
UNION ALL SELECT 'DETALLE_FACTURA', COUNT(*) FROM detalle_factura
UNION ALL SELECT 'MOVIMIENTOS', COUNT(*) FROM movimientos_inventario
UNION ALL SELECT 'VENTAS HOY ($)', COALESCE(SUM(total),0) FROM facturas WHERE DATE(fecha)=CURDATE();
