<?php
session_start();

// Configuración para SQL Server con Autenticación Windows
$serverName = "localhost\\SQLEXPRESS10"; // O ".\SQLEXPRESS10" para instancia local
$connectionInfo = array(
    "Database" => "GestionTiendaTecnologia",
    "Authentication" => "ActiveDirectoryPassword", // Para Azure AD, pero para Windows usamos:
    "TrustServerCertificate" => true,
    "CharacterSet" => "UTF-8",
    "ReturnDatesAsStrings" => true,
    "LoginTimeout" => 30
);

// Opción 1: Conexión con autenticación Windows (Trusted Connection)+
$conn = sqlsrv_connect($serverName, $connectionInfo);

// Opción alternativa si la anterior falla:
if ($conn === false) {
    // Intentar con conexión trusted
    $connectionInfo = array(
        "Database" => "GestionTiendaTecnologia",
        "TrustServerCertificate" => true,
        "CharacterSet" => "UTF-8",
        "ReturnDatesAsStrings" => true
    );
    
    $conn = sqlsrv_connect($serverName, $connectionInfo);
}

// Verificar conexión
if ($conn === false) {
    $errors = sqlsrv_errors();
    die("Error de conexión a SQL Server: <pre>" . print_r($errors, true) . "</pre>");
}

// Función para verificar si estamos conectados
function verificarConexion() {
    global $conn;
    if ($conn === false) {
        return false;
    }
    
    $sql = "SELECT 1 as test";
    $stmt = sqlsrv_query($conn, $sql);
    
    if ($stmt === false) {
        return false;
    }
    
    sqlsrv_free_stmt($stmt);
    return true;
}

// Función para ejecutar consultas seguras
function ejecutarConsulta($sql, $params = array()) {
    global $conn;
    
    $stmt = sqlsrv_prepare($conn, $sql, $params);
    if (!$stmt) {
        $errors = sqlsrv_errors();
        error_log("Error en preparación SQL: " . print_r($errors, true));
        return false;
    }
    
    if (!sqlsrv_execute($stmt)) {
        $errors = sqlsrv_errors();
        error_log("Error en ejecución SQL: " . print_r($errors, true));
        return false;
    }
    
    return $stmt;
}

// Función para obtener resultados como array
function obtenerResultados($stmt) {
    $resultados = array();
    
    if ($stmt === false) {
        return $resultados;
    }
    
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $resultados[] = $row;
    }
    
    return $resultados;
}

// Función para obtener un solo resultado
function obtenerUnResultado($stmt) {
    if ($stmt === false) {
        return null;
    }
    
    if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        return $row;
    }
    
    return null;
}

// Función para sanitizar entrada
function sanitizar($dato) {
    if (is_null($dato)) {
        return '';
    }
    return htmlspecialchars(strip_tags(trim($dato)), ENT_QUOTES, 'UTF-8');
}

// Función para formatear fecha SQL
function formatearFechaSQL($fecha) {
    if ($fecha instanceof DateTime) {
        return $fecha->format('Y-m-d H:i:s');
    }
    return date('Y-m-d H:i:s', strtotime($fecha));
}
?>