<link rel="stylesheet" href="../style/boutique.css">
<?php
if(isset($_POST["btitem1"]) || isset($_POST["btitem2"]) || isset($_POST["btitem3"]) || isset($_POST["btitem4"])){
    if(isset($_POST["btitem1"]))$valeur = 40;
    if(isset($_POST["btitem2"]))$valeur = 50;
    if(isset($_POST["btitem3"]))$valeur = 60;
    if(isset($_POST["btitem4"]))$valeur = 70;
    if(isset($_SESSION["coin"])){
        if($_SESSION["coin"]>=$valeur){
            $_SESSION["coin"] -= $valeur;
            header("refresh:0");    
        }else{
            echo "Vous n'avez pas assez de PA";
        }
    }else{
        //ERROR
    }
}

?>


<h1 class="descri">BOUTIQUE</h1>
<h2 class="descri">Bienvenue dans la boutique</h2>
<h2 class="descri"><?php if(isset($_SESSION["coin"]))?> Vous avez <?php echo $_SESSION["coin"]?> PA restants</h2>
<h3 class="descri">Ici vous pouvez dépenser vos Points Amours pour acheter des objets afin de vous aider ou rajouter une touche de fun a votre vie !</h3>
<form method="POST" action="">
    <div class="itemslist">
        <div class="item item1">
            <div class="text">
                <h2>objet</h2></br>
                <p>loremaze azezae aezaze azeeaz eazea eaz eaze aze</p></br>
                <button type="Submit" name="btitem1" id="btitem1">Acheter -40 PA</button>
            </div>
        </div>
        <div class="item item2">
            <div class="text">
                <h2>objet</h2></br>
                <p>loremaze azezae aezaze azeeaz eazea eaz eaze aze</p></br>
                <button type="Submit" name="btitem2" id="btitem2">Acheter -50 PA</button>
            </div>
        </div>
        <div class="item item3">
            <div class="text">
                <h2>objet</h2></br>
                <p>loremaze azezae aezaze azeeaz eazea eaz eaze aze</p></br>
                <button type="Submit" name="btitem3" id="btitem3">Acheter -60 PA</button>
            </div>
        </div>
        <div class="item item4">
            <div class="text">
                <h2>objet</h2></br>
                <p>loremaze azezae aezaze azeeaz eazea eaz eaze aze</p></br>
                <button type="Submit" name="btitem4" id="btitem4">Acheter -70 PA</button>
            </div>
        </div>
    </div>
</form>


