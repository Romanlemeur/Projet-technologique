    CREATE DATABASE Plano;
    USE Plano;


    CREATE TABLE Projet (
        ID_Projet INT AUTO_INCREMENT PRIMARY KEY,
        Titre VARCHAR(255),
        Description VARCHAR(255),
        Debut DATE,
        Fin DATE,
        Objectif VARCHAR(255),
        Budget float
    );

    INSERT INTO Projet VALUES(1,"Projet1","Ceci est le projet 1","2024-5-28","2024-7-3","Terminer le projet1",500000);
    INSERT INTO Projet VALUES(2,"Projet2","Ceci est le projet 2","2024-6-28","2024-8-29","Terminer le projet1",500000);
    INSERT INTO Projet VALUES(3,"Projet3","Ceci est le projet 3","2024-5-12","2024-7-22","Terminer le projet1",500000);
    INSERT INTO Projet VALUES(4,"Projet4","Ceci est le projet 4","2024-4-8","2024-7-5","Terminer le projet1",500000);
    INSERT INTO Projet VALUES(5,"Projet5","Ceci est le projet 5","2024-5-25","2024-7-1","Terminer le projet1",500000);
    INSERT INTO Projet VALUES(6,"Projet6","Ceci est le projet 6","2024-2-2","2024-8-3","Terminer le projet1",500000);

    CREATE TABLE Collaborateur (
        ID_Collaborateur INT AUTO_INCREMENT PRIMARY KEY,
        Role VARCHAR(255),
        Mdp VARCHAR(255),
        Nom VARCHAR(255),
        Mail VARCHAR(255)
    );


    INSERT INTO Collaborateur VALUES(1,"Bronze sur Lol","AntoineMDP","Antoine Baudet","AntoineBaudet@gmail.com");
    INSERT INTO Collaborateur VALUES(2,"Developpeur","ManuelMDP","Manuel Bassien","ManuelBassien@gmail.com");
    INSERT INTO Collaborateur VALUES(3,"Enorme Connard","RomanMDP","Roman LeMeur","RomanLeMeur@gmail.com");
    INSERT INTO Collaborateur VALUES(4,"Patissier","FranckMDP","Franck Faye","FranckFaye@gmail.com");
    INSERT INTO Collaborateur VALUES(5,"Administrateur","AdminMDP","Admin","Admin@gmail.com");

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
        Projet INT,
        état bit,
        FOREIGN KEY (Collaborateur) REFERENCES Collaborateur(ID_Collaborateur),
        FOREIGN KEY (Projet) REFERENCES Projet(ID_Projet)
    );

    CREATE TABLE Tache_Collaborateur (
        ID_Tache INT,
        ID_Collaborateur INT,
        FOREIGN KEY (ID_Tache) REFERENCES Tache(ID_Tache),
        FOREIGN KEY (ID_Collaborateur) REFERENCES Collaborateur(ID_Collaborateur),
        PRIMARY KEY (ID_Tache, ID_Collaborateur)
    );

    CREATE TABLE Commentaire (
        ID_Commentaire INT AUTO_INCREMENT PRIMARY KEY,
        Message VARCHAR(255),
        Projet INT,
        FOREIGN KEY (Projet) REFERENCES Projet(ID_Projet)
    );

    CREATE TABLE Fichier (
        ID_Fichier INT AUTO_INCREMENT PRIMARY KEY,
        Nom VARCHAR(255),
        Type VARCHAR(255),
        Projet INT,
        FOREIGN KEY (Projet) REFERENCES Projet(ID_Projet)
    );

    CREATE TABLE Equipe (
        ID_Equipe INT PRIMARY KEY,
        Nom VARCHAR(255),
        Projet INT,
        FOREIGN KEY (Projet) REFERENCES Projet(ID_Projet)
    );

    INSERT INTO Equipe VALUES(1,"Equipe Developpement",1);

    CREATE TABLE Notification(
        ID_Notification INT PRIMARY KEY,
        Titre VARCHAR(255),
        Contenu VARCHAR(255),
        Collaborateur INT,
        FOREIGN KEY (Collaborateur) REFERENCES Collaborateur(ID_Collaborateur)
    );


-- Tâches pour le Projet 1
INSERT INTO Tache (Titre, Debut, Fin, Collaborateur, Projet, état) VALUES
("Tâche 1 Projet 1", "2024-05-28", "2024-06-10", 1, 1, 1),
("Tâche 2 Projet 1", "2024-06-11", "2024-06-20", 2, 1, 0),
("Tâche 3 Projet 1", "2024-06-21", "2024-07-03", 3, 1, 1);

-- Tâches pour le Projet 2
INSERT INTO Tache (Titre, Debut, Fin, Collaborateur, Projet, état) VALUES
("Tâche 1 Projet 2", "2024-06-28", "2024-07-10", 1, 2, 0),
("Tâche 2 Projet 2", "2024-07-11", "2024-07-20", 4, 2, 1),
("Tâche 3 Projet 2", "2024-07-21", "2024-08-29", 2, 2, 0);

-- Tâches pour le Projet 3
INSERT INTO Tache (Titre, Debut, Fin, Collaborateur, Projet, état) VALUES
("Tâche 1 Projet 3", "2024-05-12", "2024-05-22", 3, 3, 1),
("Tâche 2 Projet 3", "2024-05-23", "2024-06-10", 1, 3, 0),
("Tâche 3 Projet 3", "2024-06-11", "2024-07-22", 2, 3, 1);

-- Tâches pour le Projet 4
INSERT INTO Tache (Titre, Debut, Fin, Collaborateur, Projet, état) VALUES
("Tâche 1 Projet 4", "2024-04-08", "2024-05-01", 4, 4, 0),
("Tâche 2 Projet 4", "2024-05-02", "2024-06-01", 3, 4, 1),
("Tâche 3 Projet 4", "2024-06-02", "2024-07-05", 1, 4, 0);

-- Tâches pour le Projet 5
INSERT INTO Tache (Titre, Debut, Fin, Collaborateur, Projet, état) VALUES
("Tâche 1 Projet 5", "2024-05-25", "2024-06-05", 2, 5, 1),
("Tâche 2 Projet 5", "2024-06-06", "2024-06-20", 4, 5, 0),
("Tâche 3 Projet 5", "2024-06-21", "2024-07-01", 1, 5, 1);

-- Tâches pour le Projet 6
INSERT INTO Tache (Titre, Debut, Fin, Collaborateur, Projet, état) VALUES
("Tâche 1 Projet 6", "2024-02-02", "2024-04-01", 3, 6, 0),
("Tâche 2 Projet 6", "2024-04-02", "2024-06-01", 2, 6, 1),
("Tâche 3 Projet 6", "2024-06-02", "2024-08-03", 4, 6, 0);

-- Associations de tâches et collaborateurs (si nécessaire)
INSERT INTO Tache_Collaborateur (ID_Tache, ID_Collaborateur) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), 
(2, 1), (2, 4), (2, 3), 
(3, 1), (3, 2), (3, 4), 
(4, 3), (4, 1), (4, 2), 
(5, 3), (5, 4), (5, 1), 
(6, 4), (6, 1), (6, 2),
(7, 2), (7, 3), (7, 4), 
(8, 1), (8, 4), (8, 2), 
(9, 1), (9, 2), (9, 3), 
(10, 3), (10, 1), (10, 4), 
(11, 3), (11, 4), (11, 2), 
(12, 4), (12, 1), (12, 3),
(13, 1), (13, 2), (13, 4), 
(14, 2), (14, 4), (14, 3), 
(15, 1), (15, 4), (15, 2), 
(16, 1), (16, 2), (16, 3), 
(17, 3), (17, 1), (17, 4), 
(18, 3), (18, 4), (18, 2);
