<?php

$mysqli = mysqli_connect("localhost", "root", "", "onlinebankingsystem");

if (mysqli_connect_error()) {
    echo "Connection Error: " . mysqli_connect_error();
}

?>