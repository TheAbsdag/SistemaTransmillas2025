<header class="page-header">
  <h1><?= $title ?></h1>

  <?php if (!empty($actions)): ?>
    <div class="header-actions">
      <?php foreach ($actions as $action): ?>
        <?= $action ?>
      <?php endforeach ?>
    </div>
  <?php endif; ?>
</header>