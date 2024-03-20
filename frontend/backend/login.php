<?php
session_start();
require("commande.php");

if(isset($_POST["envoyer"])){
    if(!empty($_POST["mail"]) && !empty($_POST['motdepasse'])){
        $mail = $_POST['mail'];
        $motdepasse = $_POST['motdepasse'];

        // Afficher les mots de passe hachés introduits et ceux de la base de données
        echo "Mot de passe introduit (haché) : " . password_hash($motdepasse, PASSWORD_DEFAULT) . "<br>";

        // Vérification des identifiants
        $admin = get_admin($mail, $motdepasse);
        $user = get_user($mail, $motdepasse);

        // Afficher les mots de passe hachés de la base de données
        if ($admin) {
            echo "Mot de passe enregistré (haché) pour l'administrateur : " . $admin['motdepasse'] . "<br>";
        }
        if ($user) {
            echo "Mot de passe enregistré (haché) pour l'utilisateur : " . $user['motdepasse'] . "<br>";
        }

        // Vérifier si l'utilisateur est trouvé et que le mot de passe est correct
        if($admin && password_verify($motdepasse, $admin['motdepasse'])){
            $_SESSION['mainSession'] = $admin;
            header('Location: admin/ajouter.php');
            exit;
        } else if($user && password_verify($motdepasse, $user['motdepasse'])) {
            $_SESSION['secondarySession'] = $user;
            header('Location: index.php');
            exit;
        } else {
            echo 'Problème de connexion administrateur ou utilisateur';
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
    <!--lien reseau-->

    <div id="acceuil" class="container">
        <!-- Image cliquable -->
        <a href="https://www.facebook.com/JMlesgraviers/" target="_blank">
            <img src="/../png/facebook.png" alt="Image Cliquable" class="img-thumbnail rounded-circle float-end img-fluid" style="max-width: 40px;">
        </a>

        <a href="https://www.instagram.com/woukong15/" target="_blank">
            <img src="/../png/insta.png" alt="Image Cliquable" class="img-thumbnail rounded-circle float-end img-fluid" style="width: 40px;">
        </a>
    </div>
    <!--retour top-->


    <div class="img-container">
        <!-- Image cliquable -->
        <a href="#acceuil">
            <img src="/../png/fleche.png" alt="retour en haut" class="img-fluid">
        </a>
    </div>
    
    
    
    

    <!-- En-tête -->
    <header class="container-fluid py-3 custom-header mb-2">
        <div class="container">
            <div class="row align-items-center">
                <!-- Logo dans le premier tiers -->
                <div class="col-lg-4 col-md-12 text-center text-lg-start">
                    <img src="/../png/logo.jpg" alt="Logo" class="img-thumbnail rounded-circle" style="max-width: 50%;" >
                </div>
                <!-- Menu avec 5 boutons -->
                <nav class="col-lg-8 col-md-12">
                    <ul class="nav justify-content-center justify-content-lg-end">
                        <li class="nav-item">
                            <a href="/../index.html" class="btn btn-primary mx-2 mb-2">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a href="/../nos_produits.html" class="btn btn-primary mx-2 mb-2">Nos Produits</a>
                        </li>

                        <li class="nav-item">
                            <a href="/../recette.html" class="btn btn-primary mx-2 mb-2">Recettes</a>
                        </li>
                        <li class="nav-item">
                            <a href="/../actualite.html" class="btn btn-primary mx-2 mb-2">Actualités</a>
                        </li>
                      
                        <li class="nav-item">
                            <a href="/../index.html#partenaire" class="btn btn-primary mx-2 mb-2">Partenaires</a>
                        </li>                        
                        <li class="nav-item">
                            <a href="/../contact.html" class="btn btn-primary mx-2 mb-2">Contact</a>
                        </li>  
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">

            </div>
            <div class="col-md-6">
                
                <form method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="mail" required>
                    </div>
                    <div class="mb-3">
                        <label for="motdepasse" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" name="motdepasse" required>
                    </div>
                    <input type="submit" class="btn btn-primary" name="envoyer" value="Se connecter">
                </form>
                <br>
                <li class="nav-item">
                    <a href="inscription.php" class="btn btn-primary mx-2 mb-2">S'inscrire</a>
                </li> 
                
            </div>
            <div class="col-md-3">
                
            </div>
        </div>
    </div>
    
    
    
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Votre Entreprise. Tous droits réservés.</p>
    </footer>
    <!-- Bootstrap JavaScript (optionnel) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

