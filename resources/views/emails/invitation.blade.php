<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation EasyColoc</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fbf7f2;
        }
        .container {
            background: white;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 32px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #0f766e;
        }
        .content {
            margin-bottom: 32px;
        }
        .button {
            display: inline-block;
            background: #0f766e;
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            margin: 16px 0;
        }
        .button:hover {
            background: #0b5f59;
        }
        .footer {
            text-align: center;
            color: #64748b;
            font-size: 14px;
            margin-top: 32px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">EasyColoc</div>
        </div>

        <div class="content">
            <h2>Vous ?tes invit?(e) ? rejoindre une colocation !</h2>

            <p>Bonjour,</p>

            <p><strong>{{ $inviter->name }}</strong> vous invite ? rejoindre la colocation <strong>{{ $colocation->name }}</strong> sur EasyColoc.</p>

            @if($colocation->description)
                <p><em>{{ $colocation->description }}</em></p>
            @endif

            <p>EasyColoc vous aide ? suivre les d?penses partag?es, calculer qui doit quoi ? qui et g?rer le budget de la colocation.</p>

            <p style="text-align: center;">
                <a href="{{ $inviteUrl }}" class="button">Accepter l'invitation</a>
            </p>

            <p><small>Cette invitation expirera dans 7 jours.</small></p>
        </div>

        <div class="footer">
            <p>Si vous n'avez pas encore de compte EasyColoc, vous pourrez en cr?er un apr?s avoir cliqu? sur le lien ci-dessus.</p>
            <p>? {{ date('Y') }} EasyColoc. Tous droits r?serv?s.</p>
        </div>
    </div>
</body>
</html>
