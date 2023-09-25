BEGIN TRANSACTION

CREATE TABLE alquileres (
  id int NOT NULL IDENTITY(1,1) PRIMARY KEY,
  id_huesped int DEFAULT NULL,
  id_habitacion varchar(4)   DEFAULT NULL,
  id_recepcionista int DEFAULT NULL,
  id_pago int DEFAULT NULL,
  fecha_alquiler date DEFAULT NULL,
  dias int DEFAULT NULL,
  costo smallmoney DEFAULT NULL,
  personas int DEFAULT NULL,
  motivo nvarchar(100)   DEFAULT NULL,
  procedencia nvarchar(100)   DEFAULT NULL,
  comentarios nvarchar(200) DEFAULT NULL
) ;

CREATE TABLE habitaciones (
  id varchar(4) NOT NULL PRIMARY KEY,
  id_alquiler int DEFAULT NULL,
  tipo varchar(50)   DEFAULT NULL,
  CHECK (tipo IN (N'SIMPLE', N'DOBLE', N'FAMILIAR', N'MATRIMONIAL')),
  precio smallmoney DEFAULT NULL,
  [desc] nvarchar(500)   DEFAULT NULL,
  estado_limpieza bit NOT NULL DEFAULT 1

) ;

CREATE TABLE huespedes (
  id int NOT NULL IDENTITY(1,1) PRIMARY KEY,
  nombres nvarchar(100) DEFAULT NULL,
  apellidos nvarchar(100) DEFAULT NULL,
  doc_tipo varchar(10) DEFAULT NULL,
  CHECK (doc_tipo IN ('DNI','CE','P')),
  doc_num varchar(50) DEFAULT NULL,
  sexo char(1) DEFAULT NULL,
  CHECK (sexo IN ('M', 'F')),
  fecha_registro date DEFAULT NULL
) ;

CREATE TABLE movimientos (
  id int NOT NULL IDENTITY(1,1) PRIMARY KEY,
  id_alquiler int DEFAULT NULL,
  id_recepcionista int DEFAULT NULL,
  tipo varchar(50)   DEFAULT NULL,
  CHECK (tipo IN (N'CHECK-IN',N'CHECK-OUT',N'ENTRADA',N'SALIDA')),
  fecha_movimiento datetime DEFAULT NULL
) ;

CREATE TABLE pagos (
  id int NOT NULL IDENTITY(1,1) PRIMARY KEY,
  tipo_pago varchar(50) NOT NULL,
  CHECK (tipo_pago IN (N'EFECTIVO',N'YAPE')),
  monto smallmoney NOT NULL
) ;

CREATE TABLE recepcionistas (
  id int NOT NULL IDENTITY(1,1) PRIMARY KEY,
  nombres nvarchar(100)   DEFAULT NULL,
  apellidos nvarchar(100) DEFAULT NULL,
  dni varchar(8)   DEFAULT NULL,
  correo nvarchar(100)   DEFAULT NULL,
  telefono varchar(9)   DEFAULT NULL,
  clave nvarchar(100) DEFAULT NULL,
  imagen nvarchar(50)   NOT NULL DEFAULT 'user.png'
) ;

CREATE TABLE reservas (
  id int NOT NULL IDENTITY(1,1) PRIMARY KEY,
  id_alquiler int NOT NULL,
  check_in date NOT NULL,
  check_out date NOT NULL
) ;

INSERT INTO habitaciones (id, id_alquiler, tipo, precio, [desc], estado_limpieza) VALUES
('0201', NULL, 'MATRIMONIAL', 50, N'1 baño propio\n1 ventana a la calle\n1 cama de 2 plazas\n1 televisión con cable y Netflix', 1),
('0202', NULL, 'SIMPLE', 20, N'1 baño propio\r\n1 cama de 1/2 plaza', 1);

INSERT INTO huespedes (nombres, apellidos, doc_tipo, doc_num, sexo, fecha_registro) VALUES
(N'Fabrizzio Fabiano', N'Esquivel Mori', 'DNI', '71668230', 'M', '2023-08-29');

INSERT INTO recepcionistas (nombres, apellidos, dni, correo, telefono, clave, imagen) VALUES
(N'Fabrizzio Fabiano', N'Esquivel Mori', '71668230', N'fabrizzio_fabiano@outlok.com', '993566249', N'123', N'user.png');

ALTER TABLE alquileres
  ADD CONSTRAINT fk_alquier_habitacion FOREIGN KEY (id_habitacion) REFERENCES habitaciones (id),
  CONSTRAINT fk_alquier_huesped FOREIGN KEY (id_huesped) REFERENCES huespedes (id),
  CONSTRAINT fk_alquier_recepcionista FOREIGN KEY (id_recepcionista) REFERENCES recepcionistas (id),
  CONSTRAINT fk_alquiler_pago FOREIGN KEY (id_pago) REFERENCES pagos (id);

ALTER TABLE habitaciones
  ADD CONSTRAINT fk_habitacion_alquiler FOREIGN KEY (id_alquiler) REFERENCES alquileres (id);

ALTER TABLE movimientos
  ADD CONSTRAINT fk_movimient_alquiler FOREIGN KEY (id_alquiler) REFERENCES alquileres (id),
  CONSTRAINT fk_movimiento_recepcionista FOREIGN KEY (id_recepcionista) REFERENCES recepcionistas (id);

ALTER TABLE reservas
  ADD CONSTRAINT fk_reserva_alquiler FOREIGN KEY (id_alquiler) REFERENCES alquileres (id);

COMMIT;