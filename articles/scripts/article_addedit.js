$(function () {
    /* DROPDOWN MENUS */
    $('#source').kendoDropDownList();
});

function validate() {
    var hasError = false;

    hasError = validateText('article-title', 'Please enter the article title.', hasError);
    hasError = validateText('article-url', 'Please enter article url.', hasError);
    hasError = validateText('source', 'Please select the source of the article.', hasError);

    validateEnd('form-new-article', hasError);
}