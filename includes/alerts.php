<?php echo $_SESSION["alertActive"] ?>
<?php if ($_SESSION["alertActive"]) { ?>
<script type="text/javascript">
    $(function () {
        ShowAlert(<?=strtolower($_SESSION["alertActive"])?>, '<?=$_SESSION["alertType"]?>', '<?=str_replace("'", "\'", $_SESSION["alertMessage"])?>');
    });
</script>
<?php
}
SystemAlert::ClearAlert();
?>