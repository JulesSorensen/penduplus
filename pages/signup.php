<title>Photo+ Signup</title>

<?php
if (isset($_POST["nom"]) && isset($_POST["prenom"]) && isset($_POST["mail"])) {
    if (((empty($_POST["nom"]) && empty($_POST["prenom"]) && empty($_POST["mail"]))) == FALSE) {
        if (isset($_POST["mdp1"]) && isset($_POST["mdp2"])) {
            if ($_POST["mdp1"] == $_POST["mdp2"] && (empty($_POST["mdp1"]) == FALSE)) {
                $prenom = addslashes($_POST["prenom"]);
                $nom = addslashes($_POST["nom"]);
                $mail = addslashes($_POST["mail"]);
                $mdp = sha1($_POST["mdp1"]);
                $_SESSION["accountToCreate"] = [
                    "prenom"=> $prenom,
                    "nom"=> $nom,
                    "mail"=> $mail,
                    "mdp"=> $mdp,
                ];
                
                $req = "SELECT * FROM users WHERE email = '$mail'";
                $ORes = $Bdd->query($req);
                if($ORes->fetch()) {
                    $erreur = "Your account already exists";
                }
                if (filter_var($mail, FILTER_VALIDATE_EMAIL) == FALSE) {
                    $erreur = "You must put a valid email address";
                }
                if (!isset($erreur)) {
                    unset($_POST);
                    $_SESSION["willSub"] = TRUE;
                }
            } else $erreur = "Your passwords do not match";
        } else $erreur = "Your passwords do not match";
    } else {
        $erreur = "Please fill in all information";
    }
}
function subUser($Bdd) {
    if ($_SESSION["accountToCreate"]) {
        $prenom = $_SESSION["accountToCreate"]['prenom'];
        $nom = $_SESSION["accountToCreate"]['nom'];
        $mail = $_SESSION["accountToCreate"]['mail'];
        $mdp = $_SESSION["accountToCreate"]['mdp'];
        if ($_SESSION["willCard"]) {
            $sub = $_SESSION["willCard"];
            $expDate = date('Y-m-d H:i:s', strtotime('+31 days'));
        } else {
            $sub = "free";
            $expDate = "";
        }
        if (filter_var($mail, FILTER_VALIDATE_EMAIL) == FALSE) {
            $erreur = "You must put a valid email address";
        } else {
            $req = "SELECT * FROM users WHERE email = '$mail'";
            $ORes = $Bdd->query($req);
            if($ORes->fetch()) {
                $erreur = "Your account already exists";
            }
        }
        if($erreur) {
            $_POST = [];
            unset($_SESSION);
        } else {
            $req = "INSERT INTO users (email, fname, lname, password, picture, sub, subexp) VALUES ('$mail', '$prenom', '$nom', '$mdp', 'default', '$sub', '$expDate')";
            $ORes = $Bdd->query($req);
            $_SESSION['subscribed'] = TRUE;
            header("Refresh:0; url=index.php?p=login");
        }
    } else {
        $_POST = [];
        $_SESSION = [];
    }
}
if (isset($_POST["goBack"])) {
    $_POST = [];
    unset($_SESSION["willSub"]);
}
if (isset($_POST["goBackTwo"])) {
    unset($_POST["goBackTwo"]);
    unset($_SESSION["willCard"]);
}
if (isset($_POST["saveCard"])) {
    if (isset($_SESSION["willCard"])) {
        subUser($Bdd);
    } else {
        unset($_POST["goBackTwo"]);
        unset($_SESSION["willCard"]);
    }
} else if (isset($_POST["freeSubChoosen"]) || isset($_POST["plusSubChoosen"]) || isset($_POST["goldSubChoosen"])) {
    if (isset($_POST["freeSubChoosen"])) {
        subUser($Bdd);
    } else {
        $_SESSION["willSub"] = TRUE;
        if(isset($_POST["goldSubChoosen"])) {
            $_SESSION["willCard"] = "gold";
        } else {
            $_SESSION["willCard"] = "plus";
        }
    }
}
if (!isset($_SESSION["willSub"])) {
?>

<div class="page w-screen h-screen flex flex-row justify-center items-center align-middle">
    <div class="page w-screen h-screen sm:w-[25em] sm:h-[35em] flex flex-row justify-center items-center align-middle text-center bg-white rounded-md shadow-md">
        <form action="" method="POST">
            <div class="page">
                <h1 style="margin-bottom: 45px;text-decoration:underline;">Join Photo+</h1>
                <label for="nom">Enter your last name and</label><label for="prenom"> your first name</label><br>
                <input class="shadow-inner rounded-md pl-1" type="text" placeholder="Last name" style="margin-right: 5px;" id="nom" name="nom"><input class="shadow-inner rounded-md pl-1" type="text" placeholder="First name" id="prenom" name="prenom"><br>
                <label for="mail">Enter your email:</label><br>
                <input class="shadow-inner rounded-md pl-1" type="email" id="mail" name="mail" placeholder="exemple@exemple.com"><br>
                <label for="password">Enter your password:</label><br>
                <input class="shadow-inner rounded-md pl-1" type="password" id="password" name="mdp1" placeholder="*****"><br>
                <label for="password2">Re-enter your password:</label><br>
                <input class="shadow-inner rounded-md pl-1" type="password" id="password2" name="mdp2" placeholder="*****"><br><br>
                <button class='bg-gray-100 rounded p-1 hover:bg-green-100 transition-all duration-500' type="submit" style="margin-top: 25px;">Sign up</button>
                <?php if (isset($erreur)) {echo "<p class='text-red-500'>$erreur</p>";} ?>
                <p>Already have an account?<br/><a href="index.php?p=login" class="text-blue-400 underline">Click here to login!</a></p>
            </div>
        </form>
    </div>
</div>
<!-- Fin de la page d'inscription -->
<?php
} else {
    if (!isset($_SESSION["willCard"])) {
        ?>
            <div class='flex flex-col justify-center items-center w-full mt-5'>
                <p class='font-bold text-2xl'>Choose your subscription</p>
                <p class='font-bold'>You can change your offer later</p>
            </div>
            <section class="flex flex-col lg:flex-row items-start items-center lg:justify-center w-full w-full lg:px-10 py-12 ">
                <article class="w-4/5 lg:w-custom mb-10 lg:px-4 py-10 text-center text-primary-dark bg-[#d1d1d1] rounded-l-lg">
                    <div class='border-b border-gray-300 h-[7rem]'>
                        <h2 class="pb-4 flex justify-center font-bold ">
                            <span class="text-6xl">Name</span>
                        </h2>
                    </div>
                    <ul class="text-sm font-bold">
                        <li class="pt-4 pb-4 border-b border-gray-300">Photo storage</li>
                        <li class="pt-3 pb-4 border-b border-gray-300">Video storage</li>
                        <li class="pt-4 pb-4 border-b border-gray-300">Available space</li>
                    </ul>
                    <div class="my-[3.7rem]"></div>
                </article>
                <article class="w-4/5 lg:w-custom mb-10 lg:px-4 py-10 text-center text-primary-dark bg-[#defade]">
                    <div class='border-b border-gray-300 h-[7rem]'>    
                        <h5 class="font-bold text-base">FREE</h5>
                        <h2 class="pb-4 flex justify-center font-bold">
                            <span class="text-6xl mr-1">0</span>
                            <span class="text-6xl">€</span>
                        </h2>
                    </div>
                    <ul class="text-sm font-bold">
                    <li class="pt-4 pb-4 border-b border-gray-200 flex align-middle justify-center"><img class='w-5' src="images/check.png" alt=""></li>
                        <li class="pt-4 pb-4 border-b border-gray-200 flex align-middle justify-center"><img class='w-5' src="images/cross.png" alt=""></li>
                        <li class="pt-4 pb-4 border-b border-gray-200 flex align-middle justify-center">10 Gb</li>
                    </ul>
                    <form action="" method='POST'>
                        <button name='freeSubChoosen' class="bg-white rounded-md shadow-md mt-5 p-2 hover:bg-green-300 hover:mt-[1.75rem] hover:p-1 transition-all duration-500">
                            Subscribe
                        </button>
                    </form>
                </article>
                <article class="w-4/5 lg:w-custom mb-10 h-[27rem] rounded-l-lg lg:px-4 py-10 text-center text-primary-dark bg-[#a7f0a6]">
                    <div class='border-b border-gray-300 h-[7rem]'>
                        <h5 class="font-bold text-base ">Photo+</h5>
                        <h2 class="font-bold pb-4 mt-2 flex justify-center">
                            <span class="text-6xl mr-1">5</span>
                            <span class="text-6xl">€</span>
                            <span class="text-sm self-end"> /mounth</span>
                        </h2>
                    </div>
                    <ul class=" text-sm font-bold">
                        <li class="pt-4 pb-4 border-b border-gray-200 flex align-middle justify-center"><img class='w-5' src="images/check.png" alt=""></li>
                        <li class="pt-4 pb-4 border-b border-gray-200 flex align-middle justify-center"><img class='w-5' src="images/check.png" alt=""></li>
                        <li class="pt-4 pb-4 border-b border-gray-200 flex align-middle justify-center">30 Gb</li>
                    </ul>
                    <form action="" method="POST">
                        <button name='plusSubChoosen' class="bg-white rounded-md shadow-md mt-7 p-2 hover:bg-green-300 hover:mt-[2rem] hover:p-1 transition-all duration-500">
                            Subscribe
                        </button>
                    </form>
                </article>
                <article class=" bg-[#50c74f] w-4/5 h-[28rem] lg:w-custom mb-10 lg:px-4 py-10 text-center text-primary-dark rounded-lg">
                    <div class='border-b border-gray-300 h-[7rem]'>
                        <h5 class="font-bold text-base">Photo GOLD</h5>
                        <h2 class="flex justify-center pb-4 font-bold">
                            <span class="text-6xl mr-1">15</span>
                            <span class="text-6xl">€</span>
                            <span class="text-sm self-end"> /mounth</span>
                        </h2>
                    </div>
                    <ul class="text-sm font-bold">
                    <li class="pt-4 pb-4 border-b border-gray-200 flex align-middle justify-center"><img class='w-5' src="images/check.png" alt=""></li>
                        <li class="pt-4 pb-4 border-b border-gray-200 flex align-middle justify-center"><img class='w-5' src="images/check.png" alt=""></li>
                        <li class="pt-4 pb-4 border-b border-gray-200 flex align-middle justify-center">50 Gb</li>
                    </ul>
                    <form action="" method="POST">
                        <button type='submit' name='goldSubChoosen' class="bg-white rounded-md shadow-md mt-10 p-2 hover:bg-green-300 hover:mt-[2.75rem] hover:p-1 transition-all duration-500">
                            Subscribe
                        </button>
                    </form>
                </article>
            </section>
            <div class='flex flex-row w-full justify-start pl-10 h-15 align-middle'>
                <form action="" method="POST">
                    <button type="submit" name="goBack">
                        <img class='w-10 hover:w-[3rem] transition-all duration-700' src="images/back.png" alt="Go back">
                    </button>
                </form>
            </div>
        <?php
    } else {
        ?>
            <div class="page w-screen h-screen flex flex-col justify-center items-center align-middle">
                <div class="page w-screen h-screen sm:w-[25em] sm:h-[25em] flex flex-row justify-center items-center align-middle text-center bg-white rounded-md shadow-md mb-10">
                    <form action="" method="POST">
                            <h1 style="text-decoration:underline;">Credit Card Information</h1><br>
                                <div class="pl flex flex-col mb-5">
                                    <div class='flex flex-col items-center'>
                                        <label for="cardNumber">Card number</label>
                                        <input class="shadow-inner rounded-md pl-1 ml-6" type="text" placeholder="1234 5678 9012 3456" style="margin-bottom: 5px;" id="cardNumber" name="cardNumber">
                                    </div>
                                    <div class='flex flex-col items-center'>
                                        <label for="cardName">Name on card</label>
                                        <input class="shadow-inner rounded-md pl-1 ml-6" type="text" placeholder="Ex. Lucas Cornelis" style="margin-bottom: 5px;" id="cardName" name="cardName">
                                    </div>
                                    <div class='flex flex-row items-center'>
                                        <div class='flex flex-col items-center jusitfy-center text-center'>
                                            <label for="cardExpDate" class='ml-3'>Expiry date</label>
                                            <input class="shadow-inner rounded-md pl-1 ml-6 w-[7rem]" type="text" placeholder="01/19" style="margin-bottom: 5px;" id="cardExpDate" name="cardExpDate">
                                        </div>
                                        <div class='flex flex-col items-center jusitfy-center text-center'>
                                            <label for="cardCode" class='ml-3'>Security code</label>
                                            <input class="shadow-inner rounded-md pl-1 ml-6 w-[7rem]" type="password" placeholder="***" style="margin-bottom: 5px;" id="cardCode" name="cardCode">
                                        </div>
                                    </div>
                                </div>
                            <button class='bg-gray-100 rounded p-1 hover:bg-green-100 transition-all duration-500' type="submit" name="saveCard">Sign up</button>
                    </form>
                </div>
                <div class='flex flex-row w-full justify-start pl-10 h-15 align-middle'>
                    <form action="" method="POST">
                        <button type="submit" name="goBackTwo">
                            <img class='w-10 hover:w-[3rem] transition-all duration-700' src="images/back.png" alt="Go back">
                        </button>
                    </form>
                </div>
            </div>
        <?php
    }
}