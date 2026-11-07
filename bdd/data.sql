USE hospital;
INSERT INTO Usuario (Usuario, Clave_hash, Nombre, Apellido, Telefono, Rol) VALUES
('papoi67',   '$2y$10$EC4ogL1FFFvDOx.o5AyRCO.9KA91iRt4L1IYMyB6OFVhnqXF9EsSG', 'Carlos',  'Rodriguez', '097412843', 'administrador'),
('operador1', '$2y$10$GWbHZL8Jt2zZ6bpX88Kzuuu3alIZCkm7GibpijLn7mxQRrR0XRrEu', 'Mariana', 'Fernandez', '099888777', 'operador');

INSERT INTO Categoria_documento (Nombre) VALUES
('Ingreso'), ('Consentimientos'), ('Estudios'), ('Alta');
INSERT INTO Documento (Codigo_qr, Titulo, Descripcion, Ruta_archivo, Nombre_original, Id_categoria, Id_usuario) VALUES
('QR123', 'Manual de ingreso', 'Pasos para el ingreso de pacientes', 'uploads/documentos/manual_ingreso.pdf', 'manual_ingreso.pdf', 1, 1);

INSERT INTO Paciente (Cedula, Nombre, Apellido, Telefono) VALUES
('12345678', 'Gonzalo', 'Perez',    '099123456'),
('23456789', 'Maria',   'Gonzalez', '099234567'),
('34567890', 'Juan',    'Perez',    '099345678');

INSERT INTO Encuesta (Id_usuario, Titulo) VALUES (1, 'Satisfaccion general');

INSERT INTO Respuesta_encuesta (Id_encuesta, Servicio, Calificacion, Recomienda, Nombre_opcional) VALUES
(1, 'Laboratorio', 5, 1, 'Lucia'),
(1, 'Emergencia',  3, 0, NULL);

INSERT INTO Ambulancia (Matricula, Modelo) VALUES
('SCH1234', 'Toyota Hiace'),
('SCH5678', 'Mercedes Sprinter');

INSERT INTO Personal (Cedula, Nombre, Apellido, Telefono) VALUES
('33445566', 'Roberto', 'Gomez',   '099444555'),
('44556677', 'Ana',     'Silva',   '099555666'),
('55667788', 'Lucia',   'Martinez','099666777'),
('66778899', 'Pedro',   'Diaz',    '099777888');

INSERT INTO Chofer (Cedula, Licencia) VALUES
('33445566', 'Profesional'),
('44556677', 'Profesional');

INSERT INTO Enfermero (Cedula, Id_enfermero) VALUES
('55667788', 'ENF-001'),
('66778899', 'ENF-002');

INSERT INTO Estado_traslado (Nombre) VALUES
('Pendiente'), ('En curso'), ('Finalizado'), ('Cancelado');

INSERT INTO Traslado (Cedula_paciente, Matricula, Cedula_chofer, Cedula_enfermero, Origen, Destino, H_salida, H_llegada, Id_estado, Id_usuario, Observaciones) VALUES
('12345678', 'SCH1234', '33445566', '55667788', 'Hospital de Clinicas', 'Sanatorio Americano', '2026-11-02 10:00', '2026-11-02 10:30', 3, 1, 'Sin novedades'),
('23456789', 'SCH5678', '44556677', '66778899', 'Domicilio',            'Hospital Maciel',     '2026-11-04 15:00', NULL,               2, 2, NULL),
('34567890', 'SCH1234', '33445566', '55667788', 'Hospital de Clinicas', 'Hospital Espanol',    '2026-11-05 09:00', NULL,               1, 2, NULL),
('12345678', 'SCH5678', '44556677', '55667788', 'Sanatorio Americano',  'Hospital de Clinicas','2026-11-03 08:00', NULL,               4, 1, 'Cancelado por el paciente');