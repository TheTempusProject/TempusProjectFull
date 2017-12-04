<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{SITENAME}</title>
        <style type="text/css">
            body {
                margin: 0;
                padding: 0;
                min-width: 100%!important;
            }
            .content {
                width: 100%; 
                max-width: 600px;
            }
            .header {
                padding: 40px 30px 20px 30px;
            }
            .subhead {
                font-size: 15px; 
                color: rgb(255, 255, 255); 
                font-family: sans-serif; 
                letter-spacing: 10px;
            }
            .h1 {
                font-size: 33px; 
                line-height: 38px; 
                font-weight: bold;
            }
            .h1, .h2, .bodycopy {
                color: rgb(21, 54, 67); 
                font-family: sans-serif;
            }
            .innerpadding {
                padding: 30px 30px 30px 30px;
            }
            .borderbottom {
                border-bottom: 1px solid rgb(242, 238, 237);
            }
            .h2 {
                padding: 0 0 15px 0; 
                font-size: 24px; 
                line-height: 28px; 
                font-weight: bold;
            }
            .bodycopy {
                font-size: 16px; 
                line-height: 22px;
            }
            .button {
                text-align: center; 
                font-size: 18px; 
                font-family: sans-serif; 
                font-weight: bold; 
                padding: 0 30px 0 30px;
            }
            .button a {
                color: rgb(255, 255, 255); 
                text-decoration: none;
            }
            img {
                height: auto;
            }
            .footer {
                padding: 20px 30px 15px 30px;
            }
            .footercopy {
                font-family: sans-serif; 
                font-size: 14px; 
                color: rgb(255, 255, 255);
            }
            .footercopy a {
                color: rgb(255, 255, 255); 
                text-decoration: underline;
            }
            @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
                .buttonwrapper {
                    background-color: transparent!important;
                }
                .button a {
                    background-color: #e05443; 
                    padding: 15px 15px 13px!important; 
                    display: block!important;
                }
                .hide {
                    display: none!important;
                }
                .unsubscribe {
                    display: block; margin-top: 20px; 
                    padding: 10px 50px; 
                    background: #2f3942; 
                    border-radius: 5px; 
                    text-decoration: none!important; 
                    font-weight: bold;
                }
            }
            @media only screen and (min-device-width: 601px) {
                .content {
                    width: 600px !important;
                }
                .col425 {
                    width: 425px!important;
                }
                .col380 {
                    width: 380px!important;
                }
            }
        </style>
    </head>
    <body bgcolor="#f6f8f1" style="margin: 0;padding: 0;min-width: 100%!important;">
        <!--[if (gte mso 9)|(IE)]>
        <table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
        <tr>
        <td>
        <![endif]-->
        <table class="content" align="center" cellpadding="0" cellspacing="0" border="0" style="width: 100%;max-width: 600px;">
            <!-- Mail Header -->
            <tr>
                <td class="header" bgcolor="#b5e6ff" style="padding: 40px 30px 20px 30px;">
                    <table width="70" align="left" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td height="70" style="padding: 0 20px 20px 0;">
                                <img src="{LOGO}" width="70" height="70" border="0" alt="" style="height: auto;">
                            </td>
                        </tr>
                    </table>
                    <!--[if (gte mso 9)|(IE)]>
                    <table width="425" align="left" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                    <td>
                    <![endif]-->
                    <table class="col425" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 425px;">
                        <tr>
                            <td height="70">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="subhead" style="padding: 0 0 0 3px; font-size: 15px; color: rgb(255, 255, 255); font-family: sans-serif;letter-spacing: 5px;">
                                            {BASE}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="h1" style="padding: 5px 0 0 0; font-size: 33px; line-height: 38px; font-weight: bold; color: rgb(21, 54, 67);font-family: sans-serif;">
                                            {SITENAME}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <!--[if (gte mso 9)|(IE)]>
                    </td>
                    </tr>
                    </table>
                    <![endif]-->
                </td>
            </tr>
            <!-- Mail Body -->
            <tr>
                <td class="innerpadding borderbottom" bgcolor="#f9fdff" style="padding: 30px 30px 30px 30px; border-bottom: 1px solid rgb(242, 238, 237);">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="h2" style="color: rgb(21, 54, 67); font-family: sans-serif; padding: 0 0 15px 0;font-size: 24px; line-height: 28px;font-weight: bold;">
                                {MAIL_TITLE}
                            </td>
                        </tr>
                        <tr>
                            <td class="bodycopy" style="color: rgb(21, 54, 67); font-family: sans-serif; font-size: 16px; line-height: 22px;">
                                {MAIL_BODY}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!-- Mail Footer -->
            <tr>
                <td class="footer" bgcolor="#44525f" style="padding: 20px 30px 15px 30px;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td align="center" class="footercopy" style="font-family: sans-serif; font-size: 14px; color: rgb(255, 255, 255);">
                                &copy; {SITENAME}, Powered by TheTempusProject<br>
                                {UNSUB}
                            </td>
                        </tr>
                        <tr>
                            <td align="center" style="padding: 20px 0 0 0;">
                                <table border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        {MAIL_FOOT}
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!--[if (gte mso 9)|(IE)]>
        </td>
        </tr>
        </table>
        <![endif]-->
    </body>
</html>