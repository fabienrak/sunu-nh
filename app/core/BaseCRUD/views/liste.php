<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">_Name_crud_</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li>[Module]</li>
                <li class="active">_Name_crud_</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /row -->
    <div class="row">
        <div class="col-sm-12" style="margin-bottom: 25px;">
            <div class="pull-right">
                <button type="button" class="open-modal btn btn-success"
                        data-modal-controller="_name_crud_/_name_crud_Modal"
                        data-modal-view="_name_crud_/_name_crud_Modal">
                    <i class="fa fa-plus"></i> Ajouter
                </button>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="white-box">
                <h3 class="box-title m-b-0">_name_crud_</h3>
                <p class="text-muted m-b-30"></p>
                <hr>
                <div class="table-responsive">
                    <table id="_name_crud_" class="table table-striped nowrap processing" data-url="<?= WEBROOT; ?>_name_crud_/_name_crud_Processing">
                        <thead>
                        _champs_processing_views_
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
</div>