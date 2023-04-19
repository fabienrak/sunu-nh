<?php
if(count($_POST) > 0) {
    $projet = \parse_ini_file(ROOT . 'config/app.config.ini');
    $default = [
        "APP"=>[
            "\n;Variable d'environnement",
            "projet"=>$projet['projet'],
            "env"=>"DEV",
            "log"=>$_POST['log'],
            "profile_level"=>$_POST['profile_level'],
            "law_generate"=>"off",
            "mail_from"=> ($_POST['mail_from'] !== '' ? $_POST['mail_from'] : "sunuframework@sunuframework.com")."\n",
            ";Use api client",
            "use_api_client"=>$_POST['use_api_client']."\n",
            ";Define session name",
            "session_name"=>"SF_USER",
            "profil_attribut"=>"profil_id",
            ";Define space",
            "space_admin"=>$_POST['space_admin']."\n",
            ";Default page",
            "default_controller"=>"Home",
            "default_action"=>"index\n",
            ";Admin page",
            "admin_controller"=>"Home",
            "admin_action"=>"index\n",
            ";Default template",
            "default_header"=>"header",
            "default_sidebar"=>"sidebar",
            "default_footer"=>"footer\n",
            ";Admin template",
            "admin_header"=>"header",
            "admin_sidebar"=>"sidebar",
            "admin_footer"=>"footer\n",
            ";Define constante",
            "CONST_VERSION"=>VERSION,
        ]
    ];
    $ini = new \app\core\ConfigFile('config/app.config.ini', 'Fichier de configuration');
    $ini->add_array($default);
    $ini->write();
    if($_POST['space_admin'] == 'on') {
        try {
            $dbConfig = \app\core\Database::getDbConfig();
            $value = [$_POST['login'], \app\core\Utils::getPassCrypt($_POST['password'])];
            $con = \app\core\Database::getConnexion();
            if($dbConfig['DB_PREFIX'] != '' && !\app\core\Utils::endsWith($dbConfig['DB_PREFIX'], '_')) $dbConfig['DB_PREFIX'] .= "_";
            $req = "UPDATE sf_user SET login = ?, password = ? WHERE id = 1";
            $resultat = $con->prepare($req);
            $resultat->execute($value);
        } catch (\PDOException $e) {
            header('Location: '.WEBROOT.'error/'.base64_encode($e->getMessage()));
            exit(500);
        } catch (\Jacwright\RestServer\RestException $e) {}
    }

    $data = "<?php
/**
 * Created by PhpStorm.
 * User: Seyni FAYE
 * Date: ".gmdate('Y-m-d')."
 * Time: ".gmdate('H:i')."
 */
require_once 'vendor/autoload.php';
use app\core\services\App;
new App();";
    file_put_contents(ROOT."index.php", $data);

    $data = "Options -MultiViews
RewriteEngine On
RewriteBase ".RACINE."
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.+)$ index.php?url=$1 [QSA,L]";
    file_put_contents(ROOT.".htaccess", $data);
    header('location: '.WEBROOT.'finish');
    exit(1);
}
?>
<form id="form-bd" action="<?= WEBROOT; ?>configuration" method="post">
    <section class="cards-section text-center">
        <div class="container">
            <h2 class="title"><i class="fa fa-cogs mr-3"></i>Fichier de configuration !</h2>
            <h5>Etape 3/3</h5>
        </div><!--//container-->
        <div id="cards-wrapper" class="cards-wrapper row">
            <div class="item item-primary col-lg-12 col-md-12 col-sm-12">
                <div class="item-inner">
                    <div class="icon-holder">
                        <i class="icon fa fa-cogs"></i>
                    </div><!--//icon-holder-->
                    <div style="text-align: initial;">
                        <div class="form-group">
                            <label for="log" class="control-label">Gestion des logs</label>
                            <select name="log" id="log" class="form-control" style="width: 75%">
                                <option value="on">Activer</option>
                                <option selected value="off">Désactiver</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="profile_level" class="control-label">Niveau de profilage</label>
                            <select name="profile_level" id="profile_level" class="form-control" style="width: 75%">
                                <option value="1">Niveau 1</option>
                                <option value="2">Niveau 2</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="space_admin" class="control-label">Espace Admin</label>
                            <select name="space_admin" id="space_admin" class="form-control" style="width: 75%" onchange="idAdmin(this.value);">
                                <option value="on">Activer</option>
                                <option value="off">Désactiver</option>
                            </select>
                        </div>
                        <div id="id-admin">
                            <div class="form-group">
                                <label for="login" class="control-label">Login</label>
                                <input required type="text" id="login" name="login" class="form-control" placeholder="Login" style="width: 75%">
                            </div>
                            <div class="form-group">
                                <label for="password" class="control-label">Password</label>
                                <div class="input-group" id="show_hide_password" style="width: 75%">
                                    <input required type="password" id="password" name="password" class="form-control"
                                           placeholder="password" autocomplete="off">
                                    <a href="javascript:;" style="position: absolute; top: 5px; right: 10px; font-size: 20px;"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            <script>
                                function idAdmin(val){
                                    let elem = $('#id-admin');
                                    if(val === 'true') {
                                        $('#id-admin input').attr("required", "required");
                                        elem.slideDown();
                                    }else {
                                        $('#id-admin input').removeAttr("required");
                                        elem.slideUp();
                                    }
                                }
                                $("#show_hide_password a").on('click', function(event) {
                                    event.preventDefault();
                                    if($('#show_hide_password input').attr("type") == "text"){
                                        $('#show_hide_password input').attr('type', 'password');
                                        $('#show_hide_password i').addClass( "fa-eye-slash" );
                                        $('#show_hide_password i').removeClass( "fa-eye" );
                                    }else if($('#show_hide_password input').attr("type") == "password"){
                                        $('#show_hide_password input').attr('type', 'text');
                                        $('#show_hide_password i').removeClass( "fa-eye-slash" );
                                        $('#show_hide_password i').addClass( "fa-eye" );
                                    }
                                });
                            </script>
                        </div>
                        <div class="form-group">
                            <label for="use_api_client" class="control-label">Client API</label>
                            <select name="use_api_client" id="use_api_client" class="form-control" style="width: 75%">
                                <option value="on">Activer</option>
                                <option value="off" selected>Désactiver</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="mail_from" class="control-label">Mail expéditeur</label>
                            <input type="text" value="sunuframework@sunuframework.com" id="mail_from" name="mail_from" class="form-control" placeholder="Mail expéditeur" style="width: 75%">
                        </div>
                    </div>
                </div><!--//item-inner-->
            </div><!--//item-->
        </div><!--//cards-->
        <div class="cta-container">
            <a class="btn btn-primary btn-cta" href="javascript:history.back();">
                <i class="fas fa-cloud-download-alt"></i>
                Etape précédante
            </a>
            <button type="submit" class="btn btn-primary btn-cta">
                <i class="fas fa-cloud-download-alt"></i>
                Terminer
            </button>
        </div><!--//cta-container-->
    </section><!--//cards-section-->
</form>