<?php

function ajouter($image, $nom, $prix, $description, $saison){
    if (require("connexion.php")){
        $req = $access->prepare("INSERT INTO produits (image, nom, prix, description, saison) VALUES (?, ?, ?, ?, ?)");
        $req->execute([$image, $nom, $prix, $description, $saison]);
        
    }
}

function afficher(){
    if (require("connexion.php")){
        $req = $access->prepare("SELECT * FROM produits ORDER BY id DESC");
        $req->execute();
        $data = $req->fetchAll(PDO::FETCH_OBJ); //recurepre les donnees
        $req->closeCursor();
        return $data;
    }
}

function supprimer($id){
    if (require("connexion.php")){
        $req = $access->prepare("DELETE FROM produits WHERE id=?;");
        $req->execute(array($id));
        $req->closeCursor();
    }
}

?>