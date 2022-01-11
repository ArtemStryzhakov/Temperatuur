<?php
require("abifunktsioonid.php");
require("registr.php");
require("login.php");
session_start();

if(isSet($_REQUEST["grupilisamine"])){
    lisaGrupp($_REQUEST["uuegrupinimi"]);
    header("Location: kaubahaldus.php");
    exit();
}
if(isSet($_REQUEST["kaubalisamine"])){
    //
    if(!empty(trim($_REQUEST["nimetus"])) && !empty(trim($_REQUEST["hind"]))){
        lisaKaup($_REQUEST["nimetus"], $_REQUEST["kaubagrupi_id"], $_REQUEST["hind"]);
        header("Location: kaubahaldus.php");
        exit();
    }
}
if(isSet($_REQUEST["kustutusid"])){
    kustutaKaup($_REQUEST["kustutusid"]);
}
if(isSet($_REQUEST["muutmine"])){
    muudaKaup($_REQUEST["muudetudid"], $_REQUEST["nimetus"],
        $_REQUEST["kaubagrupi_id"], $_REQUEST["hind"]);
}
$kaubad=kysiKaupadeAndmed();
?>
<!DOCTYPE html>
<head>
    <title>Kaupade halduse leht</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>

<h2>Bottom Modal</h2>
<!-- Trigger/Open The Modal -->
<div id="menuArea">
    <button id="myBtn">Loo uus kasutaja</button>
    <?php
    if(isset($_SESSION['unimi'])){
        ?>
        <h1>Tere, <?="$_SESSION[unimi]"?></h1>
        <button id="myBtn2">Logi välja</button>
        <?php
    } else {
        ?>
        <button id="myBtn3" onclick="gay()">Logi sisse</button>
        <?php
    }
    ?>
</div>
<!-- The Modal -->
<?php
if(isset($_SESSION['unimi'])){
    ?>
    <h1>Tere, <?="$_SESSION[unimi]"?></h1>
<?php
}
?>
<div id="myModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <span id="close1" class="close">&times;</span>
        <div class="Login-R">
            <img src="images/user.png" alt="user">
            <form action="registr.php" method="post">
                <h1>Uue kasutaja registreerimine</h1>
                <label for="knimi">Kasutajanimi</label>
                <input type="text" placeholder="Sisesta kasutajanimi" name="knimi" id="knimi" required>
                <br>
                <label for="psw">Parool</label>
                <input type="password" placeholder="Sisesta parool" name="psw" id="psw" required>
                <br>
                <label for="admin">Kas teha admin?</label>
                <input type="checkbox" name="admin" id="admin" value="1">
                <br>
                <input type="submit" value="Loo kasutaja">
            </form>
        </div>
    </div>
</div>
<div id="myModal2" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <span id="close2" class="close"><input type="button" value="&times;" onclick="gay2()"></span>
        <div class="Login-R">
            <h1>Login vorm</h1>
            <form action="login.php" method="post">
                <label for="knimi">Kasutajanimi</label>
                <input type="text" placeholder="Sisesta kasutajanimi"
                       name="knimi" id="knimi" required>
                <br>
                <label for="psw">Parool</label>
                <input type="password" placeholder="Sisesta parool"
                       name="psw" id="psw" required>
                <br>
                <br>
                <input type="submit" value="Logi sisse">
            </form>
        </div>
    </div>
</div>

<script>
    // Get the modal
    var modal = document.getElementById("myModal");
    var modal2 = document.getElementById("myModal2");
    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");
    var btn2 = document.getElementById("myBtn2");
    var btn3 = document.getElementById("myBtn3");
    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close");
    // When the user clicks the button, open the modal
    function gay() {
        modal2.style.display = "block";
    }
    function gay2() { modal.style.display = "none"; }
    btn.onclick = function() {
        modal2.style.display = "block";
    }

    btn2.onclick = function() {
        modal2.style.display = "block";
    }

    btn3.onclick = function() {
        modal2.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span[0].onclick = function() {
        modal.style.display = "none";
    }

    span[1].onclick = function() {
        modal2.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
        else{
            modal2.style.display = "none";
        }
    }
</script>

<div class="header">
    <h1>Tabelid * Kaubad ja kaubagrupid</h1>
</div>
<div class="row">
    <div class="column">
        <form action="kaubahaldus.php">
            <h2>Kauba lisamine</h2>
            <dl>
                <dt>Nimetus:</dt>
                <dd><input type="text" name="nimetus" /></dd>
                <dt>Kaubagrupp:</dt>
                <dd><?php
                    echo looRippMenyy("SELECT id, grupinimi FROM kaubagrupid",
                        "kaubagrupi_id");
                    ?>
                </dd>
                <dt>Hind:</dt>
                <dd><input type="text" name="hind" /></dd>
            </dl>
            <input type="submit" name="kaubalisamine" value="Lisa kaup" />
    </div>
    <div class="column">
        <h2>Grupi lisamine</h2>
        <input type="text" name="uuegrupinimi" />
        <input type="submit" name="grupilisamine" value="Lisa grupp" />
        </form>
    </div>
    <div class="column">
        <form action="kaubahaldus.php">
            <h2>Kaupade loetelu</h2>
            <table>
                <tr>
                    <th>Haldus</th>
                    <th>Nimetus</th>
                    <th>Kaubagrupp</th>
                    <th>Hind</th>
                </tr>
                <?php foreach($kaubad as $kaup): ?>
                    <tr>
                        <?php if(isSet($_REQUEST["muutmisid"]) &&
                            intval($_REQUEST["muutmisid"])==$kaup->id): ?>
                            <td>
                                <input type="submit" name="muutmine" value="Muuda" />
                                <input type="submit" name="katkestus" value="Katkesta" />
                                <input type="hidden" name="muudetudid" value="<?=$kaup->id ?>" />
                            </td>
                            <td><input type="text" name="nimetus" value="<?=$kaup->nimetus ?>" /></td>
                            <td><?php
                                echo looRippMenyy("SELECT id, grupinimi FROM kaubagrupid",
                                    "kaubagrupi_id", $kaup->id);
                                ?></td>
                            <td><input type="text" name="hind" value="<?=$kaup->hind ?>" /></td>
                        <?php else: ?>
                            <td>
                                <?php
                                if(isset($_SESSION['unimi'])){
                                    ?>
                                    <a href="kaubahaldus.php?kustutusid=<?=$kaup->id ?>"
                                       onclick="return confirm('Kas ikka soovid kustutada?')">x</a>
                                    <a href="kaubahaldus.php?muutmisid=<?=$kaup->id ?>">m</a>
                                <?php } ?>
                            </td>
                            <td><?=$kaup->nimetus ?></td>
                            <td><?=$kaup->grupinimi ?></td>
                            <td><?=$kaup->hind ?></td>
                        <?php endif ?>
                    </tr>
                <?php endforeach; ?>
            </table>
        </form>
    </div>
</div>
</body>
</html>