<?php
?>
    <h1>Все сообщения</h1>

<?php if (empty($messages)): ?>
    <p>Сообщений нет.</p>
<?php else: ?>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Текст сообщения</th>
            <th>Статус</th>
            <th>Дата</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($messages as $msg): ?>
            <tr>
                <td><?= htmlspecialchars($msg['id']) ?></td>
                <td><?= htmlspecialchars($msg['name']) ?></td>
                <td><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
                <td><?= htmlspecialchars($msg['status']) ?></td>
                <td><?= htmlspecialchars($msg['created_at']) ?></td>
                <td><a href="/projectObj/public/index.php?action=approve&id=<?= $msg['id'] ?>">Одобрить</a>&nbsp&nbsp
                    <a href="/projectObj/public/index.php?action=reject&id=<?= $msg['id'] ?>">Отклонить</a>&nbsp&nbsp
                    <a href="/projectObj/public/index.php?action=edit&id=<?= $msg['id'] ?>">Обновить</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>