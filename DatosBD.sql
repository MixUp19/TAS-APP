select * from "Ciudad" where "EstadoID" = 25;

-- =============================================
-- 3. INSERTAR CADENAS DE FARMACIAS
-- =============================================
INSERT INTO "Cadena" ("CadenaID", "CadenaNombre") VALUES
('FAR001', 'Farmacias Guadalajara'),
('FAR002', 'Farmacias del Ahorro'),
('FAR003', 'Farmacias FarmaCon'),
('FAR004', 'Farmacias Similares');

-- =============================================
-- 4. INSERTAR PACIENTES
-- =============================================
INSERT INTO "Paciente" (
    "PacienteNombre",
    "PacienteApellidoPaterno",
    "PacienteApellidoMaterno",
    "PacienteTelefono",
    "PacienteCorreo",
    "PacienteFechaRegistro",
    "PacienteContrasena",
    "PacienteActivo",
    "PacienteIntentosFallidos",
    "PacienteFechaUltimoIntento"
) VALUES
('Joaquin', 'Rodriguez', 'Cázarez', '6674865619', 'jovalu2015@gmail.com', '2025-01-01', '$2y$12$k2YP/OSaERTdpWq/qo/bb..Ba3UnleMbcOBS6W3OUc7SK5f3VMIL2', false, 0, null),
('Diego', 'De la Rocha', 'Linarez', '4421234568', 'diego@email.com', '2025-01-01', '$2y$12$k2YP/OSaERTdpWq/qo/bb..Ba3UnleMbcOBS6W3OUc7SK5f3VMIL2', false, 0, null),
('Omar', 'Manjarez', 'Rodelo', '4421234569', 'omar@email.com', '2025-01-01', '$2y$12$k2YP/OSaERTdpWq/qo/bb..Ba3UnleMbcOBS6W3OUc7SK5f3VMIL2', false, 0, null);

-- =============================================
-- 5. INSERTAR MEDICAMENTOS
-- =============================================
INSERT INTO "Medicamentos" (
    "MedicamentoNombre",
    "MedicamentoPrecio",
    "MedicamentoCompuestoActivo",
    "MedicamentoUnidad",
    "MedicamentoContenido"
) VALUES
('Paracetamol', 25.50, 'Paracetamol', 'Tableta', '500mg'),
('Ibuprofeno', 45.00, 'Ibuprofeno', 'Tableta', '400mg'),
('Amoxicilina', 120.00, 'Amoxicilina', 'Cápsula', '500mg'),
('Losartán', 85.00, 'Losartán potásico', 'Tableta', '50mg'),
('Metformina', 95.00, 'Metformina', 'Tableta', '850mg'),
('Omeprazol', 65.00, 'Omeprazol', 'Cápsula', '20mg'),
('Atorvastatina', 150.00, 'Atorvastatina', 'Tableta', '20mg'),
('Captopril', 55.00, 'Captopril', 'Tableta', '25mg'),
('Diclofenaco', 38.00, 'Diclofenaco sódico', 'Tableta', '100mg'),
('Ranitidina', 42.00, 'Ranitidina', 'Tableta', '150mg'),
('Clonazepam', 78.00, 'Clonazepam', 'Tableta', '2mg'),
('Loratadina', 32.00, 'Loratadina', 'Tableta', '10mg'),
('Ciprofloxacino', 135.00, 'Ciprofloxacino', 'Tableta', '500mg'),
('Azitromicina', 145.00, 'Azitromicina', 'Tableta', '500mg'),
('Salbutamol', 89.00, 'Salbutamol', 'Inhalador', '100mcg'),
('Insulina Glargina', 850.00, 'Insulina glargina', 'Inyectable', '100UI/ml'),
('Enalapril', 62.00, 'Enalapril maleato', 'Tableta', '10mg'),
('Simvastatina', 98.00, 'Simvastatina', 'Tableta', '20mg'),
('Cetirizina', 28.00, 'Cetirizina', 'Tableta', '10mg'),
('Naproxeno', 48.00, 'Naproxeno sódico', 'Tableta', '250mg');

-- =============================================
-- 6. INSERTAR SUCURSALES
-- =============================================
INSERT INTO "Sucursal" (
    "SucursalID",
    "SucursalColonia",
    "SucursalCalle",
    "SucursalLatitud",
    "SucursalLongitud",
    "CiudadID",
    "CadenaID"
) VALUES
-- Farmacias Guadalajara
('SUC001', 'Diaz Ordaz', 'Av. Constituyentes Hilario Medina 2988', 24.7742044, -107.4278093, 25006, 'FAR001'),
('SUC002', 'Díaz Ordaz', 'Constituyente Francisco J. Mujica 2004', 24.775132, -107.4184819, 25006, 'FAR001'),
('SUC003', 'Gasolinera del Valle', 'Miguel Tamayo Espinoza de los Monteros 2350', 24.789915, -107.4433086, 25006, 'FAR001'),

-- Farmacias del Ahorro
('SUC001', 'Gasolinera del Valle', 'Blvd. Emiliano Zapata', 24.7809953,-107.4364193 , 25006, 'FAR002'),
('SUC002', 'PALERMO', 'Blvd. Pedro Infante 4646-PTE', 24.7887581, -107.4412036, 25006, 'FAR002'),
('SUC003', 'Colinas De San Miguel', 'Boulevard Ciudades Hermanas', 24.79194373, -107.39312429,  25006, 'FAR002'),

-- Farmacias Farmacon
('SUC001', 'Guadalupe', 'Nicolas Bravo 1607 Loc. 1 y 2', 24.79040176, -107.40043670, 25006, 'FAR003'),
('SUC002', 'Nakayama', 'Calz. de las Torres 3332 Pte-Local 18 y 19', 24.7534960, -107.42573823, 25005, 'FAR003'),

-- Farmacias similares
('SUC001', 'Plutarco Elías Calles', 'Calz. de las Torres 3273', 24.7542212, -107.42474460, 25006, 'FAR004'),
('SUC002', 'Infonavit Barrancos', 'Genaro Estrada 4528', 24.75456047, -107.42917319, 25006, 'FAR004'),
('SUC003', 'Independencia', 'Av. Manuel J. Clouthier 1068', 24.773154490, -107.40653368, 25006, 'FAR004'),
('SUC004', 'Díaz Ordaz', 'Constituyente Francisco J. Mujica', 24.77527219, -107.41821487, 25006, 'FAR004');

-- =============================================
-- 7. INSERTAR INVENTARIOS
-- =============================================
INSERT INTO "Inventario" (
    "SucursalID",
    "CadenaID",
    "MedicamentoID",
    "InventarioCantidad",
    "InventarioMaximo",
    "InventarioMinimo"
) VALUES
-- SUC001 (Farmacias Guadalajara - Centro Querétaro)
('SUC001', 'FAR001', 1, 150, 200, 20),
('SUC001', 'FAR001', 2, 100, 150, 15),
('SUC001', 'FAR001', 3, 80, 120, 10),
('SUC001', 'FAR001', 4, 60, 100, 10),
('SUC001', 'FAR001', 5, 90, 150, 15),
('SUC001', 'FAR001', 6, 70, 100, 10),
('SUC001', 'FAR001', 7, 45, 80, 8),
('SUC001', 'FAR001', 8, 55, 100, 10),
('SUC001', 'FAR001', 9, 65, 100, 10),
('SUC001', 'FAR001', 10, 75, 120, 12),

-- SUC002 (Farmacias Guadalajara - Juriquilla)
('SUC002', 'FAR001', 1, 120, 200, 20),
('SUC002', 'FAR001', 2, 85, 150, 15),
('SUC002', 'FAR001', 5, 110, 150, 15),
('SUC002', 'FAR001', 11, 40, 80, 8),
('SUC002', 'FAR001', 12, 95, 120, 12),
('SUC002', 'FAR001', 13, 30, 60, 5),
('SUC002', 'FAR001', 14, 25, 60, 5),
('SUC002', 'FAR001', 15, 20, 50, 5),

-- SUC003 (Farmacias Guadalajara - El Pueblito)
('SUC003', 'FAR001', 1, 180, 200, 20),
('SUC003', 'FAR001', 2, 130, 150, 15),
('SUC003', 'FAR001', 3, 95, 120, 10),
('SUC003', 'FAR001', 16, 15, 30, 3),
('SUC003', 'FAR001', 17, 70, 100, 10),
('SUC003', 'FAR001', 18, 50, 80, 8),
('SUC003', 'FAR001', 19, 105, 150, 15),
('SUC003', 'FAR001', 20, 80, 120, 12),

-- SUC004 (Farmacias del Ahorro - Centro Querétaro)
('SUC001', 'FAR002', 1, 140, 200, 20),
('SUC001', 'FAR002', 2, 110, 150, 15),
('SUC001', 'FAR002', 3, 70, 120, 10),
('SUC001', 'FAR002', 4, 55, 100, 10),
('SUC001', 'FAR002', 5, 85, 150, 15),
('SUC001', 'FAR002', 6, 65, 100, 10),
('SUC001', 'FAR002', 7, 40, 80, 8),
('SUC001', 'FAR002', 10, 60, 120, 12),

-- SUC005 (Farmacias del Ahorro - Carretas)
('SUC002', 'FAR002', 1, 160, 200, 20),
('SUC002', 'FAR002', 2, 95, 150, 15),
('SUC002', 'FAR002', 8, 50, 100, 10),
('SUC002', 'FAR002', 9, 70, 100, 10),
('SUC002', 'FAR002', 11, 35, 80, 8),
('SUC002', 'FAR002', 12, 90, 120, 12),
('SUC002', 'FAR002', 15, 18, 50, 5),

-- SUC006 (Farmacias del Ahorro - San Pablo)
('SUC003', 'FAR002', 1, 175, 200, 20),
('SUC003', 'FAR002', 3, 85, 120, 10),
('SUC003', 'FAR002', 13, 28, 60, 5),
('SUC003', 'FAR002', 14, 22, 60, 5),
('SUC003', 'FAR002', 17, 65, 100, 10),
('SUC003', 'FAR002', 19, 100, 150, 15),
('SUC003', 'FAR002', 20, 75, 120, 12),

-- SUC007 (Farmacias Benavides - Centro Monterrey)
('SUC001', 'FAR003', 1, 190, 200, 20),
('SUC001', 'FAR003', 2, 140, 150, 15),
('SUC001', 'FAR003', 3, 100, 120, 10),
('SUC001', 'FAR003', 4, 75, 100, 10),
('SUC001', 'FAR003', 5, 120, 150, 15),

-- SUC008 (Farmacias Benavides - San Pedro)
('SUC002', 'FAR003', 6, 80, 100, 10),
('SUC002', 'FAR003', 7, 50, 80, 8),
('SUC002', 'FAR003', 16, 12, 30, 3),
('SUC002', 'FAR003', 18, 55, 80, 8),

-- SUC009 (Farmacias San Pablo - Roma CDMX)
('SUC001', 'FAR004', 1, 165, 200, 20),
('SUC001', 'FAR004', 2, 125, 150, 15),
('SUC001', 'FAR004', 10, 70, 120, 12),
('SUC001', 'FAR004', 11, 38, 80, 8),
('SUC001', 'FAR004', 12, 88, 120, 12),

-- SUC010 (Farmacias San Pablo - Condesa)
('SUC002', 'FAR004', 13, 32, 60, 5),
('SUC002', 'FAR004', 14, 27, 60, 5),
('SUC002', 'FAR004', 15, 22, 50, 5),
('SUC002', 'FAR004', 20, 85, 120, 12),

-- SUC011 (Farmacias Similares - Centro Guadalajara)
('SUC003', 'FAR004', 1, 155, 200, 20),
('SUC003', 'FAR004', 2, 105, 150, 15),
('SUC003', 'FAR004', 5, 95, 150, 15),
('SUC003', 'FAR004', 17, 60, 100, 10),

-- SUC012 (Farmacias Similares - Zapopan)
('SUC004', 'FAR004', 3, 75, 120, 10),
('SUC004', 'FAR004', 19, 98, 150, 15),
('SUC004', 'FAR004', 20, 72, 120, 12);

-- =============================================
-- 8. INSERTAR TARJETAS
-- =============================================
INSERT INTO "Tarjeta" (
    "Tarjeta",
    "TarjetaNombreTitular",
    "TarjetaTipoTarjeta",
    "TarjetaFechaVencimiento",
    "TarjetaCVV",
    "PacienteID"
) VALUES
('4152313812345678', 'JUAN GARCIA LOPEZ', 'Débito', '2027-12-31', '123', 1),
('5234567890123456', 'MARIA MARTINEZ HERNANDEZ', 'Crédito', '2028-06-30', '456', 2),
('4111111111111111', 'PEDRO RODRIGUEZ SANCHEZ', 'Débito', '2026-09-30', '789', 3),
('5500000000000004', 'ANA LOPEZ RAMIREZ', 'Crédito', '2029-03-31', '321', 4),
('4917484589897107', 'CARLOS GONZALEZ TORRES', 'Débito', '2027-11-30', '654', 5),
('5425233430109903', 'MIGUEL SANCHEZ CRUZ', 'Crédito', '2028-08-31', '987', 7),
('4539578763621486', 'ROSA DIAZ MORALES', 'Débito', '2027-05-31', '147', 8);

-- =============================================
-- 9. INSERTAR ADMINISTRADORES DE SUCURSAL
-- =============================================
INSERT INTO "AdminSucursal" (
    "AdminNombre",
    "AdminApellidoPaterno",
    "AdminApellidoMaterno",
    "AdminCorreo",
    "AdminTelefono",
    "AdminContrasena",
    "AdminActivo",
    "SucursalID",
    "CadenaID"
) VALUES
('Roberto', 'Fernández', 'Gutiérrez', 'S1@farmaciasguadalajara.com', '4421111111', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5PL4a.h9vQVGK', false, 'SUC001', 'FAR001'),
('Patricia', 'Moreno', 'Vázquez', 'S2@farmaciasguadalajara.com', '4421111112', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5PL4a.h9vQVGK', false, 'SUC002', 'FAR001'),
('Luis', 'Jiménez', 'Castro', 'S3@farmaciasguadalajara.com', '4421111113', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5PL4a.h9vQVGK', false, 'SUC003', 'FAR001'),
('Carmen', 'Ruiz', 'Ortiz', 'S1@farmaciasdelahorro.com', '4421111114', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5PL4a.h9vQVGK', false, 'SUC001', 'FAR002'),
('Jorge', 'Herrera', 'Mendoza', 'S2@farmaciasdelahorro.com', '4421111115', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5PL4a.h9vQVGK', false, 'SUC002', 'FAR002'),
('Mónica', 'Ramírez', 'Silva', 'S3@farmaciasdelahorro.com', '4421111116', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5PL4a.h9vQVGK', false, 'SUC003', 'FAR002'),
('Fernando', 'Torres', 'Navarro', 'S1@farmacon.com', '8181111111', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5PL4a.h9vQVGK', false, 'SUC001', 'FAR003'),
('Gabriela', 'Flores', 'Reyes', 'S2@farmacon.com', '8181111112', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5PL4a.h9vQVGK', false, 'SUC002', 'FAR003'),
('Ricardo', 'Cruz', 'Domínguez', 'S1@farmaciassimilares.com', '5551111111', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5PL4a.h9vQVGK', false, 'SUC001', 'FAR004'),
('Adriana', 'Vargas', 'Medina', 'S2@farmaciassimilares.com', '5551111112', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5PL4a.h9vQVGK', false, 'SUC002', 'FAR004'),
('Daniel', 'Romero', 'Aguilar', 'S3@farmaciassimilares.com', '3331111111', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5PL4a.h9vQVGK', false, 'SUC003', 'FAR004'),
('Verónica', 'Gómez', 'Paredes', 'S4@farmaciassimilares.com', '3331111112', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5PL4a.h9vQVGK', false, 'SUC004', 'FAR004');

update "AdminSucursal" set "AdminContrasena" = '$2y$12$X7E6QRkDdM1h8TMToIcJdeRle/jgMcQuacyv1D/Vn3CLDVvxkOHx6';
-- =============================================
-- 10. INSERTAR RECETAS
-- =============================================
INSERT INTO "Receta" (
    "CedulaDoctor",
    "RecetaFecha",
    "PacienteID",
    "CadenaID",
    "SucursalID",
    "RecetaEstado"
) VALUES
('1234567', '2024-11-15', 1, 'FAR001', 'SUC001', 'Procesada'),
('1234568', '2024-11-20', 2, 'FAR001', 'SUC002', 'Procesada'),
('1234569', '2024-11-22', 3, 'FAR002', 'SUC004', 'Pendiente'),
('1234570', '2024-11-25', 4, 'FAR002', 'SUC005', 'Procesada'),
('1234571', '2024-11-28', 5, 'FAR003', 'SUC007', 'Pendiente'),
('1234572', '2024-11-30', 1, 'FAR001', 'SUC003', 'Procesada'),
('1234573', '2024-12-01', 7, 'FAR004', 'SUC009', 'Pendiente'),
('1234574', '2024-12-02', 8, 'FAR005', 'SUC011', 'Procesada');

-- =============================================
-- 11. INSERTAR LÍNEAS DE RECETA
-- =============================================
INSERT INTO "LINEA_RECETA" (
    "RecetaFolio",
    "MedicamentoID",
    "LRCantidad",
    "LRPrecio"
) VALUES
-- Receta 1
(1, 1, 2, 25.50),
(1, 3, 1, 120.00),
(1, 6, 1, 65.00),

-- Receta 2
(2, 2, 1, 45.00),
(2, 5, 2, 95.00),
(2, 12, 1, 32.00),

-- Receta 3
(3, 1, 3, 25.50),
(3, 4, 1, 85.00),

-- Receta 4
(4, 8, 1, 55.00),
(4, 9, 2, 38.00),

-- Receta 5
(5, 1, 1, 25.50),
(5, 2, 1, 45.00),
(5, 5, 1, 95.00),

-- Receta 6
(6, 1, 2, 25.50),
(6, 17, 1, 62.00),
(6, 19, 1, 28.00),

-- Receta 7
(7, 10, 1, 42.00),
(7, 11, 1, 78.00),

-- Receta 8
(8, 1, 2, 25.50),
(8, 2, 1, 45.00),
(8, 5, 1, 95.00);

-- =============================================
-- 12. INSERTAR DETALLES DE LÍNEA DE RECETA
-- =============================================
INSERT INTO "Detalle_Linea_Receta" (
    "RecetaFolio",
    "MedicamentoID",
    "SucursalID",
    "CadenaID",
    "DLRCantidad",
    "DLREstatus"
) VALUES
-- Detalles Receta 1
(1, 1, 'SUC001', 'FAR001', 2, 'Entregado'),
(1, 3, 'SUC001', 'FAR001', 1, 'Entregado'),
(1, 6, 'SUC001', 'FAR001', 1, 'Entregado'),

-- Detalles Receta 2
(2, 2, 'SUC002', 'FAR001', 1, 'Entregado'),
(2, 5, 'SUC002', 'FAR001', 2, 'Entregado'),
(2, 12, 'SUC002', 'FAR001', 1, 'Entregado'),

-- Detalles Receta 3
(3, 1, 'SUC004', 'FAR002', 3, 'Pendiente'),
(3, 4, 'SUC004', 'FAR002', 1, 'Pendiente'),

-- Detalles Receta 4
(4, 8, 'SUC005', 'FAR002', 1, 'Entregado'),
(4, 9, 'SUC005', 'FAR002', 2, 'Entregado'),

-- Detalles Receta 5
(5, 1, 'SUC007', 'FAR003', 1, 'Pendiente'),
(5, 2, 'SUC007', 'FAR003', 1, 'Pendiente'),
(5, 5, 'SUC007', 'FAR003', 1, 'Pendiente'),

-- Detalles Receta 6
(6, 1, 'SUC003', 'FAR001', 2, 'Entregado'),
(6, 17, 'SUC003', 'FAR001', 1, 'Entregado'),
(6, 19, 'SUC003', 'FAR001', 1, 'Entregado'),

-- Detalles Receta 7
(7, 10, 'SUC009', 'FAR004', 1, 'Pendiente'),
(7, 11, 'SUC009', 'FAR004', 1, 'Pendiente'),

-- Detalles Receta 8
(8, 1, 'SUC011', 'FAR005', 2, 'Entregado'),
(8, 2, 'SUC011', 'FAR005', 1, 'Entregado'),
(8, 5, 'SUC011', 'FAR005', 1, 'Entregado');

-- =============================================
-- FIN DE DATOS DE PRUEBA
-- =============================================
select * from "Receta";
select * from "LINEA_RECETA";
update "Detalle_Linea_Receta" set "DLREstatus" = 'Recogida' where "RecetaFolio" = 1;

select * from "Detalle_Linea_Receta";
