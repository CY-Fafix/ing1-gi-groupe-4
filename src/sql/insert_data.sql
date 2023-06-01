USE datachallenge_db;

INSERT INTO Utilisateurs (Nom, Prenom, Entreprise, Telephone, Email, DateDebut, DateFin, MotDePasse, Role, Niveau, Ecole, Ville)
VALUES
('Potter', 'Harry', 'Cy entreprise', '0123456789', 'admin@gmail.com', '2023-01-01', '2023-12-31', '1234', 'Admin', NULL, NULL, NULL),
('Bombal', 'David', 'Helioparc Entreprise', '0234567891', 'projeting1pafa@gmail.com', '2023-01-01', '2023-12-31', '1234', 'Gestionnaire', NULL, NULL, NULL),
('Brown', 'Helian', NULL, '0345678912', 'adri.jacob22@gmail.com', '2023-01-01', '2023-12-31', '1234', 'Etudiant', 'M2', 'Ecole1', 'Ville1');

INSERT INTO DataChallenges (Libelle, DateDebut, DateFin, ID_Admin)
VALUES
('Challenge1', '2023-02-01', '2023-04-30', 1),
('Challenge2', '2023-02-01', '2023-07-30', 1);

INSERT INTO Projets (Libelle, Description, ImageURL, ID_DataChallenge, ID_Gestionnaire)
VALUES
('Projet1', 'Un projet pour pour aider les services de renseignement français à detecter un profil type de telecommunication pour anticiper des attentats terroristes', 'https://www.dgse.gouv.fr/sites/default/files/img/Logo_light_blue.png', 1, 2),
('Projet2', 'Un projet pour aider la NASA à developper son IA, vous pourriez peut etre un jour avoir votre IA dans lespace', 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e5/NASA_logo.svg/1200px-NASA_logo.svg.png', 2, NULL),
('Projet3', 'Un projet au service du développement durable, utiliser l IA pour economiser de l energie dans un flux de transport', 'https://www.lecannetdesmaures.com/media/k2/items/cache/9415f9bcd76598f9c08127db1641b596_XL.jpg', 1, NULL);
;


INSERT INTO Ressources (URL, Type, ID_Projet)
VALUES
('https://www.emse.fr/~picard/cours/ai/chapter01.pdf', 'PDF', 1),
('https://www.youtube.com/watch?v=CsQNF9s78Nc', 'Notebook', 2);

INSERT INTO Equipes (Nom, ID_Projet, ID_Capitaine)
VALUES
('Equipe1', 1, 3);

INSERT INTO MembresEquipe (ID_Equipe, ID_Utilisateur)
VALUES
(1, 3);

INSERT INTO Messages (Contenu, DateEnvoi, ID_Emetteur, ID_Equipe)
VALUES
('Bonjour tout le monde', NOW(), 1, 1),
('Salut', NOW(), 3, 1);

INSERT INTO Questionnaires (DateDebut, DateFin, ID_Gestionnaire,ID_Projet)
VALUES
('2023-02-01', '2023-04-30', 2, 1),
('2023-02-01', '2023-03-31', 2, 1);

INSERT INTO Questions (Contenu, ID_Questionnaire)
VALUES
('Donnez le modele IA utilisé pour votre projet, justifier de votre choix', 1),
('Pensez vous qu il soit necessaire de nettoyer les donnes avant la phase d apprentissage ?', 2);

INSERT INTO Reponses (Contenu, Note, ID_Question, ID_Equipe)
VALUES
('On utilise une IA de type reseau neuronal profond', 10, 1, 1);

INSERT INTO AnalysesCode (NombreLignes, NombreFonctions, LignesMinFonction, LignesMaxFonction, LignesMoyennesFonction, ID_Equipe)
VALUES
(100, 10, 5, 20, 10.0, 1);

INSERT INTO OccurrencesTermes (ID_AnalyseCode, Terme, NombreOccurrences)
VALUES
(1, 'function', 10);
