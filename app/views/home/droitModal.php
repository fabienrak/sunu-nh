<form id="validation" class="form-inline form-validator" data-type="update" role="form" action="<?= WEBROOT ?>droit/<?= ((isset($droit->id)) ? "modifDroit" : "ajoutDroit") ?>" method="post">

    <div class="modal-header">
        <button type="button" class="close" aria-hidden="true" data-dismiss="modal">×</button>
        <h4 class="modal-title"><?php echo $this->lang['ajoutDroit']; ?></h4>
    </div>

    <div class="modal-body">
        <div class="container-fluid">
            <div class="row">

                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <div class="form-group" style="width: 100%;padding: 10px;">

                        <label for="fk_sous_module" class="control-label"><?php echo $this->lang['labSousModule']; ?></label>
                        <select name="fk_sous_module" id="fk_sous_module" class="form-control" style="width: 100%">
                            <?php foreach ($sousModule as $item) { ?>
                                <option <?= ($droit->fk_sous_module == $item->id) ? "selected" : "" ?> value="<?= $item->id ?>"><?= $item->sous_module ?></option>
                            <?php } ?>
                        </select>
                        <span class="help-block with-errors"> </span>
                    </div>
                    <div class="form-group" style="width: 100%;padding: 10px;">
                        <label for="droit" class="control-label"><?php echo $this->lang['labDroit']; ?></label>
                        <input type="text" id="droit" name="droit" class="form-control" placeholder="Libelle"
                               value="<?= $droit->droit; ?>" style="width: 100%" required>
                        <span class="help-block with-errors"> </span>
                    </div>
                    <div class="form-group" style="width: 100%;padding: 10px;">
                        <label for="controller" class="control-label"><?php echo $this->lang['labController']; ?></label>
                        <input type="text" id="controller" name="controller" class="form-control" placeholder="Libelle"
                               value="<?= $droit->controller; ?>" style="width: 100%" required>
                        <span class="help-block with-errors"> </span>
                    </div>
                    <div class="form-group" style="width: 100%;padding: 10px;">
                        <label for="action" class="control-label"><?php echo $this->lang['labMethode']; ?></label>
                        <input type="text" id="action" name="action" class="form-control" placeholder="Libelle"
                               value="<?= $droit->action; ?>" style="width: 100%" required>
                        <span class="help-block with-errors"> </span>
                    </div>
                    <?php if(isset($droit->id)){  ?> <input type="hidden" name="id" value="<?= $droit->id; ?>"><?php } ?>
                </div>
                <div class="col-sm-3"></div>

            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button class="btn btn-success confirm" data-form="my-form" type="submit"><i class="fa fa-check"></i> <?php echo $this->lang['btnValider']; ?>
        </button>
        <button class="btn btn-default" type="button" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo $this->lang['btnFermer']; ?> </button>
    </div>

</form>

<script>
//    $('#validation').formValidation({
//            framework: 'bootstrap',
//            fields: {
//                libelle: {
//                    validators: {
//                        notEmpty: {
//                            message: '<?//= $this->lang['droitObligatoire']; ?>//'
//                        }
//                    }
//                }
//            }
//        }
//    );
</script>