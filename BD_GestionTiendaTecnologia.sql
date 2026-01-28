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


-- Funciones de agregacion

-- Funciones de cadena

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
