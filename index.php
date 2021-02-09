<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>exo176</title>
</head>
<body>

    <?php
    $string = file_get_contents("dictionnaire.txt", FILE_USE_INCLUDE_PATH);
    $dico = explode("\n", $string);
    $dicoCount = count($dico);
    //1
    echo "<p>Le nombre de mot dans le dictionnaire est de : $dicoCount</p>";
    //2
    $nbWord = 0;
    foreach ($dico as $word){
        if (strlen($word)>14){
            $nbWord++;
        }
    }
    echo "<p>Le nombre de mot de plus de 15 caractères est de : $nbWord</p>";
    //3
    $nbWord = 0;
    foreach ($dico as $word){
        if (stripos($word,'w')){
            $nbWord++;
        }
    }
    echo "<p>Le nombre de mot avec la lettre w est de : $nbWord</p>";
    //4
    $nbWord = 0;
    foreach ($dico as $word){
        if ($tmp = stripos($word,'q')){
            if($tmp === strlen($word))
            $nbWord++;
        }
    }
    echo "<p>Le nombre de mot finissant par q est de : $nbWord</p>";




    ?>
    <a href="page2.php">PART2 exercice</a>
</body>
</html>
