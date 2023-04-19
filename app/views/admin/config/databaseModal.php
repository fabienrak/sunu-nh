<form id="validation" class="form-inline form-validator" data-type="update" role="form" action="<?= WEBROOT ?>config/addDatabase" method="post">
    <style>
        .form-group {
            width: 100% !important;
            padding: 10px !important;
        }
        .form-group-input input{
            width: 100% !important;
        }
        .form-group select{
            width: 100% !important;
        }
    </style>
    <div class="modal-header">
        <button type="button" class="close" aria-hidden="true" data-dismiss="modal">×</button>
        <h4 class="modal-title"><?= $this->lang['new_space']; ?></h4>
    </div>

    <div class="modal-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <div class="form-group form-group-input">
                        <label for="type" class="control-label">Type</label>
                        <select name="DB<?= $nbr_db ?>_TYPE" id="type" class="form-control" style="width: 75%" onchange="setFormType(this.value);">
                            <option <?php if($currentDB["DB".$nbr_db."_TYPE"] == 'mysql') print 'selected'; ?>   value="mysql">MYSQL</option>
                            <option <?php if($currentDB["DB".$nbr_db."_TYPE"] == 'sqlite') print 'selected'; ?> value="sqlite">SQLite</option>
                        </select>
                    </div>
                    <div class="form-group form-group-input fg-mysql-1">
                        <label for="host" class="control-label">Serveur</label>
                        <input type="text" id="host" value="<?= $currentDB["DB".$nbr_db."_HOST"] ?>" name="DB<?= $nbr_db ?>_HOST" class="form-control" placeholder="Serveur" style="width: 75%" required>
                    </div>
                    <div class="form-group form-group-input">
                        <label for="name" class="control-label">Nom</label>
                        <input type="text" id="name" value="<?= $currentDB["DB".$nbr_db."_NAME"] ?>" name="DB<?= $nbr_db ?>_NAME" class="form-control" placeholder="Nom" style="width: 75%" required>
                    </div>
                    <div class="form-group form-group-input fg-mysql-1">
                        <label for="user" class="control-label">Username</label>
                        <input type="text" id="user" value="<?= $currentDB["DB".$nbr_db."_USER"] ?>" name="DB<?= $nbr_db ?>_USER" class="form-control" placeholder="Username" style="width: 75%" required>
                    </div>
                    <div class="form-group form-group-input fg-mysql-1">
                        <label for="password" class="control-label">Password</label>
                        <input type="text" id="password" value="<?= $currentDB["DB".$nbr_db."_PASSWORD"] ?>" name="DB<?= $nbr_db ?>_PASSWORD" class="form-control" placeholder="Password" style="width: 75%" required>
                    </div>
                    <div class="form-group form-group-input">
                        <label for="prefix" class="control-label">Prefixe des tables</label>
                        <input type="text" id="prefix" value="<?= $currentDB["DB".$nbr_db."_PREFIX"] ?>" name="DB<?= $nbr_db ?>_PREFIX" class="form-control" placeholder="Prefixe des tables" style="width: 75%">
                    </div>
                    <?php if(!isset($currentDB)){ ?>
                        <div class="form-group fg-mysql-1">
                            <label for="create" class="control-label">Créer la base de données</label>
                            <div>
                                <input type="checkbox" id="create" name="create" class="my-form-control">
                                <small>Si la base de données n'a pas encore été crée. Cochez cette case.</small>
                            </div>
                            <style>
                                .my-form-control {
                                    padding: .375rem .75rem !important;
                                    font-size: 1rem !important;
                                    line-height: 1.5 !important;
                                    color: #495057 !important;
                                    background-color: #fff !important;
                                    background-clip: padding-box !important;
                                    border: 1px solid #ced4da !important;
                                    border-radius: .25rem !important;
                                    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out !important;
                                }
                            </style>
                        </div>
                    <?php }else{ ?>
                        <input type="hidden" value="DB<?= $nbr_db ?>" name="id_db">
                    <?php } ?>
                </div>
                <div class="col-sm-3"></div>

            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button class="btn btn-success confirm" data-form="my-form" type="submit"><i class="fa fa-check"></i> <?= $this->lang['valider']; ?>
        </button>
        <button class="btn btn-default" type="button" data-dismiss="modal"><i class="fa fa-times"></i> <?= $this->lang['annuler']; ?> </button>
    </div>
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
<?php if(isset($currentDB)){ ?>
    <script>
        $(document).ready(function () {
            setFormType('<?= $currentDB["DB".$nbr_db."_TYPE"] ?>');
        });
    </script>
<?php } ?>