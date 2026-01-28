<?php
require_once '../config/database.php';

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit();
}

// Verificar que sea administrador
if ($_SESSION['usuario_rol'] !== 'Administrador') {
    header('Location: ../usuario/dashboard.php');
    exit();
}

$usuario = $_SESSION['usuario_nombre'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: Arial, sans-serif; 
            background: #f5f5f5;
            display: flex;
        }
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            height: 100vh;
            position: fixed;
        }
        .sidebar-header {
            padding: 20px;
            background: #1a252f;
            text-align: center;
        }
        .sidebar-nav ul {
            list-style: none;
            padding: 20px 0;
        }
        .sidebar-nav li {
            padding: 0;
        }
        .sidebar-nav a {
            display: block;
            color: #ecf0f1;
            padding: 15px 20px;
            text-decoration: none;
            transition: background 0.3s;
        }
        .sidebar-nav a:hover {
            background: #34495e;
        }
        .sidebar-nav a.active {
            background: #3498db;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }
        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }
        .card h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .card p {
            color: #7f8c8d;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #2980b9;
        }
        .logout-btn {
            background: #e74c3c;
        }
        .logout-btn:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>🔧 Gestión Tienda</h2>
            <p>Panel Administrador</p>
        </div>
        
        <nav class="sidebar-nav">
            <ul>
                <li><a href="dashboard.php" class="active">🏠 Dashboard</a></li>
                <li><a href="crear_usuario.php">👥 Crear Usuario</a></li>
                <li><a href="gestion_bd.php">🗄️ Gestión BD</a></li>
                <li><hr style="margin: 10px 20px; border-color: #34495e;"></li>
                <li><a href="../usuario/crud.php?tabla=Clientes">📋 Clientes</a></li>
                <li><a href="../usuario/crud.php?tabla=Producto">📦 Productos</a></li>
                <li><a href="../usuario/crud.php?tabla=Ventas">💰 Ventas</a></li>
                <li><a href="../usuario/crud.php?tabla=Inventario">📊 Inventario</a></li>
                <li><hr style="margin: 10px 20px; border-color: #34495e;"></li>
                <li><a href="../logout.php" class="logout-btn">🚪 Cerrar Sesión</a></li>
            </ul>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1>Bienvenido, <?php echo htmlspecialchars($usuario); ?></h1>
            <span>Administrador</span>
        </div>
        
        <div class="card-container">
            <div class="card">
                <div class="card-icon">👥</div>
                <h3>Usuarios</h3>
                <p>Gestión de usuarios del sistema</p>
                <a href="crear_usuario.php" class="btn">Crear Usuario</a>
            </div>
            
            <div class="card">
                <div class="card-icon">🗄️</div>
                <h3>Base de Datos</h3>
                <p>Gestión completa de la BD</p>
                <a href="gestion_bd.php" class="btn">Acceder</a>
            </div>
            
            <div class="card">
                <div class="card-icon">📋</div>
                <h3>Tablas</h3>
                <p>Acceso a todas las tablas</p>
                <a href="../usuario/dashboard.php" class="btn">Ver Tablas</a>
            </div>
            
            <div class="card">
                <div class="card-icon">📊</div>
                <h3>Reportes</h3>
                <p>Estadísticas y reportes</p>
                <a href="#" class="btn">Generar</a>
            </div>
        </div>
        
        <div class="card">
            <h2>Acceso Directo a Tablas</h2>
            <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 15px;">
                <a href="../usuario/crud.php?tabla=Clientes" class="btn">👥 Clientes</a>
                <a href="../usuario/crud.php?tabla=Producto" class="btn">📦 Productos</a>
                <a href="../usuario/crud.php?tabla=Ventas" class="btn">💰 Ventas</a>
                <a href="../usuario/crud.php?tabla=Inventario" class="btn">📊 Inventario</a>
                <a href="../usuario/crud.php?tabla=Usuario" class="btn">👤 Usuarios</a>
                <a href="../usuario/crud.php?tabla=Marca" class="btn">🏷️ Marcas</a>
            </div>
        </div>
    </div>
</body>
</html>