<?php
session_start();
if (!isset($_SESSION["mainSession"])){
    header("Location: ../login.php");
}

if (empty($_SESSION["mainSession"])){
    header("Location: ../login.php");
}

require("../commande.php");
$targetDir = "/var/www/html/maraicher_les_graviers/frontend/diapo/"; // Répertoire où vous souhaitez stocker les images téléchargées

// Définir la limite de taille de téléchargement
ini_set('upload_max_filesize', '10M');
ini_set('post_max_size', '10M');

if(isset($_POST['valider'])){
    echo "Validation du formulaire reçue.<br>";

    if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0){
        echo "Fichier image détecté.<br>";
        $filename = $_FILES["photo"]["name"];
        $filetype = $_FILES["photo"]["type"];
        $filesize = $_FILES["photo"]["size"];

        // Vérifie l'extension du fichier
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        echo "Extension du fichier : $ext<br>";

        // Vérifie la taille du fichier - 5Mo maximum
        $maxsize = 10 * 1024 * 1024;
        if($filesize > $maxsize) {
            echo "Error: La taille du fichier est supérieure à la limite autorisée.<br>";
            die();
        }

        // Vérifie le type MIME du fichier
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        if(!in_array($filetype, $allowed)){
            echo "Error: Type de fichier non autorisé. Veuillez télécharger une image au format JPG, JPEG, GIF ou PNG.<br>"; 
            die();
        }

        // Vérifie si le fichier existe avant de le télécharger.
        if(file_exists($targetDir . $filename)){
            echo "$filename existe déjà.<br>";
        } else{
            move_uploaded_file($_FILES["photo"]["tmp_name"], $targetDir . $filename);
            echo "Votre fichier $filename a été téléchargé avec succès.<br>";
        }

        $maxImages = 10;

        // Vérifie le nombre total d'images
        $imagesCount = count(glob($targetDir . "*.jpg")) + count(glob($targetDir . "*.jpeg")) + count(glob($targetDir . "*.gif")) + count(glob($targetDir . "*.png"));

        // Supprime les images les plus anciennes si le nombre total d'images dépasse $maxImages
        if ($imagesCount > $maxImages) {
            // Liste les fichiers images triés par date de création (les plus anciens en premier)
            $files = scandir($targetDir);
            foreach ($files as $file) {
                $filePath = $targetDir . $file;
                // Vérifie si le fichier est une image
                if (is_file($filePath) && in_array(pathinfo($filePath, PATHINFO_EXTENSION), array("jpg", "jpeg", "gif", "png"))) {
                    // Supprime le fichier image
                    unlink($filePath);
                    $imagesCount--; // Décrémente le nombre total d'images
                    if ($imagesCount <= $maxImages) {
                        break; // Sort de la boucle si le nombre d'images est inférieur ou égal à $maxImages
                    }
                }
            }
        }
    } else{
        echo "Error: " . $_FILES["photo"]["error"] . "<br>";
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
                            <li class="nav-item">
                                <a href="diapo.php" class="btn btn-primary mx-2 mb-2">Diapo</a>
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
                        <input type="file" name="photo" id="fileUpload">
                        <p><strong>Note:</strong> Seuls les formats .jpg, .jpeg, .jpeg, .gif, .png sont autorisés.</p>
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

