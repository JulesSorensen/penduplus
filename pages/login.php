<title>Photo+ Login</title>
<!-- page de connexion -->
<?php
    if (isset($_SESSION["online"])) { // deconnexion
        session_destroy();
        $deco = "You have been disconnected from the site";
    }
    if (isset($_SESSION["mail"])) unset($_SESSION["mail"]);

    if (isset($_POST["usermail"]) && isset($_POST["userpassword"])) {
        $mail = addslashes($_POST["usermail"]);
        $mdp = sha1($_POST["userpassword"]);

        if (filter_var($mail, FILTER_VALIDATE_EMAIL) == FALSE) {
            $erreur = "You must enter an email.";
        } else {
            $req = "SELECT * FROM users WHERE email = '$mail'";
            $ORes = $Bdd->query($req);
            if ($ORes) {
                if ($Usr = $ORes->fetch()) {
                    if ($Usr->password == $mdp) {
                        $trouve = TRUE;
                        $_SESSION["userid"] = $Usr->id;
                        $_SESSION['fname'] = $Usr->fname;
                        $_SESSION['lname'] = $Usr->lname;
                        $_SESSION['sub'] = $Usr->sub;
                        $_SESSION['img'] = $Usr->picture;
                        if ($Usr->admin) {
                            $_SESSION['admin'] = TRUE;
                        }
                    } else {
                        $trouve = FALSE;
                    }
                }
            } else $trouve = FALSE;
            if (isset($trouve)) {
                if ($trouve == TRUE) {
                    $_SESSION["online"] = TRUE;
                    unset($trouve);
                    header("Refresh:0; url=index.php?p=home");
                } else {
                    $erreur = "Incorrect email or password";
                    unset($trouve);
                }
            } else {
                $erreur = "Incorrect email or password";
            }

        }
    }
?>
<div class="page w-screen h-screen flex flex-row justify-center items-center align-middle">
    <div class="page w-screen h-screen sm:w-[25em] sm:h-[25em] flex flex-row justify-center items-center align-middle text-center bg-white rounded-md shadow-md">
        <form action="" method="POST">
                <h1 style="text-decoration:underline;">Welcome to Photo+</h1><br><br>
                <?php if (isset($deco)) {echo "<p class='text-green-200'>$deco</p>"; unset($deco);} ?>
                <?php if (isset($_SESSION['subscribed'])) {unset($_SESSION['subscribed']);?><p class='text-green-200'>You are now registered, you can login below.</p><?php } ?>
                <?php if (isset($_SESSION['pswChanged'])) {unset($_SESSION['pswChanged']);?><p class='text-green-200'>Your password has been updated.</p><?php } ?>
                <div id = "exple">
                    <input class="shadow-inner rounded-md pl-1" type="email" placeholder="Email" style="margin-bottom: 5px;" name="usermail"><br><br>
                    <div class="pl">
                    <div class='flex flex-row items-center'>
                        <input class="shadow-inner rounded-md pl-1 ml-6" type="password" placeholder="Password" style="margin-bottom: 5px;" id="userpassword" name="userpassword">
                        <img class="h-4" src="images/eye.png" id="eye" onClick="changer()" /><br><br>
                    </div>
                    </div>
                </div>
                <button class='bg-gray-100 rounded p-1 hover:bg-green-100 transition-all duration-500' type="submit">Log in</button>
                <?php if (isset($erreur)) {echo "<p class='text-red-500'>$erreur</p>";} ?>
                <p><a class="text-blue-400 underline" href="">Forgot your password?</a><br>
                <a class="text-blue-400 underline" href="index.php?p=signup">Not registered? Sign up</a></p>
        </form>
    </div>
</div>

<script>
    e = true;
    function changer() {
        if(e == true) {
            document.getElementById("userpassword").setAttribute("type", "text");
            document.getElementById("eye").src="images/ZE.png";
            e = false;
        }
        else if (e == false) {
            document.getElementById("userpassword").setAttribute("type", "password");
            document.getElementById("eye").src="images/eye.png";
            e = true;
        }
    }
</script>
<!-- fin de la page de connexion -->