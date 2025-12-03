create table migrations
(
    id        serial
        primary key,
    migration varchar(255) not null,
    batch     integer      not null
);

alter table migrations
    owner to avnadmin;

create table users
(
    id                bigserial
        primary key,
    name              varchar(255) not null,
    email             varchar(255) not null
        constraint users_email_unique
            unique,
    email_verified_at timestamp(0),
    password          varchar(255) not null,
    remember_token    varchar(100),
    created_at        timestamp(0),
    updated_at        timestamp(0)
);

alter table users
    owner to avnadmin;

create table password_reset_tokens
(
    email      varchar(255) not null
        primary key,
    token      varchar(255) not null,
    created_at timestamp(0)
);

alter table password_reset_tokens
    owner to avnadmin;

create table sessions
(
    id            varchar(255) not null
        primary key,
    user_id       bigint,
    ip_address    varchar(45),
    user_agent    text,
    payload       text         not null,
    last_activity integer      not null
);

alter table sessions
    owner to avnadmin;

create index sessions_user_id_index
    on sessions (user_id);

create index sessions_last_activity_index
    on sessions (last_activity);

create table cache
(
    key        varchar(255) not null
        primary key,
    value      text         not null,
    expiration integer      not null
);

alter table cache
    owner to avnadmin;

create table cache_locks
(
    key        varchar(255) not null
        primary key,
    owner      varchar(255) not null,
    expiration integer      not null
);

alter table cache_locks
    owner to avnadmin;

create table jobs
(
    id           bigserial
        primary key,
    queue        varchar(255) not null,
    payload      text         not null,
    attempts     smallint     not null,
    reserved_at  integer,
    available_at integer      not null,
    created_at   integer      not null
);

alter table jobs
    owner to avnadmin;

create index jobs_queue_index
    on jobs (queue);

create table job_batches
(
    id             varchar(255) not null
        primary key,
    name           varchar(255) not null,
    total_jobs     integer      not null,
    pending_jobs   integer      not null,
    failed_jobs    integer      not null,
    failed_job_ids text         not null,
    options        text,
    cancelled_at   integer,
    created_at     integer      not null,
    finished_at    integer
);

alter table job_batches
    owner to avnadmin;

create table failed_jobs
(
    id         bigserial
        primary key,
    uuid       varchar(255)                           not null
        constraint failed_jobs_uuid_unique
            unique,
    connection text                                   not null,
    queue      text                                   not null,
    payload    text                                   not null,
    exception  text                                   not null,
    failed_at  timestamp(0) default CURRENT_TIMESTAMP not null
);

alter table failed_jobs
    owner to avnadmin;

create table "Estado"
(
    "EstadoID"     integer      not null
        primary key,
    "EstadoNombre" varchar(100) not null
        unique
);

alter table "Estado"
    owner to avnadmin;

create table "Cadena"
(
    "CadenaID"     varchar(10)  not null
        primary key,
    "CadenaNombre" varchar(100) not null
        unique
);

alter table "Cadena"
    owner to avnadmin;

create table "Paciente"
(
    "PacienteID"                 serial
        primary key,
    "PacienteNombre"             varchar(100) not null,
    "PacienteApellidoPaterno"    varchar(100) not null,
    "PacienteApellidoMaterno"    varchar(100),
    "PacienteTelefono"           varchar(15),
    "PacienteCorreo"             varchar(100)
        unique,
    "PacienteFechaRegistro"      date         not null,
    "PacienteContrasena"         varchar(100),
    "PacienteActivo"             boolean default false,
    "PacienteIntentosFallidos"   integer default 0,
    "PacienteFechaUltimoIntento" date
);

alter table "Paciente"
    owner to avnadmin;

create table "Medicamentos"
(
    "MedicamentoID"              serial
        primary key,
    "MedicamentoNombre"          varchar(100)   not null,
    "MedicamentoPrecio"          numeric(10, 2) not null,
    "MedicamentoCompuestoActivo" varchar(100),
    "MedicamentoUnidad"          varchar(50),
    "MedicamentoContenido"       varchar(100),
    unique ("MedicamentoNombre", "MedicamentoCompuestoActivo")
);

alter table "Medicamentos"
    owner to avnadmin;

create table "Ciudad"
(
    "CiudadID"     integer      not null
        primary key,
    "CiudadNombre" varchar(100) not null,
    "EstadoID"     integer      not null
        constraint fk_ciudad_estado
            references "Estado"
            on delete restrict,
    unique ("CiudadNombre", "EstadoID")
);

alter table "Ciudad"
    owner to avnadmin;

create table "Tarjeta"
(
    "Tarjeta"                 char(16)     not null
        primary key,
    "TarjetaNombreTitular"    varchar(100) not null,
    "TarjetaTipoTarjeta"      varchar(50),
    "TarjetaFechaVencimiento" date         not null,
    "TarjetaCVV"              varchar(4)   not null,
    "PacienteID"              integer      not null
        constraint fk_tarjeta_paciente
            references "Paciente"
            on delete cascade
);

alter table "Tarjeta"
    owner to avnadmin;

create table "Sucursal"
(
    "SucursalID"       varchar(10) not null,
    "SucursalColonia"  varchar(255),
    "SucursalCalle"    varchar(255),
    "SucursalLatitud"  numeric(11, 8),
    "SucursalLongitud" numeric(11, 8),
    "CiudadID"         integer     not null
        constraint fk_sucursal_ciudad
            references "Ciudad"
            on delete restrict,
    "CadenaID"         varchar(10) not null
        constraint fk_sucursal_cadena
            references "Cadena"
            on delete restrict,
    primary key ("SucursalID", "CadenaID")
);

alter table "Sucursal"
    owner to avnadmin;

create table "Receta"
(
    "RecetaFolio"  serial
        primary key,
    "CedulaDoctor" varchar(20) not null,
    "RecetaFecha"  date        not null,
    "PacienteID"   integer     not null
        constraint fk_receta_paciente
            references "Paciente"
            on delete restrict,
    "CadenaID"     varchar(10) not null,
    "SucursalID"   varchar(10) not null,
    "RecetaEstado" varchar(10) default 'Pendiente'::character varying,
    constraint fk_receta_cadena
        foreign key ("SucursalID", "CadenaID") references "Sucursal"
            on delete restrict
);

alter table "Receta"
    owner to avnadmin;

create table "Inventario"
(
    "SucursalID"         varchar(10) not null,
    "CadenaID"           varchar(10) not null,
    "MedicamentoID"      integer     not null
        constraint fk_inventario_medicamento
            references "Medicamentos"
            on delete restrict,
    "InventarioCantidad" integer     not null
        constraint "Inventario_InventarioCantidad_check"
            check ("InventarioCantidad" >= 0),
    "InventarioMaximo"   integer,
    "InventarioMinimo"   integer,
    primary key ("SucursalID", "CadenaID", "MedicamentoID"),
    constraint fk_inventario_sucursal
        foreign key ("SucursalID", "CadenaID") references "Sucursal"
            on delete restrict
);

alter table "Inventario"
    owner to avnadmin;

create table "LINEA_RECETA"
(
    "RecetaFolio"   integer        not null
        constraint fk_lr_receta
            references "Receta"
            on delete cascade,
    "MedicamentoID" integer        not null
        constraint fk_lr_medicamento
            references "Medicamentos"
            on delete restrict,
    "LRCantidad"    integer        not null
        constraint "LINEA_RECETA_LRCantidad_check"
            check ("LRCantidad" > 0),
    "LRPrecio"      numeric(10, 2) not null,
    primary key ("RecetaFolio", "MedicamentoID")
);

alter table "LINEA_RECETA"
    owner to avnadmin;

create table "Detalle_Linea_Receta"
(
    "RecetaFolio"   integer     not null,
    "MedicamentoID" integer     not null,
    "SucursalID"    varchar(10) not null,
    "CadenaID"      varchar(10) not null,
    "DLRCantidad"   integer     not null
        constraint "Detalle_Linea_Receta_DLRCantidad_check"
            check ("DLRCantidad" > 0),
    "DLREstatus"    varchar(50),
    primary key ("RecetaFolio", "MedicamentoID", "SucursalID", "CadenaID"),
    constraint fk_dlr_linea_receta
        foreign key ("RecetaFolio", "MedicamentoID") references "LINEA_RECETA"
            on delete cascade,
    constraint fk_dlr_sucursal
        foreign key ("SucursalID", "CadenaID") references "Sucursal"
            on delete restrict
);

alter table "Detalle_Linea_Receta"
    owner to avnadmin;

create table "AdminSucursal"
(
    "AdminNumeroEmpleado"     serial
        primary key,
    "AdminNombre"             varchar(100) not null,
    "AdminApellidoPaterno"    varchar(100) not null,
    "AdminApellidoMaterno"    varchar(100),
    "AdminCorreo"             varchar(100) not null
        unique,
    "AdminTelefono"           varchar(15),
    "AdminContrasena"         varchar(255) not null,
    "AdminActivo"             boolean default false,
    "AdminIntentosFallidos"   integer default 0,
    "AdminFechaUltimoIntento" timestamp,
    "SucursalID"              varchar(10)  not null,
    "CadenaID"                varchar(10)  not null,
    constraint fk_admin_sucursal
        foreign key ("SucursalID", "CadenaID") references "Sucursal"
            on delete restrict
);

alter table "AdminSucursal"
    owner to avnadmin;

Create index idx_admin_sucursal_sucursal_id on "AdminSucursal" ("SucursalID", "CadenaID");

create index idx_admin_sucursal_admin_correo on "AdminSucursal" ("AdminCorreo");

create index idx_medicamento_nombre on "Medicamentos" ("MedicamentoNombre");

create index idx_receta_paciente_receta on "Receta"("PacienteID");

create index idx_receta_sucursal_receta on "Receta"("SucursalID", "CadenaID");

create index idx_inventario_sucursal_inventario on "Inventario"("SucursalID", "CadenaID");

create index idx_inventario_medicamento_inventario on "Inventario"("MedicamentoID");

create index idx_linea_receta_receta on "LINEA_RECETA"("RecetaFolio");

create index idx_linea_receta_medicamento on "LINEA_RECETA"("MedicamentoID");

create index idx_detalle_linea_receta_linea_receta on "Detalle_Linea_Receta"("RecetaFolio", "MedicamentoID");

alter table "Receta" alter column "RecetaEstado" type Varchar(20);

alter table "Detalle_Linea_Receta" alter column "DLREstatus" type Varchar(20);
