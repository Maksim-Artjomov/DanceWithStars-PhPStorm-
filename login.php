<?php
require_once("conf.php");
global $yhendus;
session_start();

//kontrollime kas väljad  login vormis on täidetud
if (!empty($_POST['login']) && !empty($_POST['pass'])) {
    //eemaldame kasutaja sisestusest kahtlase pahna
    $login = htmlspecialchars(trim($_POST['login']));
    $pass = htmlspecialchars(trim($_POST['pass']));
    //SIIA UUS KONTROLL
    $cool = 'Meat   ';
    $cryp = crypt($pass, $cool);
    //kontrollime kas andmebaasis on selline kasutaja ja parool
    $kask=$yhendus-> prepare("select kasutaja, onAdmin from kasutaja where kasutaja=? and parool=?");
    $kask->bind_param("ss", $login, $cryp);
    $kask->bind_result($kasutaja, $onAdmin);
    $kask->execute();
    //kui on, siis loome sessiooni ja suuname
    if ($kask->fetch()) {
        $_SESSION['tuvastamine'] = 'misiganes';
        $_SESSION['kasutaja'] = $login;
        $_SESSION['onAdmin'] = $onAdmin;
        if($onAdmin==1){
        header('Location: adminLeht.php');}
        else {
            header('Location: haldutLeht.php');}
        $yhendus->close();
        exit();
    } else {
        echo "kasutaja $login või parool $cryp on vale";
        $yhendus->close();
    }
}
?>
<h1>Login</h1>
<form action="" method="post">
    Login: <input type="text" name="login"><br>
    Password: <input type="password" name="pass"><br>
    <input type="submit" value="Logi sisse">
</form>