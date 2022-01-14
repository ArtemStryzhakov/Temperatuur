<?php
require("abifunktsioonid.php");
require("login.php");
require("register.php");
session_start();

$sorttulp="temperatuur";
$otsisona="";

$yhendus=new mysqli("d105623.mysql.zonevs.eu", "d105623_stryzhak", "Art02fal+!", "d105623_artemandm");
if (!isset($_SESSION["admin"])){
    $_SESSION["admin"] = false;
}

if (isset($_REQUEST['rnimi']) && isset($_REQUEST['rpsw'])){
    if(!isset($_POST['adm'])){
        $_POST['adm'] = 0;
    }
    register($_POST['adm']);
}//Login

//-----------------------------------------------------------------------
//Register
if (isset($_REQUEST['rnimi']) && isset($_REQUEST['rpsw']) && isset($_REQUEST['adm'])){
    register($_REQUEST['adm']);
}//Login
if (isset($_REQUEST['knimi']) && isset($_REQUEST['psw'])){
    login($_POST['knimi'],$_POST['psw']);
}
//-----------------------------------------------------------------------
//Maahaldus
if(isSet($_REQUEST["maalisamine"])){
    if (!empty(trim($_REQUEST["uuemaanimi"])) && !empty(trim($_REQUEST["uuskeskus"]))){
        lisaMaa($_REQUEST["uuemaanimi"],$_REQUEST["uuskeskus"]);
    }
    header("Location: maahaldus.php");
    exit();
}
if(isSet($_REQUEST["ilmalisamine"])){
    if (!empty($_REQUEST["temp"]) && !empty(trim($_REQUEST["aeg"]))){
        lisaIlm($_REQUEST["temp"], $_REQUEST["maakonna_id"], $_REQUEST["aeg"]);
    }
    header("Location: maahaldus.php");
    exit();
}
if(isSet($_REQUEST["kustutusid"])){
    kustutaIlm($_REQUEST["kustutusid"]);
}
if(isSet($_REQUEST["muutmine"])){
    muudaIlm($_REQUEST["muudetudid"], $_REQUEST["temperatuur"],
        $_REQUEST["maakonna_id"], $_REQUEST["aeg"]);
}
if(isSet($_REQUEST["sort"])){
    $sorttulp=$_REQUEST["sort"];
}
if(isSet($_REQUEST["otsisona"])){
    $otsisona=$_REQUEST["otsisona"];
}

if (!isset($_POST['adm'])){
    $_POST['adm'] = 0;
}

$ilmad=kysiIlmadeAndmed($sorttulp, $otsisona);
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <title>Ilma andmed + andmebaas</title>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="bg"></div>
<div class="bg bg2"></div>
<div class="bg bg3"></div>
<div class="content">
    <h1 style="position: absolute; top: -20px; left: 900px; font-size: 50px;"><u>Eesti ilm</u></h1>
    <div id="menuArea">
    <?php
    if (isset($_SESSION["unimi"])){
    ?>
    <h1 style="position: absolute; top: 5px;"><u>Tere, <?="$_SESSION[unimi]"?></u></h1>
    <button onclick="window.location.href='logout.php'" style="position: absolute; left: 70px; top: 120px; width:200px; font-family: Comic Sans MS; font-size: 17px;">Logi välja</button>
    <?php
    }else{
    ?>
    <button onclick="document.getElementById('id01').style.display='block'" style="position: absolute; left: 70px; top: 120px; width:200px; font-family: Comic Sans MS; font-size: 17px;" >Logi sisse</button>
    <?php
    }
    ?>
</div>
<div id="id01" class="modal">
    <form class="modal-content animate" action="" method="post">
        <div class="imgcontainer">
            <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
            <img src="userLog.png" alt="Avatar" class="avatar">
        </div>

        <div class="container">
            <label for="knimi"><b>Kasutajanimi</b></label>
            <input type="text" placeholder="Kasutajanimi" name="knimi" id="knimi" required>
            <label for="psw"><b>Parool</b></label>
            <input type="password" placeholder="Parool" name="psw" id="psw" required>
            <input type="submit" value="Loo" style="margin-top: 10px; width: 100%; font-size: 15px; color: white;"><br>
        </div>
    </form>
</div>
<button onclick="document.getElementById('id02').style.display='block'" style="position: absolute; left: 70px; top: 190px; width:200px; font-family: Comic Sans MS; font-size: 17px;">Register</button>
<div id="id02" class="modal">

    <form class="modal-content animate" action="" method="post">
        <div class="imgcontainer">
            <span onclick="document.getElementById('id02').style.display='none'" class="close" title="Close Modal">&times;</span>
            <img src="userAdd.png" alt="Avatar" class="avatar">
        </div>

        <div class="container">                      
            <label><b>Kasutajanimi</b></label>		
            <input type="text" placeholder="Kasutajanimi" name="rnimi" id="rnimi" required>
            <label><b>Parool</b></label> 
	        <input type="password" placeholder="Parool" name="rpsw" id="rpsw" required>

            <label for=""><input type="checkbox" id="adm" name="adm" value="1">Kas on admin?</label><br>
            <input type="submit" value="Loo uus konto" style="margin-top: 10px; width: 100%; font-size: 15px; color: white;">   
        </div>
    </form>
</div>
<script src="modal.js"></script>

<div class="row">
    <?php
    if($_SESSION["admin"] == true){
    ?>
    
        <form action="maahaldus.php">
        <div class="column" id="left" style="position: absolute; top: 90px; left: 900px; border: solid 2px black; box-shadow: 10px 15px 20px black;">
            <h2><u>Andmete lisamine</u></h2>
            <dl>
                <dt>Temperatuur:</dt>
                <dd><input type="number" style="width: 13em; margin: 10px; padding: 4px;" name="temp" step="0.1" min="-40" max="40"/></dd>
                <dt>Maakond:</dt>
                <dd><?php
                    echo looRippMenyy("SELECT id, maakonnanimi FROM maakondad",
                        "maakonna_id");
                    ?>
                </dd>
                <dt>Aeg   :</dt>
                <dd><input type="datetime-local" name="aeg" style="width: 13em; margin: 10px; padding: 4px;" /></dd>
            </dl>
            <input type="submit" name="ilmalisamine" value="Lisa andmed" style="color: white;"/>
        </div>
        <div class="column" id="mid" style="position: absolute; top: 90px; left: 400px; border: solid 2px black; height: 345px; box-shadow: 10px 15px 20px black;"><h2><u>Maakonna lisamine</u></h2>
            <div id="fre">
                <input type="text" name="uuemaanimi" placeholder="Maakonnanimi"/><br>
                <input type="text" name="uuskeskus" placeholder="Maakonnakeskus"/><br>
                <input type="submit" name="maalisamine" value="Lisa Maakond" style="color: white;"/></div>
        </div>
    </form>
    <?php
    }
    ?>
    
        
    <div class="column" id="rig">
        <?php
        if($_SESSION["admin"] == true){
        ?>
            <form action="maahaldus.php" style="position: absolute; left: 600px; width: 800px; height: fit-content; top: 450px; border: solid 2px black; padding: 10px; box-shadow: 10px 15px 20px black;">
        <?php
        }
        else{
        ?>
            <form action="maahaldus.php" style="position: absolute; left: 600px; width: 800px; height: fit-content; top: 150px; border: solid 2px black; padding: 10px; box-shadow: 10px 15px 20px black;">
        <?php
        }
        ?>
        
        <br><h3>Otsi: </h3><input type="text" name="otsisona"/ style="margin-top: -5px;">
            <h2><u>Ilma andmed</u></h2>
            <table style="margin: auto; box-shadow: 10px 15px 20px black;">
                <tr>
                    <?php
                    if ($_SESSION["admin"]==true){
                    ?><th>Haldus</th><?php }?>
                    <th><a href="maahaldus.php?sort=temperatuur">Temperatuur</a></th>
                    <th><a href="maahaldus.php?sort=maakonnanimi">Maakond</a></th>
                    <th><a href="maahaldus.php?sort=aeg">Aeg</a></th>
                </tr>
                <?php foreach($ilmad as $ilm): ?>
                    <tr>
                        <?php if(isSet($_REQUEST["muutmisid"]) &&
                            intval($_REQUEST["muutmisid"])==$ilm->id): ?>
                            <td>
                                <input type="submit" name="muutmine" value="Muuda" style="color: white;"/>
                                <input type="submit" name="katkestus" value="Katkesta" style="color: white; margin-top: 8px;"/>
                                <input type="hidden" name="muudetudid" value="<?=$ilm->id ?>" />
                            </td>
                            <td><input type="number" name="temperatuur" value="<?=$ilm->temperatuur ?>" step="0.1" min="-40" max="50"/></td>
                            <td><?php
                                echo looRippMenyy("SELECT id, maakonnanimi FROM maakondad",
                                    "maakonna_id", $ilm->id);
                                ?></td>
                            <td><input type="datetime-local" name="aeg" value="<?=$ilm->aeg ?>" /></td>
                        <?php else: ?>
                        <?php
                        if ($_SESSION["admin"]==true){
                        ?><td><a href="maahaldus.php?kustutusid=<?=$ilm->id ?>"
                                   onclick="return confirm('Kas ikka soovid kustutada?')"><img src="delete.png"></a>
                                <a href="maahaldus.php?muutmisid=<?=$ilm->id ?>"><img src="change.png"></a>
                            </td><?php }?>
                            <td><?=$ilm->temperatuur ?></td>
                            <td><?=$ilm->maakonnanimi ?></td>
                            <td><?=$ilm->aeg ?></td>
                        <?php endif ?>
                    </tr>
                <?php endforeach; ?>
            </table>
    </div>
    </form>
    </div>
</div>
</body>
</html>
