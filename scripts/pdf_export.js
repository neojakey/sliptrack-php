function createPdf(options) {
    kendo.ui.progress($('body'), true);
    kendo.drawing.drawDOM(options.element, {
        multiPage: true,
        paperSize: 'letter',
        landscape: true,
        scale: options.scale,
        margin: { left: '1cm', top: '1cm', right: '1cm', bottom: '1cm' }
    }).then(function (group) {
        return kendo.drawing.exportPDF(group, {
            title: options.title,
            creator: 'CalcuTrack.com'
        });
    }).done(function (data) {
        kendo.saveAs({
            dataURI: data,
            fileName: options.fileName
        });
        kendo.ui.progress($('body'), false);
    });
}
