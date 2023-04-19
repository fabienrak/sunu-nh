<form id="validation" class="form-inline form-validator" data-type="update" role="form" action="<?= WEBROOT ?>droit/<?= ((isset($droit->id)) ? "update" : "add") ?>" method="post">
    <div class="modal-header">
        <button type="button" class="close" aria-hidden="true" data-dismiss="modal">×</button>
        <h4 class="modal-title">Droit</h4>
    </div>

    <div class="modal-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <div class="form-group" style="width: 100%;padding: 10px;"><label for="libelle" class="control-label">Libelle</label> <input onchange="unique(this, '<?= \app\core\Utils::cryptString(serialize(["table" => "sf_droit", "champ" => "libelle"])) ?>')" type="text" id="libelle" name="libelle" class="form-control" value="<?= $droit->libelle; ?>" style="width: 100%" placeholder="Libelle"> <span class="help-block with-errors"> </span></div><div class="form-group" style="width: 100%;padding: 10px;"><label for="espace" class="control-label">Espace *</label> <input  required type="text" id="espace" name="espace" class="form-control" value="<?= $droit->espace; ?>" style="width: 100%" placeholder="Espace"> <span class="help-block with-errors"> </span></div><div class="form-group" style="width: 100%;padding: 10px;"><label for="sf_sous_module_id" class="control-label">Sous module *</label><select required name="sf_sous_module_id" id="sf_sous_module_id" class="form-control" style="width: 100%"><?php foreach ($sous_module as $item) { ?><option value="<?= $item->id ?>" <?= ($droit->sf_sous_module_id == $item->id) ? "selected" : "" ?> ><?= $item->libelle ?></option><?php } ?></select><span class="help-block with-errors"> </span></div><div class="form-group" style="width: 100%;padding: 10px;"><label for="controller" class="control-label">Controller *</label> <input  required type="text" id="controller" name="controller" class="form-control" value="<?= $droit->controller; ?>" style="width: 100%" placeholder="Controller"> <span class="help-block with-errors"> </span></div><div class="form-group" style="width: 100%;padding: 10px;"><label for="action" class="control-label">Action *</label> <input  required type="text" id="action" name="action" class="form-control" value="<?= $droit->action; ?>" style="width: 100%" placeholder="Action"> <span class="help-block with-errors"> </span></div>
                    <?php if(isset($droit->id)){  ?> <input type="hidden" name="id" value="<?= $droit->id; ?>"><?php } ?>
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