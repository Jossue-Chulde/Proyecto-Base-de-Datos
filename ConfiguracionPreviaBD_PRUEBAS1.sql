-- CONFIGURACIÓN DE ACCESO PARA PRUEBAS DE CARGA (JMeter)
-- ==========================================================

-- 1. Habilitar Autenticación Mixta (Windows y SQL Server) en el Registro del Sistema
-- Sin esto, el servidor rechaza cualquier conexión que no sea de Windows.

USE [master]
GO
EXEC xp_instance_regwrite N'HKEY_LOCAL_MACHINE', 
    N'Software\Microsoft\MSSQLServer\MSSQLServer', 
    N'LoginMode', REG_DWORD, 2;
GO
ALTER LOGIN sa WITH PASSWORD = 'PonerContraseñaAqui';
GO
ALTER LOGIN sa ENABLE;
GO