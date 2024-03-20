<?php
require("commande.php");

if(isset($_POST['inscription'])){
    if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['mail']) && isset($_POST['motdepasse'])){
        if (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['mail']) && !empty($_POST['motdepasse'])){
            $nom = htmlspecialchars(strip_tags($_POST['nom']));
            $prenom= htmlspecialchars(strip_tags($_POST['prenom']));
            $mail= htmlspecialchars(strip_tags($_POST['mail']));
            $motdepasse = password_hash($_POST['motdepasse'], PASSWORD_DEFAULT); 
     
            try{
                inscription($nom, $prenom, $mail, $motdepasse);
                header('Location: login.php');
            }catch(Exception $e){   
                $e->getMessage();
            }
            
        } else {
            echo "Tous les champs doivent être remplis.";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jardinier Maraîchers Les Graviers </title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/../style.css">
</head>
<body>
<header class="container-fluid py-3 custom-header mb-2">
    <div class="container">
        <div class="row align-items-center">
            <!-- Logo dans le premier tiers -->
            <div class="col-lg-4 col-md-12 text-center text-lg-start">
                <img src="/../png/logo.jpg" alt="Logo" class="img-thumbnail rounded-circle" style="max-width: 50%;">
            </div>
            <!-- Menu avec 5 boutons -->
            <nav class="col-lg-8 col-md-12">
                <ul class="nav justify-content-center justify-content-lg-end">
                    <li class="nav-item">
                        <a href="ajouter.php" class="btn btn-primary mx-2 mb-2">Ajouter produit</a>
                    </li>
                    <li class="nav-item">
                        <a href="supprimer.php" class="btn btn-primary mx-2 mb-2">Supprimer produit</a>
                    </li>
                    <li class="nav-item">
                        <a href="modifier.php" class="btn btn-primary mx-2 mb-2">Editer produit</a>
                    </li>
                    <div style="display: flex;justify-content: flex-end;">
                        <a href="deconnexion.php" class="btn btn-danger">Se deconnecter</a>
                    </div>
                </ul>
            </nav>
        </div>
    </div>
</header>


<!-- Formulaire d'inscription pour les nouveaux utilisateurs -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2>Inscription</h2>
            <form method="post">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>
                <div class="mb-3">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="mail" required>
                </div>
                <div class="mb-3">
                    <label for="motdepasse" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="motdepasse" name="motdepasse" required>
                </div>
                <button type="submit" name="inscription" class="btn btn-primary">S'inscrire</button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JavaScript (optionnel) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

