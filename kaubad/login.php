<?php
global $yhendus;
// kontroll kas login vorm on täidetud?
if(isset($_REQUEST['knimi']) && isset($_REQUEST['psw'])) {
    $login = htmlspecialchars($_REQUEST['knimi']);
    $pass = htmlspecialchars($_REQUEST['psw']);

    $sool = 'vagavagatekst';
    $krypt = crypt($pass, $sool);
    // kontrollime kas andmebaasis on selline kasutaja

    $kask = $yhendus->prepare("
SELECT id, unimi, psw, isadmin FROM uuedkasutajad WHERE unimi=?");
    $kask->bind_param("s", $login);
    $kask->bind_result($id, $kasutajanimi, $parool, $onadmin);
    $kask->execute();

    if ($kask->fetch() && $krypt == $parool) {
        $_SESSION['unimi'] = $login;
        if ($onadmin == 1) {
            $_SESSION['admin'] = true;
            }
            header("Location: kaubahaldus.php");
            $yhendus->close();
            exit();
        }
        echo "kasutaja $login või parool $krypt on vale";
        $yhendus->close();
    }

?>
<!--<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div id="Login-D">
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
        <input type="submit" href="kaubahaldus.php" value="Cancel">
    </form>
</div>

</body>
</html>-->
