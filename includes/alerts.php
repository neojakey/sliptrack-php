<?php if ($_SESSION["hasAlert"]) { ?>
<script type="text/javascript">
    $(function () {
        ShowAlert(<?=strtolower($_SESSION["hasAlert"])?>, '<?=$_SESSION["alertType"]?>', '<?=str_replace("'", "\'", $_SESSION["alertMessage"])?>');
    });
</script>
<?php
}
$_SESSION["hasAlert"] = false;
$_SESSION["alertType"] = "";
$_SESSION["alertMessage"] = "";
?>