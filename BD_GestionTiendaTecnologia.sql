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
    id_usuario INT NOT NULL,

    CONSTRAINT CK_Auditoria_Accion 
    CHECK (accion IN ('INSERT', 'UPDATE', 'DELETE')),

    CONSTRAINT FK_Auditoria_Usuario FOREIGN KEY (id_usuario)
    REFERENCES Usuario(id_usuario)
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
