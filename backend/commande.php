<?php

function ajouter($image, $nom, $prix, $description, $saison){
    if (require("connexion.php")){
        $req = $access->prepare("INSERT INTO produits (image, nom, prix, description, saison) VALUES ($image, $nom, $prix, $description, $saison)");
        $req->execute(array($image, $nom, $prix, $description, $saison));
        $req->closeCursor();
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
        $req = $access->prepare("DELETE * FROM produits WHERE id=?;");
        $req->execute(array($id));
        $req->closeCursor();
    }
}

?>