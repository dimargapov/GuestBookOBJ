<h1>Гостевая книга</h1>
<?php if (!empty($messages) && is_array($messages)): ?>
    <?php foreach ($messages as $msg): ?>
        <div class="message">
            <h3><?= htmlspecialchars($msg['name']) ?></h3>
            <p><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Сообщений нет.</p>
<?php endif; ?>
<h1>Оставить сообщение</h1>

<form action="/projectObj/public/index.php?action=create" method="post">
    <div class="form-group">
        <label for="name">Ваше имя:</label>
        <input type="text" id="name" name="name" required>
    </div>

    <div class="form-group">
        <label for="text">Сообщение:</label>
        <textarea id="text" name="text" required></textarea>
    </div>

    <input type="submit" value="Отправить" />
</form>
</body>
</html>
