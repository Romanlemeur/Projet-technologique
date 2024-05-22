CREATE DATABASE Plano;
USE Plano;


CREATE TABLE Projet (
    ID_Projet INT PRIMARY KEY,
    Titre VARCHAR(255),
    Description VARCHAR(255),
    Debut DATE,
    Fin DATE,
    Objectif VARCHAR(255),
    Avancement VARCHAR(255)
);

CREATE TABLE Collaborateur (
    ID_Collaborateur INT AUTO_INCREMENT PRIMARY KEY,
    Role VARCHAR(255),
    Mdp VARCHAR(255),
    Nom VARCHAR(255),
    Mail VARCHAR(255)
);

CREATE TABLE Collaborateur_Projet (
    ID_Collaborateur INT,
    ID_Projet INT,
    PRIMARY KEY (ID_Collaborateur, ID_Projet),
    FOREIGN KEY (ID_Collaborateur) REFERENCES Collaborateur(ID_Collaborateur),
    FOREIGN KEY (ID_Projet) REFERENCES Projet(ID_Projet)
);

CREATE TABLE Tache (
    ID_Tache INT AUTO_INCREMENT PRIMARY KEY,
    Titre VARCHAR(255),
    Debut DATE,
    Fin DATE,
    Collaborateur INT,
    FOREIGN KEY (Collaborateur) REFERENCES Collaborateur(ID_Collaborateur)
);

CREATE TABLE Tache_Collaborateur (
    ID_Tache INT,
    ID_Collaborateur INT,
    FOREIGN KEY (ID_Tache) REFERENCES Tache(ID_Tache),
    FOREIGN KEY (ID_Collaborateur) REFERENCES Collaborateur(ID_Collaborateur),
    PRIMARY KEY (ID_Tache, ID_Collaborateur)
);

CREATE TABLE Commentaire (
    ID_Commentaire INT PRIMARY KEY,
    Message VARCHAR(255),
    Collaborateur INT,
    Tache INT,
    FOREIGN KEY (Collaborateur) REFERENCES Collaborateur(ID_Collaborateur),
    FOREIGN KEY (Tache) REFERENCES Tache(ID_Tache)
);

CREATE TABLE Fichier (
    ID_Fichier INT PRIMARY KEY,
    Type VARCHAR(255),
    Collaborateur INT,
    Commentaire INT,
    FOREIGN KEY (Collaborateur) REFERENCES Collaborateur(ID_Collaborateur),
    FOREIGN KEY (Commentaire) REFERENCES Commentaire(ID_Commentaire)
);

CREATE TABLE Equipe (
    ID_Equipe INT PRIMARY KEY,
    Nom VARCHAR(255),
    Projet INT,
    FOREIGN KEY (Projet) REFERENCES Projet(ID_Projet)
);

INSERT INTO Collaborateur VALUES(1,"Bronze sur Lol","AntoineMDP","Antoine Baudet","AntoineBaudet@gmail.com");
INSERT INTO Collaborateur VALUES(2,"Developpeur","ManuelMDP","Manuel Bassien","ManuelBassien@gmail.com");
INSERT INTO Collaborateur VALUES(3,"Enorme Connard","RomanMDP","Roman LeMeur","RomanLeMeur@gmail.com");
INSERT INTO Collaborateur VALUES(4,"Patissier","FranckMDP","Franck Faye","FranckFaye@gmail.com");