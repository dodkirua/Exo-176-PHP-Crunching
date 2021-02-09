<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>exo176 part2</title>
</head>
<body>
    <?php
    $string = file_get_contents("films.json", FILE_USE_INCLUDE_PATH);
    $brut = json_decode($string, true);
    $top = $brut["feed"]["entry"];
    //1 Afficher le top10 des films sous cette forme :
    echo "<div> Top 10";
    for ($i = 0 ;$i < 10 ; $i++){
        echo "<p> " . ($i+1) . " : ";

        echo ($top[$i]["im:name"]['label']);
        echo "</p><br>";
    }
    echo "</div><br>";

    //2 Quel est le classement du film « Gravity » ?
    $j = 1;
    echo "<br><p> Classement de Gravity est de ";
    foreach ($top as $film){
        if (strtolower($film["im:name"]['label']) === "gravity"){
            echo $j;
        }
        $j++;
    }
    echo "</p><br>";

    //3 Quel est le réalisateur du film « The LEGO Movie » ?

    echo "<br><p> Le réalisateur de The LEGO Movie est  ";
    foreach ($top as $film){
        if (strtolower($film["im:name"]['label']) === strtolower("The LEGO Movie")){
            echo $film["im:artist"]['label'];
        }
    }
    echo "</p><br>";

    //4 Combien de films sont sortis avant 2000 ?

    $k = 1;
    echo "<br><p> Le nombre de film sortie avant 2000 est de : ";
    foreach ($top as $film){
        //echo "<p> $k ".$film["im:name"]['label']." : ".substr($film["im:releaseDate"]['label'],0,4)."</p><br>";
        if (intval(substr($film["im:releaseDate"]['label'],0,4) < 2000)){
            $k++;
        }

    }
    echo "$k</p><br>";

    //5 Quel est le film le plus récent ? Le plus vieux ?
    echo "<br><div> ";
    $recent = array_fill(0,1,0);
    $old = array_fill(0,1,0);
    $count = count($top);
    $year = intval(substr($top[0]["im:releaseDate"]['label'], 0, 4));
    $mounth = intval(substr($top[0]["im:releaseDate"]['label'], 8, 2));
    $day = intval(substr($top[0]["im:releaseDate"]['label'], 5, 2));
    $name = $top[0]["im:name"]['label'];
    $time = mktime(0, 0, 0, $mounth, $day, $year);
    $recent[0] = $name;
    $recent[1] = $time;
    //echo "<p>le film  : $name est  sortie le : $day $mounth $year </p>";

    for ($i = 1 ; $i < $count ; $i++) {
        $year = intval(substr($top[$i]["im:releaseDate"]['label'], 0, 4));
        $day = intval(substr($top[$i]["im:releaseDate"]['label'], 8, 2));
        $month = intval(substr($top[$i]["im:releaseDate"]['label'], 5, 2));
        $name = $top[$i]["im:name"]['label'];
        $time = mktime(0, 0, 0, $mounth, $day, $year);
       // echo "<p>".$name." : ".$time."</p>";
       // echo "<p>le film  : $name est  sortie le : $day $mounth $year </p>";
        if ($recent[1] > $time || $recent[1] === 0 ){
            $recent[0] = $name;
            $recent[1] = $time;
        }
        elseif ($old[1] < $time || $old[1] === 0){
            $old[0] = $name;
            $old[1] = $time;
        }
    }
    echo "<p>le film le plus recent est : $recent[0] sortie le : ".date("j n Y",$recent[1])." </p>";
    echo "<p>le film le plus ancien est : $old[0] sortie le : ".date("j n Y",$old[1])." </p>";
    echo "</div><br>";

    //6 Quelle est la catégorie de films la plus représentée ?
    $category = null;
    foreach ($top as $film){
        if ($category === null){
            $category[0] = [$film['category']['attributes']['label'],1];
        }
        else {
            //echo "<p>".$film['category']['attributes']['label']."</p>";
            $index = array_search($film['category']['attributes']['label'], array_column($category,0));
            if ($index !== false){
                $category[$index][1]++;
            }
            else {
                $category[] = [$film['category']['attributes']['label'], 1];
            }
        }
    }
    $search = [0,0];
    $count = count($category);
   for ($i = 0 ; $i < $count ; $i++){
       if ($category[$i][1] > $search[0] ){
           $search[0] = $category[$i][1];
           $search[1] = $i;
       }
   }
    echo "<p>La catégorie la plus représenté est : ".$category[$search[1]][0]." avec ".$category[$search[1]][1]." films </p>";

    //7Quel est le réalisateur le plus présent dans le top100 ?
    $director = null;
    foreach ($top as $film){
       //echo "<p>".$film['im:artist']."</p>";
        if ($director === null){
            $director[0] = [$film['im:artist']['label'],1];
        }
        else {
            $index = array_search($film['im:artist']['label'], array_column($director,0));
            if ($index !== false){
                $director[$index][1]++;
            }
            else {
                $director[] = [$film['im:artist']['label'], 1];
            }
        }
    }
    $search = [0,0];
    $count = count($director);
    for ($i = 0 ; $i < $count ; $i++){
        if ($director[$i][1] > $search[0] ){
            $search[0] = $director[$i][1];
            $search[1] = $i;
        }
    }
    echo "<p>Le réalisateur  la plus représenté est : ".$director[$search[1]][0]." avec ".$director[$search[1]][1]." films </p><br>";

    //8 Combien cela coûterait-il d'acheter le top10 sur iTunes ? de le louer ?
    $rent = 0;
    $price = 0;

    for ($i = 0 ;$i < 10 ; $i++){

        $rent = $rent + floatval($top[$i]["im:rentalPrice"]["attributes"]['amount']);
        $price = $price + floatval($top[$i]["im:price"]["attributes"]['amount']);
    }
    echo "<p>le prix total de location du top 10 est : $ $rent </p>";
    echo "<p>le prix total d'achat du top 10 est : $ $price </p><br>";

    //9 Quel est le mois ayant vu le plus de sorties au cinéma ?
    $mounth = [
        "January" => 0,
        "February" => 0,
        "March" => 0,
        "April" =>0,
        "May" =>0,
        "June" =>0,
        "July" =>0,
        "August" =>0,
        "September" =>0,
        "October" =>0,
        "November" =>0,
        "December" =>0
    ];
    foreach ($top as $film){
        $m = explode(" ",$film["im:releaseDate"]["attributes"]["label"]);
        $mounth[$m[0]] ++;
    }
    $max = max($mounth);
    $tmp = [];
    echo "<p>Le(s) moi(s) avec le plus de sortie est(sont) : ";
    foreach ($mounth as $key => $i){
        if ($i === $max) {
           $tmp[] = $key;
        }
    }

   $count = count($tmp);
    if ($count > 1){
        for ($i = 0 ; $i < $count ; $i++){
            echo $tmp[$i];
            if ($i < ($count-1)){
                echo " , ";
            }
        }
    }
    else {
        echo $tmp[0];
    }
    echo " avec $max films sorties</p>";


    //10 Quels sont les 10 meilleurs films à voir en ayant un budget limité ?
    foreach ($top as $film){
        $priceArray[$film["im:name"]['label']] =  [$film["im:price"]["attributes"]['amount'],$film["im:rentalPrice"]["attributes"]['amount']];
    }
    foreach ($priceArray as $key => $price){
        //echo "<p>$key : $price[0] : $price[1]</p>";
        if ($price[1] !== null){
            $pri[$key] = floatval(min($price));
        }
        else {
            $pri[$key]  = floatval($price[0]);
        }
    }
    asort($pri);
    $i = 0;
    $tmp = 0;
    echo "<p> Les 10 films sont : </p> <ol>";
    foreach ($pri as $key => $item){
        if ($i <10) {
            echo "<li>" . $key . "</li>";
            $i++;
            $tmp += $item;
        }
        else {
            break;
        }
    }
    echo "</ol><p>pour une valeur de $".$tmp." </p>"

    ?>


</body>
</html>
