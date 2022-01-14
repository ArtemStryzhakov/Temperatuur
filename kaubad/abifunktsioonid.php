<?php
$yhendus=new mysqli("d105623.mysql.zonevs.eu", "d105623_stryzhak", "Art02fal+!", "d105623_artemandm");//"d105617.mysql.zonevs.eu", "d105617_krohhin", "AKEYfass123", "d105617_jaan";  "localhost", "jaan", "123456", "jaan"
function kysiIlmadeAndmed($sorttulp="temperatuur", $otsisona=""){
    global $yhendus;
    $lubatudtulbad=array("temperatuur", "maakonnanimi", "aeg");
    if(!in_array($sorttulp, $lubatudtulbad)){
        return "lubamatu tulp";
    }
    $otsisona=addslashes(stripslashes($otsisona));
    $kask=$yhendus->prepare("SELECT ilmatemperatuur.id, temperatuur, maakonnanimi, aeg
       FROM ilmatemperatuur, maakondad
       WHERE ilmatemperatuur.maakonna_id=maakondad.id
        AND (temperatuur LIKE '%$otsisona%' OR maakonnanimi LIKE '%$otsisona%' OR aeg LIKE '%$otsisona%')
       ORDER BY $sorttulp");
    $kask->bind_result($id, $temperatuur, $maakonnanimi, $kuupaev);
    $kask->execute();
    $hoidla=array();
    while($kask->fetch()){
        $maailm=new stdClass();
        $maailm->id=$id;
        $maailm->temperatuur=htmlspecialchars($temperatuur);
        $maailm->maakonnanimi=htmlspecialchars($maakonnanimi);
        $maailm->aeg=$kuupaev;
        array_push($hoidla, $maailm);
    }
    return $hoidla;
}
function looRippMenyy($sqllause, $valikunimi, $valitudid=""){
    global $yhendus;
    $kask=$yhendus->prepare($sqllause);
    $kask->bind_result($id, $sisu);
    $kask->execute();
    $tulemus="<select name='$valikunimi'>";
    while($kask->fetch()){
        $lisand="";
        if($id==$valitudid){$lisand=" selected='selected'";}
        $tulemus.="<option value='$id' $lisand >$sisu</option>";
    }
    $tulemus.="</select>";
    return $tulemus;
}
function lisaMaa($maakonnanimi,$maakonnakeskus){
    global $yhendus;
    $kask=$yhendus->prepare("INSERT INTO maakondad(maakonnanimi, maakonnakeskus) VALUES(?,?)");
    $kask->bind_param("ss", $maakonnanimi,$maakonnakeskus);
    $kask->execute();
}
function lisaIlm($temperatuur, $maakonna_id, $kuupaev){
    global $yhendus;
    $kask=$yhendus->prepare("INSERT INTO
       ilmatemperatuur(temperatuur, maakonna_id, aeg)
       VALUES (?, ?, ?)");
    $kask->bind_param("dis", $temperatuur, $maakonna_id, $kuupaev);
    $kask->execute();
}
function kustutaIlm($ilm_id){
    global $yhendus;
    $kask=$yhendus->prepare("DELETE FROM ilmatemperatuur WHERE id=?");
    $kask->bind_param("i", $ilm_id);
    $kask->execute();
}
function muudaIlm($ilm_id, $temp, $maakonna_id, $kuupaev){
    global $yhendus;
    $kask=$yhendus->prepare("UPDATE ilmatemperatuur SET temperatuur=?, maakonna_id=?, aeg=? WHERE id=?");
    $kask->bind_param("disi", $temp, $maakonna_id, $kuupaev, $ilm_id);
    $kask->execute();
}


