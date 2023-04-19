<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Sousmodule</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li>[Module]</li>
                <li class="active">Sousmodule</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /row -->
    <div class="row">
        <div class="col-sm-12" style="margin-bottom: 25px;">
            <div class="pull-right">
                <button type="button" class="open-modal btn btn-success"
                        data-modal-controller="sousmodule/sousmoduleModal"
                        data-modal-view="sousmodule/sousmoduleModal">
                    <i class="fa fa-plus"></i> Ajouter
                </button>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="white-box">
                <h3 class="box-title m-b-0">sousmodule</h3>
                <p class="text-muted m-b-30"></p>
                <hr>
                <div class="table-responsive">
                    <table id="sousmodule" class="table table-striped nowrap processing" data-url="<?= WEBROOT; ?>sousmodule/sousmoduleProcessing">
                        <thead>
                        <tr><th>Libelle</th><th>Module</th><th>Etat</th><th>Code</th><th>Action</th></tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
</div>