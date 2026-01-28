<?php
require_once '../config/database.php';

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit();
}

$usuario_rol = $_SESSION['usuario_rol'];
$usuario_nombre = $_SESSION['usuario_nombre'];

// Tablas permitidas según rol
$tablas_permitidas = array();
switch ($usuario_rol) {
    case 'Administrador':
        $tablas_permitidas = array('Clientes', 'Usuario', 'Ventas', 'Producto', 'Marca', 'Detalle_venta', 'Inventario', 'Auditoria');
        break;
    case 'Vendedor':
        $tablas_permitidas = array('Clientes', 'Ventas', 'Producto', 'Detalle_venta');
        break;
    case 'Inventario':
        $tablas_permitidas = array('Producto', 'Marca', 'Inventario');
        break;
    case 'Consulta':
        $tablas_permitidas = array('Clientes', 'Ventas', 'Producto', 'Marca', 'Inventario');
        break;
    default:
        $tablas_permitidas = array();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Usuario</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f5f5f5;
            display: flex;
        }
        .sidebar { width: 250px; background: #2c3e50; color: white; height: 100vh; position: fixed; }
        .sidebar-header { padding: 20px; background: #1a252f; text-align: center; }
        .sidebar-nav ul { list-style: none; padding: 20px 0; }
        .sidebar-nav a { display: block; color: #ecf0f1; padding: 15px 20px; text-decoration: none; }
        .sidebar-nav a:hover { background: #34495e; }
        .main-content { margin-left: 250px; padding: 20px; width: calc(100% - 250px); }
        .header { background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; }
        .tablas-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; }
        .tabla-card { 
            background: white; 
            padding: 20px; 
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
        }
        .tabla-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        .tabla-icon { font-size: 40px; margin-bottom: 10px; }
        .tabla-card h3 { color: #2c3e50; margin: 10px 0; }
        .tabla-card p { color: #7f8c8d; font-size: 12px; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>🔧 Gestión Tienda</h2>
            <p>Panel Usuario</p>
        </div>
        
        <nav class="sidebar-nav">
            <ul>
                <li><a href="dashboard.php" style="background: #3498db;">🏠 Dashboard</a></li>
                <?php foreach ($tablas_permitidas as $tabla): ?>
                    <li><a href="crud.php?tabla=<?php echo urlencode($tabla); ?>">
                        📋 <?php echo htmlspecialchars($tabla); ?>
                    </a></li>
                <?php endforeach; ?>
                <li><hr style="margin: 10px 20px; border-color: #34495e;"></li>
                <li><a href="../logout.php">🚪 Cerrar Sesión</a></li>
            </ul>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1>Bienvenido, <?php echo htmlspecialchars($usuario_nombre); ?></h1>
            <p>Rol: <?php echo htmlspecialchars($usuario_rol); ?></p>
        </div>
        
        <h2>Tablas Disponibles</h2>
        <p>Seleccione una tabla para ver y gestionar sus datos:</p>
        
        <div class="tablas-grid">
            <?php foreach ($tablas_permitidas as $tabla): 
                // Icono según tabla
                $icono = '📋';
                $descripcion = 'Ver y gestionar datos';
                
                switch($tabla) {
                    case 'Clientes': $icono = '👥'; $descripcion = 'Gestión de clientes'; break;
                    case 'Producto': $icono = '📦'; $descripcion = 'Catálogo de productos'; break;
                    case 'Ventas': $icono = '💰'; $descripcion = 'Registro de ventas'; break;
                    case 'Inventario': $icono = '📊'; $descripcion = 'Control de stock'; break;
                    case 'Usuario': $icono = '👤'; $descripcion = 'Usuarios del sistema'; break;
                    case 'Marca': $icono = '🏷️'; $descripcion = 'Marcas de productos'; break;
                }
            ?>
                <a href="crud.php?tabla=<?php echo urlencode($tabla); ?>" style="text-decoration: none;">
                    <div class="tabla-card">
                        <div class="tabla-icon"><?php echo $icono; ?></div>
                        <h3><?php echo htmlspecialchars($tabla); ?></h3>
                        <p><?php echo $descripcion; ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>