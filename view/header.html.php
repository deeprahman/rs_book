<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="main.css">
    <style>
        /* body{
            background-color:black;
        } */
        .container{
    background-color: beige;
    width: 80%;
    margin:auto;
    }
    </style>
</head>
<body>
    <nav>
    <?php if (isset($_SESSION['admin'])): ?>
        <a href="../logout.php">
            <button>Log Out</button>
        </a>
     <?php endif?>
    </nav>
    <div class="container">
