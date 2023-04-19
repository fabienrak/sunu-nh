<?php
if(count($_POST) > 0) {
    $create = (isset($_POST['create']) && $_POST['DB_TYPE'] == 'mysql');
    $generate = (isset($_POST['generate']) && $_POST['DB_TYPE'] == 'mysql');
    unset($_POST['create']);
    unset($_POST['generate']);

    $ini = new \app\core\ConfigFile('config/db.config.ini', 'Configuration pour la base de données');
    $tab['DB'] = ($_POST['DB_TYPE'] === 'sqlite') ? ['DB_TYPE'=>$_POST['DB_TYPE'], 'DB_NAME'=>$_POST['DB_NAME'], 'DB_PREFIX'=>$_POST['DB_PREFIX']] : $_POST;
    $ini->add_array($tab);
    $ini->write();
    if($create) {
        try {
            $result = \app\core\Database::create($_POST["DB_NAME"]);
            if($result) $result = \app\core\Database::generateTable($_POST['DB_PREFIX']);
        } catch (\PDOException $e) {
            header('Location: '.WEBROOT.'error/'.base64_encode($e->getMessage()));
            exit(500);
        } catch (\Jacwright\RestServer\RestException $e) {}
    }
    if($generate || $_POST['DB_TYPE'] === 'sqlite') {
        try {
            \app\core\Database::generateTable();
        } catch (\PDOException $e) {
            header('Location: '.WEBROOT.'error/'.base64_encode($e->getMessage()));
            exit(500);
        } catch (\Jacwright\RestServer\RestException $e) {}
    }
    header('location: '.WEBROOT.'configuration');
    exit(1);
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
<form id="form-bd" action="<?= WEBROOT; ?>database" method="post">
    <section class="cards-section text-center">
        <div class="container">
            <h2 class="title"><i class="fa fa-database mr-3"></i>Base de données !</h2>
            <h5>Etape 2/3</h5>
        </div><!--//container-->
        <div id="cards-wrapper" class="cards-wrapper row">
            <div class="item item-primary col-lg-12 col-md-12 col-sm-12">
                <div class="item-inner">
                    <div class="icon-holder">
                        <i class="icon fa fa-database"></i>
                    </div><!--//icon-holder-->
                    <div style="text-align: initial;">
                        <div class="form-group">
                            <label for="type" class="control-label">Type</label>
                            <select name="DB_TYPE" id="type" class="form-control" style="width: 75%" onchange="setFormType(this.value);">
                                <option value="mysql">MYSQL</option>
                                <option value="sqlite">SQLite</option>
                            </select>
                        </div>
                        <div class="form-group fg-mysql-1">
                            <label for="host" class="control-label">Serveur</label>
                            <input type="text" id="host" name="DB_HOST" value="127.0.0.1" class="form-control" placeholder="Serveur" style="width: 75%" required>
                        </div>
                        <div class="form-group fg-mysql-1">
                            <label for="port" class="control-label">PORT</label>
                            <input type="number" id="port" name="DB_PORT" value="3306" class="form-control" placeholder="Port" style="width: 75%" required>
                        </div>
                        <div class="form-group">
                            <label for="name" class="control-label">Nom</label>
                            <input type="text" id="name" name="DB_NAME" class="form-control" placeholder="Nom" style="width: 75%" required>
                        </div>
                        <div class="form-group fg-mysql-1">
                            <label for="user" class="control-label">Username</label>
                            <input type="text" id="user" name="DB_USER" class="form-control" placeholder="Username" style="width: 75%" required>
                        </div>
                        <div class="form-group fg-mysql-1">
                            <label for="password" class="control-label">Password</label>
                            <input type="password" id="password" name="DB_PASSWORD" class="form-control" placeholder="Password" style="width: 75%" required>
                        </div>
                        <div class="form-group">
                            <label for="prefix" class="control-label">Prefixe des tables</label>
                            <input type="text" id="prefix" name="DB_PREFIX" class="form-control" placeholder="Prefixe des tables" style="width: 75%">
                        </div>
                        <div class="form-group fg-mysql-1">
                            <label for="create" class="control-label">Créer la base de données</label>
                            <div>
                                <input type="checkbox" id="create" name="create" class="my-form-control" onclick="$('#generate')[0].checked = this.checked;">
                                <small>Si la base de données n'a pas encore été crée. Cochez cette case.</small>
                            </div>
                            <style>
                                .my-form-control {
                                    padding: .375rem .75rem;
                                    font-size: 1rem;
                                    line-height: 1.5;
                                    color: #495057;
                                    background-color: #fff;
                                    background-clip: padding-box;
                                    border: 1px solid #ced4da;
                                    border-radius: .25rem;
                                    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
                                }
                            </style>
                        </div>
                        <div class="form-group fg-mysql-1">
                            <label for="generate" class="control-label">Générer les tables de base</label>
                            <div>
                                <input type="checkbox" id="generate" name="generate" class="my-form-control">
                                <small>Cochez cette case si la base de données ne contient les tables de base de SunuFramework.</small>
                            </div>
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
                Etape suivante
            </button>
        </div><!--//cta-container-->
    </section><!--//cards-section-->
</form>
<script>
    function setFormType(val) {
        let inputMysql = $('.fg-mysql-1');
        if(val === 'sqlite') {
            inputMysql.hide();
            for(var i = 0 ; i < inputMysql.length ; i++)
                $(inputMysql[i].children[1]).removeAttr('required');
        }else{
            inputMysql.show();
            for(i = 0 ; i < inputMysql.length ; i++)
                $(inputMysql[i].children[1]).attr('required', 'required');
        }
    }
</script>