CREATE DATABASE IF NOT EXISTS datachallenge_db;
USE datachallenge_db;

CREATE TABLE Utilisateurs (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Nom VARCHAR(255),
    Prénom VARCHAR(255),
    Entreprise VARCHAR(255),
    Téléphone VARCHAR(255),
    Email VARCHAR(255),
    DateDébut DATE,
    DateFin DATE,
    MotDePasse VARCHAR(255),
    Role ENUM('Admin', 'Gestionnaire', 'Étudiant'),
    Niveau ENUM('L1','L2','L3','M1','M2','D'),
    Ecole VARCHAR(255),
    Ville VARCHAR(255)
);

CREATE TABLE DataChallenges (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Libellé VARCHAR(255),
    DateDébut DATE,
    DateFin DATE,
    ID_Admin INT,
    FOREIGN KEY (ID_Admin) REFERENCES Utilisateurs(ID)
);

CREATE TABLE Projets (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Libellé VARCHAR(255),
    Description TEXT,
    ImageURL VARCHAR(255),
    ID_DataChallenge INT,
    FOREIGN KEY (ID_DataChallenge) REFERENCES DataChallenges(ID)
);

CREATE TABLE Ressources (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    URL VARCHAR(255),
    Type ENUM('Notebook', 'PDF', 'HTML', 'Vidéo', 'etc'),
    ID_Projet INT,
    FOREIGN KEY (ID_Projet) REFERENCES Projets(ID)
);

CREATE TABLE Équipes (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Nom VARCHAR(255),
    ID_Projet INT,
    ID_Capitaine INT,
    FOREIGN KEY (ID_Projet) REFERENCES Projets(ID),
    FOREIGN KEY (ID_Capitaine) REFERENCES Utilisateurs(ID)
);

CREATE TABLE MembresÉquipe (
    ID_Équipe INT,
    ID_Utilisateur INT,
    PRIMARY KEY (ID_Équipe, ID_Utilisateur),
    FOREIGN KEY (ID_Équipe) REFERENCES Équipes(ID),
    FOREIGN KEY (ID_Utilisateur) REFERENCES Utilisateurs(ID)
);

CREATE TABLE Messages (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Contenu TEXT,
    DateEnvoi DATETIME,
    ID_Émetteur INT,
    ID_Équipe INT,
    FOREIGN KEY (ID_Émetteur) REFERENCES Utilisateurs(ID),
    FOREIGN KEY (ID_Équipe) REFERENCES Équipes(ID)
);

CREATE TABLE Questionnaires (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    DateDébut DATE,
    DateFin DATE,
    ID_Gestionnaire INT,
    FOREIGN KEY (ID_Gestionnaire) REFERENCES Utilisateurs(ID)
);

CREATE TABLE Questions (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Contenu TEXT,
    ID_Questionnaire INT,
    FOREIGN KEY (ID_Questionnaire) REFERENCES Questionnaires(ID)
);

CREATE TABLE Réponses (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Contenu TEXT,
    Note INT,
    ID_Question INT,
    ID_Équipe INT,
    FOREIGN KEY (ID_Question) REFERENCES Questions(ID),
    FOREIGN KEY (ID_Équipe) REFERENCES Équipes(ID)
);

CREATE TABLE AnalysesCode (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    NombreLignes INT,
    NombreFonctions INT,
    LignesMinFonction INT,
    LignesMaxFonction INT,
    LignesMoyennesFonction FLOAT,
    ID_Équipe INT,
    FOREIGN KEY (ID_Équipe) REFERENCES Équipes(ID)
);

CREATE TABLE OccurrencesTermes (
    ID_AnalyseCode INT,
    Terme VARCHAR(255),
    NombreOccurrences INT,
    PRIMARY KEY (ID_Analyse
);