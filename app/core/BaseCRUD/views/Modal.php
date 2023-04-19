<form id="validation" class="form-inline form-validator" data-type="update" role="form" action="<?= WEBROOT ?>_name_crud_/<?= ((isset($_name_crud_->id)) ? "update" : "add") ?>" method="post">
    <div class="modal-header">
        <button type="button" class="close" aria-hidden="true" data-dismiss="modal">×</button>
        <h4 class="modal-title">_Name_crud_</h4>
    </div>

    <div class="modal-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    _champs_modal_views_
                    <?php if(isset($_name_crud_->id)){  ?> <input type="hidden" name="id" value="<?= $_name_crud_->id; ?>"><?php } ?>
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