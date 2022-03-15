<title>Photo+ Change password</title>
<div class="flex w-screen h-screen flex-row justify-center items-center align-middle">
    <div class="flex flex-col p-3 w-[30rem] shadow-lg justify-center items-center align-middle bg-white rounded space-y-3">
    <?php
        if (isset($_SESSION["online"])) { // deconnexion
            session_destroy();
        }
        if ($_GET['token']) {
            $token = $_GET['token'];
            $req = "SELECT * FROM users WHERE token = '$token'";
            $ORes = $Bdd->query($req);
            if($Usr = $ORes->fetch()) {
                if (isset($_POST['add'])) {
                    if ($_POST["mdp1"] == $_POST["mdp2"] && (empty($_POST["mdp1"]) == FALSE)) {
                        $password = sha1($_POST["mdp1"]);
                        $req = "UPDATE users SET password = '$password', token = '' WHERE id = '$Usr->id'";
                        $ORes = $Bdd->query($req);
                        $_SESSION['pswChanged'] = TRUE;
                        header("Refresh:0; url=login");
                    } else {
                        $erreur = "Your passwords do not match";
                        $_POST = [];
                    }
                }
                ?>
                <h1 class='text-lg font-bold'>Please enter your new password</h1>
                <form action="#" method="POST" class="flex flex-col justify-center text-center space-y-3 my-3">
                    <label class="font-semibold" for="psw">New password</label>
                    <input class="shadow-inner rounded-md pl-2" type="password" placeholder='****' id='psw' name='mdp1'>
                    <label class="font-semibold" for="pswConfirm">Confirm new password</label>
                    <input class="shadow-inner rounded-md pl-2" type="password" placeholder='****' id='pswConfirm' name='mdp2'>
                    <?php if (isset($erreur)) {echo "<p class='text-red-500'>$erreur</p>";} ?>
                    <button class='bg-gray-200 rounded p-1 px-3 hover:bg-green-100 transition-all duration-500' name="add" type="submit">Confirm</button>
                </form>
                <?php
            } else {
                ?> 
                <h1 class='text-lg font-bold'>Access denied</h1>
                <?php
            }
        } else {
            ?> 
            <h1 class='text-lg font-bold'>Access denied</h1>
            <?php
        }
    ?>
    </div>
</div>