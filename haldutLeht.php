<?php
require_once('conf.php');
session_start();
// punktide lisamine
if (isset($_REQUEST["heatants"]) && isAdmin()) {
    global $yhendus;
    $kask = $yhendus->prepare("update tantsud set punktid=punktid + 1 where id=?");
    $kask->bind_param("i", $_REQUEST["heatants"]);
    $kask->execute();
} elseif (isset($_REQUEST["halbtants"])) {
    global $yhendus;
    $kask = $yhendus->prepare("update tantsud set punktid=punktid - 1 where id=?");
    $kask->bind_param("i", $_REQUEST["halbtants"]);
    $kask->execute();
}

// tantsupaari lisamine
if (isset($_REQUEST["paarinimi"]) && !empty($_REQUEST["paarinimi"]) && isAdmin()) {
    global $yhendus;
    $kask = $yhendus->prepare("insert into tantsud (tantsupaar, punktid, ava_paev) values (?,0, now())");
    $kask->bind_param("s", $_REQUEST["paarinimi"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close;
    //exit();
}

// tantsupaari kustutamine
if (isset($_REQUEST["kustutatants"])) {
    global $yhendus;
    $kask = $yhendus->prepare("delete from tantsud where id=?");
    $kask->bind_param("i", $_REQUEST["kustutatants"]);
    $kask->execute();
}
function isAdmin(){
    return isset($_SESSION['onAdmin']) && $_SESSION['onAdmin'];
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Tantsud tähtedega - Kasutajaleht</title>
</head>
<body>

<?php include 'nav.php'; ?>
<header>
    <?php
    if(isset($_SESSION['kasutaja'])){
        ?>
        <h1>Tere, <?="$_SESSION[kasutaja]"?></h1>
        <a href="logout.php">Logi välja</a>
        <?php
    } else {
        ?>
        <a href="login.php">Logi sisse</a>
        <?php
    }
    ?>
</header>

<h2>Punktide lisamine</h2>
<?php
if (isset($_SESSION["kasutaja"])){
?>
<table>
    <th>Tantsupaari nimi</th>
    <th>Punktid</th>
    <th>Ava paev</th>
    <th>Kommentaarid</th>
    <?php
    global $yhendus;
    $kask = $yhendus->prepare("select id, tantsupaar, punktid, ava_paev, kommentaarid from tantsud where avalik=1");
    $kask->bind_result($id, $tantsupaar, $punktid, $paev, $komment);
    $kask->execute();
    while ($kask->fetch()) {
        echo "<tr>";
        $tantsupaar = htmlspecialchars($tantsupaar);
        echo "<td>".$tantsupaar."</td>";
        echo "<td>".$punktid."</td>";
        echo "<td>".$paev."</td>";
        echo "<td>".$komment."</td>";
        echo "<td>
<form action='?'>
        <input type='hidden' value='$id' name='komment'>
        <input type='text' name='uuskomment' id='uuskomment'>
        <input type='submit' value='OK'>
</form>";
        if (!(isset($_SESSION['kasutaja']) && isAdmin())) {
            echo "<td><a href='?heatants=$id'>Lisa +1punkt</a>   |
                          <a href='?halbtants=$id'>Lisa -1punkt</a>   |
                          <a href='?kustutatants=$id'>Kustuta</a></td>";
        }

        echo "</tr>";
    }
    ?>
</table>

<?php
if (isset($_SESSION['kasutaja']) && !isAdmin()) {
    ?>
    <form action="?">
        <label for="paarinimi">Lisa uus paar</label>
        <input type="text" name="paarinimi" id="paarinimi">
        <input type="submit" value="Lisa">
    </form>
<?php } ?>
<?php } ?>
</body>
</html>