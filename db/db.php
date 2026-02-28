<?php
date_default_timezone_set('Asia/Manila'); // Change to your timezone

$conn = mysqli_connect("localhost", "root", "", "aes");

if (!$conn) {
    echo "Connection Lost";
}
