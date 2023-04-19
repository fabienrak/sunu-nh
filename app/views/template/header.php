<!DOCTYPE html>
<html lang="<?= isset($this) ? $this->lang_choice : 'fr';?>">
<head>

    <title> SunuFramework | Docs</title>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="<?= ASSETS; ?>plugins/images/favicon.ico">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <!-- FontAwesome JS -->
    <!--    <script defer src="https://use.fontawesome.com/releases/v5.1.1/js/all.js" integrity="sha384-BtvRZcyfv4r0x/phJt9Y9HhnN5ur1Z+kZbKVgzVBAlQZX4jvAuImlIz+bG7TS00a" crossorigin="anonymous"></script>-->
    <!-- Global CSS -->
    <link rel="stylesheet" href="<?= ASSETS; ?>plugins/bootstrap/css/bootstrap.min.css">
    <!-- Global CSS -->
    <link rel="stylesheet" href="<?= ASSETS; ?>plugins/font-awesome/css/font-awesome.css">
    <!-- Plugins CSS -->
    <link rel="stylesheet" href="<?= ASSETS; ?>plugins/prism/prism.css">
    <link rel="stylesheet" href="<?= ASSETS; ?>plugins/elegant_font/css/style.css">
    <!-- Theme CSS -->
    <link id="theme-style" href="<?= ASSETS; ?>css/styles.css" rel="stylesheet">
    <!-- Jquery -->
    <script type="text/javascript" src="<?= ASSETS; ?>plugins/jquery-3.3.1.min.js"></script>

</head>
<body data-racine="<?= RACINE; ?>" data-webroot="<?= WEBROOT; ?>" data-assets="<?= ASSETS; ?>" class="<?= $color_body ?>" onscroll="fixNav(window.scrollY);">
<div id="haut"></div>
<div class="version"><p><?= VERSION; ?></p></div>
<?php $color = $color_body == 'body-green' ? '#75c181' : ($color_body == 'body-pink' ? '#EA5395' : '#F88C30') ?>
<style>
    body{
        background: #f9f9fb;
    }
    .version{
        position: fixed;
        top: -50px;
        right: -50px;
        width: 100px;
        height: 100px;
        background-color: <?= $color; ?>;;
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
    .my-green{ color: #52b161; }
    .my-pink{ color: #EA5395; }
    .my-orange{ color: #F88C30; }
    .doc-menu .nav-link {
        margin-bottom: 5px;
        display: block;
        padding: 5px 15px;
        color: white;
        font-weight: 900;
    }
    .doc-sub-menu .nav-link {
        margin-bottom: 10px;
        font-size: 12px;
        display: block;
        color: white;
        padding: 0;
        padding-left: 34px;
        background: none;
        font-weight: 900;
    }
    .doc-sub-menu .nav-link:hover {
        color: <?= $color_body == 'body-green' ? '#52b161' : ($color_body == 'body-pink' ? '#EA5395' : '#F88C30') ?>;
        text-decoration: none;
        background: none;
    }
    .doc-menu .nav-link:hover, .doc-menu .nav-link:focus {
        color: <?= $color_body == 'body-green' ? '#52b161' : ($color_body == 'body-pink' ? '#EA5395' : '#F88C30') ?>;
        text-decoration: none;
        background: none;
    }
    .doc-nav {
        position: absolute;
        top: 0;
        left: 0;
        width: 75%;
    }
    .doc-nav-fix {
        position: fixed;
        top: 0;
        left: 0;
        width: 19%;
    }
</style>
<div class="page-wrapper">
    <!-- ******Header****** -->
    <header id="header" class="header">
        <div class="container">
            <div class="branding">
                <h1 class="logo">
                    <a href="<?= WEBROOT ?>">
                        <span aria-hidden="true" class="icon_documents_alt icon"></span>
                        <span class="text-highlight">SunuFramework</span><span class="text-bold">Docs</span>
                    </a>
                </h1>
            </div><!--//branding-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= WEBROOT ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= WEBROOT ?>home/documentation"><span <?= $this->url[1] === "documentation" ? 'class="my-green"' : '' ?>>Documentation</span></a></li>
                <li class="breadcrumb-item active"><a href="<?= WEBROOT ?>home/architecture"><span <?= $this->url[1] === "architecture" ? 'class="my-pink"' : '' ?>>Architecture</span></a></li>
                <li class="breadcrumb-item active"><a href="<?= WEBROOT ?>home/forum"><span <?= $this->url[1] === "forum" ? 'class="my-orange"' : '' ?>>Forum</span></a></li>
            </ol>
        </div><!--//container-->
    </header><!--//header-->
    <script>
        // 866px
        $(document).ready(function () {
            $('#doc-nav').css("height", window.innerHeight);
        });
        function fixNav($height) {
            var docNav = $('#doc-nav');
            var isScrollBottom = $(document).innerHeight() - $(document).scrollTop() === window.innerHeight;
            if(isScrollBottom) docNav.css("height", window.innerHeight - 50);
            else docNav.css("height", window.innerHeight);
            if($height > 276){
                docNav.removeClass("doc-nav");
                docNav.addClass("doc-nav-fix");
            }else {
                docNav.removeClass("doc-nav-fix");
                docNav.addClass("doc-nav");
            }
        }
    </script>