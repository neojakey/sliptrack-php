function validate() {
    var ErrorFound = false

    if ($('#group-name').val() === '')  {
        alert("Please type the group name.");
        $('#group-name').focus();
        ErrorFound = true;
    }
    if (!ErrorFound) {
        $('#form-new-group').submit();
    }
}
