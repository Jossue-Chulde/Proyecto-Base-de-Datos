<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Datos de usuarios (simulación de BD)
$usuarios = [
    ['id' => 1, 'nombre' => 'Ana Torres', 'email' => 'ana@email.com', 'usuario' => 'ana', 'contrasena' => '***', 'rol' => 'Administrador', 'genero' => 'a'],
    ['id' => 2, 'nombre' => 'Carlos López', 'email' => 'carlos@email.com', 'usuario' => 'carlos', 'contrasena' => '***', 'rol' => 'Vendedor', 'genero' => 'o'],
    ['id' => 3, 'nombre' => 'María García', 'email' => 'maria@email.com', 'usuario' => 'maria', 'contrasena' => '***', 'rol' => 'Usuario', 'genero' => 'a'],
];

$mensaje = '';
$tipoMensaje = '';
$accion = isset($_GET['accion']) ? $_GET['accion'] : 'crear';
$idEditar = isset($_GET['editar']) ? $_GET['editar'] : null;
$registroEditar = null;

// Buscar registro para editar
if ($idEditar && $accion == 'editar') {
    foreach ($usuarios as $usuario) {
        if ($usuario['id'] == $idEditar) {
            $registroEditar = $usuario;
            break;
        }
    }
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['crear'])) {
        $mensaje = "Usuario '" . $_POST['nombre'] . "' creado exitosamente";
        $tipoMensaje = "success";
    } elseif (isset($_POST['actualizar'])) {
        $mensaje = "Usuario '" . $_POST['nombre'] . "' actualizado exitosamente";
        $tipoMensaje = "success";
    } elseif (isset($_POST['eliminar'])) {
        $id = $_POST['id'];
        $mensaje = "Usuario eliminado exitosamente";
        $tipoMensaje = "success";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Gestión Tienda</title>
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
            <h1>
                <i class="fas fa-<?php echo ($accion == 'crear') ? 'user-plus' : 'user-edit'; ?>"></i> 
                <?php echo ($accion == 'crear') ? 'Crear Usuario' : 'Editar Usuario'; ?>
            </h1>
            <div>
                <a href="?accion=crear" class="btn <?php echo ($accion == 'crear') ? 'btn-primary' : 'btn-secondary'; ?>">
                    <i class="fas fa-plus"></i> Nuevo
                </a>
                <a href="?accion=listar" class="btn <?php echo ($accion == 'listar') ? 'btn-primary' : 'btn-secondary'; ?>">
                    <i class="fas fa-list"></i> Listar
                </a>
                <a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i> Volver</a>
            </div>
        </div>

        <?php if ($mensaje): ?>
            <div class="message <?php echo $tipoMensaje; ?>">
                <i class="fas fa-check-circle"></i> <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <?php if ($accion == 'listar'): ?>
            <!-- Lista de usuarios -->
            <div class="users-table">
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-user"></i> Nombre</th>
                            <th><i class="fas fa-envelope"></i> Email</th>
                            <th><i class="fas fa-user-tag"></i> Usuario</th>
                            <th><i class="fas fa-user-tag"></i> Rol</th>
                            <th><i class="fas fa-venus-mars"></i> Género</th>
                            <th><i class="fas fa-cogs"></i> Acciones</th>
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
                                <span class="badge" style="background: 
                                    <?php echo ($usuario['rol'] == 'Administrador') ? '#e74c3c' : 
                                           (($usuario['rol'] == 'Vendedor') ? '#3498db' : '#2ecc71'); ?>; 
                                    color: white; padding: 5px 10px; border-radius: 15px;">
                                    <?php echo $usuario['rol']; ?>
                                </span>
                            </td>
                            <td>
                                <?php 
                                $icon = ($usuario['genero'] == 'o') ? 'mars' : 
                                       (($usuario['genero'] == 'a') ? 'venus' : 'genderless');
                                echo '<i class="fas fa-'.$icon.'"></i> ' . $usuario['genero'];
                                ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="?accion=editar&editar=<?php echo $usuario['id']; ?>" 
                                       class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form method="POST" action="" style="display: inline;"
                                          onsubmit="return confirm('¿Eliminar usuario <?php echo htmlspecialchars($usuario['nombre']); ?>?');">
                                        <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                                        <button type="submit" name="eliminar" class="btn btn-danger">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="crud-buttons">
                <a href="?accion=crear" class="btn btn-success">
                    <i class="fas fa-user-plus"></i> Crear Nuevo Usuario
                </a>
            </div>
        
        <?php else: ?>
            <!-- Formulario para crear/editar -->
            <div class="form-container">
                <form method="POST" action="?accion=listar">
                    <div class="form-group">
                        <label for="nombre"><i class="fas fa-id-card"></i> Nombre Completo:</label>
                        <input type="text" id="nombre" name="nombre" required 
                               value="<?php echo $registroEditar ? htmlspecialchars($registroEditar['nombre']) : ''; ?>"
                               placeholder="Ej: Juan Pérez">
                    </div>

                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Email:</label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo $registroEditar ? htmlspecialchars($registroEditar['email']) : ''; ?>"
                               placeholder="Ej: usuario@email.com">
                    </div>

                    <div class="form-group">
                        <label for="usuario"><i class="fas fa-user"></i> Nombre de Usuario:</label>
                        <input type="text" id="usuario" name="usuario" required 
                               value="<?php echo $registroEditar ? htmlspecialchars($registroEditar['usuario']) : ''; ?>"
                               placeholder="Ej: juanperez">
                    </div>

                    <div class="form-group">
                        <label for="contrasena"><i class="fas fa-key"></i> Contraseña:</label>
                        <input type="password" id="contrasena" name="contrasena" 
                               <?php echo !$registroEditar ? 'required' : ''; ?>
                               placeholder="<?php echo $registroEditar ? 'Dejar en blanco para no cambiar' : 'Ingrese contraseña'; ?>">
                        <?php if ($registroEditar): ?>
                            <small style="color: #7f8c8d; display: block; margin-top: 5px;">
                                Dejar en blanco para mantener la contraseña actual
                            </small>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="rol"><i class="fas fa-user-tag"></i> Rol:</label>
                        <select id="rol" name="rol">
                            <option value="Administrador" <?php echo ($registroEditar && $registroEditar['rol'] == 'Administrador') ? 'selected' : ''; ?>>Administrador</option>
                            <option value="Vendedor" <?php echo ($registroEditar && $registroEditar['rol'] == 'Vendedor') ? 'selected' : ''; ?>>Vendedor</option>
                            <option value="Usuario" <?php echo (!$registroEditar || $registroEditar['rol'] == 'Usuario') ? 'selected' : ''; ?>>Usuario</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="genero"><i class="fas fa-venus-mars"></i> Género:</label>
                        <select id="genero" name="genero">
                            <option value="o" <?php echo ($registroEditar && $registroEditar['genero'] == 'o') ? 'selected' : ''; ?>>Masculino</option>
                            <option value="a" <?php echo ($registroEditar && $registroEditar['genero'] == 'a') ? 'selected' : ''; ?>>Femenino</option>
                            <option value="@" <?php echo (!$registroEditar || $registroEditar['genero'] == '@') ? 'selected' : ''; ?>>Neutro</option>
                        </select>
                    </div>

                    <div class="crud-buttons">
                        <?php if ($registroEditar): ?>
                            <button type="submit" name="actualizar" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizar Usuario
                            </button>
                            <input type="hidden" name="id" value="<?php echo $registroEditar['id']; ?>">
                        <?php else: ?>
                            <button type="submit" name="crear" class="btn btn-success">
                                <i class="fas fa-user-plus"></i> Crear Usuario
                            </button>
                        <?php endif; ?>
                        <a href="?accion=listar" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        <?php endif; ?>
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