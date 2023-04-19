<!DOCTYPE html>
<html lang="<?= isset($this) ? $this->lang_choice : '';?>">
<head>

    <title> <?= PROJET ?> | Docs</title>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="<?= ASSETS; ?>plugins/images/favicon.ico">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <!-- Global CSS -->
    <link rel="stylesheet" href="<?= ASSETS; ?>plugins/bootstrap/css/bootstrap.min.css">
    <!-- intlTelInput CSS -->
    <link rel="stylesheet" href="<?= ASSETS ?>plugins/telPlug/css/intlTelInput.css"/>
    <!-- Jquery-confirm CSS -->
    <link rel="stylesheet" href="<?= ASSETS ?>plugins/jconfirm/css/jquery-confirm.css"/>
    <!-- Global CSS -->
    <link rel="stylesheet" href="<?= ASSETS; ?>plugins/font-awesome/css/font-awesome.css">
    <!-- Jquery -->
    <script type="text/javascript" src="<?= ASSETS; ?>plugins/jquery-3.3.1.min.js"></script>

    <!-- Custom CSS -->
    <link href="<?= ASSETS; ?>admin/css/style.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body data-racine="<?= RACINE; ?>" data-webroot="<?= WEBROOT; ?>" data-assets="<?= ASSETS; ?>">