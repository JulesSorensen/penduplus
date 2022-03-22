<link rel="stylesheet" href="../style/game.css">
<?php
$_SESSION["choosenLevel"] = "easy";
if(!isset($_SESSION["choosenLevel"])) {

} else {
    print_r($_SESSION);
    if (isset($_SESSION["game"])) {
        if ($_POST["letter"]) {
            array_push($_SESSION["game"]["letters"], $_POST["letter"]);
            if (!in_array($_POST["letter"], $_SESSION["game"]["finalWord"])) {
                $_SESSION["game"]["errors"] += 1;
            }
        }
    } else {
        $string = json_decode(file_get_contents("../data/words.json"));
        if ($_SESSION["choosenLevel"] == "easy") {
            $finalWord = $string->easy[rand(0, (sizeof($string->easy)-1))];
        } else if ($_SESSION["choosenLevel"] == "medium") {
            $finalWord = $string->easy[rand(0, (sizeof($string->easy)-1))];
        } else {
            $finalWord = $string->easy[rand(0, (sizeof($string->easy)-1))];
        }
        $_SESSION["game"] = [
            "word" => '',
            "finalWord" => $finalWord,
            "errors" => 0,
            "letters" => []
        ];
    }
    ?>

    <div id="content">
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
        <form id="keyboard" method="POST" action="#">
            <?php
                $chars = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
                foreach($chars as $char){
                    if (in_array($char, $_SESSION["game"]["letters"])) {
                        ?>
                            <div id="kbDisLetter">
                                <?php 
                                    if (in_array($char, $_SESSION["game"]["letters"])) {
                                        echo $char;
                                    } else echo "_";
                                ?>
                            </div>
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

    <?php
}
?>