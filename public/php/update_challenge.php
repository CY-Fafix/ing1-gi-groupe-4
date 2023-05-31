<?php

include('header.php');
    // include('../../src/classes/Etudiant.php');
    require_once __DIR__ . '/../../src/classes/Database.php';
    require_once __DIR__ . '/../../src/classes/Administrateur.php';
    require_once __DIR__ . '/../../src/controllers/admin_controller.php';
    require_once __DIR__ . '/../../src/controllers/user_controller.php';


    //Si l'utilisateur n'est pas connecté en tant qu'administrateur on ne va pas sur cette page
    if (!isset($_SESSION['user_id'])){
        if ($_SESSION['role']!= 'Admin'){  
            echo  
            header('Location: ../index.php');
            exit;
        }
    }

        // Créer une nouvelle instance de Database
        $db = new Database();
        $controller = new AdminController();
