<?php
    if(isset($_POST["accept"])){
        if(isset($_POST['pseudo']) && isset($_POST['pass'])){
            if(!empty($_POST['pseudo']) && !empty($_POST['pass'])){
                if($_POST['pseudo'] == "toto" && $_POST['pass'] == "test"){
                    $_SESSION["online"] = TRUE;
                }else {
                    echo "pas les bons id";
                }
            }else {
                echo "pas vide svp";
            }  
        }
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style/login.css">
    <title>Document</title>
</head>
<body>
<div class="login-box">
    <h2>Login</h2>
    <form>
    <div class="user-box">
        <input type="text" name="" required="">
        <label>Username</label>
    </div>
    <div class="user-box">
        <input type="password" name="" required="">
        <label>Password</label>
    </div>
    <a href="#">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        Submit
    </a>
    </form>
</div>

</body>
</html>
