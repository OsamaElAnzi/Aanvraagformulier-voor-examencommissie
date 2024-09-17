DROP DATABASE IF EXISTS vrijstelling;
CREATE DATABASE vrijstelling;
use vrijstelling;
CREATE TABLE aanvraag(
    id int NOT NULL AUTO_INCREMENT,
	naamStudent varchar(30) NOT NULL,
	studentNum int NOT NULL,
    examenNaam varchar(30) NOT NULL,
    examenCode varchar(30),
	bewijs varchar(200) NULL,
    PRIMARY KEY (id)
);