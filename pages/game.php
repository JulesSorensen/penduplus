<link rel="stylesheet" href="../style/game.css">
<?php
if(!isset($_SESSION["choosenLevel"])) { // si l'utilisateur n'a pas choisi de niveau, on le redirige vers la page home
    unset($_POST["goToMenu"]);
    unset($_SESSION["game"]);
    unset($_SESSION["choosenLevel"]);
    header("refresh:0;url=index.php?p=home");
} else if (isset($_POST["goToMenu"])) { // si l'utilisateur a cliqué sur le bouton pour revenir au menu
    if ($_SESSION["game"]["won"]) { // si il a gagné la partie, les points sont donnés
        if ((isset($_SESSION["objet"]) && $_SESSION["objet"] == "Investisseur") && ($_SESSION["game"]["errors"] < 3)) {
            $_SESSION["coin"] += ($_SESSION["game"]["gain"] * 3);
        } else $_SESSION["coin"] += $_SESSION["game"]["gain"];
    }
    if (isset($_SESSION["objet"])) unset($_SESSION["objet"]);
    unset($_POST["goToMenu"]);
    unset($_SESSION["game"]);
    unset($_SESSION["choosenLevel"]);
    header("refresh:0;url=index.php?p=home");
} else {
    if (isset($_SESSION["game"])) { // si la partie est déjà lancé
        if (isset($_POST["letter"]) && $_SESSION["game"]["finished"] === false) { // si la partie en cours et qu'une lettre est entrée
            if (!in_array($_POST["letter"], $_SESSION["game"]["letters"])) { // si la lettre n'a jamais été essayée
                array_push($_SESSION["game"]["letters"], $_POST["letter"]);
                if (!in_array($_POST["letter"], str_split($_SESSION["game"]["finalWord"]))) {
                    $addError = false;
                    if(isset($_SESSION["objet"]) && $_SESSION["objet"] == "Bouclier") { // système d'ignorance des 3 premières erreurs objet bouclier
                        if ($_SESSION["game"]["shieldErrors"] < 3) {
                            $_SESSION["game"]["shieldErrors"] += 1;
                        } else $addError = true;
                    } else $addError = true;

                    if ($addError) {
                        $_SESSION["game"]["errors"] += 1;
                        if (isset($_SESSION["objet"]) && $_SESSION["objet"] == "Chanceux") { // système de génération de lettre objet chanceux
                            $lettersFound = 0;
                            $lettersNotFound = [];
                            foreach (str_split($_SESSION["game"]["finalWord"]) as $key => $val) {
                                if (in_array($val, $_SESSION["game"]["letters"])) {
                                    $lettersFound += 1;
                                } else array_push($lettersNotFound, $val);
                            }
                            if ($lettersFound < (strlen($_SESSION["game"]["finalWord"])-1)) {
                                if ($_SESSION["choosenLevel"] == "easy") {
                                    $proba = 10;
                                } else if ($_SESSION["choosenLevel"] == "medium") {
                                    $proba = 30;
                                } else {
                                    $proba = 50;
                                }
                                $rand = rand(1, 100);
                                if($rand <= $proba) {
                                    $newLetter = $lettersNotFound[rand(0, (sizeof($lettersNotFound)-1))];
                                    array_push($_SESSION["game"]["letters"], $newLetter);
                                    array_push($_SESSION["game"]["objLetters"], $newLetter);
                                }
                            }
                        }
                    }
                }
            }
        }
        // système de vérification de fin de partie, après la vérification de lettre entrée
        if ($_SESSION["game"]["errors"] == 10) {
            $_SESSION["game"]["finished"] = true;
        } else {
            $finished = true;
            foreach (str_split($_SESSION["game"]["finalWord"]) as $key => $val) {
                if (!in_array($val, $_SESSION["game"]["letters"])) {
                    $finished = false;
                }
            }
            if ($finished) {
                $_SESSION["game"]["won"] = true;
                $_SESSION["game"]["finished"] = true;
            }
        }
    } else { // si la partie n'a pas commencée, la créer
        $string = json_decode(file_get_contents("data/words.json")); // json répertoriant les mots en fonction de leur difficulté
        if ($_SESSION["choosenLevel"] == "easy") { // système de choix du mot aléatoire en fonction de la difficulté
            $finalWord = strtoupper($string->easy[rand(0, (sizeof($string->easy)-1))]);
            $gain = 5;
        } else if ($_SESSION["choosenLevel"] == "medium") {
            $finalWord = strtoupper($string->medium[rand(0, (sizeof($string->medium)-1))]);
            $gain = 15;
        } else {
            $finalWord = strtoupper($string->hard[rand(0, (sizeof($string->hard)-1))]);
            $gain = 30;
        }
        $_SESSION["game"] = [ // tableau associatif principal du jeu
            "finalWord" => $finalWord,
            "shieldErrors" => 0,
            "letters" => [],
            "objLetters" => [],
            "gain" => $gain,
            "errors" => 0,
            "finished" => false,
            "won" => false
        ];

        if (isset($_SESSION["objet"]) && $_SESSION["objet"] == "Jedi") { // système de génération de lettre en début de partie objet jedi
            $newLetters = [];
            if ($_SESSION["choosenLevel"] == "easy") $lettersToGive = 1;
            else if ($_SESSION["choosenLevel"] == "medium") $lettersToGive = 2;
            else $lettersToGive = 3;
            
            while($lettersToGive > sizeof($newLetters)) {
                $newLetter = $_SESSION["game"]["finalWord"][rand(0, (strlen($_SESSION["game"]["finalWord"])-1))];
                if (!in_array($newLetter, $newLetters)) {
                    array_push($newLetters, $newLetter);
                    array_push($_SESSION["game"]["letters"], $newLetter);
                    array_push($_SESSION["game"]["objLetters"], $newLetter);
                }
            }
        }
    }

    ?>
        <div id="content">
            <!-- Affichage du pendu -->
            <?php 
                $errNb = $_SESSION["game"]["errors"];
                if ($errNb > 0) {
                    ?>
                        <img <?php echo "style='height:200px;' src='images/pendu/$errNb.png' alt='$errNb error'"; ?> >
                    <?php
                } else {
                    ?>
                        <div style="height:200px;"></div>
                    <?php
                }
            ?>
            <!-- Affichage des lettres du mot actuellement trouvés -->
            <div id="lettersDiv">
                <?php
                    $chars = str_split($_SESSION["game"]["finalWord"]);
                    foreach($chars as $char){
                        ?>
                            <div id="letter">
                                <?php 
                                    if (in_array($char, $_SESSION["game"]["letters"])) {
                                        echo "<p>$char</p>";
                                    } else {
                                        if ($_SESSION["game"]["finished"]) {
                                            echo "<p style='color:red'>$char</p>";
                                        } else echo "<p>_</p>";
                                    }
                                ?>
                            </div>
                        <?php
                    }
                ?>
            </div>
            <!-- Affichage du message de fin de partie -->
            <?php
                if ($_SESSION["game"]["finished"]) {
                    if ($_SESSION["game"]["won"]) {
                        $errorsNb = $_SESSION["game"]["errors"];
                        if ($errorsNb ==  0) {
                            $errGameMsg = "sans aucune erreur !";
                        } else if ($errorsNb == 1) {
                            $errGameMsg = "avec seulement $errorsNb erreur !";
                        } else $errGameMsg = "avec $errorsNb erreurs";
                        ?>
                            <p id="wonText">Vous avez gagné</p>
                            <p id="wonErrText"><?php echo $errGameMsg; ?></p>
                        <?php
                        if (isset($_SESSION["objet"]) && $_SESSION["objet"] == "Investisseur") { // affichage du résultat de l'effet de l'objet investisseur
                            if ($_SESSION["game"]["errors"] < 3) {
                                ?>
                                    <div style="display: flex; flex-direction: row; align-items: center;">
                                        <p style="margin-right: 5px;">+<?php echo ($_SESSION["game"]["gain"]*3); ?> Points Amours</p><img style="height: 35px; width: 35px; margin-left: 5px;" src="../images/piece.png" alt="">
                                    </div>
                                    <p style="margin-top:0;">Votre investissement a porté ses fruits !</p>
                                <?php
                            } else {
                                ?>
                                    <div style="display: flex; flex-direction: row; align-items: center;">
                                        <p style="margin-right: 5px;">+<?php echo ($_SESSION["game"]["gain"]); ?> Points Amours</p><img style="height: 35px; width: 35px; margin-left: 5px;" src="../images/piece.png" alt="">
                                    </div>
                                    <p style="margin-top:0;">Votre investissement n'a pas été concluant...</p>
                                <?php
                            }
                        } else {
                            ?>
                                <div style="display: flex; flex-direction: row; align-items: center;">
                                    <p style="margin-right: 5px;">+<?php echo $_SESSION["game"]["gain"]; ?> Points Amours</p><img style="height: 35px; width: 35px; margin-left: 5px;" src="../images/piece.png" alt="">
                                </div>
                            <?php
                        }
                    } else {
                        ?>
                            <p id="looseText">Vous avez perdu</p>
                            <p id="looseErrText">Avec 10 erreurs...</p>
                        <?php
                    }
                }
            ?>
            <!-- Affichage du clavier, en prennant en compte les lettres entrées valides/invalides ainsi que les objets pour leur attribuer des effets css différents -->
            <form id="keyboard" method="POST" action="">
                <?php
                    $chars = ["A", "Z", "E", "R", "T", "Y", "U", "I", "O", "P", "Q", "S", "D", "F", "G", "H", "J", "K", "L", "M", "W", "X", "C", "V", "B", "N"];
                    foreach($chars as $char){
                        if (in_array($char, $_SESSION["game"]["letters"])) {
                            if (in_array($char, str_split($_SESSION["game"]["finalWord"]))) {
                                if (in_array($char, $_SESSION["game"]["objLetters"])) {
                                    ?>
                                        <button type="reset" id="kbObjLetter">
                                            <?php echo $char; ?>
                                        </button>
                                    <?php
                                } else {
                                    ?>
                                        <button type="reset" id="kbDisRightLetter">
                                            <?php echo $char; ?>
                                        </button>
                                    <?php
                                }
                            } else {
                                ?>
                                    <button type="reset" id="kbDisLetter">
                                        <?php echo $char; ?>
                                    </button>
                                <?php
                            }
                        } else {
                            if ($_SESSION["game"]["finished"] === false) {
                                ?>
                                <button type="submit" name="letter" value="<?php echo $char ?>" id="kbLetter">
                                    <?php echo $char; ?>
                                </button>
                            <?php
                            } else {
                                if (in_array($char, str_split($_SESSION["game"]["finalWord"]))) {
                                    ?>
                                        <button type="reset" id="kbWrongLetter">
                                            <?php echo $char; ?>
                                        </button>
                                    <?php
                                } else {
                                    ?>
                                        <button type="reset" id="kbLetter">
                                            <?php echo $char; ?>
                                        </button>
                                    <?php
                                }
                            }
                        }
                    }
                ?>
            </form>
            <!-- Affichage des objets et du bouton de retour en bas de la page -->
            <div id="footer">
                <?php
                    if (isset($_SESSION["objet"])) {
                        ?>
                            <div id="objets">
                                <?php
                                    if ($_SESSION["game"]["finished"]) {
                                        if ($_SESSION["objet"] == "Bouclier") {
                                            ?>
                                                <p>Vous avez consommé l'objet BOUCLIER</p>
                                            <?php
                                        } else if ($_SESSION["objet"] == "Investisseur") {
                                            ?>
                                                <p>Vous avez consommé l'objet INVESTISSEUR</p>
                                            <?php
                                        } else if ($_SESSION["objet"] == "Chanceux") {
                                            ?>
                                                <p>Vous avez consommé l'objet CHANCEUX</p>
                                            <?php
                                        } else if ($_SESSION["objet"] == "Jedi") {
                                            ?>
                                                <p>Vous avez consommé l'objet JEDI</p>
                                            <?php
                                        }
                                    } else {
                                        if ($_SESSION["objet"] == "Bouclier") {
                                            ?>
                                                <img src="../images/bouclier.png" alt="bouclier">
                                                <p>BOUCLIER</p>
                                            <?php
                                        } else if ($_SESSION["objet"] == "Investisseur") {
                                            ?>
                                                <img src="../images/investisseur.png" alt="inversisseur">
                                                <p>INVESTISSEUR</p>
                                            <?php
                                        } else if ($_SESSION["objet"] == "Chanceux") {
                                            ?>
                                                <img src="../images/chanceux.png" alt="chanceux">
                                                <p>CHANCEUX</p>
                                            <?php
                                        } else if ($_SESSION["objet"] == "Jedi") {
                                            ?>
                                                <img src="../images/jedi.png" alt="jedi">
                                                <p>JEDI</p>
                                            <?php
                                        }
                                    }
                                ?>
                            </div>
                        <?php
                    }

                    $shieldErr = $_SESSION["game"]["shieldErrors"];
                    if ($shieldErr > 0) {
                        ?>
                            <div>
                                <p id="shieldMsg"><?php echo "Votre bouclier vous a protégé $shieldErr fois !" ?></p>
                            </div>
                        <?php
                    }
                ?>
                <form action="" method="POST">
                    <button id="goBack" type="submit" name="goToMenu">
                        <?php
                            if ($_SESSION["game"]["finished"]) {
                                echo "Retour au menu";
                            } else echo "Quitter la partie";
                        ?>
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Script permettant d'appuyer sur les touches du clavier pour l'envoyer au php -->
        <script>
            $(document).ready(function(){
                $(document).keypress(function(e){
                    let key = e.which;
                    if (key >= 97 && key <= 122) { // Les lettre minuscules seront retranscrites en majuscules
                        key -= 32;
                    }
                    if (key >= 65 && key <= 90) {
                        $.ajax({
                            type: "POST",
                            url: 'index.php?p=game',
                            data: {"letter": String.fromCharCode(key)},
                            success: function() {
                                location.reload();
                            }
                        });
                    }
                });
            });
        </script>
    <?php
}
?>