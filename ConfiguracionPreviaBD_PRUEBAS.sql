-- 1. Asegurarnos de que el usuario sa esté activo
ALTER LOGIN sa ENABLE;
GO
-- 2. Cambiar la contraseña a algo que JMeter pueda usar (ejemplo: Prueba123)
ALTER LOGIN sa WITH PASSWORD = 'PonerContraseñaAqui';
GO

-- ==========================================================
-- El servidor ya es accesible para las pruebas de rendimiento.
-- ==========================================================