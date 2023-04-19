<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Utilisateur</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li>[Module]</li>
                <li class="active">Utilisateur</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /row -->
    <div class="row">
        <div class="col-sm-12" style="margin-bottom: 25px;">
            <div class="pull-right">
                <button type="button" class="open-modal btn btn-success"
                        data-modal-controller="utilisateur/utilisateurModal"
                        data-modal-view="utilisateur/utilisateurModal">
                    <i class="fa fa-plus"></i> Ajouter
                </button>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="white-box">
                <h3 class="box-title m-b-0">utilisateur</h3>
                <p class="text-muted m-b-30"></p>
                <hr>
                <div class="table-responsive">
                    <table id="utilisateur" class="table table-striped nowrap processing" data-url="<?= WEBROOT; ?>utilisateur/utilisateurProcessing">
                        <thead>
                        <tr><th>Prenom</th><th>Nom</th><th>Email</th><th>Login</th><th>Profil</th><th>Etat</th><th>Action</th></tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
</div>