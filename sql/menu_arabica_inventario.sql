-- ============================================================
-- MENÚ "LA ARÁBICA" BRUNCH — Carga de Categorías y Productos
-- ============================================================
SET NAMES utf8mb4;
USE dolce_stock;

-- CATEGORÍAS
INSERT IGNORE INTO categorias (nombre) VALUES
  ('Cereal y Frutas'),('Crepes y Waffles'),('Desayunos'),
  ('Sandwiches y Horneados'),('Cafes'),('Otras Bebidas'),
  ('Especiales'),('Limonadas'),('Aromaticas y Sodas'),('Adiciones');

-- Cereal y Frutas
INSERT INTO productos (nombre,precio,stock,id_categorias) SELECT n,p,s,c.id_categoria FROM (SELECT 'Kumis' n,5000.00 p,30 s UNION ALL SELECT 'Cereal',5000.00,30 UNION ALL SELECT 'Fruta',5000.00,30) x JOIN categorias c ON c.nombre='Cereal y Frutas';

-- Crepes y Waffles
INSERT INTO productos (nombre,precio,stock,id_categorias) SELECT n,p,s,c.id_categoria FROM (SELECT 'Crepe Jamon y Pollo en Salsa de Champinones' n,19000.00 p,20 s UNION ALL SELECT 'Crepe Dulce Frutas Chantilli y Salsas',18000.00,20 UNION ALL SELECT 'Waffles',18000.00,20) x JOIN categorias c ON c.nombre='Crepes y Waffles';

-- Desayunos
INSERT INTO productos (nombre,precio,stock,id_categorias) SELECT n,p,s,c.id_categoria FROM (SELECT 'Tostadas Francesas' n,18000.00 p,20 s UNION ALL SELECT 'Pancakes',18000.00,20 UNION ALL SELECT 'Omelette',18000.00,20 UNION ALL SELECT 'Desayuno Americano',18000.00,20 UNION ALL SELECT 'Zumos de Naranja y Mandarina',5000.00,25 UNION ALL SELECT 'Sangria',8000.00,20 UNION ALL SELECT 'Migao',14300.00,15) x JOIN categorias c ON c.nombre='Desayunos';

-- Sandwiches y Horneados
INSERT INTO productos (nombre,precio,stock,id_categorias) SELECT n,p,s,c.id_categoria FROM (SELECT 'Sandwich Horneado Jamon Queso Lechuga y Tomate' n,8000.00 p,25 s UNION ALL SELECT 'Pan Fresco',4100.00,30) x JOIN categorias c ON c.nombre='Sandwiches y Horneados';

-- Cafes
INSERT INTO productos (nombre,precio,stock,id_categorias) SELECT n,p,s,c.id_categoria FROM (SELECT 'Espresso' n,8300.00 p,50 s UNION ALL SELECT 'Americano',5900.00,50 UNION ALL SELECT 'Tinto',3000.00,50 UNION ALL SELECT 'Cafe con Leche',4000.00,50 UNION ALL SELECT 'Latte',3600.00,50 UNION ALL SELECT 'Capuccino',5200.00,50 UNION ALL SELECT 'Mocaccino',8000.00,50 UNION ALL SELECT 'Capuccino Frio',8000.00,50 UNION ALL SELECT 'Mocaccino Frio',8500.00,50 UNION ALL SELECT 'Granizado',5500.00,50) x JOIN categorias c ON c.nombre='Cafes';

-- Otras Bebidas
INSERT INTO productos (nombre,precio,stock,id_categorias) SELECT n,p,s,c.id_categoria FROM (SELECT 'Chocolate Caliente' n,5200.00 p,40 s UNION ALL SELECT 'Milo Caliente',5200.00,40 UNION ALL SELECT 'Milo Frio',6500.00,40 UNION ALL SELECT 'Jugo Natural',6500.00,40 UNION ALL SELECT 'Frappe',6500.00,40 UNION ALL SELECT 'Malteada',8000.00,40) x JOIN categorias c ON c.nombre='Otras Bebidas';

-- Especiales
INSERT INTO productos (nombre,precio,stock,id_categorias) SELECT n,p,s,c.id_categoria FROM (SELECT 'Capricho de Avellanas' n,14000.00 p,20 s UNION ALL SELECT 'Arabigo',14000.00,20 UNION ALL SELECT 'Irlandes',14000.00,20 UNION ALL SELECT 'Carajillo',9000.00,20 UNION ALL SELECT 'Affogato',13000.00,20) x JOIN categorias c ON c.nombre='Especiales';

-- Limonadas
INSERT INTO productos (nombre,precio,stock,id_categorias) SELECT n,p,s,c.id_categoria FROM (SELECT 'Limonada Natural' n,6300.00 p,30 s UNION ALL SELECT 'Limonada de Cereza',10000.00,30 UNION ALL SELECT 'Limonada de Coco',10000.00,30 UNION ALL SELECT 'Limonada de Vino',10000.00,30) x JOIN categorias c ON c.nombre='Limonadas';

-- Aromaticas y Sodas
INSERT INTO productos (nombre,precio,stock,id_categorias) SELECT n,p,s,c.id_categoria FROM (SELECT 'Aromatica Sencilla' n,2600.00 p,40 s UNION ALL SELECT 'Aromatica Especial',3000.00,40 UNION ALL SELECT 'Aromatica Premium',9000.00,40 UNION ALL SELECT 'Soda Italiana',9000.00,30 UNION ALL SELECT 'Soda Michelada',7000.00,30) x JOIN categorias c ON c.nombre='Aromaticas y Sodas';

-- Adiciones
INSERT INTO productos (nombre,precio,stock,id_categorias) SELECT n,p,s,c.id_categoria FROM (SELECT 'Adicion Amaretto' n,7100.00 p,50 s UNION ALL SELECT 'Adicion Whisky',7000.00,50 UNION ALL SELECT 'Adicion Crema de Whisky',7000.00,50 UNION ALL SELECT 'Adicion Nutella',5500.00,50 UNION ALL SELECT 'Adicion Arequipe',5500.00,50 UNION ALL SELECT 'Adicion Chocolate',5500.00,50 UNION ALL SELECT 'Adicion Chantilly',5000.00,50 UNION ALL SELECT 'Adicion Helado',7500.00,50 UNION ALL SELECT 'Adicion Ron',5500.00,50) x JOIN categorias c ON c.nombre='Adiciones';

-- RESUMEN FINAL
SELECT c.nombre AS Categoria, COUNT(p.id_producto) AS Productos FROM categorias c LEFT JOIN productos p ON p.id_categorias=c.id_categoria GROUP BY c.id_categoria,c.nombre ORDER BY c.id_categoria;
