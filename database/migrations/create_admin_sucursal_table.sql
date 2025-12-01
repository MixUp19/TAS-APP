-- Crear tabla AdminSucursal
CREATE TABLE "AdminSucursal" (
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

-- Insertar un admin de prueba (contraseña: Admin123)
-- INSERT INTO "AdminSucursal" ("AdminNombre", "AdminApellidoPaterno", "AdminApellidoMaterno", "AdminCorreo", "AdminTelefono", "AdminContrasena", "SucursalID", "CadenaID")
-- VALUES ('Juan', 'Pérez', 'García', 'admin@farmacia.com', '6181234567', '$2y$12$LQv3c1yycEPICnCpGfCOWOe4qXdOHp4FbWjVUkTgfJ5kL5J0uGEw2', 'SUC001', 'CAD001');

