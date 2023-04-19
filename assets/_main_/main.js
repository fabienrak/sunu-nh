/**
 * Created by Seeynii Faay on 28/09/2017.
 */

let racine = $('body').data('racine');
let webroot = $('body').data('webroot');
let assets = $('body').data('assets');
let staticGlobalModal = $('body').data('modal-static');

if(racine === undefined) alert("Définir l'attribut data-racine=\"<?= RACINE; ?>\" dans la balise body");
if(webroot === undefined) alert("Définir l'attribut data-webroot=\"<?= WEBROOT; ?>\" dans la balise body");
if(assets === undefined) alert("Définir l'attribut data-assets=\"<?= ASSETS; ?>\" dans la balise body");
if(staticGlobalModal === undefined) staticGlobalModal = false;

let confirme = () => {
    $('.confirm').on('click', function (e) {
        let type_link = "url";
        let link = $(this).attr("href");

        if (link === undefined) {
            link = $(this).data("data-href");
        }
        if (link !== undefined) {
            e.preventDefault();
            $.getJSON(racine+"language/getLang", (lang) => {
                console.log(lang);
                $.confirm({
                    title: lang.confirmTitre,
                    escapeKey: true, // close the modal when escape is pressed.
                    content: lang.confirmMessage,
                    backgroundDismiss: false, // for escapeKey to work, backgroundDismiss should be enabled.
                    icon: 'fa fa-question',
                    theme: 'material',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'red',
                    buttons: {
                        'non': {
                            text: 'Non',
                            btnClass: 'btn-red',
                            keys: [
                                'ctrl',
                                'shift'],
                            action: function () {
                            }
                        },
                        'oui': {
                            text: 'Oui',
                            btnClass: 'btn-green',
                            keys: ['enter'],
                            action: function () {

                                if (type_link === "url") window.location = link;
                                else $("#" + link).submit();

                                // e.isDefaultPrevented = function(){ return false; }
                                // // retrigger with the exactly same event data
                                // $(this).trigger(e);
                            }
                        }
                    },
                });
            });
        }
    })
};

let lang_tab = null;

let $tables = [];

let processing = $(".processing");

let datatable = $(".dataTable");

switch ($('html').attr('lang')) {
    case 'us' :
        lang_tab = {
            "sEmptyTable": "No data available in table",
            "sInfo": "Showing _START_ to _END_ of _TOTAL_ entrie(s)",
            "sInfoEmpty": "Showing 0 to 0 of 0 entrie",
            "sInfoFiltered": "(filtered from _MAX_ total entries)",
            "sInfoPostFix": "",
            "sInfoThousands": ",",
            "sLengthMenu": "Show _MENU_ entries",
            "sLoadingRecords": "Loading...",
            "sProcessing": "Processing...",
            "sSearch": "Search:",
            "sZeroRecords": "No matching records found",
            "oPaginate": {
                "sFirst": "First",
                "sLast": "Last",
                "sNext": ">",
                "sPrevious": "<"
            },
            "oAria": {
                "sSortAscending": ": activate to sort column ascending",
                "sSortDescending": ": activate to sort column descending"
            }
        };
        break;
    default :
        lang_tab = {
            "sProcessing": "Traitement en cours...",
            "sSearch": "Rechercher&nbsp;:",
            "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
            "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ment(s)",
            "sInfoEmpty": "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
            "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            "sInfoPostFix": "",
            "sLoadingRecords": "Chargement en cours...",
            "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
            "sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
            "oPaginate": {
                "sFirst": "Premier",
                "sPrevious": "<",
                "sNext": ">",
                "sLast": "Dernier"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
            }
        };
        break;
}

function runProcesing(process, $url, $param, $id) {
    let temp = $(process).DataTable({
        "language": lang_tab,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: $url, // json datasource
            type: "post",  // method  , by default get
            data: {id : $id},  // method  , by default get
            error: () => {  // error handling
                $(".employee-grid-error").html("");
                $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                $("#employee-grid_processing").css("table", "none");
            }
        }
    });
    // let $add = {
    //     url : $url,
    //     tab : temp
    // };
    // $tables.push($add);
}

let processingModal = () => {
    let processModal = $(".processingModal");
    for (let i = 0; i < processModal.length; i++) {
        let $url = $(processModal[i]).data("url");
        runProcesing(processModal[i], $url, undefined);
    }
};

let number_format = (number,decimals,dec_point,thousands_sep) => {
    number  = number*1;//makes sure `number` is numeric value
    let str = number.toFixed(decimals?decimals:0).toString().split('.');
    let parts = [];
    for (let i=str[0].length; i>0; i-=3) parts.unshift(str[0].substring(Math.max(0,i-3),i));
    str[0] = parts.join(thousands_sep?thousands_sep:' ');
    return str.join(dec_point?dec_point:'.');
};

let openModal = (controller, view, param, staticModal) => {
    staticModal = staticModal == true || staticModal == false ? staticModal : staticGlobalModal;
    let $url = (param === undefined) ? webroot + controller : webroot + controller + '/' + param;
    if (controller !== undefined) {
        $.post (
            $url, {view : view},
            function(data){
                if (parseInt(data) !== 0) {
                    let modal = '<div class="modal fade bs-modal-lg" id="modal" '+(staticModal == true ? 'data-backdrop="static"' : "")+' data-keyboard="false" data-dismiss="modal" tabindex="-1" role="dialog" aria-hidden="true"> <div class="modal-dialog modal-lg"> <div class="modal-content" id="content-modal"> <div class="modal-header"> <button type="button" class="close" aria-hidden="true" data-dismiss="modal">×</button> <h4 class="modal-title">En cours de chargement</h4> </div> <div class="modal-body"> <div align="center"> <img src="'+assets+'_main_/loading.gif" width="25%"/> </div> </div> <div class="modal-footer"> <button class="btn btn-default" type="button" data-dismiss="modal"> <i class="fa fa-times"></i> Annuler </button> </div> </div> </div> </div>';
                    $('#modal-container').html(modal);
                    $('#content-modal').html(data);
                    $('#modal').modal("show");
                } else alert("La vue n'a pas été définie !");
            }
        );
    } else alert("Le controller n'a pas été défini !");
}

$(document).ready(() => {

    //runProcesing();
    $.getJSON(racine+"language/getLang", (lang, status) => {

        $('body').append('<style> .action {margin-left: 15px;} </style>');

        $('._lang_').on('click', () => {
            $.post(
                racine + "language/index",
                {
                    arg: $(this).data("lang")
                },
                function (data) {
                    //console.log(data);
                    location.reload();
                }
            );
        });
        var toltip = $('a[data-toggle="tooltip"]');
        if(toltip.length > 0) toltip.tooltip();

        if (processing.length > 0) {
            for (let i = 0; i < processing.length; i++) {
                let $url = $(processing[i]).data("url");
                let $id = $(processing[i]).data("id");
                runProcesing(processing[i], $url, undefined, $id);
            }
        }

        setTimeout(() => {
            $('#MSG_ALERT').slideUp(1500);
            $('#MSG_ERROR').slideUp(1500);
        }, 3000);

        if (datatable.length > 0) {
            for (i = 0; i < datatable.length; i++) {
                $(datatable[i]).DataTable({
                    "language": lang_tab
                });
            }
        }

        $('.open-modal').on('click', function () {
            let $this = $(this);
            let controller = $this.data('modal-controller');
            let view = $this.data('modal-view');
            let param = $(this).data('modal-param');
            let staticModal = $(this).data('modal-static');
            staticModal = staticModal == true || staticModal == false ? staticModal : staticGlobalModal;
            let $url = (param === undefined) ? webroot + controller : webroot + controller + '/' + param;
            if (controller !== undefined) {
                $.post (
                    $url, {view : view},
                    function(data){
                        if (parseInt(data) !== 0) {
                            let modal = '<div class="modal fade bs-modal-lg" id="modal" '+(staticModal == true ? 'data-backdrop="static"' : "")+' data-keyboard="false" data-dismiss="modal" tabindex="-1" role="dialog" aria-hidden="true"> <div class="modal-dialog modal-lg"> <div class="modal-content" id="content-modal"> <div class="modal-header"> <button type="button" class="close" aria-hidden="true" data-dismiss="modal">×</button> <h4 class="modal-title">En cours de chargement</h4> </div> <div class="modal-body"> <div align="center"> <img src="'+assets+'_main_/loading.gif" width="25%"/> </div> </div> <div class="modal-footer"> <button class="btn btn-default" type="button" data-dismiss="modal"> <i class="fa fa-times"></i> Annuler </button> </div> </div> </div> </div>';
                            $('#modal-container').html(modal);
                            $('#content-modal').html(data);
                            $('#modal').modal("show");
                        } else alert("La vue n'a pas été définie !")
                    }
                );
                // $.get($url, function (data) {
                //     if (parseInt(data) !== 0) {
                //         let modal = '<div class="modal fade bs-modal-lg" id="modal" '+(staticModal == true ? 'data-backdrop="static"' : "")+' data-keyboard="false" data-dismiss="modal" tabindex="-1" role="dialog" aria-hidden="true"> <div class="modal-dialog modal-lg"> <div class="modal-content" id="content-modal"> <div class="modal-header"> <button type="button" class="close" aria-hidden="true" data-dismiss="modal">×</button> <h4 class="modal-title">En cours de chargement</h4> </div> <div class="modal-body"> <div align="center"> <img src="'+assets+'_main_/loading.gif" width="25%"/> </div> </div> <div class="modal-footer"> <button class="btn btn-default" type="button" data-dismiss="modal"> <i class="fa fa-times"></i> Annuler </button> </div> </div> </div> </div>';
                //         $('#modal-container').html(modal);
                //         $('#content-modal').html(data);
                //         $('#modal').modal("show");
                //     } else alert("La vue n'a pas été définie !")
                // });
            } else alert("Le controller n'a pas été défini !")
        });

        $('.confirm').on('click', function (e) {

            let type_link = "url";
            let link = $(this).attr("href");

            if (link === undefined) {
                link = $(this).data("form");
                type_link = "form"
            }
            if (link !== undefined) {
                e.preventDefault();
                $.confirm({
                    title: lang.confirmTitre,
                    escapeKey: true, // close the modal when escape is pressed.
                    content: lang.confirmMessage,
                    backgroundDismiss: false, // for escapeKey to work, backgroundDismiss should be enabled.
                    icon: 'fa fa-question',
                    theme: 'material',
                    closeIcon: true,
                    animation: 'scale',
                    type: 'red',
                    buttons: {
                        'non': {
                            text: lang.confirmBtnKo,
                            btnClass: 'btn-red',
                            keys: ['ctrl', 'shift'],
                            action: function () {
                            }
                        },
                        'oui': {
                            text: lang.confirmBtnOk,
                            btnClass: 'btn-green',
                            keys: ['enter'],
                            action: function () {

                                if (type_link === "url") window.location = link;
                                else $("#" + link).submit();

                                // e.isDefaultPrevented = function(){ return false; }
                                // // retrigger with the exactly same event data
                                // $(this).trigger(e);
                            }
                        }
                    },
                });
            }
        });
    });

    let inputTel = $('input[type="tel"]');

    if(inputTel.length > 0){
        inputTel
            .intlTelInput({
                autoPlaceholder: true,
                preferredCountries: [ 'sn', 'gm', 'gb','ci'],
                initialDialCode: true,
                nationalMode: false
            });
    }
});