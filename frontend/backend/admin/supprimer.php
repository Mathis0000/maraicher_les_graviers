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
                                <div style="display: flex;justify-content: flex-end;">
                                <a href="deconnexion.php" class="btn btn-danger">Se deconnecter</a>
                                </div>
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
                        <label for="exampleInputPassword1" class="form-label">Identifiant du produit</label>
                        <input type="number" class="form-control" name="id_produit" required>
                    </div>  

                    <button type="submit" name="valider" class="btn btn-primary">Supprimer le produit</button>
                </form>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <?php foreach($mes_produits as $un_produit):?>
                    <div class="col">
                        <div class="card shadow-sm">
                            <img src="/../png/nos_produits/<?=$un_produit->image ?>">
                            <h3><?=$un_produit->id ?></h3>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    
    <!-- Bootstrap JavaScript (optionnel) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
    if(isset($_POST['valider'])){
        if (isset($_POST['id_produit']) ){
            if (!empty($_POST['id_produit']) AND is_numeric($_POST['id_produit']) ){
                
                $id= htmlspecialchars(strip_tags($_POST['id_produit']));

                try{
                   supprimer($id);
                }catch(Exception $e){   
                    $e->getMessage();
                }

                
            }
        }
    }
?>
