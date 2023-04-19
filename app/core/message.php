<script>
    $(document).ready(function () {
        if(parseInt("<?= count($this->_message['MSG_ALERT']) ?>") > 0) {
            swal({
                title: "<?= $this->_message['MSG_ALERT']['titre'] ?>",
                text : "<?= $this->_message['MSG_ALERT']['alert'] ?>",
                icon : "<?= $this->_message['MSG_ALERT']['type'] ?>",
            });
            $.get(racine+"error/unsetMessage/<?= base64_encode('ALERT') ?>", function(data, status){console.log(data);});
        }
        if("<?= strtoupper(ENV) ?>" === "DEV" && parseInt("<?= count($this->_message['MSG_ERROR']) ?>") > 0 && "<?= $this->_message['MSG_ERROR']['type'] ?>" === "sql"){
            let message = "<div style='z-index: 9999; position: fixed; left: 0; right: 0; bottom: -19px; width: 100%;'><div class='alert alert-warning alert-dismissable' role='alert'><?= $this->_message['MSG_ERROR']['alert'] ?></div></div>";
            $('body').append(message);
            $.get(racine+"error/unsetMessage/<?= base64_encode('ERROR') ?>", function(data, status){});
        }
        if ('<?= $this->data["modal-controller"] ?>' !== '' && '<?= $this->data["modal-view"] ?>' !== '') {
            openModal('<?= $this->data["modal-controller"] ?>', '<?= $this->data["modal-view"] ?>', '<?= implode("/", $this->data["modal-param"]) ?>');
        }
    });
</script>

<div>
    <style>
        .my-alert-warning {
            background: #fefdf9;
            color: #ffc53b;
            border: 1px solid #ffc53b;
        }
        .myadmin-alert .closed:hover {
            color: #ffc53b;
        }

        .myadmin-alert .closed {
            color: rgb(255, 197, 59);
            font-size: 20px;
            font-weight: 500;
            padding: 4px;
            position: absolute;
            right: 3px;
            text-decoration: none;
            top: 0;
        }
    </style>
</div>
<div id="modal-container"></div>