<section class="cards-section text-center">
    <div class="container">
        <div id="cards-wrapper" class="cards-wrapper row">
            <div class="col-lg-12 col-md-12 col-sm-12" style="font-size: 30px;">
                <div class="icon-holder">
                    <span aria-hidden="true" class="fa fa-4x fa-question"></span>
                </div><!--//icon-holder-->
                <?php if(isset($url[1])){ ?>
                    <p><?= base64_decode($url[1]); ?></p>
                <?php }else{ ?>
                    <h3 class="title">404</h3>
                    <p class="intro">
                        Page introuvable.
                    </p>
                <?php } ?>
                <p><a class="btn btn-lg btn-default" href="javascript:history.back();">Retour à la page précédente</a></p>
            </div><!--//item-->
        </div><!--//cards-->
    </div><!--//container-->
</section><!--//cards-section-->