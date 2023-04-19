<!DOCTYPE html>
<html lang="<?= isset($this) ? $this->lang_choice : 'fr';?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
<!--    <link rel="icon" type="image/png" sizes="16x16" href="../plugins/images/favicon.png">-->
    <title><?= PROJET ?></title>
    <!-- jQuery JavaScript -->
    <script src="<?= ASSETS; ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap Core CSS -->
    <link href="<?= ASSETS; ?>admin/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Menu CSS -->
    <link href="<?= ASSETS; ?>plugins/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
    <!-- animation CSS -->
    <link href="<?= ASSETS; ?>admin/css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= ASSETS; ?>admin/css/style.css" rel="stylesheet">
    <!-- color CSS -->
    <link href="<?= ASSETS; ?>admin/css/colors/default.css" id="theme" rel="stylesheet">

    <!-- dataTables CSS -->
    <link href="<?= ASSETS;?>plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">

    <!-- intlTelInput CSS -->
    <link rel="stylesheet" href="<?= ASSETS ?>plugins/telPlug/css/intlTelInput.css"/>

    <!-- font-awesome CSS -->
    <link rel="stylesheet" href="<?= ASSETS ?>plugins/font-awesome/css/font-awesome.css">

    <!-- Jquery-confirm CSS -->
    <link rel="stylesheet" href="<?= ASSETS ?>plugins/jconfirm/css/jquery-confirm.css"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body data-racine="<?= RACINE; ?>" data-webroot="<?= WEBROOT; ?>" data-assets="<?= ASSETS; ?>">