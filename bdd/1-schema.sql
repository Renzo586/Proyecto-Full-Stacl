CREATE DATABASE IF NOT EXISTS hospital CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hospital;
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS Respuesta_encuesta, Encuesta_de_Satisfaccion, Encuesta,
    Traslado, Traslados, Estado_traslado, Enfermero, Chofer, Personal, Ambulancia,
    Documento, Categoria_documento, Paciente, Administrativo, Usuario;
CREATE TABLE Usuario (
    Id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    Usuario VARCHAR(50) NOT NULL UNIQUE,
    Clave_hash VARCHAR(255) NOT NULL,
    Nombre VARCHAR(100) NOT NULL,
    Apellido VARCHAR(100) NOT NULL,
    Telefono VARCHAR(50),
    Rol ENUM('administrador','operador') NOT NULL DEFAULT 'operador',
    Activo TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE Categoria_documento (
    Id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL UNIQUE
);
CREATE TABLE Documento (
    Id_documento INT AUTO_INCREMENT PRIMARY KEY,
    Codigo_qr VARCHAR(64) NOT NULL UNIQUE,
    Titulo VARCHAR(150) NOT NULL,
    Descripcion TEXT,
    Ruta_archivo VARCHAR(255) NOT NULL,     
    Nombre_original VARCHAR(255),
    Id_categoria INT NOT NULL,
    Id_usuario INT NOT NULL,                 
    Fecha_subida DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (Id_categoria) REFERENCES Categoria_documento(Id_categoria),
    FOREIGN KEY (Id_usuario) REFERENCES Usuario(Id_usuario)
);
CREATE TABLE Paciente (
    Cedula VARCHAR(20) PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL,
    Apellido VARCHAR(100) NOT NULL,
    Telefono VARCHAR(50)
);
CREATE TABLE Encuesta (
    Id_encuesta INT AUTO_INCREMENT PRIMARY KEY,
    Titulo VARCHAR(150) NOT NULL UNIQUE,
    Activa TINYINT(1) NOT NULL DEFAULT 1,
    Id_usuario INT NOT NULL,
    FOREIGN KEY (Id_usuario) REFERENCES Usuario(Id_usuario)
);
CREATE TABLE Respuesta_encuesta (
    Id_respuesta INT AUTO_INCREMENT PRIMARY KEY,
    Id_encuesta INT NOT NULL,
    Servicio VARCHAR(100) NOT NULL,
    Calificacion TINYINT NOT NULL CHECK (Calificacion BETWEEN 1 AND 5),
    Recomienda TINYINT(1) NOT NULL,
    Nombre_opcional VARCHAR(100) NULL,
    Fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (Id_encuesta) REFERENCES Encuesta(Id_encuesta)
);
CREATE TABLE Ambulancia (
    Matricula VARCHAR(20) PRIMARY KEY,
    Modelo VARCHAR(100) NOT NULL,
    Activa TINYINT(1) NOT NULL DEFAULT 1
);
CREATE TABLE Personal (
    Cedula VARCHAR(20) PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL,
    Apellido VARCHAR(100) NOT NULL,
    Telefono VARCHAR(50)
);
CREATE TABLE Chofer (
    Cedula VARCHAR(20) PRIMARY KEY,
    Licencia VARCHAR(50) NOT NULL,
    FOREIGN KEY (Cedula) REFERENCES Personal(Cedula)
);
CREATE TABLE Enfermero (
    Cedula VARCHAR(20) PRIMARY KEY,
    Id_enfermero VARCHAR(50) NOT NULL UNIQUE,
    FOREIGN KEY (Cedula) REFERENCES Personal(Cedula)
);
CREATE TABLE Estado_traslado (
    Id_estado INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(30) NOT NULL UNIQUE
);
CREATE TABLE Traslado (
    Id_traslado INT AUTO_INCREMENT PRIMARY KEY,
    Cedula_paciente VARCHAR(20) NOT NULL,
    Matricula VARCHAR(20) NOT NULL,
    Cedula_chofer VARCHAR(20) NOT NULL,
    Cedula_enfermero VARCHAR(20) NOT NULL,
    Origen VARCHAR(150) NOT NULL,
    Destino VARCHAR(150) NOT NULL,
    H_salida DATETIME NOT NULL,
    H_llegada DATETIME NULL,
    Id_estado INT NOT NULL DEFAULT 1,
    Id_usuario INT NOT NULL,
    Observaciones TEXT NULL,
    FOREIGN KEY (Cedula_paciente) REFERENCES Paciente(Cedula),
    FOREIGN KEY (Matricula) REFERENCES Ambulancia(Matricula),
    FOREIGN KEY (Cedula_chofer) REFERENCES Chofer(Cedula),
    FOREIGN KEY (Cedula_enfermero) REFERENCES Enfermero(Cedula),
    FOREIGN KEY (Id_estado) REFERENCES Estado_traslado(Id_estado),
    FOREIGN KEY (Id_usuario) REFERENCES Usuario(Id_usuario),
    CHECK (H_llegada IS NULL OR H_llegada >= H_salida)
);
SET FOREIGN_KEY_CHECKS = 1;