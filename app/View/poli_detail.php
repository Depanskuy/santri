<?php
  $id = (int)$params[0];

  $poli = null;
  foreach ($data['polis'] as $p) {
      if ($p['id'] === $id) {
          $poli = $p;
          break;
      }
  }

  if (!$poli) {
      echo "<h1>Poli tidak ditemukan</h1>";
      return;
  }
?>

<h1><?= $poli['name'] ?></h1>
<p>Kode: <?= $poli['code'] ?></p>
<p><?= $poli['sub'] ?></p>