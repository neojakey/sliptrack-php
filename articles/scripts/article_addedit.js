$(function () {
    /* DROPDOWN MENUS */
    $('#source').kendoDropDownList();

    $('#article-url').blur(function () {
        let articleUrl = $(this).val();
        if (articleUrl !== '') {
            /* CHECK FOR DUPLICATE ARTICLE */
            $.ajax({
                url: $base + '/articles/duplicate_article_check.php?nurl=' + encodeURIComponent(articleUrl),
                method: 'GET',
                cache: false,
                async: false
            }).done(function (response) {
                response = removeUTF8BOM(response);
                if (response === 'EXISTS') {
                    alert('Article already exists.');
                    $('#article-url').val('');
                } else {
                    /* GET WEBSITE URL */
                    let domain = (new URL(articleUrl));
                    var host = domain.host;
                    var origin = domain.origin;

                    /* CHECK IF SOURCE ALREADY EXISTS */
                    sourceExistenceCheck(host, origin);

                    /* SCRAP TITLE AND IMAGES FROM PAGE */
                    getArticleTitle(articleUrl);
                }
            });
        }
    });

    $('.keyword-wrapper > span').click(function () {
        $(this).hasClass('selected') ? $(this).removeClass('selected') : $(this).addClass('selected');
        loopSelectedKeywords();
    });
});

function loopSelectedKeywords() {
    let selectedKeywords = [];
    $('.keyword-wrapper > span.selected').each(function () {
        selectedKeywords.push($(this).data('listid'));
    });
    $('#save-keywords').val(selectedKeywords);
}

function validate() {
    var hasError = false;

    hasError = validateText('article-title', 'Please enter the article title.', hasError);
    hasError = validateText('article-url', 'Please enter article url.', hasError);
    hasError = validateText('source', 'Please select the source of the article.', hasError);

    validateEnd('form-new-article', hasError);
}

function sourceExistenceCheck(nHost, nOrigin) {
    $.ajax({
        url: $base + '/articles/source_existence_check.php?h=' + encodeURIComponent(nHost) + '&o=' + encodeURIComponent(nOrigin),
        method: 'GET',
        cache: false,
        async: false
    }).done(function (response) {
        response = removeUTF8BOM(response);
        if (response !== '') {
            SelectSite(response);
        } else {
            var c = confirm('Do you wish to add this source to the system?');
            if (c) {
                CreateSite(nHost, nOrigin);
            }
        }
    });
}

function SelectSite(nId) {
    var dropdownlist = $('#source').data('kendoDropDownList');
    dropdownlist.value(nId);
    dropdownlist.trigger('change');
}

function CreateSite(nHost, nOrigin) {
    $.ajax({
        url: $base + '/articles/create_source.php?h=' + encodeURIComponent(nHost) + '&o=' + encodeURIComponent(nOrigin),
        method: 'GET',
        cache: false,
        async: false
    }).done(function (response) {
        response = removeUTF8BOM(response);
        if (response !== '') {
            AddSiteToDropmenu(response, nHost, nOrigin);
        }
    });
}

function AddSiteToDropmenu(nId, nHost, nOrigin) {
    var widget = $('#source').getKendoDropDownList();
    var dataSource = widget.dataSource;

    nOrigin = nOrigin.replace('https://', '');
    nOrigin = nOrigin.replace('http://', '');

    dataSource.add({
        text: nHost + ' - [ ' + nOrigin + ' ]',
        value: nId
    });

    dataSource.one('sync', function() {
        widget.select(dataSource.view().length - 1);
    });

    dataSource.sync();

    SelectSite(nId);
}

function getArticleTitle(url) {
    $.ajax({
        url: $base + '/articles/title_scrape.php?nurl=' + url,
        method: 'GET',
        cache: false,
        async: false
    }).done(function (data) {
        //var dataAry = data.split('|');
        $('#article-title').val(data);
        //$('#article-title').val(dataAry[0]);
        //$('#article-images').html('');
        //var images = dataAry[1];
        //var imageAry = images.split('~');
        //for (var i = 0; i < imageAry.length; i++) {
        //    $('#article-images').append(imageAry[i]);
        //}
    });
}