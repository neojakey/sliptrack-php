function ConfirmGroupDelete(groupid) {
    var agree = confirm('Are you sure you wish to delete this group?\n');
    if (agree) {
        document.location.href = $base + '/admin/groups/delete.php?id=' + groupid;
    }
}