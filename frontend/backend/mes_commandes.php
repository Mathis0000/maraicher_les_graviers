<?php
session_start();
if (!isset($_SESSION["secondarySession"])){
    header("Location: login.php");
}

if (empty($_SESSION["secondarySession"])){
    header("Location: login.php");
}

require("commande.php");
$user_id = $_SESSION['secondarySession']['id'];
$commandes = get_commande($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jardinier Maraîchers Les Graviers</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/../style.css">
</head>
<body>

<!-- Entête -->
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
                            <a href="../index.html" class="btn btn-success mx-2 mb-2">Accueil</a>
                        </li>                        
                        <li class="nav-item">
                            <a href="index.php" class="btn btn-success mx-2 mb-2">Le Drive</a>
                        </li>
                        <li class="nav-item">
                            <a href="panier.php" class="btn btn-success mx-2 mb-2">Mon Panier</a>
                        </li>

                        <li class="nav-item">
                            <a href="mes_commandes.php" class="btn btn-success mx-2 mb-2">Mes Commandes</a>
                        </li>
 
                        <li class="nav-item">
                            <a href="admin/deconnexion.php" class="btn btn-danger">Se deconnecter</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

<div class="container">
    <h2>Mes Commandes</h2>
    <div class="row">
        <?php foreach ($commandes as $commande): ?>
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Commande du <?= date('d/m/Y', strtotime($commande->date_commande)) ?></h5>
                        <p class="card-text">Montant total: <?= $commande->montant_total ?> €</p>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#boxModal<?= $commande->id ?>">Voir les détails</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php foreach ($commandes as $commande): ?>
    <div class="modal fade" id="boxModal<?= $commande->id ?>" tabindex="-1" role="dialog" aria-labelledby="boxModalLabel<?= $commande->id ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="boxModalLabel<?= $commande->id ?>">Produits de la commande du <?= date('d/m/Y', strtotime($commande->date_commande)) ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                    $produits_commande = get_commande_produits($commande->id);
                    foreach ($produits_commande as $produit) {
                        $details_produit = get_data($produit->produit_id);
                        if ($details_produit) {
                            echo '<p><strong>' . $details_produit[0]->nom . '</strong> - Quantité : ' . $produit->quantite .'</p>';
                        } else {
                            echo "<p>Produit introuvable</p>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>










    <!-- Pied de page -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Votre Entreprise. Tous droits réservés.</p>
    </footer>

    <!-- Bootstrap JavaScript (optionnel) -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
