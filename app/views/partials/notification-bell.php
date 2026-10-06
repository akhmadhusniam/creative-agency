<?php
/**
 * partials/notification-bell.php
 * Butuh variabel: $unreadCount (int), $recentNotifications (array)
 * Pakai <details>/<summary> native HTML agar dropdown jalan tanpa JS tambahan.
 */
$unreadCount = $unreadCount ?? 0;
$recentNotifications = $recentNotifications ?? [];
?>
<style>
.notif-bell { position: relative; display: inline-block; }
.notif-bell summary { list-style: none; cursor: pointer; font-size: 1.3rem; padding: 0.4rem 0.6rem; border-radius: 6px; position: relative; }
.notif-bell summary::-webkit-details-marker { display: none; }
.notif-bell summary:hover { background: rgba(0,0,0,0.06); }
.notif-badge { position: absolute; top: 0; right: 0; background: #C8412B; color: white; font-size: 0.65rem; font-weight: 700; min-width: 16px; height: 16px; border-radius: 8px; display: flex; align-items: center; justify-content: center; padding: 0 3px; }
.notif-panel { position: absolute; right: 0; top: 110%; width: 320px; max-height: 400px; overflow-y: auto; background: white; border: 1px solid #e5e5e5; border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,0.12); z-index: 200; }
.notif-panel-head { display: flex; justify-content: space-between; align-items: center; padding: 0.8rem 1rem; border-bottom: 1px solid #eee; }
.notif-panel-head strong { font-size: 0.9rem; }
.notif-panel-head form button { background: none; border: none; color: #C8412B; font-size: 0.78rem; cursor: pointer; }
.notif-item { display: block; padding: 0.8rem 1rem; border-bottom: 1px solid #f2f2f2; text-decoration: none; color: inherit; }
.notif-item:hover { background: #faf9f7; }
.notif-item.unread { background: #fff9f5; }
.notif-item .notif-title { font-size: 0.85rem; font-weight: 600; margin-bottom: 0.2rem; }
.notif-item .notif-msg { font-size: 0.78rem; color: #777; line-height: 1.4; }
.notif-item .notif-time { font-size: 0.7rem; color: #aaa; margin-top: 0.3rem; }
.notif-empty { padding: 2rem 1rem; text-align: center; color: #999; font-size: 0.85rem; }
.notif-footer { padding: 0.6rem 1rem; text-align: center; border-top: 1px solid #eee; }
.notif-footer a { font-size: 0.8rem; color: #666; text-decoration: none; }
</style>

<details class="notif-bell">
  <summary>
    🔔
    <?php if ($unreadCount > 0): ?>
      <span class="notif-badge"><?= $unreadCount > 9 ? '9+' : $unreadCount ?></span>
    <?php endif; ?>
  </summary>
  <div class="notif-panel">
    <div class="notif-panel-head">
      <strong>Notifikasi</strong>
      <?php if ($unreadCount > 0): ?>
        <form action="<?= APP_URL ?>/notifications/read-all" method="POST">
          <?= csrfField() ?>
          <button type="submit">Tandai semua dibaca</button>
        </form>
      <?php endif; ?>
    </div>

    <?php if (empty($recentNotifications)): ?>
      <div class="notif-empty">Belum ada notifikasi</div>
    <?php else: ?>
      <?php foreach ($recentNotifications as $n): ?>
        <form action="<?= APP_URL ?>/notifications/<?= (int) $n['id'] ?>/read" method="POST" style="margin:0;">
          <?= csrfField() ?>
          <button type="submit" class="notif-item <?= !$n['is_read'] ? 'unread' : '' ?>" style="width:100%;text-align:left;border:none;background:none;cursor:pointer;font-family:inherit;">
            <div class="notif-title"><?= e($n['title']) ?></div>
            <?php if ($n['message']): ?><div class="notif-msg"><?= e(mb_strimwidth($n['message'], 0, 90, '…')) ?></div><?php endif; ?>
            <div class="notif-time"><?= date('d M, H:i', strtotime($n['created_at'])) ?></div>
          </button>
        </form>
      <?php endforeach; ?>
    <?php endif; ?>

    <div class="notif-footer">
      <a href="<?= APP_URL ?>/notifications">Lihat semua notifikasi →</a>
    </div>
  </div>
</details>
