<!DOCTYPE html>
<!--[if IE 8]> <html lang="<?= \app\core\Session::getAttribut('lang');?>" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="<?= \app\core\Session::getAttribut('lang');?>" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="<?= \app\core\Session::getAttribut('lang');?>"> <!--<![endif]-->
<style>
    *
    {
        font-family: 'PT Sans Caption', sans-serif, 'arial', 'Times New Roman';
    }
    /* Error Page */
    .error .clip .shadow
    {
        height: 180px;  /*Contrall*/
    }
    .error .clip:nth-of-type(2) .shadow
    {
        width: 130px;   /*Contrall play with javascript*/
    }
    .error .clip:nth-of-type(1) .shadow, .error .clip:nth-of-type(3) .shadow
    {
        width: 250px; /*Contrall*/
    }
    .error .digit
    {
        width: 150px;   /*Contrall*/
        height: 150px;  /*Contrall*/
        line-height: 150px; /*Contrall*/
        font-size: 120px;
        font-weight: bold;
    }
    .error p   /*Contrall*/
    {
        font-size: 32px;
    }
    .error .msg /*Contrall*/
    {
        top: -190px;
        left: 33%;
        width: 80px;
        height: 80px;
        line-height: 80px;
        font-size: 32px;
    }
    .error span.triangle    /*Contrall*/
    {
        top: 70%;
        right: 0%;
        border-left: 20px solid #ff6600;
        border-top: 15px solid transparent;
        border-bottom: 15px solid transparent;
    }


    .error .container-error-404
    {
        margin-top: 10%;
        position: relative;
        height: 250px;
        padding-top: 40px;
    }
    .error .container-error-404 .clip
    {
        display: inline-block;
        transform: skew(-45deg);
    }
    .error .clip .shadow
    {

        overflow: hidden;
    }
    .error .clip:nth-of-type(2) .shadow
    {
        overflow: hidden;
        position: relative;
        box-shadow: inset 20px 0px 20px -15px rgba(150, 150, 150,0.8), 20px 0px 20px -15px rgba(150, 150, 150,0.8);
    }

    .error .clip:nth-of-type(3) .shadow:after, .error .clip:nth-of-type(1) .shadow:after
    {
        content: "";
        position: absolute;
        right: -8px;
        bottom: 0px;
        z-index: 9999;
        height: 100%;
        width: 10px;
        background: linear-gradient(90deg, transparent, rgba(173,173,173, 0.8), transparent);
        border-radius: 50%;
    }
    .error .clip:nth-of-type(3) .shadow:after
    {
        left: -8px;
    }
    .error .digit
    {
        position: relative;
        top: 8%;
        color: #ff6600;
        background: #294D80;
        border-radius: 50%;
        display: inline-block;
        transform: skew(45deg);
    }
    .error .clip:nth-of-type(2) .digit
    {
        left: -10%;
    }
    .error .clip:nth-of-type(1) .digit
    {
        right: -20%;
    }.error .clip:nth-of-type(3) .digit
     {
         left: -20%;
     }
    .error p
    {
        color: #999999;
        font-weight: bold;
        padding-bottom: 20px;
    }
    .error .msg
    {
        position: relative;
        z-index: 9999;
        display: block;
        background: #ff6600;
        color: #294D80;
        border-radius: 50%;
        font-style: italic;
    }
    .error .triangle
    {
        position: absolute;
        z-index: 999;
        transform: rotate(45deg);
        content: "";
        width: 0;
        height: 0;
    }

    /* Error Page */
    @media(max-width: 767px)
    {
        /* Error Page */
        .error .clip .shadow
        {
            height: 100px;  /*Contrall*/
        }
        .error .clip:nth-of-type(2) .shadow
        {
            width: 80px;   /*Contrall play with javascript*/
        }
        .error .clip:nth-of-type(1) .shadow, .error .clip:nth-of-type(3) .shadow
        {
            width: 100px; /*Contrall*/
        }
        .error .digit
        {
            width: 80px;   /*Contrall*/
            height: 80px;  /*Contrall*/
            line-height: 80px; /*Contrall*/
            font-size: 52px;
        }
        .error p   /*Contrall*/
        {
            font-size: 24px;
        }
        .error .msg /*Contrall*/
        {
            top: -110px;
            left: 15%;
            width: 40px;
            height: 40px;
            line-height: 40px;
            font-size: 18px;
        }
        .error span.triangle    /*Contrall*/
        {
            top: 70%;
            right: -3%;
            border-left: 10px solid #535353;
            border-top: 8px solid transparent;
            border-bottom: 8px solid transparent;
        }
        .error .container-error-404
        {
            height: 150px;
        }
        /* Error Page */
    }

    /*--------------------------------------------Framework --------------------------------*/

    .overlay { position: relative; z-index: 20; } /*done*/
    .ground-color { background: white; }  /*done*/
    .item-bg-color { background: #EAEAEA } /*done*/

    /* Padding Section*/
    .padding-top { padding-top: 10px; } /*done*/
    .padding-bottom { padding-bottom: 10px; }   /*done*/
    .padding-vertical { padding-top: 10px; padding-bottom: 10px; }
    .padding-horizontal { padding-left: 10px; padding-right: 10px; }
    .padding-all { padding: 10px; }   /*done*/

    .no-padding-left { padding-left: 0px; }    /*done*/
    .no-padding-right { padding-right: 0px; }   /*done*/
    .no-vertical-padding { padding-top: 0px; padding-bottom: 0px; }
    .no-horizontal-padding { padding-left: 0px; padding-right: 0px; }
    .no-padding { padding: 0px; }   /*done*/
    /* Padding Section*/

    /* Margin section */
    .margin-top { margin-top: 10px; }   /*done*/
    .margin-bottom { margin-bottom: 10px; } /*done*/
    .margin-right { margin-right: 10px; } /*done*/
    .margin-left { margin-left: 10px; } /*done*/
    .margin-horizontal { margin-left: 10px; margin-right: 10px; } /*done*/
    .margin-vertical { margin-top: 10px; margin-bottom: 10px; } /*done*/
    .margin-all { margin: 10px; }   /*done*/
    .no-margin { margin: 0px; }   /*done*/

    .no-vertical-margin { margin-top: 0px; margin-bottom: 0px; }
    .no-horizontal-margin { margin-left: 0px; margin-right: 0px; }

    .inside-col-shrink { margin: 0px 20px; }    /*done - For the inside sections that has also Title section*/
    /* Margin section */

    hr
    { margin: 0px; padding: 0px; border-top: 1px dashed #999; }
    /*--------------------------------------------FrameWork------------------------*/

    .btn-default {
        color: #999 !important;
        background-color: #fff !important;
        border-color: #999 !important;
    }

    .btn-default:hover {
        color: #294D80 !important;
        border-color: #999 !important;
    }
</style>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Error</title>
    <!-- Bootstrap CSS -->
    <link href="<?= ASSETS;?>plugins/bootstrap-3.3.7/css/bootstrap.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Anton|Passion+One|PT+Sans+Caption' rel='stylesheet' type='text/css'>
</head>
<body>
<!-- Error Page -->
<div class="error">
    <div class="container-fluid">
        <div class="col-xs-12 ground-color text-center">
            <div class="container-error-404">
                <div class="clip"><div class="shadow"><span class="digit thirdDigit"></span></div></div>
                <div class="clip"><div class="shadow"><span class="digit secondDigit"></span></div></div>
                <div class="clip"><div class="shadow"><span class="digit firstDigit"></span></div></div>
                <div class="msg">OH!<span class="triangle"></span></div>
            </div>

            <?php switch($this->_message['MSG_ERROR']['type']) {
                case '400':
                    echo "<p class='h1'>Échec de l'analyse HTTP.</p>";
                    break;
                case '401':
                    echo "<p class='h1'><p>Acces refusé !</p> <p>Vous n'étes pas autorisé à executer cette action! .</p></p>";
                    break;
                case '402':
                    echo "<p class='h1'>Le client doit reformuler sa demande avec les bonnes données de paiement.</p>";
                    break;
                case '403':
                    echo "<p class='h1'>Erreur sur la requête sql informer l' Administrateur SVP!</p>";
                    break;
                case '404':
                     echo "<p class='h1'>La page ".(is_array($this->_message['MSG_ERROR']['alert']) ? implode('/', $this->_message['MSG_ERROR']['alert']) : $this->_message['MSG_ERROR']['alert'])." est introuvable !</p>";
                    break;
                case '405':
                    echo "<p class='h1'>Méthode non autorisée.</p>";
                    break;
                case '500':
                    echo "<p class='h1'>Erreur de connexion à la base de données. <br> <small>Error : ".$this->_message['MSG_ERROR']['alert']."</small></p>";
                    break;
                case '501':
                    echo "<p class='h1'>Le serveur ne supporte pas le service demandé.</p>";
                    break;
                case '502':
                    echo "<p class='h1'>Mauvaise passerelle.</p>";
                    break;
                case '503':
                    echo "<p class='h1'>Service indisponible.</p>";
                    break;
                case '504':
                    echo "<p class='h1'>Temps d'attente à la réponse épuisée.</p>";
                    break;
                case '505':
                    echo "<p class='h1'>Version HTTP non supportée! .";
                    break;
                default:
                    echo "<p class='h1'>".$this->_message['MSG_ERROR']['alert']."</p>";
            }
            $this->_message['MSG_ERROR']['type'] = (intval($this->_message['MSG_ERROR']['type']) > 99 && intval($this->_message['MSG_ERROR']['type']) < 506) ? str_split($this->_message['MSG_ERROR']['type']): ["?","?","?"];
            ?>
            <div>
                <p><a class="btn btn-lg btn-default" href="javascript:history.back();"><?= isset($this->lang["retour"]) ? $this->lang["retour"] : 'Retour à la page précédente' ?></a></p>
            </div>
        </div>
    </div>
</div>
<script>
    function randomNum()
    {
        "use strict";
        return Math.floor(Math.random() * 9)+1;
    }
    var loop1,loop2,loop3,time=30, i=0, selector3 = document.querySelector('.thirdDigit'), selector2 = document.querySelector('.secondDigit'),
        selector1 = document.querySelector('.firstDigit');
    var valSel3 = parseInt("<?= $this->_message['MSG_ERROR']['type'][0] ?>");
    var valSel2 = parseInt("<?= $this->_message['MSG_ERROR']['type'][1] ?>");
    var valSel1 = parseInt("<?= $this->_message['MSG_ERROR']['type'][2] ?>");
    console.log(valSel3);
    console.log(valSel2);
    console.log(valSel1);
    loop3 = setInterval(function()
    {
        "use strict";
        if(i > 40)
        {
            clearInterval(loop3);
            selector3.textContent = (!isNaN(valSel3)) ? valSel3 : '?';
        }else
        {
            selector3.textContent = randomNum();
            i++;
        }
    }, time);
    loop2 = setInterval(function()
    {
        "use strict";
        if(i > 80)
        {
            clearInterval(loop2);
            selector2.textContent = (!isNaN(valSel2)) ? valSel2 : '?';
        }else
        {
            selector2.textContent = randomNum();
            i++;
        }
    }, time);
    loop1 = setInterval(function()
    {
        "use strict";
        if(i > 100)
        {
            clearInterval(loop1);
            selector1.textContent = (!isNaN(valSel1)) ? valSel1 : '?';
        }else
        {
            selector1.textContent = randomNum();
            i++;
        }
    }, time);
</script>
<!-- jQuery JavaScript -->
<script src="<?= ASSETS;?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap JavaScript -->
<script src="<?= ASSETS; ?>plugins/bootstrap-3.3.7/js/bootstrap.js"></script>
<!-- Error Page -->
</body>
</html>
