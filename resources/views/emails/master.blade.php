<!doctype html>
<html lang="fa-IR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>email</title>
</head>
<body>
<table style="border-collapse:collapse;border:none;background-color:#f6f7f6;font-family:Tahoma" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
    <tbody>
    <tr>
        <td valign="top" width="100%" align="center">
            <br>
            <table style="border-collapse:collapse;border:none;background-color:#ffffff;font-family:Tahoma" width="700" cellspacing="0" cellpadding="0" border="0" align="center">
                <tbody>
                <tr>
                    <td style="border-top-right-radius: 5px;border-top-left-radius: 5px;vertical-align:middle;background-color:#d9bb75" valign="top" width="700" align="center">
                        <table style="height:60px" width="670" cellspacing="0" cellpadding="10" border="0" align="center">
                            <tbody>
                            <tr>
                                <td valign="middle" align="center">

                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:top" valign="top" width="700" bgcolor="#ffffff" align="center">
                        <table width="670" cellspacing="0" cellpadding="10" border="0" bgcolor="#ffffff" align="center">
                            <tbody>
                            <tr>
                                <td width="670" bgcolor="#ffffff" align="right">
                                    <p style="text-align:right;font-size:14px;line-height:22px;color:#191919;font-family:Tahoma,sans-serif">
                                        <strong>سلام،</strong>
                                    </p>
                                    @yield('email-content')
                                    <p style="direction:rtl;text-align:right;font-size:14px;line-height:22px;color:#191919;font-family:Tahoma,sans-serif;margin-top:20px;">
                                        با احترام،<br>
                                        <strong>احسان دیبازر</strong><br>
                                        <span style="color:#888;font-size:12px;">مربی هنرهای رزمی و دفاع شخصی</span>
                                    </p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="border-bottom-right-radius: 5px;border-bottom-left-radius: 5px;vertical-align:top;background-color:#1a1a1a" valign="top" width="700" align="center">
                        <table style="border-collapse:collapse;font-family:Tahoma" width="670" cellspacing="0" cellpadding="10" border="0" align="center">
                            <tbody>
                            <tr>
                                <td valign="top" width="670" align="center">
                                    <p style="padding:0;margin:0;font-family:Tahoma;font-size:12px;line-height:18px;color:#d9bb75;text-align:center;">ehsandibazar.com</p>
                                    <p style="padding:0;margin:4px 0 0;font-family:Tahoma;font-size:12px;line-height:18px;color:#888;text-align:center;">
                                        <a href="mailto:contact@ehsandibazar.com" style="color:#d9bb75;" target="_blank">contact@ehsandibazar.com</a>
                                    </p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
