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

    <div class="container custom-container">
        <div class="row">
            <!-- Première paire : alternance gauche-droite -->
            <div class="col-md-6 order-md-2">
                <img src="png/nos_produits/asperge.jpg" alt="Image" class="img-fluid image-landscape">
            </div>
            <div class="col-md-6 order-md-1">
                <p class="custom-text">
                    Les asperges sont des légumes verts tendres et délicats, riches en fibres, vitamines et minéraux. Elles peuvent être grillées, cuites à la vapeur, ou ajoutées à des plats tels que des omelettes ou des salades.
                </p>
            </div>
        </div>
        <div class="row">
            <!-- Deuxième paire : alternance gauche-droite -->
            <div class="col-md-6 order-md-3">
                <img src="png/nos_produits/cerise.jpg" alt="Image" class="img-fluid image-portrait">
            </div>
            <div class="col-md-6 order-md-4">
                <p class="custom-text">
                    Les cerises sont des fruits sucrés et juteux, disponibles dans une variété de couleurs. Elles sont riches en antioxydants et peuvent être consommées fraîches, utilisées dans des desserts, ou transformées en confitures.
                </p>
            </div>
        </div>
        <div class="row">
            <!-- Troisième paire : alternance gauche-droite -->
            <div class="col-md-6 order-md-2">
                <img src="png/nos_produits/epinard.jpg" alt="Image" class="img-fluid image-landscape">
            </div>
            <div class="col-md-6 order-md-1">
                <p class="custom-text">
                    Les épinards sont des feuilles vertes foncées riches en fer, calcium et vitamines. Ils sont polyvalents et peuvent être consommés crus dans des salades, cuits dans des plats sautés ou ajoutés à des smoothies.
                </p>
            </div>
        </div>
        <div class="row">
            <!-- Quatrième paire : alternance gauche-droite -->
            <div class="col-md-6 order-md-3">
                <img src="png/nos_produits/fraise.jpg" alt="Image" class="img-fluid image-portrait">
            </div>
            <div class="col-md-6 order-md-4">
                <p class="custom-text">
                    Les fraises sont des baies sucrées et juteuses, riches en vitamine C et en antioxydants. Elles sont souvent consommées fraîches, ajoutées à des salades, des desserts ou utilisées pour préparer des confitures.
                </p>
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