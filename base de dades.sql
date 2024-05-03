CREATE DATABASE IF NOT EXISTS carta;
USE carta;

CREATE TABLE IF NOT EXISTS pais (
    idpais INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    bandera VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS piloto (
    idpiloto INT AUTO_INCREMENT PRIMARY KEY,
    media VARCHAR(255),
    name VARCHAR(255) NOT NULL,
    exp INT,
    rac INT,
    awa INT,
    pac INT,
    photo VARCHAR(255),
    idpais INT,
    FOREIGN KEY (idpais) REFERENCES pais(idpais)
);

CREATE TABLE IF NOT EXISTS piloto_competicio (
    idpiloto INT,
    idcompeticio INT,
    FOREIGN KEY (idpiloto) REFERENCES piloto(idpiloto) ON DELETE CASCADE,
    FOREIGN KEY (idcompeticio) REFERENCES competicio(idcompeticio) ON DELETE CASCADE,
    PRIMARY KEY (idpiloto, idcompeticio)
);

CREATE TABLE IF NOT EXISTS competicio (
    idcompeticio INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL
);

SELECT piloto.*, 
       pais.bandera, 
       pais.nombre AS nombre_pais
FROM piloto
JOIN pais ON piloto.idpais = pais.idpais;
