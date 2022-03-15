<title>Photo+ Home</title>
<?php
if (isset($_POST["deleteFile"])) {
    $id = explode("-", $_POST["deleteFile"])[0];
    $storage = explode("-", $_POST["deleteFile"])[1];
    $requp = "DELETE FROM files WHERE id='$id'";
    $OResup = $Bdd->query($requp);
    $requsr = "SELECT * FROM users WHERE id='$_SESSION[userid]'";
    $OResUsr = $Bdd->query($requsr);
    if ($Usr = $OResUsr->fetch()) {
        $newstorage = $Usr->storage - $storage;
        $requsr2 = "UPDATE users SET storage='$newstorage' WHERE id='$Usr->id'";
        $OResUsr2 = $Bdd->query($requsr2);
    }
} else if (isset($_POST["changeName"])) {
    $id = $_POST["changeName"];
    $name = $_POST["nomFichier"];
    $requp = "UPDATE files SET displayName = '$name' WHERE id LIKE '$id'";
    $OResup = $Bdd->query($requp);
} else if (isset($_POST["enableShare"])) {
    $id = explode('-', $_POST["enableShare"])[0];
    $token = explode('-', $_POST["enableShare"])[1];
    $requp = "UPDATE files SET token = '$token' WHERE id LIKE '$id'";
    $OResup = $Bdd->query($requp);
} else if (isset($_POST["disableShare"])) {
    $id = explode('-', $_POST["disableShare"])[0];
    $requp = "UPDATE files SET token = '' WHERE id LIKE '$id'";
    $OResup = $Bdd->query($requp);
} else if (isset($_POST["send"])) {
    if ((isset($_FILES["fichier"])) && (basename($_FILES["fichier"]["name"]) != NULL)) { // si un fichier a été mis
        $target_file = "files/" . basename($_FILES["fichier"]["name"]);
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
            $erreur2 = "Your file is not valid";
        } else {
            if (move_uploaded_file($_FILES["fichier"]["tmp_name"], $target_file)) {
                // on mets en BDD le nom du fichier, sans le nom du dossier
                $target_file = substr($target_file, 6);
                if (isset($_POST['nomFichier']) && !empty($_POST['nomFichier'])) {
                    $fileName = $_POST['nomFichier'];
                } else {
                    $fileName = substr($target_file, 0, -5);
                }
                $size = round((filesize("files/$target_file")) / 1024, 2);
                $requp = "INSERT INTO files (userId, displayName, fileName, storage, token) VALUES ('$_SESSION[userid]', '$fileName', '$target_file', '$size', '')";
                $OResup = $Bdd->query($requp);
                $requsr = "SELECT * FROM users WHERE id='$_SESSION[userid]'";
                $OResUsr = $Bdd->query($requsr);
                if ($Usr = $OResUsr->fetch()) {
                    $newstorage = $Usr->storage + $size;
                    $requsr2 = "UPDATE users SET storage='$newstorage' WHERE id='$Usr->id'";
                    $OResUsr2 = $Bdd->query($requsr2);
                }
            } else { // si le fichier n'a pas réussi à s'enregistrer
                $erreur2 = "An error occured...";
            }
        }
    } else {
        $_POST["sendPopup"] = true;
        $erreur2 = "You must put a file...";
    }
}

function formatBytes($size, $precision = 0) {
    $base = log($size, 1024);
    $suffixes = array('b', 'Kb', 'Mb', 'Gb', 'Tb');   
    return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
}

$reqUsr = "SELECT * FROM users WHERE id='$_SESSION[userid]'";
$OResUsr = $Bdd->query($reqUsr);
if ($Usr = $OResUsr->fetch()) {
    if ($Usr->storage > 0){$size = formatBytes($Usr->storage * 1000);}
    else {$size = "0Kb";}
    if ($Usr->sub == "goldSub") {
        $nbmax = 6.25;
        $sizemax = "50Gb";
    } else if ($Usr->sub == "plusSub") {
        $nbmax = 3.75;
        $sizemax = "30Gb";
    } else {
        $nbmax = 1.25;
        $sizemax = "10Gb";
    }
    $percent = (($Usr->storage)*100/($nbmax*pow(10,6))) . "%";
?>
<div class="w-[30rem] mx-5 mt-5">
    <div class="flex flex-row justify-between mb-1">
        <span class="text-base font-medium text-black">Stockage</span>
        <span class="text-sm font-medium text-black"><?php echo "$size/$sizemax"; ?></span>
    </div>
    <div class="w-full bg-white rounded-full h-2.5">
        <div class="bg-blue-600 h-2.5 rounded-full" style="width: <?php echo $percent; ?>"></div>
    </div>
</div>
<?php
}
?>
<div class="page m-5 flex flex-row justify-start items-center align-middle">
    <div class="flex flex-wrap">
        <form action="" method="POST" class="mr-3 mb-3">
            <button name="sendPopup" type="submit" class="flex flex-col h-[10rem] w-[8rem] justify-center items-center align-middle bg-white rounded">
                <img class="h-20 mb-2" src="images/plus.png" alt="plusicon">
                <p class="font-bold text-xl">New file</p>
            </button>
        </form>
        <?php
        $reqimg = "SELECT * FROM files WHERE userId like '$_SESSION[userid]'";
        $OResimg = $Bdd->query($reqimg);
        while ($curImg = $OResimg->fetch()) {
            ?>
            <div class="flex flex-col h-[10rem] w-[8rem] bg-white rounded mr-3 mb-3">
                <form action="" method="POST">
                    <button <?php if (!isset($_POST["openimg-$curImg->id"])) {echo "name='openimg-$curImg->id' type='submit'";} ?> class="flex flex-col h-[10rem] w-[8rem] bg-white rounded">
                        <div class="absolute items-center w-[8rem] bg-black/50 rounded-t"><p class="font-semibold text-white text-sm text-ellipsis"><?php echo substr($curImg->displayName, 0, 17) ?></p></div>
                        <img class="h-full w-full object-cover rounded" src="<?php echo "files/$curImg->fileName" ?>" alt="<?php echo "$curImg->displayName"; ?>">
                    </button>
                </form>
                <?php
                if (isset($_POST["openimg-$curImg->id"])) {
                    ?>
                    <div class="z-10 flex flex-row justify-around items-center absolute w-[8rem] mt-[8rem] h-[2rem] bg-black/50 rounded-b">
                        <form action="" method="POST" class="flex flex-row justify-around items-center h-full w-full">
                            <button type="submit" name="deleteFile" value="<?php echo "$curImg->id-$curImg->storage"; ?>"><i class="text-red-500 fa-solid fa-trash-can"></i></button>
                            <button type="submit" name="editFile" value="<?php echo "$curImg->displayName-$curImg->id"; ?>"><i class="text-gray-300 fa-solid fa-pen"></i></button>
                            <button type="submit" name="downloadFile" value="<?php echo "$curImg->fileName"; ?>"><i class="text-green-400 fa-solid fa-download"></i></button>
                            <button type="submit" name="shareFile" value="<?php echo "$curImg->displayName-$curImg->id-t$curImg->token"; ?>"><i class="text-blue-300 fa-solid fa-share-from-square"></i></button>
                        </form>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
        }
        ?>
    </div>

    <?php
    if (isset($_POST["cancel"])) {
        unset($_POST["sendPopup"]);
    } else if (isset($_POST["downloadFile"])) {
        $name = $_POST["downloadFile"];
        ?>
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" />
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-center items-center">
                                <img class="h-[20rem]" src="<?php echo "files/$name" ?>" alt="fileToDownload">
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" name="cancel"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Close</button>
                    </div>
                </form>
            </div>
            </div>
        </div>
        <?php
    } else if (isset($_POST["shareFile"])) {
        $name = explode('-', $_POST["shareFile"])[0];
        $id = explode('-', $_POST["shareFile"])[1];
        $oldtoken = explode('-', $_POST["shareFile"])[2];
        if ($oldtoken == "t") {
            $status = "<p class='text-lg leading-6 font-medium text-red-500'>Share: disabled</p>";
            $token = bin2hex("$name-$id");
        } else {
            $status = "<p class='text-lg leading-6 font-medium text-green-500'>Share: enabled</p>";
            $token = substr(explode('-', $_POST["shareFile"])[2], 1);
        }
        ?>
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" />
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex flex-col">
                                    <?php echo $status; ?>
                                    <label for="nlink" class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Generated link to share</label>
                                    <input readonly id="nlink" value="http://localhost/photoplus/share&i=<?php echo $token; ?>" class="shadow-inner rounded-md w-[28rem] pl-2" type="text" value='<?php echo $name; ?>'>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" name="cancel"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Close</button>
                            <button type="submit" name="enableShare" value="<?php echo "$id-$token"; ?>"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-green-400 text-base font-medium text-white hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Share</button>
                            <button type="submit" name="disableShare" value="<?php echo "$id-$token"; ?>"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">Disable</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    } else if (isset($_POST["editFile"])) {
        $name = explode('-', $_POST["editFile"])[0];
        $id = explode('-', $_POST["editFile"])[1];
        ?>
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" />
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex flex-col">
                                    <label for="nfile" class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Edit current file name</label>
                                    <input id="nfile" name="nomFichier" class="shadow-inner rounded-md w-[13rem] pl-2" type="text" value='<?php echo $name; ?>'>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" name="changeName" value="<?php echo $id; ?>"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Save</button>
                            <button type="submit" name="cancel"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    } else if (isset($_POST["sendPopup"])) {
        ?>
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" />
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex flex-col">
                                    <div class="flex flex-row space-x-1"><label for="sfile" class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Choose your file</label><p class="text-red-500">*</p></div>
                                    <input class="mb-1" id="sfile" name="fichier" type="file">
                                    <label for="nfile" class="text-lg leading-6 font-medium text-gray-900" id="modal-title">File name</label>
                                    <input id="nfile" name="nomFichier" class="shadow-inner rounded-md w-[13rem] pl-2" type="text" placeholder="Enter a name for your file">
                                </div>
                            </div>
                        </div>
                        <?php if(isset($erreur2)) { echo "<p class='bg-red-500 pl-10 text-white'>$erreur2</p>";} ?>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" name="send"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Send</button>
                            <button type="submit" name="cancel"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
</div>