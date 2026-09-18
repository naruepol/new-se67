<?php
$servername = "lemp_mariadb";
$username = "admin"; // ลองเปลี่ยนเป็น "root" ดูหากยังเข้าไม่ได้
$password = "1234";
$dbname = "titanic";

// รวมการเชื่อมต่อเซิร์ฟเวอร์และเลือกฐานข้อมูลไว้ในบรรทัดเดียว
$dbhandle = mysqli_connect($servername, $username, $password, $dbname);

// ตรวจสอบสถานะการเชื่อมต่อ
if (!$dbhandle) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Connected database server successfully<br>";
echo "Selected database: " . $dbname;
?>