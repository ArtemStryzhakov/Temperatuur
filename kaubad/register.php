<?php
$error = $_SESSION['error'] ?? "";

function puhastaAndmed($data){
    $data=trim($data);//ignores blanks
    $data=htmlspecialchars($data);// ignores code elements
    $data=stripslashes($data);//ignores \
    return $data;
}
function register($admin)
{
    $yhendus = new mysqli("d105623.mysql.zonevs.eu", "d105623_stryzhak", "Art02fal+!", "d105623_artemandm");//"d105617.mysql.zonevs.eu", "d105617_krohhin", "AKEYfass123", "d105617_jaan";  "localhost", "jaan", "123456", "jaan"
    $login = puhastaAndmed($_POST['rnimi']);
    $pass = puhastaAndmed($_POST["rpsw"]);
    $sool = 'uus';
    $krypt = crypt($pass, $sool);

    $kask = $yhendus->prepare("SELECT id,nimi,parool FROM kasutaja where nimi=?");
    $kask->bind_param("s", $login);
    $kask->bind_result($id, $kasutaja, $parool);
    $kask->execute();
    if ($kask->fetch()) {
        $_SESSION['error'] = "Kasutaja on juba olemas";
        header("Location: maahaldus.php");
        $yhendus->close();
        exit();
    }else {
        $_SESSION['error'] = " ";
    }
    $kask = $yhendus->prepare("INSERT INTO kasutaja(nimi,parool,onAdmin,koduleht) VALUES (?,?,?,'maahaldus.php')");
    $kask->bind_param("ssi", $login, $krypt, $_REQUEST["adm"]);
    $kask->execute();
    $_SESSION['unimi'] = $login;
    if ($admin == 1) {
        $_SESSION['admin'] = true;
    }
    header("Location: maahaldus.php");
    $yhendus->close();
    exit();
}
/*<?=$error ?>line 57 between the strong tag*/
?>