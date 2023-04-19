<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><?= $this->lang['espace'] ?></h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><?= $this->lang['configuration'] ?></li>
                <li class="active"><?= $this->lang['espace'] ?></li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /row -->
    <div class="row">
        <div class="col-sm-12" style="margin-bottom: 25px;">
            <div class="pull-right">
                <button type="button" class="open-modal btn btn-success"
                        data-modal-controller="config/spaceModal" data-modal-view="config/spaceModal">
                    <i class="fa fa-plus"></i> <?= $this->lang['new_space']; ?>
                </button>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="white-box">
                <h3 class="box-title m-b-0"><?= $this->lang['liste_espace'] ?></h3>
                <p class="text-muted m-b-30"></p>
                <hr>
                <div class="table-responsive">
                    <table id="myTable" class="table table-striped nowrap dataTable">
                        <thead>
                        <tr>
                            <th width="50%"><?= $this->lang['espace']; ?></th>
                            <th width="25%"><?= $this->lang['etat']; ?></th>
                            <th width="25%"><?= $this->lang['action']; ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($espace as $key => $value) {  ?>
                            <tr>
                                <td><?= $key ?></td>
                                <td><?= ($value == 'on' ? '<i class="text-success">Activer</i>' : '<i class="text-danger">Désactiver</i>') ?></td>
                                <td>
                                    <?php if($key !== "admin") { ?>
                                        <a class="action confirm-modal" data-placement="top" data-toggle="tooltip"
                                           href="<?= WEBROOT ?>config/stateSpace/<?= base64_encode($key) ?>" data-original-title="<?= $value == 'on' ? 'Desactiver' : 'Activer'; ?>">
                                            <i class="fa fa-toggle-<?= $value ?>"></i>
                                        </a>
                                        <a class="action confirm-modal" data-placement="top" data-toggle="tooltip"
                                           href="<?= WEBROOT ?>config/deleteSpace/<?= base64_encode($key) ?>" data-original-title="Supprimer">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    <?php }  ?>
                                    <a class="action" target="_blank" data-placement="top" data-toggle="tooltip"
                                       href="<?= RACINE.$key ?>" data-original-title="Acceder">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
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