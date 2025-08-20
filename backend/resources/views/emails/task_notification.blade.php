<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Notificação de Tarefa</title>
</head>
<body>
    <h2>{{ $messageText }}</h2>
    <p><strong>Título:</strong> {{ $task->title }}</p>
    <p><strong>Descrição:</strong> {{ $task->description }}</p>
    <p><strong>Status:</strong> {{ $task->status }}</p>
    <p><strong>Prioridade:</strong> {{ $task->priority }}</p>
</body>
</html>
