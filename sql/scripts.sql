CREATE DATABASE control;

USE control;

DROP TABLE IF EXISTS salida;
DROP TABLE IF EXISTS entrada;
DROP TABLE IF EXISTS pedido;
DROP TABLE IF EXISTS material;
DROP TABLE IF EXISTS categoria;
DROP TABLE IF EXISTS usuario;
DROP TABLE IF EXISTS rol;

CREATE TABLE rol (
    rolId BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    nombreRol VARCHAR(50) NOT NULL,
    descripcion VARCHAR(200) NOT NULL,
    deleted_at DATETIME NULL
);

CREATE TABLE usuario (
    usuarioId BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    rolId BIGINT NOT NULL,
    deleted_at DATETIME NULL,
    FOREIGN KEY (rolId) REFERENCES rol(rolId)
);

CREATE TABLE categoria (
    categoriaId BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    descripcion VARCHAR(200),
    deleted_at DATETIME NULL
);

CREATE TABLE material (
    materialId BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255),
    cantidad INT NOT NULL DEFAULT 0,
    categoriaId BIGINT,
    deleted_at DATETIME NULL,
    FOREIGN KEY (categoriaId) REFERENCES categoria(categoriaId)
);

CREATE TABLE pedido (
    pedidoId BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    materialId BIGINT NOT NULL,
    cantidad INT NOT NULL,
    fechaPedido DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    estado VARCHAR(50) DEFAULT 'Pendiente',
    deleted_at DATETIME NULL,
    FOREIGN KEY (materialId) REFERENCES material(materialId)
);

CREATE TABLE entrada (
    entradaId BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    pedidoId BIGINT NOT NULL,
    cantidad INT NOT NULL,
    fechaEntrada DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL,
    FOREIGN KEY (pedidoId) REFERENCES pedido(pedidoId)
);

CREATE TABLE salida (
    salidaId BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    materialId BIGINT NOT NULL,
    cantidad INT NOT NULL,
    fechaSalida DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL,
    FOREIGN KEY (materialId) REFERENCES material(materialId)
);
