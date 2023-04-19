<form id="validation" class="form-inline form-validator" data-type="update" role="form" onsubmit="return checkConstant();" action="<?= WEBROOT ?>config/constant" method="post">

    <div class="modal-header">
        <button type="button" class="close" aria-hidden="true" data-dismiss="modal">×</button>
        <h4 class="modal-title"><?= ((isset($currentConst)) ? $this->lang['edit_constant'] :  $this->lang['new_constant']); ?></h4>
    </div>
    <div class="modal-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <div class="form-group" style="width: 100%;padding: 10px;">
                        <label for="name" class="control-label"><?= $this->lang['nom']; ?></label>
                        <input type="text" id="name" name="name" class="form-control" value="<?= $currentConst['name'] ?>"
                               placeholder="<?= $this->lang['nom']; ?>" style="width: 100%" oninput="this.value = this.value.toUpperCase();" required>
                        <span class="help-block with-errors"> </span>
                    </div>
                    <div class="form-group" style="width: 100%;padding: 10px;">
                        <label for="value" class="control-label"><?= $this->lang['valeur']; ?></label>
                        <input type="text" id="value" name="value" class="form-control" value="<?= $currentConst['value'] ?>"
                               placeholder="<?= $this->lang['valeur']; ?>" style="width: 100%" required>
                        <span class="help-block with-errors"> </span>
                    </div>
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
    let constant = <?= json_encode($constant) ?>;
    function checkConstant() {
        let curConstant = $("#name").val();
        if(constant.indexOf(curConstant.toUpperCase()) === -1) return true;
        else {
            swal({
                text : "Cette constante existe déja",
                icon : "warning",
            });
            return false;
        }
    }
</script>