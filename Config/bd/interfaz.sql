
CREATE TABLE Usuarios (
    id int(11) NOT NULL AUTO_INCREMENT,
    nombre varchar(50) NOT NULL ,
    usuario varchar(250) NOT NULL,
    clave varchar(12) NOT NULL,
    estado int(2) DEFAULT 1,
    created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    user_c int(2) DEFAULT 1,
    user_m int(2) DEFAULT 1,
    PRIMARY KEY (id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

create database apiwonderful;
use apiwonderful;
CREATE TABLE Usuarios (
    id int(11) NOT NULL AUTO_INCREMENT,
    user varchar(50) NOT NULL ,
    pass varchar(250) NOT NULL,
    rol int(2) DEFAULT 1,
    estado int(2) DEFAULT 1,
    gmt varchar(10) DEFAULT "GMT-0",
    gmt varchar(10) DEFAULT "C",
    token varchar(250) NOT NULL,
    fecha_token timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    user_c int(2) DEFAULT 1,
    user_m int(2) DEFAULT 1,
    PRIMARY KEY (id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


use apiwonderful;
CREATE TABLE h_usuario (
    id int(11) NOT NULL AUTO_INCREMENT,
    user varchar(50) NOT NULL ,
    campo varchar(50) NOT NULL,
    valor_anterior varchar(150) NOT NULL,
    valor_modificado varchar(150) DEFAULT "CONFIDENTIAL",  
    valor_confidencial varchar(150) DEFAULT "NO CONFIDENTIAL",  
    ejecuto varchar(50) DEFAULT "WONDERFUL" ,
    evento varchar(250) DEFAULT "NOT SPECIFIED" ,
    fecha_evento timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE devices (
    id int(11) NOT NULL AUTO_INCREMENT,
    user varchar(50) NOT NULL ,
    campo varchar(50) NOT NULL,
    valor_anterior varchar(150) NOT NULL,
    valor_modificado varchar(150) DEFAULT "CONFIDENTIAL",  
    valor_confidencial varchar(150) DEFAULT "NO CONFIDENTIAL",  
    ejecuto varchar(50) DEFAULT "WONDERFUL" ,
    evento varchar(250) DEFAULT "NOT SPECIFIED" ,
    fecha_evento timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
