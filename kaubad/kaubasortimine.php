<?php
//require -kasutatakse abifunktsioonid.php sisu
require("abifunktsioonid.php");
$kaubad=kysiKaupadeAndmed();
//andmete sortimine
if(isSet($_REQUEST["sort"])){
    $kaubad=kysiKaupadeAndmed($_REQUEST["sort"]);
} else {
    $kaubad=kysiKaupadeAndmed();
}

?>
<!DOCTYPE html>
<head>
    <title>Kaupade leht</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
</head>
<body>
<h1>Tabelite Kaubad+kaubagrupide sisu</h1>
<table>
    <tr>
        <th><a href="kaubasortimine.php?sort=nimetus">Nimetus</a></th>
        <th><a href="kaubasortimine.php?sort=grupinimi">Kaubagrupp</a></th>
        <th><a href="kaubasortimine.php?sort=hind">Hind</a></th>
    </tr>

    <!--tagastab massivist andmed-->
    <?php foreach($kaubad as $kaup): ?>
        <tr>
            <td><?=$kaup->nimetus ?></td>
            <td><?=$kaup->grupinimi ?></td>
            <td><?=$kaup->hind ?></td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>

