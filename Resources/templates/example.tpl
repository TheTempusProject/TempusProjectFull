<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{TITLE}</title>
        <meta name="description" content="{PAGE_DESCRIPTION}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="The Tempus Project">
        {ROBOT}
        <link rel="alternate" hreflang="en-us" href="alternateURL">
        <link rel="icon" href="{BASE}Images/favicon.ico">
        <!-- Required CSS -->
        <link rel="stylesheet" href="{BASE}vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="{BASE}vendor/fortawesome/font-awesome/css/font-awesome.min.css">
        <!-- Custom styles for this template -->
        <link rel="stylesheet" href="{BASE}Templates/default/default.css">
        <!-- Required JS -->
        <script language="JavaScript" type="text/javascript" src="{BASE}vendor/tinymce/tinymce/tinymce.min.js"></script>
        <script language="JavaScript" type="text/javascript" src="{BASE}JS/default.js"></script>
        <script language="JavaScript" type="text/javascript" src='http://www.google.com/recaptcha/api.js'></script>
    </head>
    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!--Brand and toggle should get grouped for better mobile display
                but I had to account for additional menus-->
            <div class="navbar-header">
                <a class="navbar-brand" href="{BASE}">{SITENAME}</a>
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse" style="">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="container">
                <div class="collapse navbar-collapse navbar-ex1-collapse">
                    {MAINNAV}
                    <div class="navbar-right">
                        <ul class="nav navbar-nav" style="">
                            {RECENT_MESSAGES}
                            {STATUS}
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <div class="container-fluid">
        <div class="foot-pad">
            {UI}
            <div class="row">
                <div class="container">
                    {ERROR}
                    {NOTICE}
                    {SUCCESS}
                    {INFO}
                </div>
            </div>
            {/UI}
            <div class="row">
                <div class="container">
                    {CONTENT}
                </div>
            </div>
        </div>
            <div class="row">
                <footer>
                    {FOOT}
                    {COPY}
                </footer>
            </div>
        </div>
        <!-- Bootstrap core JavaScript and jquery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="{BASE}vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
    </body>
</html>