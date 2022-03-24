<link rel="stylesheet" href="../style/game.css">
<?php

if(!isset($_SESSION["choosenLevel"])) {
    // error
} else if (isset($_POST["goToMenu"])) {
    unset($_POST["goToMenu"]);
    unset($_SESSION["game"]);
    unset($_SESSION["choosenLevel"]);
    header("refresh:0;url=index.php?p=home");
} else {
    if (isset($_SESSION["game"])) {
        if (isset($_POST["letter"]) && $_SESSION["game"]["finished"] === false) {
            if (!in_array($_POST["letter"], $_SESSION["game"]["letters"])) {
                array_push($_SESSION["game"]["letters"], $_POST["letter"]);
                if (!in_array($_POST["letter"], str_split($_SESSION["game"]["finalWord"]))) {
                    $_SESSION["game"]["errors"] += 1;
                } else $_SESSION["game"]["founded"] += 1;
            }
        }
        if ($_SESSION["game"]["errors"] == 10) {
            $_SESSION["game"]["finished"] = true;
        } else if ($_SESSION["game"]["founded"] == strlen($_SESSION["game"]["finalWord"])) {
            $_SESSION["game"]["won"] = true;
            $_SESSION["game"]["finished"] = true;
        }
    } else {
        $string = json_decode(file_get_contents("data/words.json"));
        if ($_SESSION["choosenLevel"] == "easy") {
            $finalWord = strtoupper($string->easy[rand(0, (sizeof($string->easy)-1))]);
        } else if ($_SESSION["choosenLevel"] == "medium") {
            $finalWord = strtoupper($string->easy[rand(0, (sizeof($string->medium)-1))]);
        } else {
            $finalWord = strtoupper($string->easy[rand(0, (sizeof($string->hard)-1))]);
        }
        $_SESSION["game"] = [
            "finalWord" => $finalWord,
            "founded" => 0,
            "errors" => 0,
            "letters" => [],
            "finished" => false,
            "won" => false
        ];
    }

    ?>
        <div id="content">
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
            <div id="lettersDiv">
                <?php
                    $chars = str_split($_SESSION["game"]["finalWord"]);
                    foreach($chars as $char){
                        ?>
                            <div id="letter">
                                <?php 
                                    if (in_array($char, $_SESSION["game"]["letters"])) {
                                        echo $char;
                                    } else echo "_";
                                ?>
                            </div>
                        <?php
                    }
                ?>
            </div>
            <?php
                if ($_SESSION["game"]["finished"]) {
                    if ($_SESSION["game"]["won"]) {
                        $errorsNb = $_SESSION["game"]["errors"];
                        if ($errorsNb ==  0) {
                            $errGameMsg = "Sans aucune erreur !";
                        } else if ($errorsNb == 1) {
                            $errGameMsg = "Avec seulement $errorsNb erreur !";
                        } else $errGameMsg = "Avec $errorsNb erreurs";
                        ?>
                            <p id="wonText">Vous avez gagné</p>
                            <p id="wonErrText"><?php echo $errGameMsg; ?></p>
                        <?php
                    } else {
                        ?>
                            <p id="looseText">Vous avez perdu</p>
                            <p id="looseErrText">Avec 10 erreurs...</p>
                        <?php
                    }
                    ?>
                        <form method="POST" action="">
                            <button id="backButton" name="goToMenu" type="submit">Retour au menu</button>
                        </form>
                    <?php
                }
            ?>
            <form id="keyboard" method="POST" action="">
                <?php
                    $chars = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
                    foreach($chars as $char){
                        if (in_array($char, $_SESSION["game"]["letters"])) {
                            if (in_array($char, str_split($_SESSION["game"]["finalWord"]))) {
                                ?>
                                    <button type="reset" id="kbDisRightLetter">
                                        <?php 
                                            if (in_array($char, $_SESSION["game"]["letters"])) {
                                                echo $char;
                                            } else echo "_";
                                        ?>
                                    </button>
                                <?php
                            } else {
                                ?>
                                    <button type="reset" id="kbDisLetter">
                                        <?php 
                                            if (in_array($char, $_SESSION["game"]["letters"])) {
                                                echo $char;
                                            } else echo "_";
                                        ?>
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
                                ?>
                                <button type="reset" id="kbLetter">
                                    <?php echo $char; ?>
                                </button>
                            <?php
                            }
                        }
                    }
                ?>
            </form>
        </div>

        <script>
            $(document).ready(function(){
                $(document).keypress(function(e){
                    let key = e.which;
                    if (key >= 97 && key <= 122) {
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