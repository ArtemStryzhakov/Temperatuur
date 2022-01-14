<?php
function login($login, $pass){
    $yhendus=new mysqli("d105623.mysql.zonevs.eu", "d105623_stryzhak", "Art02fal+!", "d105623_artemandm");//"d105617.mysql.zonevs.eu", "d105617_krohhin", "AKEYfass123", "d105617_jaan";  "localhost", "jaan", "123456", "jaan"
    //login ja parool salvestatud andmebaasiga andmetega
    if (isset($_POST['knimi']) && isset($_POST['psw'])){
        $login=htmlspecialchars($_POST['knimi']);
        $pass=htmlspecialchars($_POST['psw']);
        $sool='uus';
        $krypt=crypt($pass,$sool);
        //check the database for the user
        $kask=$yhendus->prepare("SELECT id,nimi,parool,onAdmin FROM kasutaja where nimi=?");
        $kask->bind_param("s",$login);
        $kask->bind_result($id,$kasutaja,$parool,$onAdmin);
        $kask->execute();
        if ($kask->fetch() && $krypt == $parool) {
            $_SESSION['unimi'] = $login;
            if ($onAdmin == 1) {
                $_SESSION['admin'] = true;
            }else{
                $_SESSION['admin'] = false;
            }
            header("Location: maahaldus.php");
            $yhendus->close();
            exit();
        }
        echo "kasutaja $login või parool $krypt on vale";
        $yhendus->close();
    }
}
?>