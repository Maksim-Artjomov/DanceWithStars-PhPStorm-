<?php
require_once('conf.php');
session_start();

// punktide nulliks
if (isset($_REQUEST["punktid0"])) {
    global $yhendus;
    $kask = $yhendus->prepare("update tantsud set punktid = 0 where id = ?");
    $kask->bind_param("i", $_REQUEST["punktid0"]);
    $kask->execute();
} elseif (isset($_REQUEST["halbtants"])) {
    global $yhendus;
    $kask = $yhendus->prepare("update tantsud set punktid = punktid - 1 where id = ?");
    $kask->bind_param("i", $_REQUEST["halbtants"]);
    $kask->execute();
}

// peitmine
if (isset($_REQUEST["peitmine"])) {
    global $yhendus;
    $kask = $yhendus->prepare("update tantsud set avalik = 0 where id = ?");
    $kask->bind_param("i", $_REQUEST["peitmine"]);
    $kask->execute();
}

// näitamine
if (isset($_REQUEST["naitmine"])) {
    global $yhendus;
    $kask = $yhendus->prepare("update tantsud set avalik = 1 where id = ?");
    $kask->bind_param("i", $_REQUEST["naitmine"]);
    $kask->execute();
}

// tantsupaari kustutamine
if (isset($_REQUEST["kustutatants"]) && isAdmin()) {
    global $yhendus;
    $kask = $yhendus->prepare("delete from tantsud where id = ?");
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
    <title>Tantsud tähtedega - Administreerimisleht</title>
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

<table>
    <th>Tantsupaari nimi</th>
    <th>Punktid</th>
    <th>Kuupäev</th>
    <th>Kommentaarid</th>
    <th>Avalik</th>
    <?php
    global $yhendus;
    $kask = $yhendus->prepare("SELECT id, tantsupaar, punktid, ava_paev, kommentaarid, avalik FROM tantsud");
    $kask->bind_result($id, $tantsupaar, $punktid, $paev, $komment, $avalik);
    $kask->execute();
    while ($kask->fetch()) {
        $tekst = "Näita";
        $seisund = "naitmine";
        $tekst2 = "Peidetud";
        if ($avalik == 1) {
            $tekst = "Peida";
            $seisund = "peitmine";
            $tekst2 = "Kasutaja näeb";
        }

        echo "<tr>";
        $tantsupaar = htmlspecialchars($tantsupaar);
        echo "<td>".$tantsupaar."</td>";
        echo "<td>".$punktid."</td>";
        echo "<td>".$paev."</td>";
        echo "<td>".$komment."</td>";
        echo "<td>".$avalik."/". $tekst2."</td>";

        if (!(isset($_SESSION['kasutaja']) && isAdmin())) {
            echo "<td><a href='?punktid0=$id'>Punktid Nulliks</a> | 
                          <a href='?kustutatants=$id'>Kustuta</a></td>";
        }

        echo "<td><a href='?$seisund=$id'>$tekst</a></td>";
        echo "</tr>";
    }
    ?>
</table>
</body>
</html>