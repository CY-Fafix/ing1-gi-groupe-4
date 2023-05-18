USE datachallenge_db;

INSERT INTO Utilisateurs (Nom, Prénom, Entreprise, Téléphone, Email, DateDébut, DateFin, MotDePasse, Role, Niveau, Ecole, Ville)
VALUES
('Doe', 'John', 'Company1', '0123456789', 'john.doe@example.com', '2023-01-01', '2023-12-31', 'password1', 'Admin', NULL, NULL, NULL),
('Smith', 'Jane', 'Company2', '0234567891', 'jane.smith@example.com', '2023-01-01', '2023-12-31', 'password2', 'Gestionnaire', NULL, NULL, NULL),
('Brown', 'James', NULL, '0345678912', 'james.brown@example.com', '2023-01-01', '2023-12-31', 'password3', 'Étudiant', 'M2', 'Ecole1', 'Ville1');

INSERT INTO DataChallenges (Libellé, DateDébut, DateFin, ID_Admin)
VALUES
('Challenge1', '2023-02-01', '2023-04-30', 1),
('Challenge2', '2023-05-01', '2023-07-31', 1);

INSERT INTO Projets (Libellé, Description, ImageURL, ID_DataChallenge)
VALUES
('Projet1', 'Description du Projet1', 'http://example.com/image1.jpg', 1),
('Projet2', 'Description du Projet2', 'http://example.com/image2.jpg', 2);

INSERT INTO Ressources (URL, Type, ID_Projet)
VALUES
('http://example.com/resource1', 'PDF', 1),
('http://example.com/resource2', 'Notebook', 2);

INSERT INTO Équipes (Nom, ID_Projet, ID_Capitaine)
VALUES
('Équipe1', 1, 3),
('Équipe2', 2, 3);

INSERT INTO MembresÉquipe (ID_Équipe, ID_Utilisateur)
VALUES
(1, 3),
(2, 3);

INSERT INTO Messages (Contenu, DateEnvoi, ID_Émetteur, ID_Équipe)
VALUES
('Bonjour tout le monde', NOW(), 1, 1),
('Salut', NOW(), 3, 1);

INSERT INTO Questionnaires (DateDébut, DateFin, ID_Gestionnaire)
VALUES
('2023-02-01', '2023-02-28', 2),
('2023-03-01', '2023-03-31', 2);

INSERT INTO Questions (Contenu, ID_Questionnaire)
VALUES
('Quelle est la réponse à la vie, l\'univers et tout ?', 1),
('Quel est le sens de la vie ?', 2);

INSERT INTO Réponses (Contenu, Note, ID_Question, ID_Équipe)
VALUES
('42', 10, 1, 1),
('La vie est ce que vous en faites', 8, 2, 2);

INSERT INTO AnalysesCode (NombreLignes, NombreFonctions, LignesMinFonction, LignesMaxFonction, LignesMoyennesFonction, ID_Équipe)
VALUES
(100, 10, 5, 20, 10.0, 1),
(200, 20, 5, 25, 15.0, 2);

INSERT INTO OccurrencesTermes (ID_AnalyseCode, Terme, NombreOccurrences)
VALUES
(1, 'function', 10),
(2, 'variable', 20);
