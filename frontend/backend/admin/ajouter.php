<?php
session_start();
if (!isset($_SESSION["mainSession"])){
    header("Location: ../login.php");
}

if (empty($_SESSION["mainSession"])){
    header("Location: ../login.php");
}

require("../commande.php");

$targetDir = "../../png/nos_produits/"; // Répertoire où vous souhaitez stocker les images téléchargées

// Vérifiez si le fichier a été correctement téléchargé

if(isset($_POST['valider'])){
    if(isset($_FILES["image"])) {
        $targetFile = basename($_FILES["image"]["name"]); // Chemin complet du fichier téléchargé

        // Récupérer l'extension du fichier téléchargé
        $extension = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    }
    if (isset($_POST['nom']) && isset($_POST['prix']) && isset($_POST['description']) && isset($_POST['saison'])){
        if (!empty($_POST['nom']) && !empty($_POST['prix']) && !empty($_POST['description'])){
            $nom = htmlspecialchars(strip_tags($_POST['nom']));
            $prix = htmlspecialchars(strip_tags($_POST['prix']));
            $description = htmlspecialchars(strip_tags($_POST['description'])); 
            $saison = htmlspecialchars(strip_tags($_POST['saison']));
            $stock_kg = !empty($_POST['stock_kg']) ? htmlspecialchars(strip_tags($_POST['stock_kg'])) : 0;
            $stock_unite = !empty($_POST['stock_unite']) ? htmlspecialchars(strip_tags($_POST['stock_unite'])) : 0;
            


            try {
                ajouter($targetFile, $nom, $prix, $description, $saison, $stock_kg, $stock_unite);
                echo "Le produit a été ajouté avec succès à la base de données.";
            } catch(Exception $e) {   
                echo "Une erreur s'est produite lors de l'ajout du produit à la base de données : " . $e->getMessage();
            }       
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
                        <img src="/../png/logo.jpg" alt="Logo" class="img-thumbnail rounded-circle" style="max-width: 50%;" >
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
                </div>
                        </ul>
                    </nav>

            </div>
    </header>

    <div class="album py-5 bg-body-tertiary">
        <div class="container">

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="image" class="form-label">Sélectionner une image :</label>
                        <input type="file" class="form-control" id="image" name="image" accept=".jpg, .png" required>
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
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Stock en kilos</label>
                            <input type="number" class="form-control" name="stock_kg">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Stock en unite</label>
                            <input type="number" class="form-control" name="stock_unite" >
                        </div>

                    <button type="submit" name="valider" class="btn btn-primary">Ajouter un nouveau produit</button>
                </form>
        </div>
    </div>
</div>
    

    <!-- Bootstrap JavaScript (optionnel) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

