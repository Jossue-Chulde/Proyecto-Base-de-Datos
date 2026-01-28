<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $genero = $_POST['genero'] ?? 'o';
    
    if (!empty($username) && !empty($password)) {
        $_SESSION['usuario'] = $username;
        $_SESSION['genero'] = $genero;
        header("Location: index.php");
        exit();
    } else {
        $error = "Por favor, complete todos los campos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gestión Tienda</title>
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #3498db, #2c3e50);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="text-center mb-20">
            <i class="fas fa-store fa-3x" style="color: #3498db; margin-bottom: 20px;"></i>
            <h2 class="login-title">Gestión Tienda</h2>
            <p style="color: #7f8c8d; margin-top: 10px;">Sistema de administración</p>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username"><i class="fas fa-user"></i> Usuario:</label>
                <input type="text" id="username" name="username" required placeholder="Ingrese su usuario">
            </div>
            
            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> Contraseña:</label>
                <input type="password" id="password" name="password" required placeholder="Ingrese su contraseña">
            </div>
            
            <div class="form-group">
                <label for="genero"><i class="fas fa-venus-mars"></i> Género (para saludo):</label>
                <select id="genero" name="genero">
                    <option value="o">Masculino (Bienvenido)</option>
                    <option value="a">Femenino (Bienvenida)</option>
                    <option value="@">Neutro (Bienvenid@)</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 1.1rem;">
                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
            </button>
        </form>
    </div>
</body>
</html>