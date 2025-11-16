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
                          CONSTRAINT fk_receta_cadena FOREIGN KEY ("CadenaID")
                              REFERENCES "Sucursal" ("CadenaID") ON DELETE RESTRICT,
                          CONSTRAINT fk_receta_sucursal FOREIGN KEY ("SucursalID")
                              REFERENCES "Sucursal" ("SucursalID") ON DELETE RESTRICT
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
                              CONSTRAINT fk_inventario_sucursal FOREIGN KEY ("SucursalID")
                                  REFERENCES "Sucursal" ("SucursalID") ON DELETE RESTRICT,
                              CONSTRAINT fk_inventario_cadena FOREIGN KEY ("CadenaID")
                                  REFERENCES "Sucursal" ("CadenaID") ON DELETE RESTRICT,
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
                                        CONSTRAINT fk_dlr_sucursal FOREIGN KEY ("SucursalID")
                                            REFERENCES "Sucursal" ("SucursalID") ON DELETE RESTRICT,
                                        CONSTRAINT fk_dlr_cadena FOREIGN KEY ("CadenaID")
                                            REFERENCES "Sucursal" ("CadenaID") ON DELETE RESTRICT
);
