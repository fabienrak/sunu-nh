<form id="validation" class="form-inline form-validator" data-type="update" role="form"
      action="<?= WEBROOT ?>utilisateur/<?= ((isset($utilisateur->id)) ? "update" : "add") ?>" method="post">
    <div class="modal-header">
        <button type="button" class="close" aria-hidden="true" data-dismiss="modal">×</button>
        <h4 class="modal-title">Utilisateur</h4>
    </div>

    <div class="modal-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <div class="form-group" style="width: 100%;padding: 10px;"><label for="prenom"
                                                                                      class="control-label">Prenom
                            *</label> <input required type="text" id="prenom" name="prenom" class="form-control"
                                             value="<?= $utilisateur->prenom; ?>" style="width: 100%"
                                             placeholder="Prenom"> <span class="help-block with-errors"> </span></div>
                    <div class="form-group" style="width: 100%;padding: 10px;"><label for="nom" class="control-label">Nom
                            *</label> <input required type="text" id="nom" name="nom" class="form-control"
                                             value="<?= $utilisateur->nom; ?>" style="width: 100%" placeholder="Nom">
                        <span class="help-block with-errors"> </span></div>
                    <div class="form-group" style="width: 100%;padding: 10px;"><label for="email" class="control-label">Email
                            *</label> <input onchange="unique(this, '<?= \app\core\Utils::cryptString(serialize(["table" => "sf_user", "champ" => "email"])) ?>')"
                                required type="text" id="email" name="email" class="form-control"
                                value="<?= $utilisateur->email; ?>" style="width: 100%" placeholder="Email"> <span
                                class="help-block with-errors"> </span></div>
                    <div class="form-group" style="width: 100%;padding: 10px;"><label for="login" class="control-label">Login
                            *</label> <input
                                onchange="unique(this, '<?= \app\core\Utils::cryptString(serialize(["table" => "sf_user", "champ" => "login"])) ?>')"
                                required type="text" id="login" name="login" class="form-control"
                                value="<?= $utilisateur->login; ?>" style="width: 100%" placeholder="Login"> <span
                                class="help-block with-errors"> </span></div>
                    <div class="form-group" style="width: 100%;padding: 10px;"><label for="sf_profil_id"
                                                                                      class="control-label">Profil</label><select
                                name="sf_profil_id" id="sf_profil_id" class="form-control"
                                style="width: 100%"><?php foreach ($profil as $item) { ?>
                                <option
                                value="<?= $item->id ?>" <?= ($utilisateur->sf_profil_id == $item->id) ? "selected" : "" ?> ><?= $item->libelle ?></option><?php } ?>
                        </select><span class="help-block with-errors"> </span></div>
                    <?php if (isset($utilisateur->id)) { ?> <input type="hidden" name="id"
                                                                   value="<?= $utilisateur->id; ?>"><?php } ?>
                </div>
                <div class="col-sm-3"></div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button class="btn btn-success confirm" data-form="my-form" type="submit"><i
                    class="fa fa-check"></i> <?= $this->lang['valider']; ?>
        </button>
        <button class="btn btn-default" type="button" data-dismiss="modal"><i
                    class="fa fa-times"></i> <?= $this->lang['annuler']; ?> </button>
    </div>
</form>