<link rel="stylesheet" href="style.css">

<nav>
    <ul>
        <li><a href="adminLeht.php">Administreerimisleht</a></li>
        <?php
        if (isset($_SESSION["kasutaja"])){
            ?>
        <li><a href="haldutLeht.php">Kasutajaleht</a></li>
        <?php } ?>
    </ul>
</nav>