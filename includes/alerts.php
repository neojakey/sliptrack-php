<?php if ($_SESSION["alertActive"]) { ?>
<script type="text/javascript">
    $(function () {
        ShowAlert(<?=strtolower($_SESSION["hasAlert"])?>, '<?=$_SESSION["alertType"]?>', '<?=str_replace("'", "\'", $_SESSION["alertMessage"])?>');
    });
</script>
<?php
}
SystemAlert::ClearAlert();
?>