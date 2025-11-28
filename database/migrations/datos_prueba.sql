-- ================================================================
-- DATOS DE PRUEBA CON HASHES REALES
-- Sistema de Autenticación - Te Acerco Salud
-- ================================================================

-- IMPORTANTE: Antes de ejecutar estos inserts, verifica que tengas
-- sucursales existentes en tu base de datos. Ejecuta:
-- SELECT "SucursalID", "CadenaID" FROM "Sucursal" LIMIT 5;

-- ================================================================
-- EJEMPLO: Insertar un Administrador de Prueba
-- ================================================================
-- Usuario: admin@farmacia.com
-- Contraseña: Admin123
-- Hash bcrypt generado

/*
-- PASO 1: Primero encuentra una sucursal válida
SELECT "SucursalID", "CadenaID", "SucursalColonia"
FROM "Sucursal"
LIMIT 1;

-- PASO 2: Usa esos valores en el siguiente INSERT
INSERT INTO "AdminSucursal" (
    "AdminNombre",
    "AdminApellidoPaterno",
    "AdminApellidoMaterno",
    "AdminCorreo",
    "AdminTelefono",
    "AdminContrasena",
    "SucursalID",
    "CadenaID"
)
VALUES (
    'Juan',
    'Pérez',
    'García',
    'admin@farmacia.com',
    '6181234567',
    '$2y$12$DjMrS9rqHVBwxNAcNhlKpuXeOh.uqv1Ut.GHtzcgbwn74RHYcs93C',
    'SUC001',  -- Reemplaza con valor del PASO 1
    'FAR001'     -- Reemplaza con valor del PASO 1
);
*/

-- ================================================================
-- EJEMPLO: Insertar un Paciente de Prueba
-- ================================================================
-- Usuario: paciente@test.com
-- Contraseña: Paciente123
-- Hash bcrypt generado

INSERT INTO "Paciente" (
    "PacienteNombre",
    "PacienteApellidoPaterno",
    "PacienteApellidoMaterno",
    "PacienteCorreo",
    "PacienteTelefono",
    "PacienteFechaRegistro",
    "PacienteContrasena",
    "PacienteActivo",
    "PacienteIntentosFallidos"
)
VALUES (
    'María',
    'López',
    'Ramírez',
    'paciente@test.com',
    '6189876543',
    CURRENT_DATE,
    '$2y$12$DjMrS9rqHVBwxNAcNhlKpuXeOh.uqv1Ut.GHtzcgbwn74RHYcs93C',
    false,
    0
);

-- ================================================================
-- VERIFICAR LOS DATOS INSERTADOS
-- ================================================================

-- Ver pacientes de prueba
SELECT "PacienteID", "PacienteNombre", "PacienteApellidoPaterno", "PacienteCorreo", "PacienteActivo"
FROM "Paciente"
WHERE "PacienteCorreo" = 'paciente@test.com';

-- Ver administradores (después de insertar)
-- SELECT "AdminNumeroEmpleado", "AdminNombre", "AdminApellidoPaterno", "AdminCorreo", "SucursalID", "CadenaID"
-- FROM "AdminSucursal"
-- WHERE "AdminCorreo" = 'admin@farmacia.com';

select * from "AdminSucursal"
