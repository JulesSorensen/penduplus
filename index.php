<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/photoplus.ico">
    <script src="./tailwind.js"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    clifford: '#da373d',
                }
            }
        }
    }
    </script>
</head>

<body class="bg-gray-200">
    <?php
    session_start();
    // error_reporting(E_ERROR | E_PARSE);
    
    if (!isset($_SESSION["online"])) {
        if(isset($_GET['p'])) {
            $page = $_GET['p'] . ".php";
            switch ($_GET['p']) {
                case 'login':
                    include("pages/$page"); break;
                case 'signup':
                    include("pages/$page"); break;
                default:
                    $_GET['p'] = "about"; include("about.php"); break;
            }
        } else {
            $_GET['p'] = "home";
            include("pages/home.php");
        }
    } else {
        include("pages/home.php");
    }
    ?>
</body>

</html>