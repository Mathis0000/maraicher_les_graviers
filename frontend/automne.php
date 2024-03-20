<?php
require("commande.php");
$mes_produits=afficher_si_automne() ;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jardinier Maraîchers Les Graviers </title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!--lien reseau-->

    <div id="acceuil" class="container">
        <!-- Image cliquable -->
        <a href="https://www.facebook.com/JMlesgraviers/" target="_blank">
            <img src="png/facebook.png" alt="Image Cliquable" class="img-thumbnail rounded-circle float-end img-fluid" style="max-width: 40px;">
        </a>

        <a href="https://www.instagram.com/woukong15/" target="_blank">
            <img src="png/insta.png" alt="Image Cliquable" class="img-thumbnail rounded-circle float-end img-fluid" style="width: 40px;">
        </a>
    </div>
    <!--retour top-->


    <div class="img-container">
        <!-- Image cliquable -->
        <a href="#acceuil" target="_blank">
            <img src="png/fleche.png" alt="retour en haut" class="img-fluid">
        </a>
    </div>
    
    
    
    

    <!-- En-tête -->
    <header class="container-fluid py-3 custom-header">
        <div class="container">
            <div class="row align-items-center">
                <!-- Logo dans le premier tiers -->
                <div class="col-lg-4 col-md-12 text-center text-lg-start">
                    <img src="png/logo.jpg" alt="Logo" class="img-thumbnail rounded-circle" style="max-width: 50%;" >
                </div>
                <!-- Menu avec 5 boutons -->
                <nav class="col-lg-8 col-md-12">
                    <ul class="nav justify-content-center justify-content-lg-end">
                        <li class="nav-item">
                            <a href="index.html" class="btn btn-success mx-2 mb-2">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a href="nos_produits.html" class="btn btn-success mx-2 mb-2">Nos Produits</a>
                        </li>

                        <li class="nav-item">
                            <a href="recette.html" class="btn btn-success mx-2 mb-2">Recettes</a>
                        </li>
                        <li class="nav-item">
                            <a href="actualite.html" class="btn btn-success mx-2 mb-2">Actualités</a>
                        </li>
                      
                        <li class="nav-item">
                            <a href="backend/index.php" class="btn btn-success mx-2 mb-2">Drive</a>
                        </li>                        
                        <li class="nav-item">
                            <a href="contact.html" class="btn btn-success mx-2 mb-2">Contact</a>
                        </li>  
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <div class="album py-5 bg-body-tertiary">
    <div class="container">

      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">

      <?php foreach($mes_produits as $un_produit):?>
        <div class="col">
          <div class="card shadow-sm">
           <title><?=$un_produit->nom ?></title><rect width="100%" height="100%" fill="#55595c"/><img src="/../png/nos_produits/<?=$un_produit->image ?>">
            <div class="card-body">
              <p class="card-text"><?=$un_produit->description ?></p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-secondary">Acheter</button>
                </div>
                <small class="text-body-secondary"><?=$un_produit->prix ?> euros</small>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>

    </div>
</div>
    
    
    
    
    
    
    
    

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Votre Entreprise. Tous droits réservés.</p>
    </footer>
    <!-- Bootstrap JavaScript (optionnel) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>