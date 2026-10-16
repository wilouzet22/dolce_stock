-- ============================================================
--  DOLCE STOCK — SEED COMPLETO DE DATOS DE PRUEBA
-- ============================================================
USE dolce_stock;
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE detalle_factura;
TRUNCATE TABLE facturas;
TRUNCATE TABLE movimientos_inventario;
TRUNCATE TABLE productos;
TRUNCATE TABLE categorias;
TRUNCATE TABLE clientes;
TRUNCATE TABLE empleados;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. EMPLEADOS (contraseña: Dolce2024!)
INSERT INTO empleados (primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, nombre, cargo, salario, email, password_hash, rol) VALUES
('Valentina', 'Sofia',     'Mora',          'Herrera', 'Valentina Mora',     'Administradora', 2800000.00, 'valentina@dolcecafe.com',  '$2y$10$dYRlyF.pP9UfTGrE.KorPeZxhuWxsy4iDoyvMvsrL/0QJO5SDCux6', 'admin'),
('Carlos',    'Andres',    'Restrepo',      'Cano',    'Carlos Restrepo',    'Cajero',         1600000.00, 'carlos@dolcecafe.com',     '$2y$10$dYRlyF.pP9UfTGrE.KorPeZxhuWxsy4iDoyvMvsrL/0QJO5SDCux6', 'cajero'),
('Mariana',   NULL,        'Perea',         'Zapata',  'Mariana Perea',      'Cajera',         1600000.00, 'mariana@dolcecafe.com',    '$2y$10$dYRlyF.pP9UfTGrE.KorPeZxhuWxsy4iDoyvMvsrL/0QJO5SDCux6', 'cajero'),
('Felipe',    'Alejandro', 'Saldarriaga',   'Rios',    'Felipe Saldarriaga', 'Cajero',         1600000.00, 'felipe@dolcecafe.com',     '$2y$10$dYRlyF.pP9UfTGrE.KorPeZxhuWxsy4iDoyvMvsrL/0QJO5SDCux6', 'cajero'),
('Laura',     'Isabela',   'Jimenez',       'Castro',  'Laura Jimenez',      'Administradora', 2800000.00, 'laura@dolcecafe.com',      '$2y$10$dYRlyF.pP9UfTGrE.KorPeZxhuWxsy4iDoyvMvsrL/0QJO5SDCux6', 'admin');

-- 2. CATEGORIAS
INSERT INTO categorias (nombre) VALUES
('Cereal y Frutas'),('Crepes y Waffles'),('Desayunos'),
('Sandwiches y Horneados'),('Cafes'),('Otras Bebidas'),
('Especiales'),('Limonadas'),('Aromaticas y Sodas'),('Adiciones');

-- 3. PRODUCTOS
INSERT INTO productos (nombre, precio, stock, id_categorias) SELECT n,p,s,c.id_categoria FROM (SELECT 'Kumis' n,5000.00 p,30 s UNION ALL SELECT 'Cereal',5000.00,30 UNION ALL SELECT 'Fruta',5000.00,30) x JOIN categorias c ON c.nombre='Cereal y Frutas';
INSERT INTO productos (nombre, precio, stock, id_categorias) SELECT n,p,s,c.id_categoria FROM (SELECT 'Crepe Jamon y Pollo en Salsa de Champinones' n,19000.00 p,20 s UNION ALL SELECT 'Crepe Dulce Frutas Chantilli y Salsas',18000.00,20 UNION ALL SELECT 'Waffles',18000.00,20) x JOIN categorias c ON c.nombre='Crepes y Waffles';
INSERT INTO productos (nombre, precio, stock, id_categorias) SELECT n,p,s,c.id_categoria FROM (SELECT 'Tostadas Francesas' n,18000.00 p,20 s UNION ALL SELECT 'Pancakes',18000.00,20 UNION ALL SELECT 'Omelette',18000.00,20 UNION ALL SELECT 'Desayuno Americano',18000.00,20 UNION ALL SELECT 'Zumos de Naranja',5000.00,25 UNION ALL SELECT 'Sangria',8000.00,20 UNION ALL SELECT 'Migao',14300.00,15) x JOIN categorias c ON c.nombre='Desayunos';
INSERT INTO productos (nombre, precio, stock, id_categorias) SELECT n,p,s,c.id_categoria FROM (SELECT 'Sandwich Horneado Jamon Queso Lechuga y Tomate' n,8000.00 p,25 s UNION ALL SELECT 'Pan Fresco',4100.00,30) x JOIN categorias c ON c.nombre='Sandwiches y Horneados';
INSERT INTO productos (nombre, precio, stock, id_categorias) SELECT n,p,s,c.id_categoria FROM (SELECT 'Espresso' n,8300.00 p,80 s UNION ALL SELECT 'Americano',5900.00,60 UNION ALL SELECT 'Tinto',3000.00,100 UNION ALL SELECT 'Cafe con Leche',4000.00,60 UNION ALL SELECT 'Latte',3600.00,70 UNION ALL SELECT 'Capuccino',5200.00,60 UNION ALL SELECT 'Mocaccino',8000.00,50 UNION ALL SELECT 'Capuccino Frio',8000.00,40 UNION ALL SELECT 'Mocaccino Frio',8500.00,40 UNION ALL SELECT 'Granizado',5500.00,50) x JOIN categorias c ON c.nombre='Cafes';
INSERT INTO productos (nombre, precio, stock, id_categorias) SELECT n,p,s,c.id_categoria FROM (SELECT 'Chocolate Caliente' n,5200.00 p,50 s UNION ALL SELECT 'Milo Caliente',5200.00,40 UNION ALL SELECT 'Milo Frio',6500.00,35 UNION ALL SELECT 'Jugo Natural',6500.00,40 UNION ALL SELECT 'Frappe',6500.00,35 UNION ALL SELECT 'Malteada',8000.00,30) x JOIN categorias c ON c.nombre='Otras Bebidas';
INSERT INTO productos (nombre, precio, stock, id_categorias) SELECT n,p,s,c.id_categoria FROM (SELECT 'Capricho de Avellanas' n,14000.00 p,25 s UNION ALL SELECT 'Arabigo',14000.00,20 UNION ALL SELECT 'Irlandes',14000.00,20 UNION ALL SELECT 'Carajillo',9000.00,25 UNION ALL SELECT 'Affogato',13000.00,20) x JOIN categorias c ON c.nombre='Especiales';
INSERT INTO productos (nombre, precio, stock, id_categorias) SELECT n,p,s,c.id_categoria FROM (SELECT 'Limonada Natural' n,6300.00 p,35 s UNION ALL SELECT 'Limonada de Cereza',10000.00,30 UNION ALL SELECT 'Limonada de Coco',10000.00,25 UNION ALL SELECT 'Limonada de Vino',10000.00,25) x JOIN categorias c ON c.nombre='Limonadas';
INSERT INTO productos (nombre, precio, stock, id_categorias) SELECT n,p,s,c.id_categoria FROM (SELECT 'Aromatica Sencilla' n,2600.00 p,60 s UNION ALL SELECT 'Aromatica Especial',3000.00,50 UNION ALL SELECT 'Aromatica Premium',9000.00,30 UNION ALL SELECT 'Soda Italiana',9000.00,30 UNION ALL SELECT 'Soda Michelada',7000.00,25) x JOIN categorias c ON c.nombre='Aromaticas y Sodas';
INSERT INTO productos (nombre, precio, stock, id_categorias) SELECT n,p,s,c.id_categoria FROM (SELECT 'Adicion Amaretto' n,7100.00 p,50 s UNION ALL SELECT 'Adicion Whisky',7000.00,50 UNION ALL SELECT 'Adicion Crema de Whisky',7000.00,50 UNION ALL SELECT 'Adicion Nutella',5500.00,60 UNION ALL SELECT 'Adicion Arequipe',5500.00,60 UNION ALL SELECT 'Adicion Chocolate',5500.00,60 UNION ALL SELECT 'Adicion Chantilly',5000.00,60 UNION ALL SELECT 'Adicion Helado',7500.00,50 UNION ALL SELECT 'Adicion Ron',5500.00,50) x JOIN categorias c ON c.nombre='Adiciones';

-- 4. CLIENTES
INSERT INTO clientes (identificacion, nombre, apellidos, email, direccion) VALUES
('1045678901','Sofia','Ramirez Montoya','sofia.ramirez@gmail.com','Cra 45 #12-30 Laureles'),
('1067234510','Valentina','Gomez Rios','vgomez@hotmail.com','Cl 33 #76-10 El Poblado'),
('1023456789','Camila','Torres Herrera','camila.torres@outlook.com','Av El Ferrocarril #38-20'),
('900123456','Cafe Delicias SAS','','contacto@cafedelicias.co','Cl 80 #52-15 Robledo'),
('1052341100','Andres','Marin Ospina','andres.marin@gmail.com','Cra 70 #41-05 Belen'),
('1044789012','Mariana','Perea Cano','mariana.perea@gmail.com','Cl 10 #43D-12 El Centro'),
('1038456712','Juan','Cardona Silva','jcardona@gmail.com','Cra 80 #34-67 Castilla'),
('71234567','Carlos','Perez Lopera','carlos.perez@empresarial.co','Cl 19 #45-23 La America'),
('1060987654','Isabella','Vargas Duque','isabella.v@gmail.com','Cra 65 #50A-10 Laureles'),
('43876543','Maria','Restrepo Calle','mrestrepo@outlook.com','Cl 44 #78-90 Florida Nueva'),
('1029876543','Santiago','Gutierrez Mora','sgutierrez@gmail.com','Av 33 #65-11 Manrique'),
('900234567','Pasteleria La Dulce Vida','','admin@ladulcevida.co','Cl 70 #45-20 Aranjuez'),
('1071234561','Laura','Jimenez Castro','laura.jimenez@gmail.com','Cra 48 #22-33 Boston'),
('1080456123','Felipe','Saldarriaga Rios','felipe.s@hotmail.com','Cl 77 #80-54 Robledo'),
('1066123456','Alejandra','Zapata Naranjo','ale.zapata@gmail.com','Cra 43A #1-50 El Poblado');

-- 5. IDs dinamicos de productos
SET @esp    = (SELECT id_producto FROM productos WHERE nombre='Espresso' LIMIT 1);
SET @choc   = (SELECT id_producto FROM productos WHERE nombre='Chocolate Caliente' LIMIT 1);
SET @cap    = (SELECT id_producto FROM productos WHERE nombre='Capuccino' LIMIT 1);
SET @latte  = (SELECT id_producto FROM productos WHERE nombre='Latte' LIMIT 1);
SET @tinto  = (SELECT id_producto FROM productos WHERE nombre='Tinto' LIMIT 1);
SET @crepe  = (SELECT id_producto FROM productos WHERE nombre='Crepe Jamon y Pollo en Salsa de Champinones' LIMIT 1);
SET @waffle = (SELECT id_producto FROM productos WHERE nombre='Waffles' LIMIT 1);
SET @capav  = (SELECT id_producto FROM productos WHERE nombre='Capricho de Avellanas' LIMIT 1);
SET @arab   = (SELECT id_producto FROM productos WHERE nombre='Arabigo' LIMIT 1);
SET @affog  = (SELECT id_producto FROM productos WHERE nombre='Affogato' LIMIT 1);
SET @caraj  = (SELECT id_producto FROM productos WHERE nombre='Carajillo' LIMIT 1);
SET @lim    = (SELECT id_producto FROM productos WHERE nombre='Limonada Natural' LIMIT 1);
SET @gran   = (SELECT id_producto FROM productos WHERE nombre='Granizado' LIMIT 1);
SET @milo   = (SELECT id_producto FROM productos WHERE nombre='Milo Caliente' LIMIT 1);
SET @arom   = (SELECT id_producto FROM productos WHERE nombre='Aromatica Sencilla' LIMIT 1);
SET @kumis  = (SELECT id_producto FROM productos WHERE nombre='Kumis' LIMIT 1);
SET @frapp  = (SELECT id_producto FROM productos WHERE nombre='Frappe' LIMIT 1);
SET @soda   = (SELECT id_producto FROM productos WHERE nombre='Soda Italiana' LIMIT 1);

-- 6. FACTURAS + DETALLES
INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-01 08:32:00',1,1,22100.00); SET @f=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES (@f,@esp,1,8300.00,8300.00),(@f,@choc,1,5200.00,5200.00),(@f,@tinto,2,3000.00,6000.00),(@f,@arom,1,2600.00,2600.00);

INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-01 09:15:00',2,2,49600.00); SET @f=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES (@f,@capav,2,14000.00,28000.00),(@f,@caraj,1,9000.00,9000.00),(@f,@latte,1,3600.00,3600.00),(@f,@soda,1,9000.00,9000.00);

INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-01 10:00:00',3,1,47900.00); SET @f=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES (@f,@crepe,1,19000.00,19000.00),(@f,@waffle,1,18000.00,18000.00),(@f,@esp,1,8300.00,8300.00),(@f,@arom,1,2600.00,2600.00);

INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-02 08:45:00',4,3,60600.00); SET @f=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES (@f,@capav,1,14000.00,14000.00),(@f,@arab,1,14000.00,14000.00),(@f,@affog,1,13000.00,13000.00),(@f,@esp,2,8300.00,16600.00),(@f,@tinto,1,3000.00,3000.00);

INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-02 11:30:00',5,2,24500.00); SET @f=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES (@f,@gran,2,5500.00,11000.00),(@f,@esp,1,8300.00,8300.00),(@f,@milo,1,5200.00,5200.00);

INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-03 09:00:00',6,1,45200.00); SET @f=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES (@f,@crepe,1,19000.00,19000.00),(@f,@waffle,1,18000.00,18000.00),(@f,@cap,1,5200.00,5200.00),(@f,@tinto,1,3000.00,3000.00);

INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-03 14:20:00',7,3,32900.00); SET @f=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES (@f,@affog,1,13000.00,13000.00),(@f,@caraj,1,9000.00,9000.00),(@f,@esp,1,8300.00,8300.00),(@f,@arom,1,2600.00,2600.00);

INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-04 08:10:00',8,2,22900.00); SET @f=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES (@f,@latte,2,3600.00,7200.00),(@f,@gran,1,5500.00,5500.00),(@f,@cap,1,5200.00,5200.00),(@f,@kumis,1,5000.00,5000.00);

INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-04 10:45:00',9,1,43600.00); SET @f=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES (@f,@capav,1,14000.00,14000.00),(@f,@arab,1,14000.00,14000.00),(@f,@lim,2,6300.00,12600.00),(@f,@tinto,1,3000.00,3000.00);

INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-05 09:30:00',10,3,31200.00); SET @f=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES (@f,@crepe,1,19000.00,19000.00),(@f,@latte,2,3600.00,7200.00),(@f,@kumis,1,5000.00,5000.00);

INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-06 08:00:00',11,1,55400.00); SET @f=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES (@f,@capav,1,14000.00,14000.00),(@f,@affog,1,13000.00,13000.00),(@f,@waffle,1,18000.00,18000.00),(@f,@choc,2,5200.00,10400.00);

INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-06 11:00:00',12,2,36100.00); SET @f=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES (@f,@esp,2,8300.00,16600.00),(@f,@caraj,1,9000.00,9000.00),(@f,@gran,1,5500.00,5500.00),(@f,@kumis,1,5000.00,5000.00);

INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-07 09:10:00',13,1,21600.00); SET @f=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES (@f,@crepe,1,19000.00,19000.00),(@f,@arom,1,2600.00,2600.00);

INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-07 13:00:00',14,3,50500.00); SET @f=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES (@f,@capav,2,14000.00,28000.00),(@f,@caraj,1,9000.00,9000.00),(@f,@esp,1,8300.00,8300.00),(@f,@milo,1,5200.00,5200.00);

INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-08 08:30:00',15,2,15000.00); SET @f=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES (@f,@cap,1,5200.00,5200.00),(@f,@latte,2,3600.00,7200.00),(@f,@arom,1,2600.00,2600.00);

INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-08 10:15:00',1,1,65200.00); SET @f=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES (@f,@capav,3,14000.00,42000.00),(@f,@affog,1,13000.00,13000.00),(@f,@cap,1,5200.00,5200.00),(@f,@kumis,1,5000.00,5000.00);

INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-09 09:00:00',3,3,35800.00); SET @f=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES (@f,@waffle,1,18000.00,18000.00),(@f,@esp,1,8300.00,8300.00),(@f,@frapp,1,6500.00,6500.00),(@f,@tinto,1,3000.00,3000.00);

INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-09 12:45:00',5,2,22000.00); SET @f=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES (@f,@crepe,1,19000.00,19000.00),(@f,@tinto,1,3000.00,3000.00);

INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-10 08:20:00',7,1,41800.00); SET @f=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES (@f,@capav,1,14000.00,14000.00),(@f,@arab,1,14000.00,14000.00),(@f,@esp,1,8300.00,8300.00),(@f,@gran,1,5500.00,5500.00);

INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES ('2026-10-10 15:30:00',9,3,32000.00); SET @f=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES (@f,@crepe,1,19000.00,19000.00),(@f,@cap,2,5200.00,10400.00),(@f,@arom,1,2600.00,2600.00);

-- Facturas de HOY
INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES (NOW(),2,1,29300.00); SET @f=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES (@f,@esp,1,8300.00,8300.00),(@f,@waffle,1,18000.00,18000.00),(@f,@tinto,1,3000.00,3000.00);

INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES (NOW(),4,2,51200.00); SET @f=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES (@f,@capav,2,14000.00,28000.00),(@f,@affog,1,13000.00,13000.00),(@f,@cap,1,5200.00,5200.00),(@f,@kumis,1,5000.00,5000.00);

INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES (NOW(),6,3,35400.00); SET @f=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES (@f,@crepe,1,19000.00,19000.00),(@f,@esp,1,8300.00,8300.00),(@f,@gran,1,5500.00,5500.00),(@f,@arom,1,2600.00,2600.00);

INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES (NOW(),8,1,14000.00); SET @f=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES (@f,@arab,1,14000.00,14000.00);

INSERT INTO facturas (fecha,id_cliente,id_empleado,total) VALUES (NOW(),10,2,22500.00); SET @f=LAST_INSERT_ID();
INSERT INTO detalle_factura (id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES (@f,@esp,1,8300.00,8300.00),(@f,@caraj,1,9000.00,9000.00),(@f,@cap,1,5200.00,5200.00);

-- 7. MOVIMIENTOS DE INVENTARIO
INSERT INTO movimientos_inventario (id_producto,tipo,cantidad,descripcion,fecha) VALUES
(@esp,'ENTRADA',100,'Apertura inventario inicial - Espresso','2026-09-28 07:00:00'),
(@choc,'ENTRADA',100,'Apertura inventario inicial - Chocolate Caliente','2026-09-28 07:00:00'),
(@crepe,'ENTRADA',80,'Apertura inventario inicial - Crepe Salado','2026-09-28 07:00:00'),
(@waffle,'ENTRADA',80,'Apertura inventario inicial - Waffles','2026-09-28 07:00:00'),
(@capav,'ENTRADA',60,'Apertura inventario inicial - Capricho Avellanas','2026-09-28 07:00:00'),
(@arab,'ENTRADA',60,'Apertura inventario inicial - Arabigo','2026-09-28 07:00:00'),
(@affog,'ENTRADA',60,'Apertura inventario inicial - Affogato','2026-09-28 07:00:00'),
(@caraj,'ENTRADA',60,'Apertura inventario inicial - Carajillo','2026-09-28 07:00:00'),
(@lim,'ENTRADA',80,'Apertura inventario inicial - Limonada Natural','2026-09-28 07:00:00'),
(@gran,'ENTRADA',80,'Apertura inventario inicial - Granizado','2026-09-28 07:00:00'),
(@milo,'ENTRADA',100,'Apertura inventario inicial - Milo Caliente','2026-09-28 07:00:00'),
(@tinto,'ENTRADA',120,'Apertura inventario inicial - Tinto','2026-09-28 07:00:00'),
(@arom,'ENTRADA',100,'Apertura inventario inicial - Aromatica Sencilla','2026-09-28 07:00:00'),
(@kumis,'ENTRADA',80,'Apertura inventario inicial - Kumis','2026-09-28 07:00:00'),
(@cap,'ENTRADA',80,'Apertura inventario inicial - Capuccino','2026-09-28 07:00:00'),
(@esp,'ENTRADA',50,'Reposicion semanal - Espresso','2026-10-05 07:30:00'),
(@crepe,'ENTRADA',30,'Reposicion semanal - Crepe Salado','2026-10-05 07:30:00'),
(@capav,'ENTRADA',20,'Reposicion especiales - Capricho de Avellanas','2026-10-07 08:00:00'),
(@tinto,'ENTRADA',50,'Reposicion diaria - Tinto','2026-10-10 07:00:00'),
(@latte,'ENTRADA',40,'Reposicion semanal - Latte','2026-10-07 07:30:00'),
(@esp,'SALIDA',5,'Muestra cortesia clientes VIP','2026-10-08 14:00:00'),
(@waffle,'SALIDA',2,'Merma - Waffles quemados','2026-10-06 11:00:00'),
(@crepe,'SALIDA',1,'Merma - Crepe danado','2026-10-07 10:30:00'),
(@arab,'SALIDA',1,'Prueba de calidad','2026-10-09 09:00:00');

-- VERIFICACION FINAL
SELECT 'EMPLEADOS' AS Tabla,COUNT(*) AS Total FROM empleados
UNION ALL SELECT 'CATEGORIAS',COUNT(*) FROM categorias
UNION ALL SELECT 'PRODUCTOS',COUNT(*) FROM productos
UNION ALL SELECT 'CLIENTES',COUNT(*) FROM clientes
UNION ALL SELECT 'FACTURAS',COUNT(*) FROM facturas
UNION ALL SELECT 'DETALLE_FACTURA',COUNT(*) FROM detalle_factura
UNION ALL SELECT 'MOVIMIENTOS',COUNT(*) FROM movimientos_inventario
UNION ALL SELECT 'VENTAS HOY',COALESCE(SUM(total),0) FROM facturas WHERE DATE(fecha)=CURDATE();
