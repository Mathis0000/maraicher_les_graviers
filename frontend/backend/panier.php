<?php
session_start();
if (!isset($_SESSION["secondarySession"])){
    header("Location: login.php");
}

if (empty($_SESSION["secondarySession"])){
    header("Location: login.php");
}


require("commande.php");

if (isset($_POST['id_produit'])) {
    // Récupère l'identifiant du produit envoyé depuis le formulaire
    $id_produit = $_POST['id_produit'];

    // Récupérer les détails du produit à partir de la base de données
    $details_produit = get_data($id_produit);

    // Vérifie si les détails du produit existent et sont valides
    if ($details_produit !== false && !empty($details_produit)) {
        // Si le produit est vendu au poids
        if ($details_produit[0]->stock_kg != 0 && isset($_POST['quantite_kg'])) {
            // Récupère la nouvelle quantité en kilogrammes
            $quantite_kg = $_POST['quantite_kg'];
            // Vérifie si la quantité demandée ne dépasse pas le stock disponible en kilogrammes
            if ($quantite_kg <= $details_produit[0]->stock_kg) {
                $_SESSION["secondarySession"]["panier"][$id_produit]["quantite_kg"] = $quantite_kg;
            } else {
                // Si la quantité demandée dépasse le stock disponible en kilogrammes, utilisez le stock maximal disponible
                $_SESSION["secondarySession"]["panier"][$id_produit]["quantite_kg"] = $details_produit[0]->stock_kg;
            }
        } elseif ($details_produit[0]->stock_unite != 0 && isset($_POST['quantite_unite'])) {
            // Si le produit est vendu par unité
            // Récupère la nouvelle quantité en unités
            $quantite_unite = $_POST['quantite_unite'];
            // Vérifie si la quantité demandée ne dépasse pas le stock disponible en unités
            if ($quantite_unite <= $details_produit[0]->stock_unite) {
                $_SESSION["secondarySession"]["panier"][$id_produit]["quantite_unite"] = $quantite_unite;
            } else {
                // Si la quantité demandée dépasse le stock disponible en unités, utilisez le stock maximal disponible
                $_SESSION["secondarySession"]["panier"][$id_produit]["quantite_unite"] = $details_produit[0]->stock_unite;
            }
        }
    }
    header("Location: panier.php");
}





if(isset($_POST['id_produit'])) {
    // Récupère l'identifiant du produit envoyé depuis le formulaire
    $id_produit = $_POST['id_produit'];

    // Vérifie si le champ caché 'supprimer' est défini
    if(isset($_POST['supprimer'])) {
        // Si le champ caché 'supprimer' est défini, supprimez le produit du panier
        unset($_SESSION["secondarySession"]["panier"][$id_produit]);
    } else {
        // Sinon, redirigez vers la page du panier
        header("Location: panier.php");
        exit(); // Assurez-vous de terminer le script pour éviter toute exécution supplémentaire
    }
}
          

// Partie commande
if (!empty($_SESSION["secondarySession"]["panier"])) {
    if (isset($_POST['date_commande'])) {
        // Récupérer les informations sur la réservation
        $date_commande = $_POST['date_commande'];

        $date = new DateTime($date_commande);
        
        // Récupérer l'heure de la commande
        $heure_commande = $date->format('H:i');
        if ($heure_commande >= '14:00' && $heure_commande <= '19:00') {

            // Récupérer l'identifiant de l'utilisateur connecté depuis la session
            $user_id = $_SESSION['secondarySession']['id'];

            // Initialiser le montant total à 0
            $montant_total = 0;

            // Parcourir chaque produit dans le panier pour calculer le montant total et gérer les stocks
            foreach ($_SESSION["secondarySession"]["panier"] as $produit_id => $produit) {
                // Récupérer les détails du produit à partir de la base de données
                $details_produit = get_data($produit_id);

                // Vérifier si les détails du produit existent et sont valides
                if ($details_produit !== false && !empty($details_produit)) {
                    // Calculer le montant total de chaque produit en multipliant le prix unitaire par la quantité
                    $montant_produit = ($details_produit[0]->stock_kg != 0) ? $produit['quantite_kg'] * $details_produit[0]->prix : $produit['quantite_unite'] * $details_produit[0]->prix;
                    // Ajouter le montant total de chaque produit au montant total de la commande
                    $montant_total += $montant_produit;

                    // Gérer les stocks en fonction du type de produit
                    if ($details_produit[0]->stock_kg == 0) {
                        // Si le produit est vendu par unité, mettre à jour le stock en unités
                        $stock_unite = $details_produit[0]->stock_unite - $produit['quantite_unite'];
                        // Mettre à jour le stock dans la base de données
                        modifier_quantite_apres_commande($produit_id, 0, $stock_unite);
                    } else {
                        // Si le produit est vendu au poids, mettre à jour le stock en kilogrammes
                        $stock_kg = $details_produit[0]->stock_kg - $produit['quantite_kg'];
                        // Mettre à jour le stock dans la base de données
                        modifier_quantite_apres_commande($produit_id, $stock_kg, 0);
                    }
                }
            }

            // Ajouter une nouvelle commande dans la base de données
            $commande_id = commande($user_id, $date_commande, $montant_total);

            foreach ($_SESSION["secondarySession"]["panier"] as $produit_id => $produit) {
                $quantite_kg = isset($produit['quantite_kg']) ? $produit['quantite_kg'] : 0;
                $quantite_unite = isset($produit['quantite_unite']) ? $produit['quantite_unite'] : 0;
                detail_commande($commande_id, $produit_id, $quantite_kg, $quantite_unite);
            }
            


            // Remettre à zéro le panier
            $_SESSION["secondarySession"]["panier"] = array();


            // Rediriger vers une page de confirmation ou une autre page appropriée
            header("Location: index.php");
            exit; // Arrêter l'exécution du script après la redirection
        } else {
            echo "<div class='alert alert-danger' role='alert'>Les réservations ne sont possibles qu'entre 14h et 19h.</div>";
        }
    }
} else {
    echo "<div class='alert alert-danger' role='alert'>Votre panier est vide. Veuillez ajouter des produits avant de passer une commande.</div>";
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

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap DateTimePicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/build/css/tempusdominus-bootstrap-4.min.css">
    <!-- Include French locale for Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js"></script>
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

    <div class="container mt-5">
    <h2 class="text-center">Votre Panier</h2>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Produit</th>
                    <th scope="col">Quantité</th>
                    <th scope="col">Prix unitaire</th>
                    <th scope="col">Prix total</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Vérifie si le panier est vide
                if (!empty($_SESSION["secondarySession"]["panier"])) {
                    // Parcours chaque produit dans le panier
                    foreach ($_SESSION["secondarySession"]["panier"] as $id_produit => $produit) {
                        // Récupérer les détails du produit à partir de la base de données en utilisant l'ID
                        $details_produit = get_data($id_produit);
                    
                        // Vérifier si les détails du produit existent et sont valides
                        if ($details_produit !== false && !empty($details_produit)) {
                            // Maintenant, vous pouvez accéder aux informations du produit
                            // Par exemple, pour afficher le nom du produit
                            echo "<tr>";
                            echo "<td>{$details_produit[0]->nom}</td>"; // Supposons que la colonne 'nom' existe dans votre table 'produits'
                            echo "<td>";
                            echo "<form action='' method='post' class='d-flex'>";
                            echo "<input type='hidden' name='id_produit' value='{$id_produit}'>";
                            if (isset($produit['quantite_kg'])) {
                                // Si la quantité est en kg, affichez le champ quantite_kg
                                echo "<input type='number' style='width: 70px;' class='form-control me-2' name='quantite_kg' value='{$produit['quantite_kg']}' min='0.5' step='0.5'>";
                            } elseif (isset($produit['quantite_unite'])) {
                                // Si la quantité est en unité, affichez le champ quantite_unite
                                echo "<input type='number' style='width: 70px;' class='form-control me-2' name='quantite_unite' value='{$produit['quantite_unite']}' min='1'>";
                            }
                            echo "<button type='submit' class='btn btn-primary'>Modifier</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "<td>{$details_produit[0]->prix}</td>"; 
                            echo "<td>";
                            if (isset($produit['quantite_kg'])) {
                                // Si la quantité est en kg, calculez le prix total en kg
                                echo ($produit['quantite_kg'] * $details_produit[0]->prix) . " euros";
                            } elseif (isset($produit['quantite_unite'])) {
                                // Si la quantité est en unité, calculez le prix total en unité
                                echo ($produit['quantite_unite'] * $details_produit[0]->prix) . " euros";
                            }
                            echo "</td>";
                            echo "<td>";
                            echo "<form action='' method='post'>";
                            echo "<input type='hidden' name='id_produit' value='{$id_produit}'>";
                            echo "<input type='hidden' name='supprimer'>"; // Champ caché pour indiquer que le produit doit être supprimé
                            echo "<button type='submit' class='btn btn-danger'>Supprimer</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        } else {
                            // Gérer le cas où les détails du produit ne sont pas disponibles ou invalides
                            echo "<tr><td colspan='5'>Le produit correspondant à l'ID $id_produit n'existe pas ou ses détails sont introuvables</td></tr>";
                        }
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

        <div class="container">
    <h2 class="text-center mt-5 mb-4">Passer une commande</h2>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="post">
                <div class="form-group">
                    <label for="date_commande">Date de la commande :</label>
                    <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" id="date_commande" name="date_commande" data-target="#datetimepicker" required>
                        <div class="input-group-append" data-target="#datetimepicker" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                        </div>
                    </div>
                    <small class="form-text text-muted">Veuillez sélectionner une date et une heure pour votre commande.</small>
                </div>
                
                <button type="submit" class="btn btn-primary">Valider la commande</button>
            </form>
        </div>
    </div>
</div>
</div>
</div>



    
    
    
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Votre Entreprise. Tous droits réservés.</p>
    </footer>
    <!-- Bootstrap JavaScript (optionnel) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/build/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Initialisation du DateTimePicker -->
    <script>
        $(function () {
            // Définir la langue de moment.js sur le français
            moment.locale('fr');
            
            // Inclure la localisation française pour Bootstrap DateTimePicker
            $.fn.datetimepicker.Constructor.Default = $.extend({}, $.fn.datetimepicker.Constructor.Default, {
                locale: 'fr'
            });

            $('#datetimepicker').datetimepicker({
                format: 'YYYY-MM-DD HH:mm', // Format de date et heure
                minDate: moment() // Date minimale (aujourd'hui)
            });
        });
    </script>
</body>
</html>
