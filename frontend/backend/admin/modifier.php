<?php
session_start();
if (!isset($_SESSION["mainSession"])){
    header("Location: ../login.php");
}

if (empty($_SESSION["mainSession"])){
    header("Location: ../login.php");
}

require("../commande.php");
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
    <table class="table">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">image</th>
                <th scope="col">nom</th>
                <th scope="col">prix</th>
                <th scope="col">Description</th>
                <th scope="col">Saison</th>
                <th scope="col">Editer</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($mes_produits as $produit): ?>
    <tr>
        <th scope="row"><?= $produit->id ?></th>
        <td>
            <img src="/../../png/nos_produits/<?= $produit->image ?>" style="width: 15%">
        </td>
        <td><?= $produit->nom ?></td>
        <td style="font-weight: bold; color: green;"><?= $produit->prix ?>€</td>
        <td><?= $produit->stock_kg ?></td>
        <td><?= $produit->stock_unite ?></td>
        <td>
            <a href="editer.php?id=<?= $produit->id ?>" class="btn btn-primary">
                <i class="fa fa-pencil" style="font-size: 20px;"></i> Modifier
            </a>
        </td>
    </tr>      
<?php endforeach; ?>


            </tbody>
            </table>
    </div>
</div>
</div>

    
    <!-- Bootstrap JavaScript (optionnel) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
