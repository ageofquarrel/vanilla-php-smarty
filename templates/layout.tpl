<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{block name=title}Блог{/block}</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <header class="site-header">
        <a href="/" class="site-logo">Блог</a>
    </header>

    <main class="site-main">
        {block name=content}{/block}
    </main>
</body>
</html>
