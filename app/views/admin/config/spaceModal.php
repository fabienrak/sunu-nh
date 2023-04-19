<form id="validation" class="form-inline form-validator" data-type="update" role="form" onsubmit="return checkSpace();" action="<?= WEBROOT ?>config/addSpace" method="post">

    <div class="modal-header">
        <button type="button" class="close" aria-hidden="true" data-dismiss="modal">×</button>
        <h4 class="modal-title"><?= $this->lang['new_space']; ?></h4>
    </div>

    <div class="modal-body">
        <div class="container-fluid">
            <div class="row">

                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <div class="form-group" style="width: 100%;padding: 10px;">
                        <label for="space" class="control-label"><?= $this->lang['espace']; ?></label>
                        <input type="text" id="space" name="space" class="form-control"
                               placeholder="<?= $this->lang['espace']; ?>" style="width: 100%" required>
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
    let space = <?= json_encode($espace) ?>;

    function checkSpace() {
        let curSpace = $("#space").val();
        if(space.indexOf(curSpace.toLowerCase()) === -1) return true;
        else {
            swal({
                text : "Cet espace existe déja",
                icon : "warning",
            });
            return false;
        }
    }
</script>