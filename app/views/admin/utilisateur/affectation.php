<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><?= $this->lang['affectation_droit'] ?></h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><?= $this->lang['parametrage'] ?></li>
                <li><?= $this->lang['profil'] ?></li>
                <li class="active"><?= $this->lang['affectation'] ?></li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /row -->
    <div class="row">
        <form action="<?= WEBROOT; ?>utilisateur/addAffectation" method="post">

            <div class="col-sm-12" style="margin-bottom: 25px;">
                <?php if (count($droit) != 0) { ?>
                    <div class="pull-right">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-check"></i> <?= $this->lang['valider']; ?>
                        </button>
                    </div>
                <?php } ?>
            </div>
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title m-b-0"><span style="font-weight: 300;"><?= $this->lang['affectation_droit_profil']; ?></span> : <?= $nomProfil ?></h3>
                    <p class="text-muted m-b-30"></p>
                    <hr>
                    <div class="table-responsive">
                        <div class="panel panel-default">
                            <input type="hidden" name="idProfil" value="<?= $idProfil ?>">
                            <div class="panel-body">
                                <?php if (count($droit) == 0) { ?>
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-sm-12 text-center">
                                                <h3 class="panel-title">Aucun droit ajouté !</h3>
                                            </div>
                                        </div>
                                    </div>
                                <?php }else{ ?>
                                    <input type="hidden" name="idProfil" value="<?= $idProfil ?>">
                                    <input type="hidden" name="idUser" value="<?= $idUser ?>">
                                    <?php $i = 0; $tabClass = [];
                                    foreach ($droit as $libMod => $module) { ?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><?= $libMod; ?></h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <?php foreach ($module as $lib_sm => $one_sm) { array_push($tabClass, strtolower(str_replace(' ', '-', $lib_sm))); ?>
                                                        <div class="col-md-6">
                                                            <div id="accor<?= str_replace(" ", "-", $lib_sm); ?>" class="panel-group">
                                                                <div class="panel panel-default">
                                                                    <div class="panel-heading">
                                                                        <h4 class="panel-title">
                                                                            <input id="ica-<?= strtolower(str_replace(" ", "-", $lib_sm)); ?>"
                                                                                   class="form-check-input" type="checkbox" style="position: absolute;right: 60px;"
                                                                                   onclick="setCheck('<?= strtolower(str_replace(" ", "-", $lib_sm)); ?>', this.checked)">
                                                                            <a href="#<?= strtolower(str_replace(' ', '-', $lib_sm)); ?>" data-parent="#accordion"
                                                                               data-toggle="collapse"
                                                                               class="accordion-toggle collapsed"
                                                                               aria-expanded="false">
                                                                                <?= $lib_sm; ?>
                                                                            </a>
                                                                        </h4>
                                                                    </div>
                                                                    <div class="panel-collapse collapse" id="<?= strtolower(str_replace(' ', '-', $lib_sm));; ?>"
                                                                         aria-expanded="false">
                                                                        <div class="panel-body">
                                                                            <?php foreach ($one_sm as $val) { ?>
                                                                                <div class="col-md-6">
                                                                                    <div class="form-check form-check-inline">
                                                                                        <input <?= (isset($val['id_aff_user']) && $val['etat_aff_user'] == 1 ? 'checked' : '') ?>
                                                                                                value="<?= (isset($val['id_aff_user']) ? $val['id_aff_user'] : $val['id_aff']) ?>"
                                                                                                name="<?= (isset($val['id_aff_user']) ? 'update[]' : 'add[]') ?>"
                                                                                                onclick="setCheck2('<?= strtolower(str_replace(" ", "-", $lib_sm)); ?>')"
                                                                                                class="form-check-input <?= strtolower(str_replace(' ', '-', $lib_sm)); ?>" type="checkbox">
                                                                                        <label class="form-check-label">
                                                                                            <?= $val['droit'] ?>
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php $i++;
                                    }
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- /.row -->
</div>
<script>
    function setCheck2($class) {
        var tabItem = $("."+$class);
        var $check = true;
        for(var i = 0 ; i < tabItem.length ; i++) if($(tabItem[i])[0].checked === false) $check = false;
        $("#ica-"+$class)[0].checked = $check;
    }

    function setCheck($class, $check) {
        var tabItem = $("."+$class);
        for(var i = 0 ; i < tabItem.length ; i++) $(tabItem[i])[0].checked = $check;
    }

    function setCheckAll($tabClass) {
        var tabItem;
        var $check;
        $tabClass.forEach(function (item) {
            $check = true;
            tabItem = $("."+item);
            for(var i = 0 ; i < tabItem.length ; i++) if($(tabItem[i])[0].checked === false) $check = false;
            // console.log($("#"+item));
            $("#ica-"+item)[0].checked = $check;
        })
    }
    $(document).ready(function () {
        setCheckAll(<?= json_encode($tabClass) ?>)
    });
</script>