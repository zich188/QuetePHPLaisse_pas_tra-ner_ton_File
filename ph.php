<?php

if (isset($_POST['send']))
{

    $file = $_FILES['photo'];
    $fileName = $_FILES['photo']['name'];
    $fileTmpName = $_FILES['photo']['tmp_name'];
    $fileSize = $_FILES['photo']['size'];
    $fileType = $_FILES['photo']['type'];
    $fileError = $_FILES['photo']['error'];


    // Vérifie l'extension du fichier
    $extFile = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $extensionOk = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    $maxFileSize = 1 * 1024 * 1024;
    $errors = [];

    //Si l'extension est autorisée
    if( (!in_array($extFile, $extensionOk ))){
        $errors[] = 'Veuillez sélectionner une image de type Jpg ou Jpeg ou Png !';
    }


    // Vérifie la taille du fichier - 1Mo maximum
    if (file_exists($fileTmpName) && $fileSize > $maxFileSize)
    {
        $errors[] = "Error: La taille du fichier est supérieure à la limite autorisée.";
    }

    if ($fileError == 4 || empty($file)) {
        $errors[] = "Inserer un fichier à uploader";
    }

    if ($fileError == 1) {
        $errors[] = "Fichier trop grand.";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    foreach ($_POST as $key => $value) {
        $data[$key] = trim($value);
    }

    $lastname = $_POST ["lastname"];
    $firstname = $_POST["firstname"];
    $age = $_POST["age"];


    if (empty($data["lastname"] && isset($data["lastname"]))) {
        $errors[] = "Entrez un prenom";
    }

    if (empty($data["firstname"] && isset($data["firstname"]))) {
        $errors[] = "Entrez un nom";
    }

    if (empty($data["age"] && isset($data["age"]))) {
        $errors[] = "Entrez un âge";
    }


    if (empty($errors) && $fileError == 0) {
        // Creating unique name + its current extension
        $fileNameNew = uniqid('filename -', true);
        $baseName = basename($_FILES["photo"]["name"]);
        $fileDestination = "public/uploads/".$fileNameNew .$baseName;

        move_uploaded_file($fileTmpName, $fileDestination);
    }

}

?>

<form method="post" enctype="multipart/form-data">
    <label for="imageUpload">Upload an profile image</label>
    <input type="file" name="photo" id="imageUpload" />
    <p><strong>Note:</strong> Seuls les formats .jpg,.png, .webp sont autorisés jusqu'à une taille maximale de 1 Mo.</p>
    <input type="text" name="firstname" placeholder="firstname"  />
    <input type="text" name="lastname" placeholder="lastname"  />
    <label for="age">age :</label>
    <input type="text" name="age" placeholder="age"  />
    <button name="send">Send</button>
    <br>
    <br>
    <?php

    if (!empty($errors)){
        foreach ($errors as $error) {    ?>
    <li><?= $error; ?></li>
    <?php }
    }else{
        ?> <img src="<?php if(isset($fileDestination)){ echo $fileDestination;} ?>">
   <?php } ?>

    <?php
    if (isset($lastname) && isset($firstname) && isset($age)){
        echo $firstname .' '. $lastname. ' ' .$age;
    }
    ?>

</form>

