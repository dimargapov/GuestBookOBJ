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