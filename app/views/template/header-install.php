<!DOCTYPE html>
<html lang="<?= isset($this) ? $this->lang_choice : 'fr';?>">
<head>
    <title> SunuFramework | Installation</title>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="<?= ASSETS; ?>plugins/images/favicon.ico">
<!--    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>-->
    <!-- FontAwesome JS -->
<!--    <script defer src="https://use.fontawesome.com/releases/v5.1.1/js/all.js" integrity="sha384-BtvRZcyfv4r0x/phJt9Y9HhnN5ur1Z+kZbKVgzVBAlQZX4jvAuImlIz+bG7TS00a" crossorigin="anonymous"></script>-->
    <!-- Global CSS -->
    <link rel="stylesheet" href="<?= ASSETS; ?>plugins/bootstrap/css/bootstrap.min.css">
     <!-- Global CSS -->
    <link rel="stylesheet" href="<?= ASSETS; ?>plugins/font-awesome/css/font-awesome.css">
    <!-- Plugins CSS -->
    <link rel="stylesheet" href="<?= ASSETS; ?>plugins/elegant_font/css/style.css">
    <!-- Theme CSS -->
    <link id="theme-style" href="<?= ASSETS; ?>css/styles.css" rel="stylesheet">
    <!-- Jquery -->
    <script type="text/javascript" src="<?= ASSETS; ?>plugins/jquery-3.3.1.min.js"></script>
    <style>
        body{
            background: #f9f9fb;
        }
        .cards-section .item-inner:hover {
            background: white !important;
        }
        .version{
            position: fixed;
            top: -50px;
            right: -50px;
            width: 100px;
            height: 100px;
            background-color: #40babd;
            transform: rotate(45deg);
        }
        .version > p{
            position: absolute;
            right: 28px;
            bottom: -15px;
            transform: rotate(1deg);
            color: white;
            font-size: 18px;
        }
    </style>
</head>

<body data-racine="<?= RACINE; ?>" data-webroot="<?= WEBROOT; ?>" data-assets="<?= ASSETS; ?>" class="landing-page">

<!-- GITHUB BUTTON JS -->
<!--<script async defer src="https://buttons.github.io/buttons.js"></script>-->

<!--FACEBOOK LIKE BUTTON JAVASCRIPT SDK-->
<div class="version"><p><?= VERSION; ?></p></div>
<div id="fb-root"></div>

<div class="page-wrapper">

    <!-- ******Header****** -->
    <header class="header text-center">
        <div class="container">
            <div class="branding">
                <h1 class="logo">
                    <span aria-hidden="true" class="icon_documents_alt icon"></span>
                    <span class="text-highlight">SunuFramework</span><span class="text-bold"> Installation</span>
                </h1>
            </div><!--//branding-->
            <div class="social-container">
                <!-- Replace with your Github Button -->
<!--                <div class="github-btn mb-2">-->
<!--                    <a class="github-button" href="https://github.com/xriley/PrettyDocs-Theme" data-size="large" aria-label="Star xriley/PrettyDocs-Theme on GitHub">Github</a>-->
<!--                </div>-->
            </div><!--//social-container-->
        </div><!--//container-->
    </header><!--//header-->