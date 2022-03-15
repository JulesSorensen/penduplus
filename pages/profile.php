<title>Photo+ Profile</title>
<?php
    // changement de mdp, adresse mail, nom, prenom ou adresse
    if (isset($_POST["valider"])) {
        if(!empty($_POST["mdpa"])) {
            $confirm = "";
            $req = "SELECT * FROM users WHERE id = '$_SESSION[userid]'";
            $ORes = $Bdd->query($req);
            if ($Usr = $ORes->fetch()) {
                if (sha1($_POST["mdpa"]) == $Usr->password) {
                    // changement de nom
                    if(!empty($_POST["nom"])) {
                        $nom = addslashes($_POST["nom"]);
                        $req2 = "UPDATE users SET lname = '$nom' WHERE id = $_SESSION[userid]";
                        $ORes2 = $Bdd->query($req2);
                        $confirm = $confirm . "The name has been changed<br/>";
                    }
                    // changement de prenom
                    if(!empty($_POST["prenom"])) {
                        $prenom = addslashes($_POST["prenom"]);
                        $req2 = "UPDATE users SET fname = '$prenom' WHERE id = $_SESSION[userid]";
                        $ORes2 = $Bdd->query($req2);
                        $confirm = $confirm . "The first name has been changed<br/>";
                    }
                    // changement de mdp
                    if(!empty($_POST["mdp1"])) {
                        if ($_POST["mdp1"] == $_POST["mdp2"]) {
                            $mdp = sha1($_POST["mdp1"]);
                            $req2 = "UPDATE users SET password = '$mdp' WHERE id = $_SESSION[userid]";
                            $ORes2 = $Bdd->query($req2);
                            $confirm = $confirm . "The password has been saved<br/>";
                        } else $erreur = "Your passwords do not match";
                    }
                    // changement de mail
                    if(!empty($_POST["mail"])) {
                        $mail = addslashes($_POST["mail"]);
                        $req2 = "UPDATE users SET email = '$mail' WHERE id = $_SESSION[userid]";
                        $ORes2 = $Bdd->query($req2);
                        $confirm = $confirm . "The email address has been changed";
                    }
                } else {
                    $erreur = "Your current password is not correct";    
                }
            }
        } else $erreur = "You must enter your current password";
    }
    // changement de photo
    if(isset($_POST["validerimage"])) {
        if (basename($_FILES["fichier"]["name"]) != NULL) { // si un fichier a été mis
            $target_file = "userpics/" . basename($_FILES["fichier"]["name"]);
            $target_file =  substr($target_file, 0, -4) . 1 . substr($target_file, -4);
            if(getimagesize($_FILES["fichier"]["tmp_name"]) != false) { // si la taille du fichier n'est pas résonable
                $uploaderror = 1;
            } else {
                $uploaderror = 0;
            }

            // si le fichier existe déjà on ajoute + 1 à la fin du nom
            while (file_exists($target_file)) {
                $lastchar = $target_file[strlen($target_file)-5];
                $numb = (int)$lastchar + 1;
                $target_file = substr($target_file, 0, -5) . "$numb" . substr($target_file, -4);
            }

            // vérification du format
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "gif" ) {
                $uploaderror = 0;
            }

            // Si il y a eu une erreur
            if ($uploaderror == 0) {
                $erreur2 = "Votre fichier n'est pas valide";
            } else {
                if (move_uploaded_file($_FILES["fichier"]["tmp_name"], $target_file)) {
                    $confirm2 = "Le fichier ". htmlspecialchars(basename( $_FILES["fichier"]["name"])) . " has been updated";
                    // on mets en BDD le nom du fichier, sans le nom du dossier
                    $target_file = substr($target_file, 12, (strlen($target_file)-1));
                    $requp = "UPDATE users SET photo = '$target_file' WHERE id = $_SESSION[userid]";
                    $OResup = $Bdd->query($requp);
                } else { // si le fichier n'a pas réussi à s'enregistrer
                    $erreur2 = "An error has occurred...";
                }
            }
        } else { // si aucune photo n'a été mis, ont reset l'image
            $confirm2 = "Your photo has been reset  ";
            $requp = "UPDATE users SET photo = '' WHERE id = $_SESSION[userid]";
            $OResup = $Bdd->query($requp);
        }
    }

    $req = "SELECT * FROM users WHERE id = '$_SESSION[userid]'";
    $ORes = $Bdd->query($req);
    if ($Usr = $ORes->fetch()) {
        ?>
        <div class="page mt-[5rem] flex w-screen flex-row justify-center items-center align-middle">
            <div class="flex flex-col p-3 w-[25rem] shadow-lg justify-center items-center align-middle bg-white rounded">
                <div class="flex flex-row"><p class="mr-1">Profile of</p><?php 
                    echo "
                    <h1 class='font-semibold ml-1'>$Usr->lname $Usr->fname</h1>
                    ";
                ?></div>
                <img class='my-3' src="<?php if($Usr->picture == 'default') { echo 'images/user.png'; } else { echo 'userpics/' . $Usr->picture . '.png'; }  ?>" alt="">
                <form action="" method="POST">
                        <div class='flex flex-col items-center space-y-4'>
                        <input type="file" class="w-[13rem]">
                        <div class="flex flex-row space-x-2">
                            <input class="w-[7rem] shadow-inner rounded-md pl-2" type="text" name="nom" placeholder="<?php echo $Usr->lname; ?>">
                            <input class="w-[7rem] shadow-inner rounded-md pl-2" type="text" name="prenom" placeholder="<?php echo $Usr->fname; ?>">
                        </div>
                        <input class="shadow-inner rounded-md pl-2" type="email" placeholder="<?php echo $Usr->email; ?>" name="mail">
                        <div class="flex flex-col space-y-4">
                                <input class="shadow-inner rounded-md pl-2" type="password" placeholder="Old Password" name="mdpa">
                                <input class="mt-2 shadow-inner rounded-md pl-2" type="password" placeholder="New Password" name="mdp1">
                                <input class="mt-2 shadow-inner rounded-md pl-2" type="password" placeholder="New Password Again" name="mdp2">
                        </div>
                        <button class='bg-gray-100 rounded p-1 px-3 hover:bg-green-100 transition-all duration-500' name="valider" type="submit">Save</button>
                        <?php if(isset($erreur)) {echo "<p style='color:red;'>$erreur</p>";unset($erreur);} ?>
                        <?php if(isset($confirm)) {echo "<p style='color:green;'>$confirm</p>";unset($confirm);} ?>
                        <?php if(isset($erreur2)) {echo "<p style='color:red;'>$erreur2</p>";unset($erreur2);} ?>
                        <?php if(isset($confirm2)) {echo "<p style='color:green;'>$confirm2</p>";unset($confirm2);} ?>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }





