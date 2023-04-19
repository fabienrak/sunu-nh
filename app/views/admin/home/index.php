<section id="wrapper" class="new-login-register">
    <div class="lg-info-panel">
        <div class="inner-panel">
            <div class="lg-content">
                <h2><?= PROJET ?></h2>
                <p class="text-muted">Espace d'administration</p>
            </div>
        </div>
    </div>
    <div class="new-login-box">
        <div class="white-box">
            <h3 class="box-title m-b-0">Se connecter</h3>
            <form class="form-horizontal new-lg-form" method="post" id="loginform" action="<?= WEBROOT ?>home/login">
                <div class="form-group  m-t-20">
                    <div class="col-xs-12">
                        <label>Login</label>
                        <input class="form-control" name="login" type="text" required placeholder="Login">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <label>Password</label>
                        <input class="form-control" type="password" name="password" required placeholder="Password">
                    </div>
                </div>
                <div class="form-group text-center m-t-20">
                    <div class="col-xs-12">
                        <button class="btn btn-info btn-lg btn-block btn-rounded text-uppercase waves-effect waves-light" type="submit">connecter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
