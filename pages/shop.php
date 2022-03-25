<link rel="stylesheet" href="../style/boutique.css">
<?php
if (isset($_POST["btitem1"]) || isset($_POST["btitem2"]) || isset($_POST["btitem3"]) || isset($_POST["btitem4"])) {
    if (isset($_POST["btitem1"])) {
        $valeur = 40;
        $objet = "Bouclier";    //Nom de l'objet
        $textobjet = "Il faut parfois être prudent, ce bouclier vous offre 3 tentatives supplémentaires";
    }
    if (isset($_POST["btitem2"])) {
        $valeur = 50;
        $objet = "Investisseur";    //Nom de l'objet
        $textobjet = "Miser sur vos talents, et multiplier par 3 vos gains si vous venez à bout du pendu en moins de 3 erreurs";
    }
    if (isset($_POST["btitem3"])) {
        $valeur = 60;
        $objet = "Chanceux";    //Nom de l'objet
        $textobjet = "Vous avez une petite probabilité de faire apparaitre une lettre suite à une erreur";
    }
    if (isset($_POST["btitem4"])) {
        $valeur = 70;
        $objet = "Jedi";    //Nom de l'objet
        $textobjet = "« Vis le moment présent, ne pense pas ; ressens, utilise ton instinct et ton intuition, ressens la Force. »";
    }
    if (isset($_SESSION["coin"])) {
        if ($_SESSION["coin"] >= $valeur) {
            // if($_SESSION["objet"] && $_SESSION["textobjet"]){

            // }
            $_SESSION["coin"] -= $valeur;   //Variable qui enregistre le nb des pieces
            $_SESSION["objet"] = $objet;    //Variable qui enregistre le nom de l'objet
            $_SESSION["textobjet"] = $textobjet;
            header("refresh:0");
        } else {
            echo "Vous n'avez pas assez de PA";
        }
    } else {
        //ERROR
    }
}

?>

<!--1 BOUCLIER : 3 TENTATIVES SUPP (FONCTION POUR T'AIDER):
    if(isset($_SESSION["objet"])){
        if($_SESSION["objet"] == "Bouclier"){
            //ton code
        }
    } 
-->

<!--2 Investisseur : x3 GAINS pour -3 ERREURS-->

<!--3 Chanceux : 20% CHANCE DE FAIRE APPARAITRE UNE LETTRE SUITE A L'ERREUR  (FONCTION POUR T'AIDER):
    $proba = 20;//20% chance.
    $rand = rand(1, 100);//aléatoire entre 1 et 100.
    if($rand <= $proba):
        //C'est réussi
    else:
        //C'est foiré.
    endif; 
-->

<!--4 Jedi : FAIT APPARAITRE DES LETTRES DES LE DEBUT (EN FONTION DE LA LONGUEUR OU NON, COMME TU LE SENS) -->

<h1 class="titre">BOUTIQUE</h1>
<h2 class="cointext"><?php if (isset($_SESSION["coin"])) ?><?php echo $_SESSION["coin"] ?><img class="piecetitre" src="../images/piece.png" alt=""> Points Amours restants</h2>
<h2 class="descri">Ici vous pouvez dépenser vos Points Amours pour acheter des objets afin de vous aider ou rajouter une touche de fun a votre vie !</h2>
<h2 class="descri">Attention seul 1 objet est utilisable, en acheter un deuxième vous fera perdre le premier et vos Points Amours avec.</h2>
<form method="POST" action="">
    <div class="container">
        <div class="card">
            <div class="box">
                <div class="content">
                    <h3>Bouclier</h3>
                    <img src="../images/bouclier.png" alt="">
                    <p>Il faut parfois être prudent, ce bouclier vous offre 3 tentatives supplémentaires</p>
                    <div class="container2">
                        <div class="center">
                                <button type="submit" name="btitem1" class="btn">
                                    <svg width="180px" height="60px" viewBox="0 0 180 60" class="border"><polyline points="179,1 179,59 1,59 1,1 179,1" class="bg-line" /><polyline points="179,1 179,59 1,59 1,1 179,1" class="hl-line" /></svg>
                                    <span>Acheter - 40 <img class="piece" src="../images/piece.png" alt="PA"></span>
                                </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="box">
                <div class="content">
                    <h3>Investisseur</h3>
                    <img src="../images/investisseur.png" alt="">
                    <p>Miser sur vos talents, et multiplier par 3 vos gains si vous venez à bout du pendu en moins de 3 erreurs</p>
                    <div class="container2">
                        <div class="center">
                                <button type="submit" name="btitem2" class="btn">
                                    <svg width="180px" height="60px" viewBox="0 0 180 60" class="border"><polyline points="179,1 179,59 1,59 1,1 179,1" class="bg-line" /><polyline points="179,1 179,59 1,59 1,1 179,1" class="hl-line" /></svg>
                                    <span>Acheter - 50 <img class="piece" src="../images/piece.png" alt="PA"></span>
                                </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="box">
                <div class="content">
                    <h3>Chanceux</h3>
                    <img src="../images/chanceux.png" alt="">
                    <p>Vous avez une petite probabilité de faire apparaitre une lettre suite à une erreur</p>
                    <div class="container2">
                        <div class="center">
                                <button type="submit" name="btitem3" class="btn">
                                    <svg width="180px" height="60px" viewBox="0 0 180 60" class="border"><polyline points="179,1 179,59 1,59 1,1 179,1" class="bg-line" /><polyline points="179,1 179,59 1,59 1,1 179,1" class="hl-line" /></svg>
                                    <span>Acheter - 60 <img class="piece" src="../images/piece.png" alt="PA"></span>
                                </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="box">
                <div class="content">
                    <h3>Jedi</h3>
                    <img src="../images/jedi.png" alt="">
                    <p>« Vis le moment présent, ne pense pas ; ressens, utilise ton instinct et ton intuition, ressens la Force. »</p>
                    <div class="container2">
                        <div class="center">
                                <button type="submit" name="btitem1" class="btn">
                                    <svg width="180px" height="60px" viewBox="0 0 180 60" class="border"><polyline points="179,1 179,59 1,59 1,1 179,1" class="bg-line" /><polyline points="179,1 179,59 1,59 1,1 179,1" class="hl-line" /></svg>
                                    <span>Acheter - 70 <img class="piece" src="../images/piece.png" alt="PA"></span>
                                </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>