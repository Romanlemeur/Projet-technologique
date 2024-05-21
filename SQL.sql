

CREATE TABLE Tache (
    ID_Tache INT PRIMARY KEY,
    Debut DATE,
    Collaborateur INT,
    FOREIGN KEY (Collaborateur) REFERENCES Collaborateur(ID_Collaborateur)
);

CREATE TABLE Commentaire (
    ID_Commentaire INT PRIMARY KEY,
    Message VARCHAR(255),
    Collaborateur INT,
    Tache INT,
    FOREIGN KEY (Collaborateur) REFERENCES Collaborateur(ID_Collaborateur),
    FOREIGN KEY (Tache) REFERENCES Tache(ID_Tache)
);

CREATE TABLE Projet (
    ID_Projet INT PRIMARY KEY,
    Titre VARCHAR(255),
    Description VARCHAR(255),
    Debut DATE,
    Fin DATE,
    Objectif VARCHAR(255),
    Avancement VARCHAR(255),
    
);

CREATE TABLE Fichier (
    ID_Fichier INT PRIMARY KEY,
    Type VARCHAR(255),
    Collaborateur INT,
    Commentaire INT,
    FOREIGN KEY (Collaborateur) REFERENCES Collaborateur(ID_Collaborateur),
    FOREIGN KEY (Commentaire) REFERENCES Commentaire(ID_Commentaire)
);

CREATE TABLE Collaborateur (
    ID_Collaborateur INT PRIMARY KEY,
    Role VARCHAR(255),
    Mdp VARCHAR(255),
    Admin VARCHAR(255),
    Nom VARCHAR(255),
    Mail VARCHAR(255),
    Projets INT,
    Taches INT,
    FOREIGN KEY (Projets) REFERENCES Projet(ID_Projet),
    FOREIGN KEY (Taches) REFERENCES Tache(ID_Tache),
   
);

CREATE TABLE Equipe (
    ID_Equipe INT PRIMARY KEY,
    Nom VARCHAR(255),
    Projet INT,
    Taches INT,
    FOREIGN KEY (Projet) REFERENCES Projet(ID_Projet),
    FOREIGN KEY (Taches) REFERENCES Tache(ID_Tache)
);