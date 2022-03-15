<title>Photo+ Admin</title>
<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    // Import PHPMailer classes into the global namespace
    require 'vendor/phpmailer/src/Exception.php';
    require 'vendor/phpmailer/src/PHPMailer.php';
    require 'vendor/phpmailer/src/SMTP.php';

    function str_starts_with ( $haystack, $needle ) {
        return strpos( $haystack , $needle ) === 0;
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function sendInvite($Bdd, $email) {
        $mdp = sha1(generateRandomString());
        $name = (explode('@', $email)[0]);
        $expDate = date('Y-m-d H:i:s', strtotime('+1 year'));
        $req = "SELECT * FROM users WHERE email = '$email'";
        $ORes = $Bdd->query($req);
        if($Usr = $ORes->fetch()) {
            $req2 = "UPDATE users SET sub = 'goldSub', subexp = '$expDate' WHERE id = '$Usr->id'";
            $ORes2 = $Bdd->query($req2);
        } else {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $req2 = "INSERT INTO users (email, fname, lname, password, picture, sub, subexp) VALUES ('$email', '$name', 'EFFICOM', '$mdp', 'default', 'goldSub', '$expDate') ";
                $ORes2 = $Bdd->query($req2);
                $id = $Bdd->lastInsertId();
        
                $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
                $payload = json_encode(['id' => $id, 'email' => $email, 'password' => $mdp ]);
                $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
                $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
                $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'abC123!', true);
                $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
                $jwt = $base64UrlHeader . ";" . $base64UrlPayload . ";" . $base64UrlSignature;
    
                $req3 = "UPDATE users SET token = '$jwt' WHERE id = '$id'";
                $Ores3= $Bdd->query($req3);
    
                $Mail = new PHPMailer();
                // Create an instance of PHPMailer class
                $Mail->isSMTP();
                $Mail->Mailer = "smtp";
                $Mail->SMTPDebug  = 0; 
                $Mail->SMTPAuth   = TRUE;
                $Mail->SMTPSecure = "tls";
                $Mail->Port       = 587;
                $Mail->Host       = "smtp.gmail.com";
                $Mail->Username   = "photoplus2023@gmail.com";
                $Mail->Password   = "mdpsport+";
                // Sender info 
                $Mail->setFrom('photoplus2023@gmail.com', 'Photo+');
                // Add a recipient 
                $Mail->addAddress($email);
                // Email subject 
                $Mail->Subject = 'Invitation a rejoindre Photo+'; 
                // Set email format to HTML 
                $Mail->isHTML(true); 
                // Email body content 
                $mailContent = ' 
                    <h2>Invitation Photo+</h2>
                    <h3>Votre école vous a invité à rejoindre Photo+</h3>
                    <p>En cliquant sur le lien ci-dessous vous bénéficierez d\'un an de notre abonnement GOLD gratuitement !</p> 
                    <p><a href="http://localhost/photoplus/password&token=' . $jwt . '">Cliquez ici pour accepter l\'invitation</a></p>
                    <br>
                    <p>Notre abonnement contient 10Go d\'upload de fichier photo et vidéos</p>
                    '; 
                $Mail->Body = $mailContent; 
                // Send email 
                $Mail->send();
                $Mail->ClearAllRecipients();
                echo "MAIL HAS BEEN SENT SUCCESSFULLY";
            }
        }
    }

    foreach ($_POST as $key => $val) {
        if($key == 'add') {
            if (isset($_POST["mail"])) {
                if (!isset($_SESSION["usersList"])) {
                    $_SESSION["usersList"] = [$_POST["mail"]];
                } else {
                    if (!in_array( $_POST["mail"], $_SESSION["usersList"])) {
                        array_push($_SESSION["usersList"], $_POST["mail"]);
                    }
                }
            }
        }
        if ($key == "inviteAll") {
            if (isset($_SESSION["usersList"])) {
                if (count($_SESSION["usersList"]) > 0) {
    
                    foreach ($_SESSION["usersList"] as $index => $currentEmail) {
                        sendInvite($Bdd, $currentEmail);
                    }
                    $_SESSION["oldList"] = $_SESSION["usersList"];
                    unset($_POST["inviteAll"]);
                    unset($_SESSION["usersList"]);
                }
            }
        }
        if (str_starts_with($key, 'remove-')) {
            if (isset($_SESSION["usersList"])) {
                if (count($_SESSION["usersList"]) > 0) {
                    $id = (explode("-", $key))[1];
                    array_splice($_SESSION["usersList"], $id, 1);
                    if (count($_SESSION["usersList"]) < 1) {
                        echo "del";
                        unset($_SESSION["usersList"]);
                    }
                } else {
                    unset($_SESSION["usersList"]);
                }
            }
        }
    }

    if (isset($_SESSION['admin'])) {
        ?>

        <div class="page mt-[5rem] flex w-screen flex-row justify-center items-center align-middle">
            <div class="flex flex-col p-3 w-[30rem] shadow-lg justify-center items-center align-middle bg-white rounded space-y-3">
                
                <h1 class='font-bold'>Administrator Interface</h1>
                <h2 class='font-semibold'>Invite users (GOLD subscription for 1y)</h2>
                <div class='mx-5 w-full rounded-lg bg-gray-100'>
                    <form action="#" method="POST" class="flex flex-row justify-center space-x-5 my-3">
                        <input class="shadow-inner rounded-md pl-2" type="email" placeholder='example@example.com' name='mail'>
                        <button class='bg-gray-200 rounded p-1 px-3 hover:bg-green-100 transition-all duration-500' name="add" type="submit">Add</button>
                    </form>
                </div>
                <?php
                    if (isset($_SESSION["usersList"])) {
                        ?><div class='mx-5 px-3 rounded-md justify-center bg-gray-100/50'><?php
                        foreach ($_SESSION["usersList"] as $key => $value) {
                            ?><form action="#" method="POST" class="flex flex-row justify-evenly space-x-5 my-3 px-1">
                                <p class='w-full text-center'><?php echo $value; ?></p>
                                <button class='bg-gray-200 rounded p-1 px-3 hover:bg-red-100 transition-all duration-500' name="remove-<?php echo $key; ?>" type="submit">x</button>
                            </form><?php
                        }
                        ?></div><?php
                    }
                    if (isset($_SESSION["oldList"])) {
                        ?>
                            <div class='mx-5 px-3 rounded-md justify-center bg-gray-200/50'>
                            <h3 class='font-semibold'>Les comptes ci-dessous viennent d'être invités</h3>
                        <?php
                        foreach ($_SESSION["oldList"] as $key => $value) {
                            ?><form action="#" method="POST" class="flex flex-row justify-evenly space-x-5 my-3 px-1">
                                <p class='w-full text-center text-green-400'><?php echo $value; ?></p>
                            </form><?php
                        }
                        ?></div><?php
                        unset($_SESSION["oldList"]);
                    }
                ?>
                <form action="#" method="POST" class="flex flex-row justify-center space-x-5 my-3">
                    <button class='bg-gray-200 rounded p-1 px-3 hover:bg-green-100 transition-all duration-500' name="inviteAll" type="submit">Invite users</button>
                </form>
            </div>
        </div>

        <?php
    }
?>