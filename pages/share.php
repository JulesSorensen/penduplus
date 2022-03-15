<title>Photo+ Shared file</title>

<?php
if (isset($_GET["i"]) && !empty($_GET["i"])) {
    $reqimg = "SELECT * FROM files WHERE token='$_GET[i]'";
    $OResimg = $Bdd->query($reqimg);
    if ($img = $OResimg->fetch()) {
        ?>
        <div class="page flex flex-col w-screen h-screen justify-start items-center align-middle">
            <div class="flex flex-row justify-center items-center bg-gray-100 px-3 py-1 w-full shadow-md">
                <p class="font-semibold text-black mr-1">Shared file named</p>
                <p class="font-bold text-black ml-1 text-lg"><?php echo $img->displayName; ?></p>
            </div>
            <img class="h-full" src="<?php echo "files/$img->fileName" ?>" alt="<?php echo "$img->displayName"; ?>">
        </div>
        <?php
    } else {
        ?>
        <div class="page w-screen h-screen flex flex-row justify-start items-center align-middle">
            <div class="bg-gray-100 px-3 py-1 m-auto">
                <p class="font-semibold text-red-500">You are not authorized to view this image</p>
            </div>
        </div>
        <?php
    }
} else {
    ?>
    <div class="page w-screen h-screen flex flex-row justify-start items-center align-middle">
        <div class="bg-gray-100 px-3 py-1 m-auto">
            <p class="font-semibold text-red-500">You are not authorized to view this image</p>
        </div>
    </div>
    <?php
}
?>