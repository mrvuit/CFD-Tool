<?php
if(isset($_GET["file"])){
    $file = $_GET['file'];
    $filepath = "upload/" . $file;
    if(file_exists($filepath)) {
    header('Content-type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($filepath).'');
    readfile($filepath);
    exit;
    }
}