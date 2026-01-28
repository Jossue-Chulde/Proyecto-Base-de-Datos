<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$usuarios = [
    ['id' => 1, 'nombre' => 'Ana Torres', 'email' => 'ana@email.com', 'usuario' => 'ana', 'genero' => 'a'],
    ['id' => 2, 'nombre' => 'Carlos López', 'email' => 'carlos@email.com', 'usuario' => 'carlos', 'genero' => 'o'],
    ['id' => 3, 'nombre' => 'María García', 'email' => 'maria@email.com', 'usuario' => 'maria', 'genero' => 'a'],
];

$mensaje = '';
$tipoMensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    $id = $_POST['id'];
    
    if (isset($_POST['confirmar']) && $_POST['confirmar'] === 'si') {
        $mensaje = "Usuario eliminado exitosamente (SIMULACIÓN)";
        $tipoMensaje = "success";
    } else {
        $mensaje = "Debe confirmar la eliminación";
        $tipoMensaje = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Usuario - Gestión Tienda</title>
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <h2><i class="fas fa-store"></i> Gestión Tienda</h2>
        <ul>
            <li><a href="index.php"><i class="fas fa-home"></i> Inicio</a></li>
            <li><a href="logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
        </ul>
    </div>

    <div class="content">
        <div class="header">
            <h1><i class="fas fa-user-times"></i> Eliminar Usuario</h1>
            <a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i> Volver al Inicio</a>
        </div>

        <?php if ($mensaje): ?>
            <div class="message <?php echo $tipoMensaje; ?>">
                <i class="fas fa-info-circle"></i> <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <div class="users-table">
            <table>
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> ID</th>
                        <th><i class="fas fa-user"></i> Nombre</th>
                        <th><i class="fas fa-envelope"></i> Email</th>
                        <th><i class="fas fa-user-tag"></i> Usuario</th>
                        <th><i class="fas fa-venus-mars"></i> Género</th>
                        <th><i class="fas fa-cogs"></i> Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo $usuario['id']; ?></td>
                        <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['usuario']); ?></td>
                        <td>
                            <?php 
                            $icon = ($usuario['genero'] == 'o') ? 'mars' : (($usuario['genero'] == 'a') ? 'venus' : 'genderless');
                            echo '<i class="fas fa-'.$icon.'"></i> '.$usuario['genero'];
                            ?>
                        </td>
                        <td>
                            <form method="POST" action="" style="display: inline;">
                                <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                                <input type="hidden" name="confirmar" value="si">
                                <button type="submit" name="eliminar" class="btn btn-danger" 
                                        onclick="return confirm('¿Está seguro de eliminar a <?php echo htmlspecialchars($usuario['nombre']); ?>?')">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="message info">
            <i class="fas fa-exclamation-circle"></i> 
            Esta es una simulación. En una implementación real, los usuarios se eliminarían de una base de datos.
        </div>
    </div>

    <script>
        document.querySelector('.logout-link').addEventListener('click', function(e) {
            if (!confirm('¿Está seguro de cerrar sesión?')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>