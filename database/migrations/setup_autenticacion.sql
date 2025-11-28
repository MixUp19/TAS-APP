-- ================================================================
-- Script SQL para crear tabla AdminSucursal y datos de prueba
-- Sistema de Autenticación - Te Acerco Salud
-- ================================================================

-- 1. Crear tabla AdminSucursal
CREATE TABLE IF NOT EXISTS "AdminSucursal" (
    "AdminNumeroEmpleado" SERIAL PRIMARY KEY,
    "AdminNombre" VARCHAR(100) NOT NULL,
    "AdminApellidoPaterno" VARCHAR(100) NOT NULL,
    "AdminApellidoMaterno" VARCHAR(100),
    "AdminCorreo" VARCHAR(100) UNIQUE NOT NULL,
    "AdminTelefono" VARCHAR(15),
    "AdminContrasena" VARCHAR(255) NOT NULL,
    "AdminActivo" BOOLEAN DEFAULT false,
    "AdminIntentosFallidos" INT DEFAULT 0,
    "AdminFechaUltimoIntento" TIMESTAMP,
    "SucursalID" VARCHAR(10) NOT NULL,
    "CadenaID" VARCHAR(10) NOT NULL,
    CONSTRAINT fk_admin_sucursal FOREIGN KEY ("SucursalID", "CadenaID")
        REFERENCES "Sucursal" ("SucursalID", "CadenaID") ON DELETE RESTRICT
);

-- ================================================================
-- 2. INSTRUCCIONES PARA INSERTAR DATOS DE PRUEBA
-- ================================================================

-- IMPORTANTE: Antes de ejecutar los siguientes inserts, necesitas:
-- 1. Verificar que exista al menos una Sucursal en tu base de datos
-- 2. Reemplazar 'TU_SUCURSAL_ID' y 'TU_CADENA_ID' con valores válidos

-- Para ver las sucursales disponibles:
-- SELECT "SucursalID", "CadenaID" FROM "Sucursal" LIMIT 5;

-- ================================================================
-- 3. DATOS DE PRUEBA
-- ================================================================

-- A) Insertar un Administrador de Prueba
-- Usuario: admin@farmacia.com
-- Contraseña: Admin123
-- Hash generado con: password_hash('Admin123', PASSWORD_BCRYPT)

/*
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
    '$2y$12$LQv3c1yycEPICnCpGfCOWOe4qXdOHp4FbWjVUkTgfJ5kL5J0uGEw2',
    'TU_SUCURSAL_ID',  -- REEMPLAZAR con un SucursalID válido
    'TU_CADENA_ID'     -- REEMPLAZAR con un CadenaID válido
);
*/

-- B) Insertar un Paciente de Prueba
-- Usuario: paciente@test.com
-- Contraseña: Paciente123
-- Hash generado con: password_hash('Paciente123', PASSWORD_BCRYPT)

/*
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
    '$2y$12$LQv3c1yycEPICnCpGfCOWOe4qXdOHp4FbWjVUkTgfJ5kL5J0uGEw2',
    false,
    0
);
*/

-- ================================================================
-- 4. VERIFICACIÓN
-- ================================================================

-- Verificar que la tabla se creó correctamente
SELECT table_name, column_name, data_type
FROM information_schema.columns
WHERE table_name = 'AdminSucursal'
ORDER BY ordinal_position;

-- Ver administradores registrados
-- SELECT "AdminNumeroEmpleado", "AdminNombre", "AdminCorreo", "SucursalID", "CadenaID"
-- FROM "AdminSucursal";

-- Ver pacientes registrados
-- SELECT "PacienteID", "PacienteNombre", "PacienteCorreo", "PacienteActivo"
-- FROM "Paciente"
-- WHERE "PacienteCorreo" LIKE '%test%';

-- ================================================================
-- 5. NOTAS IMPORTANTES
-- ================================================================

-- Las contraseñas hasheadas mostradas arriba son válidas para:
--   - Admin123
--   - Paciente123
--
-- Para generar nuevos hashes, puedes usar el script incluido:
--   php hash_password.php "TuContraseña"
--
-- O en línea de comandos de PostgreSQL:
--   No es posible hashear con bcrypt directamente en PostgreSQL
--   Siempre usa PHP o el script hash_password.php

-- ================================================================
-- FIN DEL SCRIPT
-- ================================================================

