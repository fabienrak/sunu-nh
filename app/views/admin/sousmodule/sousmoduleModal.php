<form id="validation" class="form-inline form-validator" data-type="update" role="form" action="<?= WEBROOT ?>sousmodule/<?= ((isset($sousmodule->id)) ? "update" : "add") ?>" method="post">
    <div class="modal-header">
        <button type="button" class="close" aria-hidden="true" data-dismiss="modal">×</button>
        <h4 class="modal-title">Sousmodule</h4>
    </div>

    <div class="modal-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <div class="form-group" style="width: 100%;padding: 10px;"><label for="libelle" class="control-label">Libelle *</label> <input  required type="text" id="libelle" name="libelle" class="form-control" value="<?= $sousmodule->libelle; ?>" style="width: 100%" placeholder="Libelle"> <span class="help-block with-errors"> </span></div><div class="form-group" style="width: 100%;padding: 10px;"><label for="sf_module_id" class="control-label">Module *</label><select required name="sf_module_id" id="sf_module_id" class="form-control" style="width: 100%"><?php foreach ($module as $item) { ?><option value="<?= $item->id ?>" <?= ($sousmodule->sf_module_id == $item->id) ? "selected" : "" ?> ><?= $item->libelle ?></option><?php } ?></select><span class="help-block with-errors"> </span></div><div class="form-group" style="width: 100%;padding: 10px;"><label for="code" class="control-label">Code *</label> <input onchange="unique(this, '<?= \app\core\Utils::cryptString(serialize(["table" => "sf_sous_module", "champ" => "code"])) ?>')" required type="text" id="code" name="code" class="form-control" value="<?= $sousmodule->code; ?>" style="width: 100%" placeholder="Code"> <span class="help-block with-errors"> </span></div>
                    <?php if(isset($sousmodule->id)){  ?> <input type="hidden" name="id" value="<?= $sousmodule->id; ?>"><?php } ?>
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