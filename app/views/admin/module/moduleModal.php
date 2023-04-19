<form id="validation" class="form-inline form-validator" data-type="update" role="form" action="<?= WEBROOT ?>module/<?= ((isset($module->id)) ? "update" : "add") ?>" method="post">
    <div class="modal-header">
        <button type="button" class="close" aria-hidden="true" data-dismiss="modal">×</button>
        <h4 class="modal-title">Module</h4>
    </div>

    <div class="modal-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <div class="form-group" style="width: 100%;padding: 10px;"><label for="libelle" class="control-label">Libelle *</label> <input onchange="unique(this, '<?= \app\core\Utils::cryptString(serialize(["table" => "sf_module", "champ" => "libelle"])) ?>')" required type="text" id="libelle" name="libelle" class="form-control" value="<?= $module->libelle; ?>" style="width: 100%" placeholder="Libelle"> <span class="help-block with-errors"> </span></div><div class="form-group" style="width: 100%;padding: 10px;"><label for="code" class="control-label">Code *</label> <input onchange="unique(this, '<?= \app\core\Utils::cryptString(serialize(["table" => "sf_module", "champ" => "code"])) ?>')" required type="text" id="code" name="code" class="form-control" value="<?= $module->code; ?>" style="width: 100%" placeholder="Code"> <span class="help-block with-errors"> </span></div>
                    <?php if(isset($module->id)){  ?> <input type="hidden" name="id" value="<?= $module->id; ?>"><?php } ?>
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