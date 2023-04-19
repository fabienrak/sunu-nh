<style>
    .mr10 {
        margin-right: 10px;
    }
    .ml25 {
        margin-left: 25px;
    }
    .ml50 {
        margin-left: 50px;
    }
    .ml75 {
        margin-left: 75px;
    }
    .ml100 {
        margin-left: 100px;
    }
</style>
<div class="doc-wrapper">
    <div class="container-fluid">
        <div id="doc-header" class="doc-header text-center">
            <h1 class="doc-title"><i class="icon fa fa-paper-plane"></i> Documentation</h1>
            <div class="meta"><i class="far fa-clock"></i> Last updated: July 18th, 2018</div>
        </div><!--//doc-header-->
        <!--<div class="doc-body row">
            <div class="doc-sidebar col-md-3 col-sm-3 col-xs-6"></div>
            <div class="doc-content col-md-8 col-sm-8 col-xs-6">
                <div class="content-inner">
                    <p>
                        Sunuframeawork est cadre de travail qui va vous permettre de développer éfficacement et rapidement des <strong>applications Web</strong>, des <strong>Api REST</strong>.
                        <br>
                        Il vous permettra également trés facilement de faire appel à des <strong>Webservices REST</strong> et <strong>SOAP</strong>.
                        Il est basé sur le <strong>pattern MVC</strong>.
                    </p>
                </div><!--//content-inner
            </div><!--//doc-content
        </div>-->
        <div class="doc-body row">
            <div class="doc-content col-md-8 col-sm-8 col-xs-6 order-1">
                <div class="content-inner">
                    <div style="position: fixed;bottom: 75px;right: 30px;border: 1px solid <?= $color ?>;border-radius: 7px;opacity: 0.5;">
                        <a class="nav-link scrollto" href="#haut"><i class="fa fa-2x fa-level-up"></i></a>
                    </div>
                    <section id="projet" class="doc-section">
                        <h2 class="section-title"><i class="fa fa-archive mr10"></i>[votre_nom_de_projet]</h2>
                        <br>
                        <p>
                            Sunuframeawork est cadre de travail qui va vous permettre de développer éfficacement et rapidement des <strong>applications Web</strong>, des <strong>Api REST</strong>.
                            <br>
                            Il vous permettra également trés facilement de faire appel à des <strong>Webservices REST</strong> et <strong>SOAP</strong>.
                            Il est basé sur le <strong>pattern MVC</strong>.
                        </p>
                        <div id="app" class="section-block ml25">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>app</h3>
                            <p>
                                Ce dossier contiendra toute la logique applicative, à savoir les <strong>controlleurs</strong>, les <strong>modéles</strong> et les <strong>vues</strong> entre autres.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="common" class="section-block ml50">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>common</h3>
                            <p>
                                Dans ce dossier on retrouvra toutes les méthodes des <strong>modéles</strong> et de la classe <strong>Utils</strong>, partagées et accéssibles un peu partout dans l'application.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="CommonModel" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-file-code-o mr10"></i>CommonModel</h3>
                            <p>
                                Ce fichier est un <a target="_blank" href="http://php.net/manual/fr/language.oop5.traits.php">trait</a> qui est utilisé par le <strong>BaseModels</strong>, les méthodes qu'elle contient sont accéssibles à toutes les classes modéles.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="CommonUtils" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-file-code-o mr10"></i>CommonUtils</h3>
                            <p>
                                Ce fichier est un <a target="_blank" href="http://php.net/manual/fr/language.oop5.traits.php">trait</a> qui est utilisé par le <strong>Utils</strong>, les méthodes qu'elle contient sont accéssibles via la classe static <strong>Utils</strong>.
                                <br>
                                <strong>NB : </strong>la classe <strong>Utils</strong> étant une classe static, les méthodes qui seront définies dans le trait <strong>CommonUtils</strong> devront etre des <strong>méthodes static</strong>.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="controllers" class="section-block ml50">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>controllers</h3>
                            <p>
                                Dans ce dossier et ses sous dossiers on retrouvra tous les controleurs.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="controllers-admin" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>admin</h3>
                            <p>
                                Ce dossier est un espace de l'application il est prédifini dans le fichier configuration.
                                <strong>NB : </strong>les classes qui seront définies dans ce dossier devront avoir le namespace suivant : <strong>namespace app\controllers\admin;</strong>
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="ErrorController" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-file-code-o mr10"></i>ErrorController *</h3>
                            <p>
                                Ce fichier est un controlleur servant à la gestion des erreurs.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="HomeController" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-file-code-o mr10"></i>HomeController</h3>
                            <p>
                                Ce fichier est un controlleur typique, vous pouvez vous y réferrer pour créer vos contolleurs.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="LanguageController" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-file-code-o mr10"></i>LanguageController *</h3>
                            <p>
                                Ce fichier est un controlleur servant à la gestion des multilangues.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="WebserviceClientController" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-file-code-o mr10"></i>WebserviceClientController</h3>
                            <p>
                                Ce fichier est un controlleur qui est spécifiquement utilisé pour faire des appels d'api REST et SOAP.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="WebserviceServerController" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-file-code-o mr10"></i>WebserviceServerController</h3>
                            <p>
                                Ce fichier est un controlleur qui est spécifiquement utilisé pour définir des api REST.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="core" class="section-block ml50">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>core *</h3>
                            <p>
                                Ce dossier représente le coeur du framework.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="services" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>services</h3>
                            <p>
                                Ce dossier contient le service de routing du framework.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="App" class="section-block ml100">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>App</h3>
                            <p>
                                Le fichier App représente le fichier de routing. Il gére les redirections et valide les routes de l'application.
                                <br>
                                Il permet aussi de définir les constantes propres au framework et celles prédéfinies au niveau du fichier <strong>app.config.ini</strong>
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="ApiClient" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>ApiClient</h3>
                            <p>
                                Ce fichier établie la configuration de base pour l'appel d'api <strong>REST</strong>.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="ApiClientSoap" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>ApiClientSoap</h3>
                            <p>
                                Ce fichier établie la configuration de base pour l'appel d'api <strong>SOAP</strong>.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="ApiServer" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>ApiServer</h3>
                            <p>
                                Ce fichier établie la configuration de base pour une implémentation d'api <strong>REST</strong>.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="BaseController" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>BaseController</h3>
                            <p>
                                Nous avons ici la classe qui représente la couche métier de base. Elle est la classe parente de  tous les controlleurs.
                                <br>
                                Elle permet de définir des méthodes communes à tous les controlleurs de l'application.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="BaseModel" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>BaseModel</h3>
                            <p>
                                Nous avons ici la classe qui représente la couche d'acces aux données. Elle est la classe parente de tous les modéles.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="BaseViews" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>BaseViews</h3>
                            <p>
                                Cette classe permet de gerer le templating et retourne la vue.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="error" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>error</h3>
                            <p>
                                Ce fichier représente la vue à retourner pour la gestion des erreurs.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="Language" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>Language</h3>
                            <p>
                                Ce fichier est la classe de base de la configuration des langues et donne accés aux données des fichiers de langue prédéfinis.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="message" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>message</h3>
                            <p>
                                Ce fichier représente le gestionnaire des messages d'erreur et notifications.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="Model" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>Model</h3>
                            <p>
                                Ce fichier est une classe modéle utilisée par le framework.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="Security" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>Security</h3>
                            <p>
                                Cette classe gérer l'aspect sécuritaire de l'application.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="Session" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>Session</h3>
                            <p>
                                Cette classe gérer la sesssion et permet d'y avoir accés plus facilement.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="TokenJWT" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>TokenJWT</h3>
                            <p>
                                Cette classe static est utilisée pour la génération de token <a href="">JWT (JSON WEB TOKEN)</a>.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="Utils" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>Utils</h3>
                            <p>
                                Cette classe est un utilitaire regroupant plusieurs méthodes <strong>statics</strong> utiles
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="language" class="section-block ml50">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>language</h3>
                            <p>
                                Ce doosier regroupe les fichiers des différentes langues de votre application.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="fr" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>fr</h3>
                            <p>
                                Ce fichier est un exemple de fichier de langue sur lequel il faudra se baser pour définir les autres fichiers de langue.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="models" class="section-block ml50">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>models</h3>
                            <p>
                                Ce dossier va regrouper toutes vos classes modéles.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="models-admin" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>admin</h3>
                            <p>
                                Ce dossier est un espace de l'application il est prédifini dans le fichier configuration.
                                <strong>NB : </strong>les classes qui seront définies dans ce dossier devront avoir le namespace suivant : <strong>namespace app\models\admin;</strong>
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="HomeModel" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>HomeModel</h3>
                            <p>
                                Cette classe est un modéle typique, vous pouvez vous y réferrer pour créer vos modéles.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="views" class="section-block ml50">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>views</h3>
                            <p>
                                Ce dossier regroupe toutes vos vues. Vous avez la liberté de les organiser dans des dossiers et sous dossiers.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="views-admin" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>admin</h3>
                            <p>
                                Ce dossier est un espace de l'application il est prédifini dans le fichier configuration.
                                <strong>NB : </strong>Ce dossier devra contenir toutes les vues ainsi que le dossier de templating qu'utilisera l'espace <strong>admin</strong>.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="home" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>home</h3>
                            <p>
                                Ce dossier est un modéle typique d'un dossier contenant des vues, vous pouvez vous y réferrer pour créer vos propres dossier de vues.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="template" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>template</h3>
                            <p>
                                Ce dossier est contient les fichiers de template qu'utilisera l'espace par défaut.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="webservice" class="section-block ml50">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>webservice</h3>
                            <p>
                                Dans ce dossier on trouvera les classes qui définissent les services des api.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="api" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>api</h3>
                            <p>
                                Cette classe est un exemple offrant des services d'api.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="assets" class="section-block ml25">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>assets</h3>
                            <p>
                                Commes son nom l'indique on retrouvera les assets (css, js, plugins) dans ce dossier.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="_main_" class="section-block ml50">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>_main_</h3>
                            <p>
                                Ce dossier appartient au framework. Il contient une dependance JS du framework.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="main.js" class="section-block ml75">
                            <h3 class="block-title"><i class="fa fa-file-code-o mr10"></i>main.js *</h3>
                            <p>
                                Ce fichier est la dépendance en question dont je parlais. <br>
                                C'est un script JS à inclure <strong>obligatoirement</strong> dans tous les footer de vos templates.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="css" class="section-block ml50">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>css</h3>
                            <p>
                                Ce dossier va contenir tous vos fichiers <strong>CSS</strong>
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="medias" class="section-block ml50">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>medias</h3>
                            <p>
                                Ce dossier va contenir tous vos fichiers <strong>images/vidéos</strong> que devra utiliser vos CSS et/ou JS.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="js" class="section-block ml50">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>js</h3>
                            <p>
                                Ce dossier va contenir tous vos fichiers <strong>JS</strong>
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="plugins" class="section-block ml50">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>plugins</h3>
                            <p>
                                Dans ce dossier vous allez regrouper tous les plugins de votre application.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="config" class="section-block ml25">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>config</h3>
                            <p>
                                Ce dossier est le <strong>premier</strong> à visiter. <br>
                                Il va contenir tous les fichiers de configuration de base à effectuer en amont.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="config-htaccess" class="section-block ml50">
                            <h3 class="block-title"><i class="fa fa-file-code-o mr10"></i>htaccess</h3>
                            <p>
                                Ce fichier <strong>htacces</strong> sert juste à restreindre l'accés à dossier.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="app-config-ini" class="section-block ml50">
                            <h3 class="block-title"><i class="fa fa-file-code-o mr10"></i>app.config.ini</h3>
                            <p>
                                Ce fichier doit etre le <strong>premier à etre éditer</strong>.
                                On y configure tous les prérequis de l'application.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="db-config-ini" class="section-block ml50">
                            <h3 class="block-title"><i class="fa fa-file-code-o mr10"></i>db.config.ini</h3>
                            <p>
                                Ce fichier doit etre le <strong>second à etre éditer</strong>.
                                On y configure les accés à la base de données.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="DB-sql" class="section-block ml50">
                            <h3 class="block-title"><i class="fa fa-file-code-o mr10"></i>DB.mysql.sql</h3>
                            <p>
                                Ce fichier contient le script <strong>SQL</strong> de base qui sera importé dans votre base de données <strong>MYSQL</strong>.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="DB-sqlite" class="section-block ml50">
                            <h3 class="block-title"><i class="fa fa-file-code-o mr10"></i>DB.sqlite.sql</h3>
                            <p>
                                Ce fichier contient le script <strong>SQLITE</strong> de base qui sera importé dans votre base de données <strong>SQLITE</strong>. <br>
                                La base de données SQLITE sera enrgistré dans le dossier <strong>config</strong>. <br>
                                Elle aura le nom de la base de données que vous aviez configurer dans le fichier <strong>DB.config.ini</strong>
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="public" class="section-block ml25">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>public</h3>
                            <p>
                                Dans ce dossier on mettra toutes ressources publiques propres a votre apllication.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="vendor" class="section-block ml25">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>vendor</h3>
                            <p>
                                Ce dossier est créer par <strong>composer</strong> qui l'utilise pour gérer toutes dépendances installées.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="htaccess" class="section-block ml25">
                            <h3 class="block-title"><i class="fa fa-file-code-o mr10"></i>htaccess</h3>
                            <p>
                                Ce fichier doit etre le <strong>troiséme à etre éditer</strong>.
                                Il est principalement utilisé pour gérer la réécriture d'url.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="composer.json" class="section-block ml25">
                            <h3 class="block-title"><i class="fa fa-file-code-o mr10"></i>composer.json</h3>
                            <p>
                                Dans ce on y trouve principalement la liste de toutes les dépendances installées.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="composer.lock" class="section-block ml25">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>composer.lock</h3>
                            <p>
                                Ce fichier est créé et utilisé par composer pour la gestion de nos dépendance.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="composer.phar" class="section-block ml25">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>composer.phar</h3>
                            <p>
                                Ceci est ni plus ni moins que <strong>composer</strong>.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="index" class="section-block ml25">
                            <h3 class="block-title"><i class="fa fa-file-code-o mr10"></i>index</h3>
                            <p>
                                Ce fichier est le <strong>point d'entrée</strong> du framework.
                            </p>
                        </div><!--//section-block ml75-->
                        <div id="readme" class="section-block ml25">
                            <h3 class="block-title"><i class="fa fa-archive mr10"></i>README</h3>
                            <p>
                                Ceci est juste un fichier qui décrit briévement c'est quoi <strong>SunuFramework</strong>.
                            </p>
                        </div><!--//section-block ml75-->
                    </section><!--//doc-section-->
                </div><!--//content-inner-->
            </div><!--//doc-content-->
            <div class="doc-sidebar col-md-3 col-sm-3 col-xs-6 order-0 d-none d-md-flex">
                <div id="doc-nav" class="doc-nav" style="background-color: #484d55;overflow: scroll;">
                    <nav id="doc-menu" class="nav doc-menu flex-column sticky" style="padding: 15px 0 0 0;">
                        <a class="nav-link scrollto" href="#projet"><i class="fa fa-archive mr10"></i>[votre_nom_de_projet]</a>
                        <nav class="doc-sub-menu nav flex-column">
                            <a class="nav-link scrollto" href="#app"><i class="fa fa-archive mr10"></i>app</a>
                            <nav class="doc-sub-menu nav flex-column ml25">
                                <a class="nav-link scrollto" href="#common"><i class="fa fa-archive mr10"></i>common</a>
                                <nav class="doc-sub-menu nav flex-column ml25">
                                    <a class="nav-link scrollto" href="#CommonModel"><i class="fa fa-file-code-o mr10"></i>CommonModel</a>
                                    <a class="nav-link scrollto" href="#CommonUtils"><i class="fa fa-file-code-o mr10"></i>CommonUtils</a>
                                </nav><!--//nav-->
                                <a class="nav-link scrollto" href="#controllers"><i class="fa fa-archive mr10"></i>controllers</a>
                                <nav class="doc-sub-menu nav flex-column ml25">
                                    <a class="nav-link scrollto" href="#controllers-admin"><i class="fa fa-archive mr10"></i>admin</a>
                                    <a class="nav-link scrollto" href="#ErrorController"><i class="fa fa-file-code-o mr10"></i>ErrorController</a>
                                    <a class="nav-link scrollto" href="#HomeController"><i class="fa fa-file-code-o mr10"></i>HomeController</a>
                                    <a class="nav-link scrollto" href="#LanguageController"><i class="fa fa-file-code-o mr10"></i>LanguageController</a>
                                    <a class="nav-link scrollto" href="#WebserviceClientController"><i class="fa fa-file-code-o mr10"></i>WebserviceClientController</a>
                                    <a class="nav-link scrollto" href="#WebserviceServerController"><i class="fa fa-file-code-o mr10"></i>WebserviceServerController</a>
                                </nav><!--//nav-->
                                <a class="nav-link scrollto" href="#core"><i class="fa fa-archive mr10"></i>core</a>
                                <nav class="doc-sub-menu nav flex-column ml25">
                                    <a class="nav-link scrollto" href="#services"><i class="fa fa-archive mr10"></i>services</a>
                                    <nav class="doc-sub-menu nav flex-column ml25">
                                        <a class="nav-link scrollto" href="#App"><i class="fa fa-file-code-o mr10"></i>App</a>
                                    </nav><!--//nav-->
                                    <a class="nav-link scrollto" href="#ApiClient"><i class="fa fa-file-code-o mr10"></i>ApiClient</a>
                                    <a class="nav-link scrollto" href="#ApiClientSoap"><i class="fa fa-file-code-o mr10"></i>ApiClientSoap</a>
                                    <a class="nav-link scrollto" href="#ApiServer"><i class="fa fa-file-code-o mr10"></i>ApiServer</a>
                                    <a class="nav-link scrollto" href="#BaseController"><i class="fa fa-file-code-o mr10"></i>BaseController</a>
                                    <a class="nav-link scrollto" href="#BaseModel"><i class="fa fa-file-code-o mr10"></i>BaseModel</a>
                                    <a class="nav-link scrollto" href="#BaseViews"><i class="fa fa-file-code-o mr10"></i>BaseViews</a>
                                    <a class="nav-link scrollto" href="#error"><i class="fa fa-file-code-o mr10"></i>error</a>
                                    <a class="nav-link scrollto" href="#Language"><i class="fa fa-file-code-o mr10"></i>Language</a>
                                    <a class="nav-link scrollto" href="#message"><i class="fa fa-file-code-o mr10"></i>message</a>
                                    <a class="nav-link scrollto" href="#Model"><i class="fa fa-file-code-o mr10"></i>Model</a>
                                    <a class="nav-link scrollto" href="#Security"><i class="fa fa-file-code-o mr10"></i>Security</a>
                                    <a class="nav-link scrollto" href="#Session"><i class="fa fa-file-code-o mr10"></i>Session</a>
                                    <a class="nav-link scrollto" href="#TokenJWT"><i class="fa fa-file-code-o mr10"></i>TokenJWT</a>
                                    <a class="nav-link scrollto" href="#Utils"><i class="fa fa-file-code-o mr10"></i>Utils</a>
                                </nav><!--//nav-->
                                <a class="nav-link scrollto" href="#language"><i class="fa fa-archive mr10"></i>language</a>
                                <nav class="doc-sub-menu nav flex-column ml25">
                                    <a class="nav-link scrollto" href="#fr"><i class="fa fa-file-code-o mr10"></i>fr</a>
                                </nav><!--//nav-->
                                <a class="nav-link scrollto" href="#models"><i class="fa fa-archive mr10"></i>models</a>
                                <nav class="doc-sub-menu nav flex-column ml25">
                                    <a class="nav-link scrollto" href="#models-admin"><i class="fa fa-archive mr10"></i>admin</a>
                                    <a class="nav-link scrollto" href="#HomeModel"><i class="fa fa-file-code-o mr10"></i>HomeModel</a>
                                </nav><!--//nav-->
                                <a class="nav-link scrollto" href="#views"><i class="fa fa-archive mr10"></i>views</a>
                                <nav class="doc-sub-menu nav flex-column ml25">
                                    <a class="nav-link scrollto" href="#views-admin"><i class="fa fa-archive mr10"></i>admin</a>
                                    <a class="nav-link scrollto" href="#home"><i class="fa fa-archive mr10"></i>home</a>
                                    <a class="nav-link scrollto" href="#template"><i class="fa fa-archive mr10"></i>template</a>
                                </nav><!--//nav-->
                                <a class="nav-link scrollto" href="#webservice"><i class="fa fa-archive mr10"></i>webservice</a>
                                <nav class="doc-sub-menu nav flex-column ml25">
                                    <a class="nav-link scrollto" href="#api"><i class="fa fa-file-code-o mr10"></i>api</a>
                                </nav><!--//nav-->
                            </nav><!--//nav-->
                            <a class="nav-link scrollto" href="#assets"><i class="fa fa-archive mr10"></i>assets</a>
                            <nav class="doc-sub-menu nav flex-column ml25">
                                <a class="nav-link scrollto" href="#_main_"><i class="fa fa-archive mr10"></i>_main_</a>
                                <a class="nav-link scrollto" href="#css"><i class="fa fa-archive mr10"></i>css</a>
                                <a class="nav-link scrollto" href="#medias"><i class="fa fa-archive mr10"></i>medias</a>
                                <a class="nav-link scrollto" href="#js"><i class="fa fa-archive mr10"></i>js</a>
                                <a class="nav-link scrollto" href="#plugins"><i class="fa fa-archive mr10"></i>plugins</a>
                            </nav><!--//nav-->
                            <a class="nav-link scrollto" href="#config"><i class="fa fa-archive mr10"></i>config</a>
                            <nav class="doc-sub-menu nav flex-column ml25">
                                <a class="nav-link scrollto" href="#config-htaccess"><i class="fa fa-file-code-o mr10"></i>.htaccess</a>
                                <a class="nav-link scrollto" href="#app-config-ini"><i class="fa fa-file-code-o mr10"></i>app.config.ini</a>
                                <a class="nav-link scrollto" href="#db-config-ini"><i class="fa fa-file-code-o mr10"></i>db.config.ini</a>
                                <a class="nav-link scrollto" href="#DB-sql"><i class="fa fa-file-code-o mr10"></i>DB.mysql.sql</a>
                                <a class="nav-link scrollto" href="#DB-sqlite"><i class="fa fa-file-code-o mr10"></i>DB.sqlite.sql</a>
                            </nav><!--//nav-->
                            <a class="nav-link scrollto" href="#public"><i class="fa fa-archive mr10"></i>public</a>
                            <a class="nav-link scrollto" href="#vendor"><i class="fa fa-archive mr10"></i>vendor</a>
                            <a class="nav-link scrollto" href="#htaccess"><i class="fa fa-file-code-o mr10"></i>.htaccess</a>
                            <a class="nav-link scrollto" href="#composer.json"><i class="fa fa-file-code-o mr10"></i>composer.json</a>
                            <a class="nav-link scrollto" href="#composer.lock"><i class="fa fa-file-code-o mr10"></i>composer.lock</a>
                            <a class="nav-link scrollto" href="#composer.phar"><i class="fa fa-file-code-o mr10"></i>composer.phar</a>
                            <a class="nav-link scrollto" href="#index"><i class="fa fa-file-code-o mr10"></i>index</a>
                            <a class="nav-link scrollto" href="#readme"><i class="fa fa-file-code-o mr10"></i>README</a>
                        </nav><!--//nav-->
                    </nav><!--//doc-menu-->
                </div>
            </div><!--//doc-sidebar-->
        </div><!--//doc-body-->
    </div><!--//container-->
</div><!--//doc-wrapper-->