--
-- Script de Creación de Base de Datos para el Diagrama Relacional de Farmacia (Versión 2.0)
--

-- Se recomienda crear la base de datos y conectarse a ella antes de ejecutar estas sentencias.
-- Por ejemplo: CREATE DATABASE "farmacia_v2_db"; \c farmacia_v2_db

-------------------------------------------
-- 1. Tablas Sin Dependencias Foráneas
-------------------------------------------

CREATE TABLE "Estado" (
                          "EstadoID" INT PRIMARY KEY,
                          "EstadoNombre" VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE "Cadena" (
                          "CadenaID" VARCHAR(10) PRIMARY KEY,
                          "CadenaNombre" VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE "Paciente" (
                            "PacienteID" SERIAL PRIMARY KEY,
                            "PacienteNombre" VARCHAR(100) NOT NULL,
                            "PacienteApellidoPaterno" VARCHAR(100) NOT NULL,
                            "PacienteApellidoMaterno" VARCHAR(100),
                            "PacienteTelefono" VARCHAR(15),
                            "PacienteCorreo" VARCHAR(100) UNIQUE,
                            "PacienteFechaRegistro" DATE NOT NULL
);

CREATE TABLE "Medicamentos" (
                                "MedicamentoID" SERIAL PRIMARY KEY,
                                "MedicamentoNombre" VARCHAR(100) NOT NULL,
                                "MedicamentoPrecio" NUMERIC(10, 2) NOT NULL,
                                "MedicamentoCompuestoActivo" VARCHAR(100),
                                "MedicamentoUnidad" VARCHAR(50),
                                "MedicamentoContenido" VARCHAR(100),
                                UNIQUE ("MedicamentoNombre", "MedicamentoCompuestoActivo")
);

-------------------------------------------
-- 2. Tablas con Dependencias de Nivel 1
-------------------------------------------

CREATE TABLE "Ciudad" (
                          "CiudadID" INT PRIMARY KEY,
                          "CiudadNombre" VARCHAR(100) NOT NULL,
                          "EstadoID" INT NOT NULL,
                          CONSTRAINT fk_ciudad_estado FOREIGN KEY ("EstadoID")
                              REFERENCES "Estado" ("EstadoID") ON DELETE RESTRICT,
                          UNIQUE ("CiudadNombre", "EstadoID")
);

CREATE TABLE "Tarjeta" (
                           "Tarjeta" CHAR(16) PRIMARY KEY,
                           "TarjetaNombreTitular" VARCHAR(100) NOT NULL,
                           "TarjetaTipoTarjeta" VARCHAR(50),
                           "TarjetaFechaVencimiento" DATE NOT NULL,
                           "TarjetaCVV" VARCHAR(4) NOT NULL,
                           "PacienteID" INT NOT NULL,
                           CONSTRAINT fk_tarjeta_paciente FOREIGN KEY ("PacienteID")
                               REFERENCES "Paciente" ("PacienteID") ON DELETE CASCADE
);

-------------------------------------------
-- 3. Tablas con Dependencias de Nivel 2
-------------------------------------------

CREATE TABLE "Sucursal" (
                            "SucursalID" VARCHAR(10),
                            "SucursalColonia" VARCHAR(255),
                            "SucursalCalle" VARCHAR(255),
                            "SucursalLatitud" NUMERIC(10, 6),
                            "SucursalLongitud" NUMERIC(10, 6),
                            "CiudadID" INT NOT NULL,
                            "CadenaID" VARCHAR(10) NOT NULL,
                            PRIMARY KEY ("SucursalID", "CadenaID"),
                            CONSTRAINT fk_sucursal_ciudad FOREIGN KEY ("CiudadID")
                                REFERENCES "Ciudad" ("CiudadID") ON DELETE RESTRICT,
                            CONSTRAINT fk_sucursal_cadena FOREIGN KEY ("CadenaID")
                                REFERENCES "Cadena" ("CadenaID") ON DELETE RESTRICT
);

CREATE TABLE "Receta" (
                          "RecetaFolio" SERIAL PRIMARY KEY, -- Especificado como SERIAL
                          "CedulaDoctor" VARCHAR(20) NOT NULL,
                          "RecetaFecha" DATE NOT NULL,
                          "PacienteID" INT NOT NULL,
                          "CadenaID" VARCHAR(10) NOT NULL,
                          "SucursalID" VARCHAR(10) NOT NULL,
                          CONSTRAINT fk_receta_paciente FOREIGN KEY ("PacienteID")
                              REFERENCES "Paciente" ("PacienteID") ON DELETE RESTRICT,
                          CONSTRAINT fk_receta_cadena FOREIGN KEY ("SucursalID","CadenaID")
                              REFERENCES "Sucursal" ("SucursalID","CadenaID") ON DELETE RESTRICT
);

-------------------------------------------
-- 4. Tablas de Relación (Muchos a Muchos)
-------------------------------------------

CREATE TABLE "Inventario" (
                              "SucursalID" VARCHAR(10) NOT NULL,
                              "CadenaID" VARCHAR(10) NOT NULL,
                              "MedicamentoID" INT NOT NULL,
                              "InventarioCantidad" INT NOT NULL CHECK ("InventarioCantidad" >= 0),
                              "InventarioMaximo" INT,
                              "InventarioMinimo" INT,
                              PRIMARY KEY ("SucursalID", "CadenaID", "MedicamentoID"),
                              CONSTRAINT fk_inventario_sucursal FOREIGN KEY ("SucursalID","CadenaID")
                                  REFERENCES "Sucursal" ("SucursalID","CadenaID") ON DELETE RESTRICT,
                              CONSTRAINT fk_inventario_medicamento FOREIGN KEY ("MedicamentoID")
                                  REFERENCES "Medicamentos" ("MedicamentoID") ON DELETE RESTRICT
);

CREATE TABLE "LINEA_RECETA" (
                                "RecetaFolio" INT NOT NULL,
                                "MedicamentoID" INT NOT NULL,
                                "LRCantidad" INT NOT NULL CHECK ("LRCantidad" > 0),
                                "LRPrecio" NUMERIC(10, 2) NOT NULL,
                                PRIMARY KEY ("RecetaFolio", "MedicamentoID"),
                                CONSTRAINT fk_lr_receta FOREIGN KEY ("RecetaFolio")
                                    REFERENCES "Receta" ("RecetaFolio") ON DELETE CASCADE,
                                CONSTRAINT fk_lr_medicamento FOREIGN KEY ("MedicamentoID")
                                    REFERENCES "Medicamentos" ("MedicamentoID") ON DELETE RESTRICT
);

CREATE TABLE "Detalle_Linea_Receta" (
                                        "RecetaFolio" INT NOT NULL,
                                        "MedicamentoID" INT NOT NULL,
                                        "SucursalID" VARCHAR(10) NOT NULL,
                                        "CadenaID" VARCHAR(10) NOT NULL,
                                        "DLRCantidad" INT NOT NULL CHECK ("DLRCantidad" > 0),
                                        "DLREstatus" VARCHAR(50),
                                        PRIMARY KEY ("RecetaFolio", "MedicamentoID", "SucursalID", "CadenaID"),
                                        CONSTRAINT fk_dlr_linea_receta FOREIGN KEY ("RecetaFolio", "MedicamentoID")
                                            REFERENCES "LINEA_RECETA" ("RecetaFolio", "MedicamentoID") ON DELETE CASCADE,
                                        CONSTRAINT fk_dlr_sucursal FOREIGN KEY ("SucursalID", "CadenaID")
                                            REFERENCES "Sucursal" ("SucursalID","CadenaID") ON DELETE RESTRICT
);

alter table "Paciente" add column "PacienteContrasena" VARCHAR(100);
alter table "Paciente" add column "PacienteActivo" BOOLEAN DEFAULT false;
alter table "Paciente" add column "PacienteIntentosFallidos" INT DEFAULT 0;
alter table "Paciente" add column "PacienteFechaUltimoIntento" DATE;

alter table "Sucursal" alter column "SucursalLatitud" type numeric(11,8);
alter table "Sucursal" alter column "SucursalLongitud" type numeric(11,8);

Select * from "Estado";
Select * from "Ciudad" where "EstadoID" = 25;

INSERT INTO "Medicamentos" ("MedicamentoNombre", "MedicamentoPrecio", "MedicamentoCompuestoActivo", "MedicamentoUnidad", "MedicamentoContenido") VALUES
('Paracetamol', 25.50, 'Paracetamol', 'mg', '500 mg, 10 tabletas'),
('Ibuprofeno', 30.00, 'Ibuprofeno', 'mg', '400 mg, 10 tabletas'),
('Amoxicilina', 80.75, 'Amoxicilina', 'mg', '500 mg, 12 cápsulas'),
('Loratadina', 45.00, 'Loratadina', 'mg', '10 mg, 10 tabletas'),
('Omeprazol', 60.20, 'Omeprazol', 'mg', '20 mg, 14 cápsulas'),
('Salbutamol Inhalador', 150.00, 'Salbutamol', 'mcg', '100 mcg/dosis, 200 dosis'),
('Metformina', 75.50, 'Metformina', 'mg', '850 mg, 30 tabletas'),
('Losartán', 95.00, 'Losartán potásico', 'mg', '50 mg, 30 tabletas'),
('Aspirina', 20.00, 'Ácido acetilsalicílico', 'mg', '500 mg, 20 tabletas'),
('Diclofenaco Gel', 70.00, 'Diclofenaco sódico', 'g', '60 g'),
('Clonazepam', 120.00, 'Clonazepam', 'mg', '2 mg, 30 tabletas'),
('Ciprofloxacino', 110.50, 'Ciprofloxacino', 'mg', '500 mg, 10 tabletas'),
('Naproxeno', 40.00, 'Naproxeno sódico', 'mg', '550 mg, 10 tabletas'),
('Cetirizina', 55.00, 'Diclorhidrato de cetirizina', 'mg', '10 mg, 10 tabletas'),
('Atorvastatina', 180.00, 'Atorvastatina', 'mg', '20 mg, 30 tabletas');

select * from "Sucursal";

select * from "Receta";

ALTER TABLE "Receta" ADD COLUMN "RecetaEstado" varchar(10) default 'Pendiente';
select * from "Paciente";

select * from "Receta";
select * from "LINEA_RECETA";

update "Paciente" set "PacienteActivo" = false;
update "AdminSucursal" set "AdminActivo" = false;
