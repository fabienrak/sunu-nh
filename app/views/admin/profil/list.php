<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Profil</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li>[Module]</li>
                <li class="active">Profil</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /row -->
    <div class="row">
        <div class="col-sm-12" style="margin-bottom: 25px;">
            <div class="pull-right">
                <button type="button" class="open-modal btn btn-success"
                        data-modal-controller="profil/profilModal"
                        data-modal-view="profil/profilModal">
                    <i class="fa fa-plus"></i> Ajouter
                </button>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="white-box">
                <h3 class="box-title m-b-0">profil</h3>
                <p class="text-muted m-b-30"></p>
                <hr>
                <div class="table-responsive">
                    <table id="profil" class="table table-striped nowrap processing" data-url="<?= WEBROOT; ?>profil/profilProcessing">
                        <thead>
                        <tr><th>Libelle</th><th>Etat</th><th>Action</th></tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
</div>