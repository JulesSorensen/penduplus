<link rel="stylesheet" href="../style/boutique.css">
<?php
if(isset($_POST["btitem1"]) || isset($_POST["btitem2"]) || isset($_POST["btitem3"]) || isset($_POST["btitem4"])){
    if(isset($_POST["btitem1"])){
        $valeur = 40;
        $objet = "objet1";
        $textobjet = "Ceci est la description de l'objet 1, la pop up est incroyable !";
    }
    if(isset($_POST["btitem2"])){
        $valeur = 50;
        $objet = "objet2";
        $textobjet = "Ceci est la description de l'objet 2, la pop up est incroyable !";
    }
    if(isset($_POST["btitem3"])){
        $valeur = 60;
        $objet = "objet3";
        $textobjet = "Ceci est la description de l'objet 3, la pop up est incroyable !";
    }
    if(isset($_POST["btitem4"])){
        $valeur = 70;
        $objet = "objet4";
        $textobjet = "Ceci est la description de l'objet 4, la pop up est incroyable !";
    }
    if(isset($_SESSION["coin"])){
        if($_SESSION["coin"]>=$valeur){
            // if($_SESSION["objet"] && $_SESSION["textobjet"]){
                
            // }
            $_SESSION["coin"] -= $valeur;
            $_SESSION["objet"] = $objet;
            $_SESSION["textobjet"] = $textobjet;
            header("refresh:0");    
        }else{
            echo "Vous n'avez pas assez de PA";
        }
    }else{
        //ERROR
    }
}

?>


<h1 class="titre">BOUTIQUE</h1>
<h2 class="cointext"><?php if(isset($_SESSION["coin"]))?><?php echo $_SESSION["coin"]?> Points Amours restants</h2>
<h2 class="descri">Ici vous pouvez dépenser vos Points Amours pour acheter des objets afin de vous aider ou rajouter une touche de fun a votre vie !</h2>
<h2 class="descri">Attention seul 1 objet est utilisable, en acheter un deuxième vous fera perdre le premier et vos Points Amours avec.</h2>
<form method="POST" action="">
    <div class="itemslist">
        <div class="item item1">
            <div class="text">
                <h2>objet1</h2></br>
                <p>loremaze azezae aezaze azeeaz eazea eaz eaze aze</p></br>
                <button type="Submit" name="btitem1" id="btitem1">Acheter -40 PA</button>
            </div>
        </div>
        <div class="item item2">
            <div class="text">
                <h2>objet2</h2></br>
                <p>loremaze azezae aezaze azeeaz eazea eaz eaze aze</p></br>
                <button type="Submit" name="btitem2" id="btitem2">Acheter -50 PA</button>
            </div>
        </div>
        <div class="item item3">
            <div class="text">
                <h2>objet3</h2></br>
                <p>loremaze azezae aezaze azeeaz eazea eaz eaze aze</p></br>
                <button type="Submit" name="btitem3" id="btitem3">Acheter -60 PA</button>
            </div>
        </div>
        <div class="item item4">
            <div class="text">
                <h2>objet4</h2></br>
                <p>loremaze azezae aezaze azeeaz eazea eaz eaze aze</p></br>
                <button type="Submit" name="btitem4" id="btitem4">Acheter -70 PA</button>
            </div>
        </div>
    </div>
</form>


