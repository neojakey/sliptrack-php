<?php if ($_SESSION["hasAlert"]) { ?>
<script type="text/javascript">
    $(function () {
        ShowAlert(<?php echo strtolower($_SESSION["hasAlert"])?>, '<?php echo $_SESSION["alertType"]?>', '<?php echo str_replace($_SESSION["alertMessage"], "'", "\'")?>');
    });
</script>
<?php
}
$_SESSION["hasAlert"] = false;
$_SESSION["alertType"] = "";
$_SESSION["alertMessage"] = "";
?>