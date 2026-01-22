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
