<section class="cards-section text-center">
    <div class="container">
        <h2 class="title">Débutez facilement !</h2>
        <div class="intro">
            <p class="justify-content-around">
                SunuFramework est un environnement de travail qui va vous faciliter la vie. <br>
                Il définit plusieurs fonctionnalités en amont avec peu de pré-requies.
            </p>
            <div class="cta-container">
                <form action="<?= WEBROOT; ?>check" method="post">
                    <div>
                        <div class="form-group fg-mysql-1">
                            <label for="projet" class="control-label">Nom du projet</label>
                            <input type="text" id="projet" name="projet" class="form-control" placeholder="Nom du projet" style="width: 50%; margin-left: 25%;" required>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-cta" type="submit">
                        <i class="fas fa-cloud-download-alt"></i>
                        Débuter l'installation
                    </button>
                </form>
            </div><!--//cta-container-->
        </div><!--//intro-->
    </div><!--//container-->
</section><!--//cards-section-->