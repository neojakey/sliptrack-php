function ConfirmIssuerDelete(issuerId) {
    var agree = confirm('Are you sure you wish to delete this issuer?\n');
    if (agree) {
        document.location.href = '/issuers/delete/?id=' + issuerId;
    }
}