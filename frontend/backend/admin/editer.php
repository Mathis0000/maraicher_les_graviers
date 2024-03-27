<?php
session_start();
if (!isset($_SESSION["mainSession"]) || empty($_SESSION["mainSession"])) {
    header("Location: ../login.php");
    exit(); // Terminer le script après une redirection
}

require("../commande.php");

// Vérifier si l'ID est défini et valide
$id = null;
if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
} else {
    // Rediriger si l'ID n'est pas valide
    header("Location: ../error.php");
    exit();
}

$mes_produits = afficher();
$targetDir = "../../png/nos_produits/"; // Répertoire où vous souhaitez stocker les images téléchargées

// Vérifiez si le fichier a été correctement téléchargé
if(isset($_POST['valider'])){
    // Vérifiez si un fichier a été téléchargé
    if(isset($_FILES["image"]) && $_FILES["image"]["error"] != UPLOAD_ERR_NO_FILE) {
        $targetFile = basename($_FILES["image"]["name"]); // Chemin complet du fichier téléchargé

        // Récupérer l'extension du fichier téléchargé
        $extension = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    } else { 
        $targetFile =get_image($id); // Utilisez le nom de l'image actuelle
    }

    // Assurez-vous que toutes les données POST nécessaires sont définies et non vides
    $required_fields = ['nom', 'prix', 'description', 'saison', 'stock_kg', 'stock_unite'];
    if (array_diff($required_fields, array_keys($_POST)) === []) {
        // Récupérez les données POST
        $nom = htmlspecialchars(strip_tags($_POST['nom']));
        $prix = htmlspecialchars(strip_tags($_POST['prix']));
        $description = htmlspecialchars(strip_tags($_POST['description']));
        $saison = htmlspecialchars(strip_tags($_POST['saison']));
        $stock_kg = htmlspecialchars(strip_tags($_POST['stock_kg']));
        $stock_unite = htmlspecialchars(strip_tags($_POST['stock_unite']));

        // Essayez de modifier le produit
        try {
            modifier($targetFile, $nom, $prix, $description, $id, $saison, $stock_kg, $stock_unite);
            header('Location: ajouter.php');
            exit(); // Terminer le script après une redirection
        } catch (Exception $e) {
            echo "Une erreur est survenue lors de la modification du produit.";
        }
    } else {
        echo "Tous les champs doivent être remplis.";
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
                    <li class="nav-item">
                                <a href="diapo.php" class="btn btn-primary mx-2 mb-2">Diapo</a>
                            </li>         
                    <div style="display: flex;justify-content: flex-end;">
                        <a href="deconnexion.php" class="btn btn-danger">Se deconnecter</a>
                    </div>
                </ul>
            </nav>

        </div>
    </div>
</header>

<div class="album py-5 bg-light">
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            <?php foreach ($mes_produits as $produit): ?>
                <?php if ($produit->id == $id): ?>
                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="image" class="form-label">Sélectionner une image :</label>
                            <input type="file" class="form-control" id="image" name="image" accept=".jpg, .png" >
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Nom du produit</label>
                            <input type="text" class="form-control" name="nom" value="<?= $produit->nom ?>"  required>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Prix</label>
                            <input type="number" class="form-control" name="prix" value="<?= $produit->prix ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Description</label>
                            <textarea class="form-control" name="description" required><?= $produit->description ?></textarea>
                        </div>
                        <label for="saison">Choisissez une saison :</label>
                        <select name="saison" id="saison">
                            <option value="printemps" <?= ($produit->saison == "printemps") ? "selected" : "" ?>>Printemps</option>
                            <option value="ete" <?= ($produit->saison == "ete") ? "selected" : "" ?>>Été</option>
                            <option value="automne" <?= ($produit->saison == "automne") ? "selected" : "" ?>>Automne</option>
                            <option value="hiver" <?= ($produit->saison == "hiver") ? "selected" : "" ?>>Hiver</option>
                        </select>

                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Stock en kilos</label>
                            <input type="number" class="form-control" name="stock_kg" value="<?= $produit->stock_kg ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Stock en unite</label>
                            <input type="number" class="form-control" name="stock_unite" value="<?= $produit->stock_unite ?>" required>
                        </div>


                        <button type="submit" name="valider" class="btn btn-success">Enregistrer</button>
                    </form>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Bootstrap JavaScript (optionnel) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
