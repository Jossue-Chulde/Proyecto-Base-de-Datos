<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$nombreUsuario = $_SESSION['usuario'];
$genero = isset($_SESSION['genero']) ? $_SESSION['genero'] : 'o';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrador - Gestión Tienda</title>
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <h2><i class="fas fa-store"></i> Gestión Tienda</h2>
        <ul>
            <li><a href="index.php"><i class="fas fa-home"></i> Inicio</a></li>
            <li><a href="logout.php" id="logout-link"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
        </ul>
    </div>

    <div class="content">
        <h1 class="welcome">Bienvenid<?php echo $genero; ?>, <?php echo htmlspecialchars($nombreUsuario); ?></h1>
        <p style="color: #7f8c8d; margin-bottom: 30px; font-size: 1.1rem;">
            <i class="fas fa-shield-alt"></i> Panel de Administración - Gestión de Usuarios y Base de Datos
        </p>
        
        <div class="options-grid">
            <div class="option-card">
                <h3><i class="fas fa-user-cog"></i> Gestión de Usuarios</h3>
                <p>Administración completa de usuarios del sistema. Crear, editar, eliminar y gestionar roles y permisos de todos los usuarios registrados.</p>
                <div class="crud-buttons" style="margin-top: 20px;">
                    <a href="crear_usuario.php?accion=crear" class="btn btn-success">
                        <i class="fas fa-user-plus"></i> Nuevo Usuario
                    </a>
                    <a href="crear_usuario.php?accion=listar" class="btn btn-primary">
                        <i class="fas fa-users"></i> Listar Usuarios
                    </a>
                </div>
            </div>
            
            <div class="option-card">
                <h3><i class="fas fa-database"></i> Base de Datos</h3>
                <p>Acceso completo a todas las tablas del sistema. Realice operaciones CRUD (Crear, Leer, Actualizar, Eliminar) en cualquier tabla.</p>
                <div class="crud-buttons" style="margin-top: 20px;">
                    <a href="ver_tablas.php" class="btn btn-primary">
                        <i class="fas fa-table"></i> Gestionar Tablas
                    </a>
                </div>
            </div>
            
            <div class="option-card">
                <h3><i class="fas fa-chart-bar"></i> Reportes</h3>
                <p>Generación de reportes y análisis estadísticos del sistema. Visualice gráficos y exporte datos en diferentes formatos.</p>
                <div class="crud-buttons" style="margin-top: 20px;">
                    <a href="reportes.php" class="btn btn-warning">
                        <i class="fas fa-chart-pie"></i> Ver Reportes
                    </a>
                </div>
            </div>
        </div>
        
        <div style="margin-top: 40px; padding: 20px; background: #f8f9fa; border-radius: 10px; border-left: 4px solid #3498db;">
            <h3 style="color: #2c3e50; margin-bottom: 15px;">
                <i class="fas fa-info-circle"></i> Información del Sistema
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div style="background: white; padding: 15px; border-radius: 8px;">
                    <h4 style="color: #3498db; margin-bottom: 10px;">
                        <i class="fas fa-user-shield"></i> Permisos de Administrador
                    </h4>
                    <p style="color: #666; font-size: 0.95rem;">
                        Tiene acceso completo al sistema para gestionar usuarios, tablas de la base de datos y generar reportes.
                    </p>
                </div>
                <div style="background: white; padding: 15px; border-radius: 8px;">
                    <h4 style="color: #2ecc71; margin-bottom: 10px;">
                        <i class="fas fa-database"></i> Base de Datos
                    </h4>
                    <p style="color: #666; font-size: 0.95rem;">
                        Acceso a todas las tablas del sistema: Usuarios, Clientes, Productos, Ventas, Inventario y Marcas.
                    </p>
                </div>
                <div style="background: white; padding: 15px; border-radius: 8px;">
                    <h4 style="color: #e74c3c; margin-bottom: 10px;">
                        <i class="fas fa-exclamation-triangle"></i> Acciones Críticas
                    </h4>
                    <p style="color: #666; font-size: 0.95rem;">
                        Las operaciones de eliminación requieren confirmación. Se recomienda realizar copias de seguridad periódicas.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('logout-link').addEventListener('click', function(e) {
            if (!confirm('¿Está seguro de cerrar sesión?')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>