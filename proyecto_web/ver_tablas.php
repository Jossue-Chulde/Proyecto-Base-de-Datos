<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos (SIMULACIÓN - para producción usar conexión real)
$tablas = [
    'clientes' => [
        'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
        'nombre' => 'VARCHAR(100) NOT NULL',
        'email' => 'VARCHAR(100) NOT NULL UNIQUE',
        'telefono' => 'VARCHAR(20)',
        'direccion' => 'TEXT',
        'ciudad' => 'VARCHAR(50)',
        'fecha_registro' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
    ],
    'productos' => [
        'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
        'nombre' => 'VARCHAR(100) NOT NULL',
        'descripcion' => 'TEXT',
        'precio' => 'DECIMAL(10,2) NOT NULL',
        'categoria' => 'VARCHAR(50)',
        'stock' => 'INT DEFAULT 0',
        'marca_id' => 'INT',
        'fecha_creacion' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
    ],
    'ventas' => [
        'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
        'cliente_id' => 'INT NOT NULL',
        'producto_id' => 'INT NOT NULL',
        'cantidad' => 'INT NOT NULL',
        'total' => 'DECIMAL(10,2) NOT NULL',
        'fecha_venta' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        'estado' => 'VARCHAR(20) DEFAULT "completada"'
    ],
    'inventario' => [
        'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
        'producto_id' => 'INT NOT NULL',
        'cantidad' => 'INT NOT NULL',
        'ubicacion' => 'VARCHAR(100)',
        'fecha_actualizacion' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
    ],
    'usuarios' => [
        'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
        'nombre' => 'VARCHAR(100) NOT NULL',
        'email' => 'VARCHAR(100) NOT NULL UNIQUE',
        'usuario' => 'VARCHAR(50) NOT NULL UNIQUE',
        'contrasena' => 'VARCHAR(255) NOT NULL',
        'rol' => 'VARCHAR(20) DEFAULT "usuario"',
        'genero' => 'CHAR(1)',
        'fecha_registro' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
    ],
    'marcas' => [
        'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
        'nombre' => 'VARCHAR(100) NOT NULL',
        'pais' => 'VARCHAR(50)',
        'categoria' => 'VARCHAR(50)',
        'descripcion' => 'TEXT'
    ]
];

// Datos de ejemplo para cada tabla
$datosEjemplo = [
    'clientes' => [
        ['id' => 1, 'nombre' => 'Juan Pérez', 'email' => 'juan@email.com', 'telefono' => '555-1234', 'ciudad' => 'Madrid'],
        ['id' => 2, 'nombre' => 'Ana Gómez', 'email' => 'ana@email.com', 'telefono' => '555-5678', 'ciudad' => 'Barcelona'],
        ['id' => 3, 'nombre' => 'Carlos Ruiz', 'email' => 'carlos@email.com', 'telefono' => '555-9012', 'ciudad' => 'Valencia'],
    ],
    'productos' => [
        ['id' => 1, 'nombre' => 'Laptop Gaming', 'categoria' => 'Electrónica', 'precio' => '899.99', 'stock' => 15],
        ['id' => 2, 'nombre' => 'Mouse Inalámbrico', 'categoria' => 'Accesorios', 'precio' => '29.99', 'stock' => 50],
        ['id' => 3, 'nombre' => 'Teclado Mecánico', 'categoria' => 'Accesorios', 'precio' => '79.99', 'stock' => 25],
    ],
    'ventas' => [
        ['id' => 1, 'cliente_id' => 1, 'producto_id' => 1, 'cantidad' => 1, 'total' => '899.99', 'fecha_venta' => '2024-01-15'],
        ['id' => 2, 'cliente_id' => 2, 'producto_id' => 2, 'cantidad' => 2, 'total' => '59.98', 'fecha_venta' => '2024-01-16'],
    ],
    'inventario' => [
        ['id' => 1, 'producto_id' => 1, 'cantidad' => 15, 'ubicacion' => 'Almacén A'],
        ['id' => 2, 'producto_id' => 2, 'cantidad' => 50, 'ubicacion' => 'Almacén B'],
    ],
    'usuarios' => [
        ['id' => 1, 'nombre' => 'Admin', 'email' => 'admin@tienda.com', 'usuario' => 'admin', 'rol' => 'Administrador', 'genero' => 'o'],
        ['id' => 2, 'nombre' => 'Vendedor', 'email' => 'vendedor@tienda.com', 'usuario' => 'vendedor', 'rol' => 'Vendedor', 'genero' => 'a'],
    ],
    'marcas' => [
        ['id' => 1, 'nombre' => 'HP', 'pais' => 'USA', 'categoria' => 'Electrónica'],
        ['id' => 2, 'nombre' => 'Logitech', 'pais' => 'Suiza', 'categoria' => 'Accesorios'],
    ]
];

// Obtener la tabla seleccionada
$tablaSeleccionada = isset($_GET['tabla']) ? $_GET['tabla'] : 'clientes';
$accion = isset($_GET['accion']) ? $_GET['accion'] : 'listar';
$mensaje = '';
$tipoMensaje = '';

// Procesar operaciones CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['crear'])) {
        // Crear registro
        $mensaje = "Registro creado exitosamente en la tabla '$tablaSeleccionada'";
        $tipoMensaje = "success";
    } elseif (isset($_POST['actualizar'])) {
        // Actualizar registro
        $mensaje = "Registro actualizado exitosamente en la tabla '$tablaSeleccionada'";
        $tipoMensaje = "success";
    } elseif (isset($_POST['eliminar'])) {
        // Eliminar registro
        $id = $_POST['id'];
        $mensaje = "Registro #$id eliminado exitosamente de la tabla '$tablaSeleccionada'";
        $tipoMensaje = "success";
    }
}

// Obtener datos de la tabla seleccionada
$datosTabla = $datosEjemplo[$tablaSeleccionada] ?? [];

// Si se está editando un registro específico
$registroEditar = null;
if (isset($_GET['editar']) && is_numeric($_GET['editar'])) {
    $idEditar = $_GET['editar'];
    foreach ($datosTabla as $registro) {
        if ($registro['id'] == $idEditar) {
            $registroEditar = $registro;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Tablas - Gestión Tienda</title>
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
            <h1><i class="fas fa-database"></i> CRUD - Tablas de Base de Datos</h1>
            <a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i> Volver</a>
        </div>

        <?php if ($mensaje): ?>
            <div class="message <?php echo $tipoMensaje; ?>">
                <i class="fas fa-check-circle"></i> <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <div class="tabs">
            <?php foreach (array_keys($tablas) as $tabla): ?>
                <a href="?tabla=<?php echo $tabla; ?>&accion=listar" 
                   class="tab <?php echo ($tablaSeleccionada == $tabla) ? 'active' : ''; ?>">
                    <i class="fas fa-table"></i> <?php echo ucfirst($tabla); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="header">
            <h2><i class="fas fa-<?php echo ($accion == 'crear') ? 'plus' : 'edit'; ?>"></i> 
                <?php echo ucfirst($accion); ?> Registro - <?php echo ucfirst($tablaSeleccionada); ?>
            </h2>
            <span class="badge" style="background: #3498db; color: white; padding: 8px 15px; border-radius: 20px;">
                <i class="fas fa-list"></i> <?php echo count($datosTabla); ?> registros
            </span>
        </div>

        <!-- Formulario para CREAR o EDITAR -->
        <?php if ($accion == 'crear' || $registroEditar): ?>
            <div class="form-container">
                <form method="POST" action="?tabla=<?php echo $tablaSeleccionada; ?>&accion=listar">
                    <?php
                    $campos = $tablas[$tablaSeleccionada];
                    foreach ($campos as $nombreCampo => $tipoCampo):
                        // Omitir campos autoincrement y timestamp
                        if (strpos($tipoCampo, 'AUTO_INCREMENT') !== false || 
                            strpos($nombreCampo, 'fecha_') !== false) {
                            continue;
                        }
                        
                        $etiqueta = ucfirst(str_replace('_', ' ', $nombreCampo));
                        $valor = $registroEditar[$nombreCampo] ?? '';
                        $tipoInput = 'text';
                        
                        // Determinar tipo de input
                        if ($nombreCampo == 'email') $tipoInput = 'email';
                        if ($nombreCampo == 'precio' || $nombreCampo == 'total') $tipoInput = 'number';
                        if ($nombreCampo == 'cantidad' || $nombreCampo == 'stock') $tipoInput = 'number';
                        if ($nombreCampo == 'contrasena') $tipoInput = 'password';
                        if (strpos($tipoCampo, 'TEXT') !== false) $tipoInput = 'textarea';
                        if ($nombreCampo == 'rol') $tipoInput = 'select';
                        if ($nombreCampo == 'genero') $tipoInput = 'select';
                    ?>
                    <div class="form-group">
                        <label for="<?php echo $nombreCampo; ?>">
                            <i class="fas fa-<?php 
                                if ($nombreCampo == 'nombre') echo 'tag';
                                elseif ($nombreCampo == 'email') echo 'envelope';
                                elseif ($nombreCampo == 'precio' || $nombreCampo == 'total') echo 'dollar-sign';
                                elseif ($nombreCampo == 'cantidad' || $nombreCampo == 'stock') echo 'boxes';
                                elseif ($nombreCampo == 'contrasena') echo 'lock';
                                else echo 'edit';
                            ?>"></i> 
                            <?php echo $etiqueta; ?>:
                        </label>
                        
                        <?php if ($tipoInput == 'textarea'): ?>
                            <textarea id="<?php echo $nombreCampo; ?>" name="<?php echo $nombreCampo; ?>" 
                                      rows="4" <?php echo (strpos($tipoCampo, 'NOT NULL') !== false) ? 'required' : ''; ?>><?php echo htmlspecialchars($valor); ?></textarea>
                        
                        <?php elseif ($tipoInput == 'select' && $nombreCampo == 'rol'): ?>
                            <select id="<?php echo $nombreCampo; ?>" name="<?php echo $nombreCampo; ?>">
                                <option value="administrador" <?php echo ($valor == 'administrador') ? 'selected' : ''; ?>>Administrador</option>
                                <option value="vendedor" <?php echo ($valor == 'vendedor') ? 'selected' : ''; ?>>Vendedor</option>
                                <option value="usuario" <?php echo ($valor == 'usuario' || empty($valor)) ? 'selected' : ''; ?>>Usuario</option>
                            </select>
                        
                        <?php elseif ($tipoInput == 'select' && $nombreCampo == 'genero'): ?>
                            <select id="<?php echo $nombreCampo; ?>" name="<?php echo $nombreCampo; ?>">
                                <option value="o" <?php echo ($valor == 'o') ? 'selected' : ''; ?>>Masculino</option>
                                <option value="a" <?php echo ($valor == 'a') ? 'selected' : ''; ?>>Femenino</option>
                                <option value="@" <?php echo ($valor == '@' || empty($valor)) ? 'selected' : ''; ?>>Neutro</option>
                            </select>
                        
                        <?php else: ?>
                            <input type="<?php echo $tipoInput; ?>" id="<?php echo $nombreCampo; ?>" 
                                   name="<?php echo $nombreCampo; ?>" 
                                   value="<?php echo htmlspecialchars($valor); ?>"
                                   <?php echo (strpos($tipoCampo, 'NOT NULL') !== false) ? 'required' : ''; ?>
                                   <?php echo ($tipoInput == 'number') ? 'step="0.01"' : ''; ?>
                                   placeholder="Ingrese <?php echo strtolower($etiqueta); ?>">
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                    
                    <div class="crud-buttons">
                        <?php if ($registroEditar): ?>
                            <button type="submit" name="actualizar" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizar Registro
                            </button>
                            <input type="hidden" name="id" value="<?php echo $registroEditar['id']; ?>">
                        <?php else: ?>
                            <button type="submit" name="crear" class="btn btn-success">
                                <i class="fas fa-plus-circle"></i> Crear Nuevo Registro
                            </button>
                        <?php endif; ?>
                        <a href="?tabla=<?php echo $tablaSeleccionada; ?>&accion=listar" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        
        <!-- Listar registros -->
        <?php elseif ($accion == 'listar'): ?>
            <?php if (!empty($datosTabla)): ?>
                <div class="data-table">
                    <table>
                        <thead>
                            <tr>
                                <?php 
                                if (!empty($datosTabla)) {
                                    foreach (array_keys($datosTabla[0]) as $columna) {
                                        echo "<th>" . ucfirst(str_replace('_', ' ', $columna)) . "</th>";
                                    }
                                    echo "<th>Acciones</th>";
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($datosTabla as $fila): ?>
                            <tr>
                                <?php foreach ($fila as $columna => $valor): ?>
                                    <td>
                                        <?php 
                                        if ($columna == 'precio' || $columna == 'total') {
                                            echo '$' . number_format($valor, 2);
                                        } elseif ($columna == 'genero') {
                                            $icon = ($valor == 'o') ? 'mars' : (($valor == 'a') ? 'venus' : 'genderless');
                                            echo '<i class="fas fa-'.$icon.'"></i> ' . $valor;
                                        } else {
                                            echo htmlspecialchars($valor);
                                        }
                                        ?>
                                    </td>
                                <?php endforeach; ?>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <a href="?tabla=<?php echo $tablaSeleccionada; ?>&editar=<?php echo $fila['id']; ?>" 
                                           class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form method="POST" action="?tabla=<?php echo $tablaSeleccionada; ?>&accion=listar" 
                                              style="display: inline;"
                                              onsubmit="return confirm('¿Eliminar este registro?');">
                                            <input type="hidden" name="id" value="<?php echo $fila['id']; ?>">
                                            <button type="submit" name="eliminar" class="btn btn-danger">
                                                <i class="fas fa-trash"></i> Eliminar
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
                    <a href="?tabla=<?php echo $tablaSeleccionada; ?>&accion=crear" class="btn btn-success">
                        <i class="fas fa-plus-circle"></i> Crear Nuevo Registro
                    </a>
                    <button class="btn btn-primary" onclick="exportarDatos()">
                        <i class="fas fa-file-export"></i> Exportar Datos
                    </button>
                </div>
            <?php else: ?>
                <div class="empty-message">
                    <i class="fas fa-database fa-3x" style="color: #95a5a6; margin-bottom: 20px;"></i>
                    <h3>Tabla vacía</h3>
                    <p>La tabla <strong><?php echo $tablaSeleccionada; ?></strong> no contiene registros.</p>
                    <div class="crud-buttons" style="margin-top: 30px;">
                        <a href="?tabla=<?php echo $tablaSeleccionada; ?>&accion=crear" class="btn btn-success">
                            <i class="fas fa-plus-circle"></i> Crear Primer Registro
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <script>
        document.querySelector('.logout-link').addEventListener('click', function(e) {
            if (!confirm('¿Está seguro de cerrar sesión?')) {
                e.preventDefault();
            }
        });
        
        function exportarDatos() {
            alert('Exportando datos de la tabla <?php echo $tablaSeleccionada; ?>...\nFormato: CSV');
        }
        
        // Confirmar eliminación
        document.querySelectorAll('form[onsubmit]').forEach(form => {
            form.onsubmit = function() {
                return confirm('¿Está seguro de eliminar este registro?');
            };
        });
    </script>
</body>
</html>