<link rel="stylesheet" href="../style/header.css">
<?php
if(isset($_POST["deco"])){
    session_destroy();
    header("refresh:0;url=index.php?p=login");
}

?>

<form method="POST" action="">
    <div class="topnav">
        <a class="tnav" href="">Pendu+</a>

        <div id="">
            <a class="tnav" href="index.php?p=home">Accueil</a>
            <a class="tnav" href="index.php?p=shop">Boutique</a>
            <a class="tnav"><?php
                if(isset($_SESSION["coin"])){
                    echo $_SESSION["coin"];
                }?> P.A</a>
        </div>

        <button type="Submit" name="deco" id="destroy">Déconexion</button>
    </div>
</form>
    
