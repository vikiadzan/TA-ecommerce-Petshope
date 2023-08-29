<?php
// Buat gambar baru dengan lebar 200px dan tinggi 100px
$image = imagecreatetruecolor(200, 100);

// Atur warna background menjadi putih
$white = imagecolorallocate($image, 255, 255, 255);
imagefill($image, 0, 0, $white);

// Atur warna teks menjadi hitam
$black = imagecolorallocate($image, 0, 0, 0);

// Tulis teks di tengah gambar
$text = "Hello, GD Imaging!";
$font = 'arial.ttf'; // Ganti dengan path ke file font yang Anda miliki
$font_size = 20;
$font_x = (imagesx($image) - (strlen($text) * $font_size * 0.6)) / 2;
$font_y = (imagesy($image) + $font_size) / 2;
imagettftext($image, $font_size, 0, $font_x, $font_y, $black, $font, $text);

// Simpan gambar ke file PNG
imagepng($image, 'hello_gd_imaging.png');

// Hapus gambar dari memory
imagedestroy($image);
?>