<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Invoice</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap"
        rel="stylesheet">

    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap');

        img {
            max-width: 600px;
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
        }

        a {
            text-decoration: none;
            border: 0;
            outline: none;
            color: #bbbbbb;
        }

        a img {
            border: none;
        }

        /* General styling */
        td,
        h1,
        h2,
        h3 {
            font-family: 'Roboto', sans-serif;
            font-weight: 400;
        }

        body {
            -webkit-font-smoothing: antialiased;
            -webkit-text-size-adjust: none;
            width: 100%;
            height: 100%;
            color: #37302d;
            background: #ffffff;
            font-size: 12px;
        }

        table {
            border-collapse: collapse !important;
        }

        table td,
        table th {
            padding-bottom: 5px;
            padding-top: 5px;
            text-align: left;
        }

        .force-full-width {
            width: 100% !important;
        }

        .force-width-90 {
            width: 90% !important;
        }

    </style>
    <style type="text/css" media="only screen and (max-width: 480px)">
        /* Mobile styles */
        @media only screen and (max-width: 480px) {
            table[class="w320"] {
                width: 320px !important;
            }

            td[class="mobile-block"] {
                width: 100% !important;
                display: block !important;
            }
        }

    </style>
</head>

<body class="body"
    style="padding:0; margin:0; display:block; background:#ffffff; -webkit-text-size-adjust:none" bgcolor="#ffffff">
    <table align="center" cellpadding="0" cellspacing="0" class="force-full-width" height="100%">
        <tr>
            <td align="center" valign="top" bgcolor="#ffffff" width="100%">
                <center>
                    <table style="margin: 0 auto;" cellpadding="0" cellspacing="0" width="600" class="w320">
                        <tr>
                            <td align="center" valign="top" bgcolor="#52599a"
                                style="padding: 50px; border: 1px solid #52599a;">
                                <center>
                                    <table style="margin-bottom: 20px;">
                                        <tr>
                                            <td>
                                                <img width="200" height="" src="{{ url('images/logo2.png') }}" alt="">
                                            </td>
                                        </tr>
                                    </table>
                                </center>
                                <center>
                                    <table style="margin: 0 auto;" cellpadding="0" cellspacing="0" width="400"
                                        class="w320">
                                        <tr>
                                            <td align="center" valign="top" bgcolor="#ffffff"
                                                style="padding: 50px; border: 1px solid #E5E5E5;">
                                                <?php echo isset($content) && $content != '' ? $content : ''; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </center>

                                <center>
                                    <table>
                                        <tr>
                                            <td>
                                                <p
                                                    style="font-size: 18px; font-weight: 700; text-align: center;color: #ffffff; margin-top: 20px; text-transform: uppercase;">
                                                    &#169; 2022 nurseify. all rights reserved.</p>
                                            </td>
                                        </tr>
                                    </table>
                                </center>
                            </td>
                        </tr>
                    </table>
                </center>
            </td>
        </tr>
    </table>
</body>

</html>
