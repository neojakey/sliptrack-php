function ConfirmArticleDelete(articleId) {
    var agree = confirm('Are you sure you wish to delete this article?\n');
    if (agree) {
        document.location.href = $base + '/articles/delete.php?id=' + articleId;
    }
}