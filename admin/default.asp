<!-- #include virtual="/includes/adovbs.inc" -->
<!-- #include virtual="/includes/functions.asp" -->
<!-- #include virtual="/includes/common.asp" -->
<!-- #include virtual="/includes/SqlVerify.asp" -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title><%=SITE_NAME%> - User Area</title>
    <!--#include virtual="/includes/stylesheets.asp" -->
</head>

<body>

<div id="page-wrapper">
    <div class="menu">
        <!--#include virtual="/includes/menu_admin.asp" -->
    </div>
    <div class="main">
        <header>
            <div></div>
            <div class="notification-wrapper">
                <a href="javascript:void(0);"><i class="fa fa-bell" aria-hidden="true"></i></a>
                <a href="javascript:void(0);"><i class="fa fa-envelope" aria-hidden="true"></i></a>
            </div>
            <div class="user-wrapper" id="user-menu-link">
                <span id="desktop-user-menu-bars"><i class="fa fa-user-circle-o" aria-hidden="true"></i></span>
                <span id="desktop-user-menu-name"><%=Session("userFullName")%></span>
                <span><i class="fa fa-caret-down" aria-hidden="true"></i></span>
            </div>
        </header>
        <section>
                <h1 class="page-title">Administration Area</h1>
                <div class="breadcrumb">
                    <a href="/">Home</a><%=SPACER%>Administration
                </div>
                <div id="alert-wrapper" style="display:none">
                    <div id="alert">
                        <div id="alert-icon"></div>
                    </div>
                </div>
        </section>
    </div>
</div>
<!-- #include virtual="/includes/footer.asp" -->
<!-- #include virtual="/includes/javascripts.asp" -->
<!-- #include virtual="/includes/kendo_includes.asp" -->
<!-- #include virtual="/includes/alerts.asp" -->
<script type="text/javascript">
    $(function () {
        $('#search-category').kendoDropDownList();

        $('.item.profile').click(function () {
            document.location.href = '/profile/';
        });

        $('.item.logout').click(function () {
            document.location.href = 'logout.asp';
        });
    });

    function validate() {
        var ErrorFound = 0;
        if ($('#search-box').val() === '') {
            alert('Please enter a user search query');
            $('#search-box').focus();
            ErrorFound++;
        }
        if (ErrorFound === 0) {
            var search = encodeURI($('#search-box').val());
            var searchCategory = encodeURI($('#search-category').data('kendoDropDownList').value());
            var url = location.protocol + '//' + location.host + '/admin/users/';
            var urlProperties = location.search;

            if (urlProperties === '') {
                document.location.href = url + '?search=' + search + '&cat=' + searchCategory;
            } else {
                urlProperties = urlProperties.replace('?', '&');
                document.location.href = url + '?search=' + search + '&cat=' + searchCategory + urlProperties;
            }
        }
    }
</script>
</body>

</html>
<!--#include virtual="/includes/closeconnection.asp" -->
