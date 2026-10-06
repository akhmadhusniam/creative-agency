<?php
$notifications = $notifications ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?></title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; background: #f5f5f5; margin: 0; padding: 2rem; }
        .wrap { max-width: 640px; margin: 0 auto; }
        .head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .head h1 { font-size: 1.4rem; }
        .head form button { background: none; border: 1px solid #ccc; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-size: 0.85rem; }
        .back { display: inline-block; margin-bottom: 1rem; color: #666; text-decoration: none; font-size: 0.85rem; }
        .list { background: white; border-radius: 8px; overflow: hidden; }
        .item { display: block; width: 100%; text-align: left; padding: 1rem 1.2rem; border: none; border-bottom: 1px solid #f0f0f0; background: white; cursor: pointer; font-family: inherit; }
        .item:last-child { border-bottom: none; }
        .item.unread { background: #fff9f5; }
        .item .title { font-weight: 700; font-size: 0.92rem; margin-bottom: 0.25rem; display: flex; align-items: center; gap: 0.5rem; }
        .dot { width: 8px; height: 8px; border-radius: 50%; background: #C8412B; display: inline-block; }
        .item .msg { font-size: 0.85rem; color: #666; line-height: 1.5; margin-bottom: 0.3rem; }
        .item .time { font-size: 0.75rem; color: #aaa; }
        .empty { text-align: center; padding: 3rem; color: #999; background: white; border-radius: 8px; }
    </style>
</head>
<body>
<div class="wrap">
    <a href="<?= APP_URL ?>/dashboard" class="back">← Kembali ke Dashboard</a>
    <div class="head">
        <h1><?= e($title) ?></h1>
        <form action="<?= APP_URL ?>/notifications/read-all" method="POST">
            <?= csrfField() ?>
            <button type="submit">Tandai semua dibaca</button>
        </form>
    </div>

    <div class="list">
        <?php foreach ($notifications as $n): ?>
            <form action="<?= APP_URL ?>/notifications/<?= (int) $n['id'] ?>/read" method="POST">
                <?= csrfField() ?>
                <button type="submit" class="item <?= !$n['is_read'] ? 'unread' : '' ?>">
                    <div class="title"><?php if (!$n['is_read']): ?><span class="dot"></span><?php endif; ?> <?= e($n['title']) ?></div>
                    <?php if ($n['message']): ?><div class="msg"><?= e($n['message']) ?></div><?php endif; ?>
                    <div class="time"><?= date('d M Y, H:i', strtotime($n['created_at'])) ?></div>
                </button>
            </form>
        <?php endforeach; ?>
        <?php if (empty($notifications)): ?>
            <div class="empty">Belum ada notifikasi</div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
