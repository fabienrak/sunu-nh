<?php
if(count($_POST) > 0) {
    $default = [
        "APP"=>[
            "projet"=>$_POST['projet']
        ]
    ];
    $ini = new \app\core\ConfigFile('config/app.config.ini', 'Fichier de configuration');
    $ini->add_array($default);
    $ini->write();
}
?>
<style>
    ul {
        font-size: 18px;
    }
    ul li {
        display: block;
    }
    ul li i {
        margin-right: 15px;
    }
</style>
<section class="cards-section text-center">
    <div class="container">
        <h2 class="title"><i class="fa fa-certificate mr-3"></i>Vérification des prérequis !</h2>
        <h5>Etape 1/3</h5>
    </div><!--//container-->
    <div id="cards-wrapper" class="cards-wrapper row">
        <div class="item item-primary col-lg-12 col-md-12 col-sm-12">
            <div class="item-inner">
                <div class="icon-holder">
                    <i class="icon fa fa-certificate"></i>
                </div><!--//icon-holder-->
                <div style="text-align: initial;">
                    <ul>
                        <li>
                            <?php $suivant = true;
                            if(version_compare(PHP_VERSION, '7.0.0', ">=") >= 0){ ?>
                                <i class="fa fa-thumbs-up text-success"></i>Version de PHP <?= PHP_VERSION; ?>
                            <?php }else{ $suivant = false; ?>
                                <i class="fa fa-thumbs-down text-danger"></i>Version de PHP <?= PHP_VERSION; ?> <span class="text-warning">(il faut au minimum php >= 5.6.0)</span>
                            <?php } ?>
                        </li>
                        <li>
                            <?php if(!isset($_GET["testget"]) && !isset($_POST["testpost"])){ ?>
                                <i class="fa fa-thumbs-up text-success"></i>Votre version de PHP prend en charge les variables en POST et en GET.
                            <?php }else{ $suivant = false; ?>
                                <i class="fa fa-thumbs-down text-danger"></i>Votre version de PHP ne prend pas en charge les variables en POST et en GET.
                            <?php } ?>
                        </li>
                        <li>
                            <?php if(function_exists("session_id")){ ?>
                                <i class="fa fa-thumbs-up text-success"></i>Votre version de PHP prend en charge les sessions.
                            <?php }else{ $suivant = false; ?>
                                <i class="fa fa-thumbs-down text-danger"></i>Votre version de PHP ne prend pas en charge les sessions.
                            <?php } ?>
                        </li>
                        <li>
                            <?php if(function_exists("imagecreate")){ ?>
                                <i class="fa fa-thumbs-up text-success"></i>Votre version de PHP prend en charge les graphiques GD.
                            <?php }else{ $suivant = false; ?>
                                <i class="fa fa-thumbs-down text-danger"></i>Votre version de PHP ne prend pas en charge les graphiques GD.
                            <?php } ?>
                        </li>
                        <li>
                            <?php if(function_exists("curl_init")){ ?>
                                <i class="fa fa-thumbs-up text-success"></i>Votre version de PHP supporte l'extension Curl.
                            <?php }else{ $suivant = false; ?>
                                <i class="fa fa-thumbs-down text-danger"></i>Votre version de PHP ne supporte pas l'extension Curl.
                            <?php } ?>
                        </li>
                        <li>
                            <?php if(function_exists("utf8_encode")){ ?>
                                <i class="fa fa-thumbs-up text-success"></i>Votre version de PHP prend en charge les fonctions UTF8.
                            <?php }else{ $suivant = false; ?>
                                <i class="fa fa-thumbs-down text-danger"></i>Votre version de PHP ne prend pas en charge les fonctions UTF8.
                            <?php } ?>
                        </li>
                        <?php if(@ini_get("memory_limit") != '') { ?>
                            <li>
                                <?php
                                $mem_session_req=64*1024*1024;
                                $mem_session = @ini_get("memory_limit");
                                $test = false;
                                if($mem_session != -1){
                                    preg_match('/([0-9]+)([a-zA-Z]*)/i', $mem_session, $reg);
                                    if ($reg[2])
                                    {
                                        if (strtoupper($reg[2]) == 'G') $mem_session=$reg[1]*1024*1024*1024;
                                        if (strtoupper($reg[2]) == 'M') $mem_session=$reg[1]*1024*1024;
                                        if (strtoupper($reg[2]) == 'K') $mem_session=$reg[1]*1024;
                                    }
                                    $test = ($mem_session >= $mem_session_req);
                                }else $test = true;
                                if($test){ ?>
                                    <i class="fa fa-thumbs-up text-success"></i>Votre mémoire maximum de session PHP est définie à <?= isset($reg) ? $reg[0] : 'aucune limite (NB: il est préférable de le fixer a 128M)'; ?>.
                                <?php }else{ $suivant = false; ?>
                                    <i class="fa fa-thumbs-down text-danger"></i>Votre mémoire maximum de session PHP est insuffisante, elle est définie à <?= isset($reg) ? $reg[0] : 'une valeur inférieure à 64M'; ?>.
                                <?php } ?>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div><!--//item-inner-->
        </div><!--//item-->
    </div><!--//cards-->
    <div class="cta-container">
        <a class="btn btn-primary btn-cta" href="javascript:history.back();">
            <i class="fas fa-cloud-download-alt"></i>
            Etape précédante
        </a>
        <?php if($suivant){ ?>
            <a class="btn btn-primary btn-cta" href="<?= WEBROOT; ?>database">
                <i class="fas fa-cloud-download-alt"></i>
                Etape suivante
            </a>
        <?php } ?>
    </div><!--//cta-container-->
</section><!--//cards-section-->