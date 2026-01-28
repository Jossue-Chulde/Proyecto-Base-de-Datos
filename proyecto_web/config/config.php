<?php
// Configuración de la aplicación
define('APP_NAME', 'Gestión Tienda Tecnología');
define('APP_VERSION', '1.0.0');

// Configuración de permisos por rol (SIN tabla Permisos)
$PERMISOS_POR_ROL = array(
    'Administrador' => array(
        'descripcion' => 'Acceso completo al sistema',
        'tablas' => array('Clientes', 'Usuario', 'Ventas', 'Producto', 'Marca', 'Detalle_venta', 'Inventario', 'Auditoria'),
        'crud' => array('C', 'R', 'U', 'D'), // Todos los permisos
        'menu' => array('dashboard', 'crear_usuario', 'gestion_bd', 'tablas')
    ),
    'Vendedor' => array(
        'descripcion' => 'Gestión de ventas y clientes',
        'tablas' => array('Clientes', 'Ventas', 'Producto', 'Detalle_venta'),
        'crud' => array('C', 'R', 'U'), // No puede eliminar
        'menu' => array('dashboard', 'tablas')
    ),
    'Inventario' => array(
        'descripcion' => 'Gestión de productos e inventario',
        'tablas' => array('Producto', 'Marca', 'Inventario'),
        'crud' => array('C', 'R', 'U', 'D'),
        'menu' => array('dashboard', 'tablas')
    ),
    'Consulta' => array(
        'descripcion' => 'Solo permisos de lectura',
        'tablas' => array('Clientes', 'Ventas', 'Producto', 'Marca', 'Inventario'),
        'crud' => array('R'), // Solo lectura
        'menu' => array('dashboard', 'tablas')
    )
);

// Configuración de las tablas del sistema
$TABLAS_SISTEMA = array(
    'Clientes' => array(
        'nombre' => 'Clientes',
        'descripcion' => 'Registro de clientes de la tienda',
        'campo_id' => 'id_cliente',
        'campos' => array('nombre', 'cedula', 'correo', 'telefono')
    ),
    'Usuario' => array(
        'nombre' => 'Usuario',
        'descripcion' => 'Usuarios del sistema',
        'campo_id' => 'id_usuario',
        'campos' => array('nombre', 'rol', 'username', 'contraseña')
    ),
    'Ventas' => array(
        'nombre' => 'Ventas',
        'descripcion' => 'Registro de ventas realizadas',
        'campo_id' => 'id_venta',
        'campos' => array('fecha', 'total', 'metodo_de_pago', 'id_cliente', 'id_usuario')
    ),
    'Producto' => array(
        'nombre' => 'Producto',
        'descripcion' => 'Catálogo de productos tecnológicos',
        'campo_id' => 'id_producto',
        'campos' => array('nombre', 'precio', 'id_marca')
    ),
    'Marca' => array(
        'nombre' => 'Marca',
        'descripcion' => 'Marcas de productos',
        'campo_id' => 'id_marca',
        'campos' => array('nombre_marca')
    ),
    'Detalle_venta' => array(
        'nombre' => 'Detalle_venta',
        'descripcion' => 'Detalle de productos en cada venta',
        'campo_id' => 'id_detalle_venta',
        'campos' => array('cantidad', 'precio_unitario', 'subtotal', 'id_venta', 'id_producto')
    ),
    'Inventario' => array(
        'nombre' => 'Inventario',
        'descripcion' => 'Control de inventario de productos',
        'campo_id' => 'id_inventario',
        'campos' => array('stock_total', 'id_producto')
    ),
    'Auditoria' => array(
        'nombre' => 'Auditoria',
        'descripcion' => 'Registro de auditoría del sistema',
        'campo_id' => 'id_auditoria',
        'campos' => array('fecha', 'tabla_afectada', 'accion', 'usuario_responsable')
    )
);

// Función para verificar permisos
function tienePermiso($rol, $tabla, $accion) {
    global $PERMISOS_POR_ROL;
    
    if (!isset($PERMISOS_POR_ROL[$rol])) {
        return false;
    }
    
    // Verificar si la tabla está permitida para este rol
    if (!in_array($tabla, $PERMISOS_POR_ROL[$rol]['tablas'])) {
        return false;
    }
    
    // Verificar si la acción está permitida
    return in_array($accion, $PERMISOS_POR_ROL[$rol]['crud']);
}

// Función para obtener tablas permitidas para un rol
function obtenerTablasPermitidas($rol) {
    global $PERMISOS_POR_ROL;
    
    if (isset($PERMISOS_POR_ROL[$rol])) {
        return $PERMISOS_POR_ROL[$rol]['tablas'];
    }
    
    return array();
}

// Función para obtener acciones permitidas para un rol
function obtenerAccionesPermitidas($rol) {
    global $PERMISOS_POR_ROL;
    
    if (isset($PERMISOS_POR_ROL[$rol])) {
        return $PERMISOS_POR_ROL[$rol]['crud'];
    }
    
    return array();
}
?>