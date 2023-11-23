function ConfirmReceiptDelete(receiptId) {
    var agree = confirm('Are you sure you wish to delete this receipt?\n');
    if (agree) {
        document.location.href = '/receipts/delete/?id=' + receiptId;
    }
}