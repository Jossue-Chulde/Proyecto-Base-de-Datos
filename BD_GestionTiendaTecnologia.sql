create database GestionTiendaTecnologia;
use GestionTiendaTecnologia;

-- Tabla Clientes
CREATE TABLE Clientes (
    id_cliente INT IDENTITY(1,1) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    cedula VARCHAR(20) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,

    CONSTRAINT UQ_Clientes_Cedula UNIQUE (cedula),
    CONSTRAINT UQ_Clientes_Correo UNIQUE (correo)
);

-- Tabla Usuario
CREATE TABLE Usuario (
    id_usuario INT IDENTITY(1,1) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    rol VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL,
    contraseña VARCHAR(255) NOT NULL,

    CONSTRAINT UQ_Usuario_Username UNIQUE (username)
);

-- Tabla Ventas
CREATE TABLE Ventas (
    id_venta INT IDENTITY(1,1) PRIMARY KEY,
    fecha DATETIME NOT NULL DEFAULT GETDATE(),
    total DECIMAL(10,2) NOT NULL,
    metodo_de_pago VARCHAR(50) NOT NULL,
    id_cliente INT NOT NULL,
    id_usuario INT NOT NULL,

    CONSTRAINT CK_Ventas_Total CHECK (total >= 0),

    CONSTRAINT FK_Ventas_Cliente FOREIGN KEY (id_cliente)
    REFERENCES Clientes(id_cliente),

    CONSTRAINT FK_Ventas_Usuario FOREIGN KEY (id_usuario)
    REFERENCES Usuario(id_usuario)
);

-- Tabla Auditoria
CREATE TABLE Auditoria (
    id_auditoria INT IDENTITY(1,1) PRIMARY KEY,
    fecha DATETIME NOT NULL DEFAULT GETDATE(),
    tabla_afectada VARCHAR(50) NOT NULL,
    accion VARCHAR(10) NOT NULL,
    -- id_usuario INT NOT NULL, -- ELIMINADO
    usuario_responsable VARCHAR(100) NOT NULL,

    CONSTRAINT CK_Auditoria_Accion 
    CHECK (accion IN ('INSERT', 'UPDATE', 'DELETE'))
);

-- Tabla Marca
CREATE TABLE Marca (
    id_marca INT IDENTITY(1,1) PRIMARY KEY,
    nombre_marca VARCHAR(100) NOT NULL,

    CONSTRAINT UQ_Marca_Nombre UNIQUE (nombre_marca)
);


-- Tabla Producto
CREATE TABLE Producto (
    id_producto INT IDENTITY(1,1) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    id_marca INT NOT NULL,

    CONSTRAINT CK_Producto_Precio CHECK (precio > 0),
    CONSTRAINT FK_Producto_Marca FOREIGN KEY (id_marca)
    REFERENCES Marca(id_marca)
);

-- Tabla Detalle Venta
CREATE TABLE Detalle_venta (
    id_detalle_venta INT IDENTITY(1,1) PRIMARY KEY,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    id_venta INT NOT NULL,
    id_producto INT NOT NULL,

    CONSTRAINT CK_Detalle_Cantidad CHECK (cantidad > 0),
    CONSTRAINT CK_Detalle_Precio CHECK (precio_unitario > 0),
    CONSTRAINT CK_Detalle_Subtotal CHECK (subtotal >= 0),

    CONSTRAINT FK_Detalle_Venta FOREIGN KEY (id_venta)
    REFERENCES Ventas(id_venta) ON DELETE CASCADE,

    CONSTRAINT FK_Detalle_Producto FOREIGN KEY (id_producto)
    REFERENCES Producto(id_producto)
);

-- Tabla Inventario
CREATE TABLE Inventario (
    id_inventario INT IDENTITY(1,1) PRIMARY KEY,
    stock_total INT NOT NULL,
    id_producto INT NOT NULL,

    CONSTRAINT CK_Inventario_Stock CHECK (stock_total >= 0),
    CONSTRAINT UQ_Inventario_Producto UNIQUE (id_producto),
    CONSTRAINT FK_Inventario_Producto FOREIGN KEY (id_producto)
    REFERENCES Producto(id_producto)
);

-----------------------------------------------------------------------
-- Datos de Prueba
INSERT INTO Clientes (nombre, cedula, correo, telefono) VALUES
    ('Juan Pérez', '1754896328', 'juan.perez@email.com', '0985564487'),
    ('María López', '1788741447', 'maria.lopez@email.com', '0988574583'),
    ('Carlos Ramírez', '1796368574', 'carlos.ramirez@email.com', '0963638515');

INSERT INTO Usuario (nombre, rol, username, contraseña) VALUES
    ('Ana Torres', 'Administrador', 'ana.torres', 'ana123'),
    ('Luis Gómez', 'Vendedor', 'luis.gomez', 'luisd123'),
    ('Sofía Martínez', 'Vendedor', 'sofia.martinez', 'sofia123');

INSERT INTO Ventas (fecha, total, metodo_de_pago, id_cliente, id_usuario) VALUES
    ('2026-01-20', 320.00, 'Tarjeta', 1, 1),
    ('2026-01-20', 730.00, 'Efectivo', 2, 2),
    ('2026-01-20', 280.00, 'Transferencia', 3, 3);

INSERT INTO Auditoria (fecha, tabla_afectada, accion, usuario_responsable) VALUES
    (GETDATE(), 'Clientes', 'INSERT', 'Ana Torres'),
    (GETDATE(), 'Ventas', 'INSERT', 'Luis Gómez'),
    (GETDATE(), 'Producto', 'INSERT', 'Sofía Martínez');

INSERT INTO Marca (nombre_marca) VALUES
    ('Intel'), ('AMD'), ('NVIDIA');

INSERT INTO Producto (nombre, precio, id_marca) VALUES
    ('Procesador Intel i7', 320.00, 1),
    ('Tarjeta Gráfica NVIDIA RTX 3060', 450.00, 3),
    ('Procesador AMD Ryzen 5', 280.00, 2);

INSERT INTO Detalle_venta (cantidad, precio_unitario, subtotal, id_venta, id_producto) VALUES
    (1, 320.00, 320.00, 1, 1),
    (1, 450.00, 450.00, 2, 2),
    (1, 280.00, 280.00, 3, 3),
    (1, 280.00, 280.00, 2, 3);


INSERT INTO Inventario (stock_total, id_producto) VALUES
    (50, 1), (30, 2), (40, 3);

-- Mostrar información de las tablas
select * from Clientes;
select * from Usuario;
select * from Ventas;
select * from Auditoria;
select * from Marca;
select * from Producto;
select * from Detalle_venta;
select * from Inventario;

-- Consultas y operaciones

-- Consultas simples
-- Listar todos los nombres de los productos y sus precios
SELECT nombre, precio FROM Producto;

-- Conculta con condiciones 
-- Buscar clientes con correos específicos que tengan un teléfono registrado
SELECT nombre, correo FROM Clientes 
WHERE correo LIKE '%@email.com' AND telefono IS NOT NULL;

-- Buscar productos de la marca con ID 1 que superen los $300 (Gama alta)
SELECT nombre, precio 
FROM Producto 
WHERE id_marca = 1 AND precio > 300;

-- Buscar ventas realizadas exclusivamente con el método de pago 'Efectivo'
SELECT id_venta, fecha, total 
FROM Ventas 
WHERE metodo_de_pago = 'Efectivo';

-- Uso de Joins
-- Reporte de ventas: Conecta Ventas, Detalle_venta, Producto y Clientes
SELECT V.id_venta, C.nombre AS Cliente, P.nombre AS Producto, DV.cantidad, DV.subtotal
FROM Ventas V
INNER JOIN Clientes C ON V.id_cliente = C.id_cliente
INNER JOIN Detalle_venta DV ON V.id_venta = DV.id_venta
INNER JOIN Producto P ON DV.id_producto = P.id_producto;

-- Inventario por Marca: Conecta Inventario, Producto y Marca
SELECT M.nombre_marca, P.nombre, I.stock_total
FROM Inventario I
INNER JOIN Producto P ON I.id_producto = P.id_producto
INNER JOIN Marca M ON P.id_marca = M.id_marca;

-- Registro de actividad: Conecta Ventas con el Vendedor
SELECT V.id_venta, U.nombre AS Vendedor, V.total
FROM Ventas V
INNER JOIN Usuario U ON V.id_usuario = U.id_usuario;

-- Muestra todos los productos y su ID de venta; si no aparece ID, el producto no se ha vendido
SELECT P.nombre, DV.id_venta
FROM Producto P
LEFT JOIN Detalle_venta DV ON P.id_producto = DV.id_producto;

-- Lista todos los clientes y sus fechas de compra, incluyendo los que aún no han comprado
SELECT C.nombre, V.fecha
FROM Clientes C
LEFT JOIN Ventas V ON C.id_cliente = V.id_cliente;

-- Muestra todas las marcas registradas y qué productos tienen asociados
SELECT M.nombre_marca, P.nombre AS Articulo
FROM Producto P
RIGHT JOIN Marca M ON P.id_marca = M.id_marca;
------------------------------------------------------------------------------------------
-- Funciones de agregacion
-- 1. Función para obtener estadísticas de ventas por período
SELECT 
    -- Funciones de agregación básicas
    COUNT(*) AS Total_Ventas,
    SUM(total) AS Ingreso_Total,
    AVG(total) AS Promedio_Venta,
    MIN(total) AS Venta_Minima,
    MAX(total) AS Venta_Maxima,
    -- Estadísticas adicionales
    STDEV(total) AS Desviacion_Estandar,
    VAR(total) AS Varianza
FROM Ventas
WHERE fecha BETWEEN '2026-01-01' AND '2026-01-31';

-- 2. Análisis de inventario por marca con múltiples agregaciones
SELECT 
    M.nombre_marca,
    COUNT(P.id_producto) AS Cantidad_Productos,
    SUM(I.stock_total) AS Stock_Total,
    AVG(P.precio) AS Precio_Promedio,
    SUM(P.precio * I.stock_total) AS Valor_Total_Inventario,
    MIN(P.precio) AS Producto_Mas_Barato,
    MAX(P.precio) AS Producto_Mas_Caro
FROM Marca M
JOIN Producto P ON M.id_marca = P.id_marca
JOIN Inventario I ON P.id_producto = I.id_producto
GROUP BY M.nombre_marca
HAVING SUM(I.stock_total) > 0
ORDER BY Valor_Total_Inventario DESC;

-- 3. Estadísticas de ventas por cliente
SELECT 
    C.nombre AS Cliente,
    COUNT(V.id_venta) AS Total_Compras,
    SUM(V.total) AS Total_Gastado,
    AVG(V.total) AS Promedio_por_Compra,
    -- Cálculo del valor del cliente (CLV)
    (SUM(V.total) / NULLIF(COUNT(V.id_venta), 0)) * COUNT(V.id_venta) AS CLV_Estimado,
    -- Análisis de frecuencia
    DATEDIFF(DAY, MIN(V.fecha), MAX(V.fecha)) / NULLIF(COUNT(V.id_venta), 1) AS Dias_entre_Compras
FROM Clientes C
LEFT JOIN Ventas V ON C.id_cliente = V.id_cliente
GROUP BY C.nombre, C.id_cliente
HAVING COUNT(V.id_venta) > 0
ORDER BY Total_Gastado DESC;

-- 4. Análisis de productos más vendidos
SELECT 
    P.nombre AS Producto,
    M.nombre_marca AS Marca,
    -- Estadísticas de ventas
    SUM(DV.cantidad) AS Total_Vendido,
    COUNT(DISTINCT DV.id_venta) AS Veces_Vendido,
    SUM(DV.subtotal) AS Ingreso_Generado,
    AVG(DV.precio_unitario) AS Precio_Promedio_Venta,
    -- Porcentaje del total
    ROUND(SUM(DV.subtotal) * 100.0 / (SELECT SUM(subtotal) FROM Detalle_venta), 2) AS Porcentaje_Ingresos
FROM Producto P
JOIN Marca M ON P.id_marca = M.id_marca
JOIN Detalle_venta DV ON P.id_producto = DV.id_producto
GROUP BY P.nombre, M.nombre_marca, P.id_producto
HAVING SUM(DV.cantidad) > 0
ORDER BY Total_Vendido DESC;

-- 5. Análisis de métodos de pago
SELECT 
    metodo_de_pago,
    COUNT(*) AS Cantidad_Ventas,
    SUM(total) AS Total_Recaudado,
    AVG(total) AS Ticket_Promedio,
    -- Análisis de distribución
    ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM Ventas), 2) AS Porcentaje_Uso,
    ROUND(SUM(total) * 100.0 / (SELECT SUM(total) FROM Ventas), 2) AS Porcentaje_Monto
FROM Ventas
GROUP BY metodo_de_pago
ORDER BY Total_Recaudado DESC;
-------------------------------------------------------------------------------------------------
-- Funciones de cadena
-- 1. Formateo de información de clientes
SELECT 
    id_cliente,
    -- Concatenación y formateo
    UPPER(nombre) AS Nombre_Mayusculas,
    LOWER(correo) AS Correo_Minusculas,
    -- Extracción de partes
    LEFT(nombre, CHARINDEX(' ', nombre + ' ') - 1) AS Primer_Nombre,
    RIGHT(nombre, CHARINDEX(' ', REVERSE(nombre) + ' ') - 1) AS Primer_Apellido,
    -- Manipulación de correo
    SUBSTRING(correo, 1, CHARINDEX('@', correo) - 1) AS Usuario_Correo,
    SUBSTRING(correo, CHARINDEX('@', correo) + 1, LEN(correo)) AS Dominio,
    -- Formateo de teléfono
    CASE 
        WHEN LEN(telefono) = 10 THEN '(' + SUBSTRING(telefono, 1, 3) + ') ' + 
                                   SUBSTRING(telefono, 4, 3) + '-' + 
                                   SUBSTRING(telefono, 7, 4)
        ELSE telefono
    END AS Telefono_Formateado,
    -- Validación de datos
    CASE 
        WHEN correo LIKE '%@%.%' THEN 'Correo Válido'
        ELSE 'Correo Inválido'
    END AS Validacion_Correo
FROM Clientes;

-- 2. Normalización y análisis de nombres de productos
SELECT 
    id_producto,
    nombre,
    -- Limpieza de texto
    LTRIM(RTRIM(nombre)) AS Nombre_Limpio,
    -- Búsqueda de palabras clave
    CASE 
        WHEN CHARINDEX('Intel', nombre) > 0 THEN 'Intel'
        WHEN CHARINDEX('AMD', nombre) > 0 THEN 'AMD' 
        WHEN CHARINDEX('NVIDIA', nombre) > 0 THEN 'NVIDIA'
        ELSE 'Otra Marca'
    END AS Marca_Detectada,
    -- Clasificación por tipo
    CASE
        WHEN nombre LIKE '%Procesador%' OR nombre LIKE '%CPU%' THEN 'Procesador'
        WHEN nombre LIKE '%Tarjeta Gráfica%' OR nombre LIKE '%GPU%' OR nombre LIKE '%RTX%' THEN 'Tarjeta Gráfica'
        WHEN nombre LIKE '%Laptop%' OR nombre LIKE '%Notebook%' THEN 'Laptop'
        ELSE 'Otro Componente'
    END AS Categoria_Producto,
    -- Extracción de modelo
    SUBSTRING(nombre, CHARINDEX(' ', nombre) + 1, LEN(nombre)) AS Modelo,
    -- Contador de palabras
    LEN(nombre) - LEN(REPLACE(nombre, ' ', '')) + 1 AS Palabras_en_Nombre
FROM Producto;

-- 3. Generación de códigos y referencias únicas
SELECT 
    id_producto,
    nombre,
    -- Generación de SKU
    UPPER(
        LEFT(REPLACE(nombre, ' ', ''), 3) + 
        RIGHT('000' + CAST(id_producto AS VARCHAR), 3) +
        SUBSTRING(CONVERT(VARCHAR(6), GETDATE(), 12), 3, 4)
    ) AS SKU_Generado,
    -- Código abreviado
    LEFT(REPLACE(REPLACE(nombre, ' ', ''), '-', ''), 8) AS Codigo_Corto,
    -- Referencia para búsqueda
    REPLACE(REPLACE(REPLACE(LOWER(nombre), ' ', '_'), 'á', 'a'), 'é', 'e') AS Slug_URL
FROM Producto;

-- 4. Análisis de nombres de usuarios y seguridad
SELECT 
    id_usuario,
    nombre,
    username,
    -- Análisis de nombre de usuario
    CASE 
        WHEN username LIKE '%.%' THEN 'Contiene punto'
        WHEN PATINDEX('%[0-9]%', username) > 0 THEN 'Contiene números'
        ELSE 'Solo letras'
    END AS Tipo_Username,
    -- Validación de fortaleza de nombre de usuario
    CASE
        WHEN LEN(username) >= 8 AND username LIKE '%[a-z]%' 
             AND username LIKE '%[A-Z]%' AND PATINDEX('%[0-9]%', username) > 0 
             THEN 'Fuerte'
        WHEN LEN(username) >= 6 THEN 'Moderada'
        ELSE 'Débil'
    END AS Fortaleza_Username,
    -- Máscara de contraseña (solo para demostración)
    REPLICATE('*', LEN(contraseña)) AS Password_Masked,
    -- Iniciales del usuario
    LEFT(nombre, 1) + 
    ISNULL(LEFT(SUBSTRING(nombre, CHARINDEX(' ', nombre) + 1, LEN(nombre)), 1), '') AS Iniciales
FROM Usuario;

-- 5. Formateo de información de ventas para reportes
SELECT 
    V.id_venta,
    -- Número de factura formateado
    'FACT-' + RIGHT('00000' + CAST(V.id_venta AS VARCHAR), 5) AS Numero_Factura,
    -- Fecha formateada
    FORMAT(V.fecha, 'dd/MM/yyyy HH:mm') AS Fecha_Formateada,
    -- Cliente con formato
    UPPER(LEFT(C.nombre, 1)) + LOWER(SUBSTRING(C.nombre, 2, LEN(C.nombre))) AS Cliente_Formateado,
    -- Total con formato monetario
    '$' + FORMAT(V.total, 'N2') AS Total_Formateado,
    -- Detalle concatenado de productos
    STUFF((
        SELECT ', ' + CAST(DV.cantidad AS VARCHAR) + 'x ' + P.nombre
        FROM Detalle_venta DV
        JOIN Producto P ON DV.id_producto = P.id_producto
        WHERE DV.id_venta = V.id_venta
        FOR XML PATH(''), TYPE
    ).value('.', 'VARCHAR(MAX)'), 1, 2, '') AS Productos_Comprados,
    -- Resumen de la venta
    CONCAT(
        'Venta #', V.id_venta, 
        ' - Cliente: ', LEFT(C.nombre, CHARINDEX(' ', C.nombre + ' ') - 1),
        ' - Total: $', CAST(V.total AS DECIMAL(10,2))
    ) AS Resumen_Venta
FROM Ventas V
JOIN Clientes C ON V.id_cliente = C.id_cliente;

-- 6. Función para calcular valor total del inventario por marca (función escalar)
GO

CREATE FUNCTION dbo.CalcularValorInventarioMarca (@id_marca INT)
RETURNS DECIMAL(15,2)
AS
BEGIN
    DECLARE @valor_total DECIMAL(15,2);
    
    SELECT @valor_total = SUM(P.precio * I.stock_total)
    FROM Producto P
    JOIN Inventario I ON P.id_producto = I.id_producto
    WHERE P.id_marca = @id_marca;
    
    RETURN ISNULL(@valor_total, 0);
END;
GO

-- Uso de la función
SELECT 
    nombre_marca,
    dbo.CalcularValorInventarioMarca(id_marca) AS Valor_Total_Inventario
FROM Marca;
-------------------------------------------------------------------------------------
-- Subconsulta: Productos cuyo stock es menor al promedio global de stock
SELECT nombre FROM Producto 
WHERE id_producto IN (
    SELECT id_producto FROM Inventario WHERE stock_total < (SELECT AVG(stock_total) FROM Inventario)
);

-- Vista: Resumen de stock por marca para gerencia
GO

CREATE VIEW Vista_Stock_Marcas AS
SELECT M.nombre_marca, SUM(I.stock_total) AS Total_Unidades
FROM Marca M
JOIN Producto P ON M.id_marca = P.id_marca
JOIN Inventario I ON P.id_producto = I.id_producto
GROUP BY M.nombre_marca;

GO

SELECT * FROM Vista_Stock_Marcas;


-- Ejemplos de insercion 
-- Agregar un nuevo producto al catálogo
INSERT INTO Producto (nombre, precio, id_marca) VALUES 
('Laptop Asus ROG', 1200.00, 1),
('Laptop TUF Gaming A16', 1400.00, 2);

-- Actualizar el stock tras una recepción de mercadería
UPDATE Inventario 
SET stock_total = stock_total + 20 
WHERE id_producto = 1;

-- Eliminar un registro de auditoría antiguo (Ejemplo de limpieza)
DELETE FROM Auditoria WHERE fecha < '2024-01-01';


-- Administración y seguridad
-- Crear Roles para la aplicación 
CREATE ROLE rol_admin;
CREATE ROLE rol_vendedor;
GO

-- Asignar permisos al Administrador (Control total) 
GRANT SELECT, INSERT, UPDATE, DELETE TO rol_admin;
GO

-- Asignar permisos al Vendedor (Solo lectura de productos y creación de ventas) 
GRANT SELECT ON Producto TO rol_vendedor;
GRANT SELECT ON Clientes TO rol_vendedor;
GRANT INSERT, SELECT ON Ventas TO rol_vendedor;
GRANT INSERT, SELECT ON Detalle_venta TO rol_vendedor;
GO

-- Crear un Login y Usuario de prueba para el vendedor 
CREATE LOGIN vendedor_usuario WITH PASSWORD = 'ClaveSegura_2025*';
GO
CREATE USER vendedor01 FOR LOGIN vendedor_usuario;
GO

-- Vincular al usuario con su rol correspondiente 
ALTER ROLE rol_vendedor ADD MEMBER vendedor01;
GO

-- CIFRADO 
-- Insertar Usuario con rol de Administrador
INSERT INTO Usuario (nombre, rol, username, contraseña)
VALUES ('Admin Sistema EPN', 'Administrador', 'admin_tecnologia', 
        HASHBYTES('SHA2_512', 'Admin_EPN_2025*'));
GO

-- Insertar Usuario con rol de Vendedor
INSERT INTO Usuario (nombre, rol, username, contraseña)
VALUES ('Luis Vendedor', 'Vendedor', 'luis.ventas', 
        HASHBYTES('SHA2_512', 'Ventas_EPN_2025!'));
GO

-- Verificación de los datos cifrados 
SELECT id_usuario, nombre, rol, username, contraseña AS Password_Cifrado 
FROM Usuario;
GO

-- BACKUPS
-- Generar Backup Completo
BACKUP DATABASE GestionTiendaTecnologia 
TO DISK = 'C:\Backups\Tienda_Full.bak' 
WITH FORMAT, NAME = 'Respaldo Completo de la Tienda';
GO

-- para hacer la restauracion
-- Siempre cámbiate a la base 'master' para no bloquear la restauración
USE master;
GO

-- Este comando "abre" el archivo y recrea la base de datos
RESTORE DATABASE GestionTiendaTecnologia 
FROM DISK = 'C:\Backups\Tienda_Full.bak' 
WITH REPLACE;
GO

-- Se realiza con la base en uso (Online)
BACKUP DATABASE GestionTiendaTecnologia 
TO DISK = 'C:\Backups\Tienda_Hot.bak' 
WITH NOSKIP, NOFORMAT;
GO

-- Respaldo del Log (Equivalente a Incremental)
BACKUP LOG GestionTiendaTecnologia 
TO DISK = 'C:\Backups\Tienda_Log.trn';
GO

-- AUDITORIA
-- Trigger para insert 
CREATE TRIGGER trg_Auditoria_Ventas_Insert
ON Ventas
AFTER INSERT
AS
BEGIN
    INSERT INTO Auditoria (tabla_afectada, accion, usuario_responsable)
    VALUES ('Ventas', 'INSERT', SYSTEM_USER);
END;
GO

-- Trigger para update 
CREATE TRIGGER trg_Auditoria_Producto_Update
ON Producto
AFTER UPDATE
AS
BEGIN
    INSERT INTO Auditoria (tabla_afectada, accion, usuario_responsable)
    VALUES ('Producto', 'UPDATE', SYSTEM_USER);
END;
GO

-- Tigger para delete
CREATE TRIGGER trg_Auditoria_Cliente_Delete
ON Clientes
AFTER DELETE
AS
BEGIN
    INSERT INTO Auditoria (tabla_afectada, accion, usuario_responsable)
    VALUES ('Clientes', 'DELETE', SYSTEM_USER);
END;
GO

-- Insertar un cliente de prueba para activar el trigger de INSERT
INSERT INTO Clientes (nombre, correo, telefono, direccion)
VALUES ('Prueba Auditoria', 'test@epn.edu.ec', '0999999999', 'Quito');

-- Consultar la tabla de auditoría para ver si el Trigger dejó huella
SELECT * FROM Auditoria;
