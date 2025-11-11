<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4F46E5;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            background-color: #f9fafb;
            padding: 20px;
        }
        .stat-box {
            background-color: white;
            border-left: 4px solid #4F46E5;
            padding: 15px;
            margin: 10px 0;
        }
        .stat-label {
            font-size: 14px;
            color: #6b7280;
        }
        .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: #1f2937;
        }
        .article-list {
            list-style: none;
            padding: 0;
        }
        .article-item {
            background-color: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .article-title {
            font-weight: bold;
            color: #4F46E5;
        }
        .article-views {
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Статистика сайта за {{ now()->subDay()->format('d.m.Y') }}</h1>
        </div>

        <div class="content">
            <div class="stat-box">
                <div class="stat-label">Всего просмотров статей</div>
                <div class="stat-value">{{ $totalViews }}</div>
            </div>

            <div class="stat-box">
                <div class="stat-label">Новых комментариев</div>
                <div class="stat-value">{{ $newComments }}</div>
            </div>

            <h3>Топ-5 статей по просмотрам:</h3>
            <ul class="article-list">
                @forelse ($topArticles as $article)
                    <li class="article-item">
                        <div class="article-title">{{ $article->title }}</div>
                        <div class="article-views">Просмотров: {{ $article->views_count }}</div>
                    </li>
                @empty
                    <li class="article-item">Нет данных</li>
                @endforelse
            </ul>
        </div>
    </div>
</body>
</html>
