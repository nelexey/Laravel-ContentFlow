<!DOCTYPE html>
<html>
<head>
    <title>New Comment Requires Moderation</title>
</head>
<body>
    <h2>New Comment Requires Moderation</h2>
    
    <p>A new comment has been posted and requires your moderation.</p>
    
    <p><strong>Article:</strong> {{ $comment->article->title }}</p>
    
    <p><strong>Comment by:</strong> {{ $comment->user->name }}</p>
    
    <p><strong>Comment:</strong></p>
    <p>{{ $comment->body }}</p>
    
    <p><a href="{{ route('admin.comments.index') }}">Moderate Comments</a></p>
    
    <p>Thanks,<br>
    {{ config('app.name') }}</p>
</body>
</html>