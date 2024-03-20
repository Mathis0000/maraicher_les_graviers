<?php
session_start();
if (!isset($_SESSION["secondarySession"])){
    header("Location: login.php");
}

if (empty($_SESSION["secondarySession"])){
    header("Location: login.php");
}


require("commande.php");

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
                            <a href="admin/ajouter.php" class="btn btn-primary mx-2 mb-2">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a href="admin/supprimer.php" class="btn btn-primary mx-2 mb-2">Nos Produits</a>
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
                        <div style="display: flex;justify-content: flex-end;">
                                <a href="deconnexion.php" class="btn btn-danger">Se deconnecter</a>
                        </div>
                    </ul>
                </nav>
            </div>
        </div>
    </header>



    
    
    
    
    
    
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Votre Entreprise. Tous droits réservés.</p>
    </footer>
    <!-- Bootstrap JavaScript (optionnel) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
