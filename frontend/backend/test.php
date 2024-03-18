<?php
require("commande.php");
$mes_produits=afficher() ;
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

    <div class="album py-5 bg-body-tertiary">
        <div class="container">

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <form method="post">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Titre de l'image (ex: cerise.jpg)</label>
                        <input type="name" class="form-control" name="image" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Nom du produit</label>
                        <input type="text" class="form-control" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Prix</label>
                        <input type="number" class="form-control" name="prix" required>
                    </div>  
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Description</label>
                        <textarea class="form-control" name="description" required></textarea> 
                    </div>
                    <label for="saison">Choisissez une saison :</label>
                        <select name="saison" id="saison">
                            <option value="printemps">Printemps</option>
                            <option value="ete">Été</option>
                            <option value="automne">Automne</option>
                            <option value="hiver">Hiver</option>
                        </select>

                    <button type="submit" name="valider" class="btn btn-primary">Ajouter un nouveau produit</button>
                </form>
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

<?php
    if(isset($_POST['valider'])){
        if (isset($_POST['image']) AND isset($_POST['nom']) AND isset($_POST['prix']) AND isset($_POST['description']) AND isset($_POST['saison'])){
            if (!empty($_POST['image']) AND !empty($_POST['nom']) AND !empty($_POST['prix']) AND !empty($_POST['description'])){
                $image = htmlspecialchars(strip_tags($_POST['image']));
                $nom= htmlspecialchars(strip_tags($_POST['nom']));
                $prix= htmlspecialchars(strip_tags($_POST['prix']));
                $description= htmlspecialchars(strip_tags($_POST['description'])); 
                $saison= htmlspecialchars(strip_tags($_POST['saison']));

                try{
                    ajouter($image, $nom, $prix, $description, $saison);
                }catch(Exception $e){   
                    $e->getMessage();
                }

                
            }
        }
    }
?>
