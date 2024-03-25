<?php
session_start();
if (!isset($_SESSION["secondarySession"])){
    header("Location: login.php");
}

if (empty($_SESSION["secondarySession"])){
    header("Location: login.php");
}

require("commande.php");

// Vérifie si le panier existe dans la session
if (!isset($_SESSION["secondarySession"]["panier"])) {
    $_SESSION["secondarySession"]["panier"] = array();
}

if(isset($_POST['id_produit'])) {
    $id_produit = $_POST['id_produit'];

    // Vérifie si la quantité est spécifiée en unité ou en kg
    if (isset($_POST['quantite_unite'])) {
        $quantite = $_POST['quantite_unite'];
    } elseif (isset($_POST['quantite'])) {
        $quantite = $_POST['quantite'];
    }

    // Vérifie si le produit est déjà dans le panier
    if (isset($_SESSION["secondarySession"]["panier"][$id_produit])) {
        // Met à jour la quantité du produit existant dans le panier
        $_SESSION["secondarySession"]["panier"][$id_produit]["quantite"] += $quantite;
    } else {
        // Ajoute le produit au panier avec la quantité spécifiée
        $_SESSION["secondarySession"]["panier"][$id_produit] = array(
            "quantite" => $quantite,
            "id_produit" => $id_produit
            // Vous pouvez également stocker d'autres informations sur le produit ici
        );
    }
}

$mes_produits = afficher_si_stock();
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


    <!-- Contenu principal -->
    <div class="album py-5 bg-body-tertiary">
        <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <?php foreach($mes_produits as $un_produit):?>
                    <div class="col">
                        <div class="card shadow-sm">
                            <title><?=$un_produit->nom ?></title><rect width="100%" height="100%" fill="#55595c"/><img src="/../png/nos_produits/<?=$un_produit->image ?>">
                            <div class="card-body">
                                <p class="card-text"><?=$un_produit->nom ?></p>
                                <?php if ($un_produit->stock_kg != 0): ?>
                                    <p class="card-text"><?=$un_produit->stock_kg ?> kg</p>
                                    <form action="" method="post">
                                        <!-- Champ d'entrée pour la quantité -->
                                        <input type="number" name="quantite" value="1" min="1" max="<?=$un_produit->stock_kg ?>" required>
                                        <!-- Champ d'entrée caché pour l'ID du produit -->
                                        <input type="hidden" name="id_produit" value="<?=$un_produit->id ?>">
                                        <!-- Bouton pour ajouter au panier -->
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">Acheter</button>
                                    </form>
                                <?php endif; ?>

                                <?php if ($un_produit->stock_unite != 0): ?>
                                    <p class="card-text"><?=$un_produit->stock_unite ?> en stock</p>
                                    <form action="" method="post">
                                        <!-- Champ d'entrée pour la quantité -->
                                        <input type="number" name="quantite_unite" value="1" min="1" max="<?=$un_produit->stock_unite ?>" required>
                                        <!-- Champ d'entrée caché pour l'ID du produit -->
                                        <input type="hidden" name="id_produit" value="<?=$un_produit->id ?>">
                                        <!-- Bouton pour ajouter au panier -->
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">Acheter</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Votre Entreprise. Tous droits réservés.</p>
    </footer>

    <!-- Bootstrap JavaScript (optionnel) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
