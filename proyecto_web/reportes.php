<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes - Gestión Tienda</title>
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <h1><i class="fas fa-chart-bar"></i> Reportes y Estadísticas</h1>
            <a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i> Volver</a>
        </div>

        <div class="options-grid">
            <div class="option-card">
                <h3><i class="fas fa-chart-pie"></i> Ventas por Mes</h3>
                <canvas id="ventasChart" height="200"></canvas>
            </div>
            
            <div class="option-card">
                <h3><i class="fas fa-chart-line"></i> Productos Más Vendidos</h3>
                <canvas id="productosChart" height="200"></canvas>
            </div>
            
            <div class="option-card">
                <h3><i class="fas fa-users"></i> Distribución de Usuarios</h3>
                <canvas id="usuariosChart" height="200"></canvas>
            </div>
            
            <div class="option-card">
                <h3><i class="fas fa-box"></i> Nivel de Inventario</h3>
                <canvas id="inventarioChart" height="200"></canvas>
            </div>
            
            <div class="option-card">
                <h3><i class="fas fa-file-excel"></i> Reportes Disponibles</h3>
                <ul style="list-style: none; padding: 0; margin: 20px 0;">
                    <li style="margin-bottom: 15px; padding: 10px; background: #f8f9fa; border-radius: 5px;">
                        <i class="fas fa-file-pdf" style="color: #e74c3c;"></i> 
                        <a href="#" style="margin-left: 10px;">Reporte de Ventas Mensual</a>
                    </li>
                    <li style="margin-bottom: 15px; padding: 10px; background: #f8f9fa; border-radius: 5px;">
                        <i class="fas fa-file-excel" style="color: #2ecc71;"></i> 
                        <a href="#" style="margin-left: 10px;">Inventario Actual</a>
                    </li>
                    <li style="margin-bottom: 15px; padding: 10px; background: #f8f9fa; border-radius: 5px;">
                        <i class="fas fa-file-csv" style="color: #3498db;"></i> 
                        <a href="#" style="margin-left: 10px;">Clientes Registrados</a>
                    </li>
                </ul>
            </div>
            
            <div class="option-card">
                <h3><i class="fas fa-cogs"></i> Generar Reporte</h3>
                <div class="form-group">
                    <label for="tipoReporte">Tipo de Reporte:</label>
                    <select id="tipoReporte" class="form-control">
                        <option value="ventas">Ventas</option>
                        <option value="inventario">Inventario</option>
                        <option value="clientes">Clientes</option>
                        <option value="usuarios">Usuarios</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fechaInicio">Fecha Inicio:</label>
                    <input type="date" id="fechaInicio" class="form-control">
                </div>
                <div class="form-group">
                    <label for="fechaFin">Fecha Fin:</label>
                    <input type="date" id="fechaFin" class="form-control">
                </div>
                <button class="btn btn-primary" onclick="generarReporte()">
                    <i class="fas fa-download"></i> Generar Reporte
                </button>
            </div>
        </div>
    </div>

    <script>
        // Gráfico de ventas por mes
        const ventasCtx = document.getElementById('ventasChart').getContext('2d');
        new Chart(ventasCtx, {
            type: 'line',
            data: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                datasets: [{
                    label: 'Ventas ($)',
                    data: [12000, 19000, 15000, 25000, 22000, 30000],
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true }
                }
            }
        });

        // Gráfico de productos más vendidos
        const productosCtx = document.getElementById('productosChart').getContext('2d');
        new Chart(productosCtx, {
            type: 'bar',
            data: {
                labels: ['Laptop', 'Mouse', 'Teclado', 'Monitor', 'Impresora'],
                datasets: [{
                    label: 'Unidades Vendidas',
                    data: [45, 120, 78, 56, 34],
                    backgroundColor: [
                        '#2ecc71', '#3498db', '#f39c12', '#e74c3c', '#9b59b6'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Gráfico de distribución de usuarios
        const usuariosCtx = document.getElementById('usuariosChart').getContext('2d');
        new Chart(usuariosCtx, {
            type: 'doughnut',
            data: {
                labels: ['Administradores', 'Vendedores', 'Usuarios'],
                datasets: [{
                    data: [3, 8, 25],
                    backgroundColor: [
                        '#e74c3c',
                        '#3498db',
                        '#2ecc71'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // Gráfico de nivel de inventario
        const inventarioCtx = document.getElementById('inventarioChart').getContext('2d');
        new Chart(inventarioCtx, {
            type: 'radar',
            data: {
                labels: ['Electrónica', 'Accesorios', 'Software', 'Muebles', 'Ropa'],
                datasets: [{
                    label: 'Nivel de Stock',
                    data: [85, 60, 45, 70, 30],
                    backgroundColor: 'rgba(52, 152, 219, 0.2)',
                    borderColor: '#3498db'
                }]
            },
            options: {
                responsive: true
            }
        });

        function generarReporte() {
            const tipo = document.getElementById('tipoReporte').value;
            const inicio = document.getElementById('fechaInicio').value;
            const fin = document.getElementById('fechaFin').value;
            
            alert(`Generando reporte de ${tipo}\nPeríodo: ${inicio} a ${fin}\nDescargando...`);
        }

        document.querySelector('.logout-link').addEventListener('click', function(e) {
            if (!confirm('¿Está seguro de cerrar sesión?')) {
                e.preventDefault();
            }
        });
    </script>
    <style>
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 15px;
        }
    </style>
</body>
</html>