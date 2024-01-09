<?php
$kasutaja='maksimartjomov';
$serverinimi='localhost';
$parool='12345';
$andmebaas='maksimartjomov';
$yhendus=new mysqli($serverinimi, $kasutaja, $parool, $andmebaas);
$yhendus->set_charset('UTF8');