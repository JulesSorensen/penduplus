<link rel="stylesheet" href="../style/game.css">
<?php
    $_SESSION["choosenLevel"] = "easy";
if(!isset($_SESSION["choosenLevel"])) {

} else {
    print_r($_SESSION);
    if (isset($_SESSION["game"])) {
        if (isset($_POST["letter"])) {
            if (!in_array($_POST["letter"], $_SESSION["game"]["letters"])) {
                array_push($_SESSION["game"]["letters"], $_POST["letter"]);
                if (!in_array($_POST["letter"], str_split($_SESSION["game"]["finalWord"]))) {
                    $_SESSION["game"]["errors"] += 1;
                }
            }
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
            "errors" => 0,
            "letters" => [],
            "finished" => false
        ];
    }

    // if ($_SESSION["game"]["finished"] === false)
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
            <form id="keyboard" method="POST" action="">
                <?php
                    $chars = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
                    foreach($chars as $char){
                        if (in_array($char, $_SESSION["game"]["letters"])) {
                            ?>
                                <button type="reset" id="kbDisLetter">
                                    <?php 
                                        if (in_array($char, $_SESSION["game"]["letters"])) {
                                            echo $char;
                                        } else echo "_";
                                    ?>
                                </button>
                            <?php
                        } else {
                            ?>
                                <button type="submit" name="letter" value="<?php echo $char ?>" id="kbLetter">
                                    <?php echo $char; ?>
                                </button>
                            <?php
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