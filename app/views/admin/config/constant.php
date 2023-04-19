<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><?= $this->lang['constant'] ?></h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><?= $this->lang['configuration'] ?></li>
                <li class="active"><?= $this->lang['constant'] ?></li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /row -->
    <div class="row">
        <div class="col-sm-12" style="margin-bottom: 25px;">
            <div class="pull-right">
                <button type="button" class="open-modal btn btn-success"
                        data-modal-controller="config/constantModal" data-modal-view="config/constantModal">
                    <i class="fa fa-plus"></i> <?= $this->lang['new_constant']; ?>
                </button>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="white-box">
                <h3 class="box-title m-b-0"><?= $this->lang['liste_constant'] ?></h3>
                <p class="text-muted m-b-30"></p>
                <hr>
                <div class="table-responsive">
                    <table id="myTable" class="table table-striped nowrap dataTable">
                        <thead>
                        <tr>
                            <th width="40%"><?= $this->lang['nom']; ?></th>
                            <th width="40%"><?= $this->lang['valeur']; ?></th>
                            <th width="20%"><?= $this->lang['action']; ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($constant as $key => $value) { ?>
                            <?php if($key !== "KEY_TOKEN" && $key !== "VERSION") { ?>
                                <tr>
                                    <td><?= $key ?></td>
                                    <td><?= $value ?></td>
                                    <td>
                                        <a class="action open-modal-processing" data-placement="top" data-toggle="tooltip" href="javascript:;"
                                           data-modal-controller="config/constantModal" data-modal-view="config/constantModal" data-modal-param="<?= base64_encode($key); ?>" data-original-title="Modifer">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a class="action confirm-modal" data-placement="top" data-toggle="tooltip"
                                           href="<?= WEBROOT ?>config/deleteConstant/<?= base64_encode($key) ?>" data-original-title="Supprimer">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php }  ?>

                        <?php }  ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
</div>

<script>
    $(".open-modal-processing").on("click", function() {
        let racine = "<?= RACINE ?>";
        let controller = $(this).data("modal-controller");
        let view = $(this).data("modal-view");
        let param = $(this).data("modal-param");
        let staticModal = $(this).data("modal-static");
        staticModal = staticModal == true || staticModal == false ? staticModal : staticGlobalModal;
        let $url = (param === undefined) ? webroot + controller : webroot + controller + "/" + param;
        if (controller !== undefined) {
            $.post (
                $url, {view : view},
                function(data){
                    if (parseInt(data) !== 0) {
                        let modal = '<div class="modal fade bs-modal-lg" id="modal" '+(staticModal == true ? 'data-backdrop="static"' : "")+' data-keyboard="false" data-dismiss="modal" tabindex="-1" role="dialog" aria-hidden="true"> <div class="modal-dialog modal-lg"> <div class="modal-content" id="content-modal"> <div class="modal-header"> <button type="button" class="close" aria-hidden="true" data-dismiss="modal">×</button> <h4 class="modal-title">En cours de chargement</h4> </div> <div class="modal-body"> <div align="center"> <img src="'+assets+'_main_/loading.gif" width="25%"/> </div> </div> <div class="modal-footer"> <button class="btn btn-default" type="button" data-dismiss="modal"> <i class="fa fa-times"></i> Annuler </button> </div> </div> </div> </div>';
                        $('#modal-container').html(modal);
                        $('#content-modal').html(data);
                        $('#modal').modal("show");
                    } else alert("La vue n'a pas été définie !")
                }
            );
        }else alert("Le controller n'a pas été défini !")
    });

    $(".confirm-modal").on("click", function (e) {
        let type_link = "url";
        let link = $(this).attr("href");
        if(link === undefined) {
            link = $(this).data("form");
            type_link = "form"
        }
        if(link !== undefined){
            e.preventDefault();
            $.getJSON(racine+"language/getLang/YWRtaW4=", (lang) => {
                console.log(lang);
                $.confirm({
                    title: lang.confirmTitre,
                    escapeKey: true, // close the modal when escape is pressed.
                    content: lang.confirmMessage,
                    backgroundDismiss: false, // for escapeKey to work, backgroundDismiss should be enabled.
                    icon: "fa fa-question",
                    theme: "material",
                    closeIcon: true,
                    animation: "scale",
                    type: "red",
                    buttons: {
                        "non" : {
                            text: lang.confirmBtnKo,
                            btnClass: "btn-red",
                            keys: ["ctrl","shift"],
                            action: () => {}
                        },
                        "oui" : {
                            text: lang.confirmBtnOk,
                            btnClass: "btn-green",
                            keys: ["enter"],
                            action: () => {
                                if(type_link === "url") window.location = link;
                                else $("#"+link).submit();
                            }
                        }
                    },
                });
            });
        }
    });
</script>
<!-- /.container-fluid -->