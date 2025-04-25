<!DOCTYPE html>
<html>
<head>
    <title>Редактирование</title>
</head>
<body>
<form action="/projectObj/public/index.php?action=update&id=<?= $message['id'] ?>" method="post">
    <div class="form-group">
        <label>Имя:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($message['name']) ?>" >
    </div>

    <div class="form-group">
        <label>Сообщение:</label>
        <textarea name="text"><?= htmlspecialchars($message['message']) ?></textarea>
    </div>

    <input type="submit" value="Обновить" />
</form>
</body>
</html>