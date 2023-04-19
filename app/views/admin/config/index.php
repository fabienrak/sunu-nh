<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><?= $this->lang['app-config'] ?></h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><?= $this->lang['configuration'] ?></li>
                <li class="active"><?= $this->lang['app-config'] ?></li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /row -->
    <div class="row">
        <form method="post" action="<?= WEBROOT ?>config/updateConfig">
            <div class="col-sm-12" style="margin-bottom: 25px;">
                <div class="pull-right">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check"></i> <?= $this->lang['valider']; ?>
                    </button>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="white-box">
                    <div class="table-responsive">
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-8">
                            <section class="cards-section text-center">
                                <div id="cards-wrapper" class="cards-wrapper row">
                                    <div class="item item-primary col-lg-12 col-md-12 col-sm-12">
                                        <div class="item-inner">
                                            <div style="text-align: initial;">
                                                <div class="form-group">
                                                    <label for="env" class="control-label">Environnement</label>
                                                    <select name="env" id="env" class="form-control" style="width: 75%">
                                                        <option <?php if($appConfig['env'] == 'DEV') print 'selected'; ?> value="DEV">DEV</option>
                                                        <option <?php if($appConfig['env'] == 'PROD') print 'selected'; ?> value="PROD">PROD</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="log" class="control-label">Gestion des logs</label>
                                                    <select name="log" id="log" class="form-control" style="width: 75%">
                                                        <option <?php if($appConfig['log'] == '1') print 'selected'; ?> value="on">Activer</option>
                                                        <option <?php if($appConfig['log'] == '') print 'selected'; ?> value="off">Désactiver</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="profile_level" class="control-label">Niveau de profilage</label>
                                                    <select name="profile_level" id="profile_level" class="form-control" style="width: 75%">
                                                        <option <?php if($appConfig['profile_level'] == '1') print 'selected'; ?>  value="1">Niveau 1</option>
                                                        <option <?php if($appConfig['profile_level'] == '2') print 'selected'; ?>  value="2">Niveau 2</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="law_generate" class="control-label">Génération des droits</label>
                                                    <select name="law_generate" id="law_generate" class="form-control" style="width: 75%">
                                                        <option <?php if($appConfig['law_generate'] == '1') print 'selected'; ?> value="on">Activer</option>
                                                        <option <?php if($appConfig['law_generate'] == '') print 'selected'; ?> value="off">Désactiver</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="use_api_client" class="control-label">Client API</label>
                                                    <select name="use_api_client" id="use_api_client" class="form-control" style="width: 75%">
                                                        <option <?php if($appConfig['use_api_client'] == '1') print 'selected'; ?> value="on">Activer</option>
                                                        <option <?php if($appConfig['use_api_client'] == '') print 'selected'; ?> value="off">Désactiver</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="mail_from" class="control-label">Mail expéditeur</label>
                                                    <input type="text" value="<?= $appConfig['mail_from'] ?>" id="mail_from" name="mail_from" class="form-control" placeholder="Mail expéditeur" style="width: 75%">
                                                </div>
                                            </div>
                                        </div><!--//item-inner-->
                                    </div><!--//item-->
                                </div><!--//cards-->
                            </section><!--//cards-section-->
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->