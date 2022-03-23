<link rel="stylesheet" href="../style/header.css">

<?php
    if(isset($_POST["deco"])){
        session_destroy();
        header("refresh:0;url=index.php?p=login");
    }

    if(isset($_POST["suprobjet"])){
        unset($_SESSION["objet"]);
        unset($_SESSION["textobjet"]);
        header("refresh:0");
    }

?>

<form method="POST" action="">
    <div class="topnav">
        <a class="tnav" href="index.php?p=home">Pendu+</a>

        <div id="">
            <?php if($_GET["p"] == "shop"){?>
                <a class="tnav" href="index.php?p=home">Sélectionner un Niveau</a><?php
            }
            ?>
            <?php if($_GET["p"] == "home"){?>
                <a class="tnav" href="index.php?p=shop">Visiter la Boutique</a><?php
            }
            ?><?php
            if(isset($_SESSION["objet"])){?>
                <div class="popup tnav" onclick="myFunction()">Objet Actif
                    <span class="popuptext" id="myPopup">
                        <?php echo $_SESSION["objet"] ?>:</br>
                        <?php echo $_SESSION["textobjet"] ?></br>
                        <button name="suprobjet" id="suprbutton">Supprimer L'objet</button>
                    </span>                
                </div><?php
            } ?>
        </div>

        <button type="Submit" name="deco" id="destroy">Déconexion</button>
    </div>
</form>

<script>
    function myFunction() {
    var popup = document.getElementById("myPopup");
    popup.classList.toggle("show");
    }
</script>
    
