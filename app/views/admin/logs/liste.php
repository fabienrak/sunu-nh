<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><?= $this->lang['liste_logs'] ?></h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><?= $this->lang['parametrage'] ?></li>
                <li class="active"><?= $this->lang['logs'] ?></li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /row -->
    <div class="row">
        <div class="col-sm-12" style="margin-bottom: 25px;">

        </div>
        <div class="col-sm-12">
            <div class="white-box">
                <h3 class="box-title m-b-0"><?= $this->lang['liste_logs'] ?></h3>
                <p class="text-muted m-b-30"></p>
                <hr>
                <div class="table-responsive">
                    <table id="myTable" class="table table-striped nowrap processing" data-url="<?= WEBROOT; ?>module/listeProcessing">
                        <thead>
                        <tr>
                            <th width="33%"><?= $this->lang['modules']; ?></th>
                            <th width="33%"><?= $this->lang['code']; ?></th>
                            <th width="33%"><?= $this->lang['action']; ?></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
</div>