<?php

$koneksi = mysqli_connect(
    "localhost",
    "root",
    "",
    "si_aksi"
);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
