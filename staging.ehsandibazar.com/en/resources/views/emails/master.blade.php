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
                    <td style="border-top-right-radius: 5px;border-top-left-radius: 5px;vertical-align:middle;background-color:#d2cfb5" valign="top" width="700" align="left">
                        <table style="height:28px" width="670" cellspacing="0" cellpadding="10" border="0" align="center">
                            <tbody>
                            <tr>
                                <td valign="middle" width="290" align="right">
                                    <a href="ehsandibazar.com" style="text-decoration:none;display:block;font-family:Tahoma,sans-serif;font-weight:bold;font-size:24px;color:#979797" target="_blank"><img alt="" src="https://ehsandibazar.com/site_theme/images/logo.png" style="float:right" class="CToWUd" width="112" height="44" border="0"></a>
                                    {{--<p style="text-decoration:none;font-family:Tahoma,sans-serif;font-size:12px;line-height:18px;color:#ffffff">&nbsp;</p>--}}
                                </td>
                                <td valign="middle" width="200" align="center">
                                   {{-- <a href="{{ url('/') }}" style="text-decoration:none;display:block;font-family:Tahoma,sans-serif;font-weight:bold;font-size:24px;color:#979797" target="_blank"><img alt="" src="{{url('site_theme/img/logo/logo.png')}}" style="float:right" class="CToWUd" width="112" height="44" border="0"></a>--}}
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
                                <td width="670" bgcolor="#ffffff" align="left">
                                    <p style="text-align:right;font-size:14px;line-height:22px;color:#191919;font-family:Tahoma,sans-serif">
                                        <strong style="font-family: Tahoma"> @yield('email-name') Hello</strong>
                                    </p>
                                    <p style="direction:rtl;text-align:right;font-size:14px;line-height:22px;color:#191919;font-family:Tahoma,sans-serif">Thanks for that {{env('APP_NAME')}} You are in touch</p>
                                    @yield('email-content')
                                    <p style="direction:rtl;text-align:right;font-size:14px;line-height:22px;color:#191919;font-family:Tahoma,sans-serif;margin-top: 20px"> Thank you {{env('APP_NAME')}} You have selected</p>
                                    <p style="text-align:right;font-size:14px;line-height:22px;color:#191919;font-family:Tahoma,sans-serif;margin-top: 20px">This email will be sent automatically, please do not reply to this email</p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="border-bottom-right-radius: 5px;border-bottom-left-radius: 5px;vertical-align:top;background-color: #343d5e" valign="top" width="700" bgcolor="#d4d4d4" align="center">
                        <table style="border-collapse:collapse;font-family:Tahoma" width="670" cellspacing="0"
                               cellpadding="10" border="0" align="center">
                            <tbody>
                            <tr>
                                <td valign="top" width="670" align="left">
                                    <p style="padding:0;margin:0;font-family:Tahoma;font-size:12px;line-height:18px;color:#ffffff;text-align:center">Customer relationship unit {{env('APP_NAME')}}</p>
                                    <p style="padding:0;margin:0;font-family:Tahoma;font-size:12px;line-height:18px;color:#c3c3c3;text-align:center"><a href="mailto:info@kaci.ir" style="color:#1b8dbc" target="_blank">info@ehsandibazar.com</a> : mail </p>
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
