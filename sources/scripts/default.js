function ConfirmSourceDelete(sourceId) {
    var agree = confirm('Are you sure you wish to delete this source?\n');
    if (agree) {
        document.location.href = $base + '/sources/delete.php?id=' + sourceId;
    }
}