<?php
// ratelimit.php – sehr einfacher IP-basierter Rate-Limiter (Datei-Storage)
function rate_limit_ok(string $key, int $maxRequests, int $perSeconds): bool {
  $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'contact_rate';
  if (!is_dir($dir)) @mkdir($dir, 0777, true);

  $file = $dir . DIRECTORY_SEPARATOR . preg_replace('/[^a-z0-9_\-]/i', '_', $key) . '.json';
  $now  = time();
  $data = ['ts' => $now, 'hits' => 0];

  if (is_file($file)) {
    $raw = @file_get_contents($file);
    if ($raw !== false) {
      $existing = json_decode($raw, true);
      if (is_array($existing) && isset($existing['ts'], $existing['hits'])) {
        // Fenster verschieben
        if (($now - (int)$existing['ts']) <= $perSeconds) {
          $data['ts']   = (int)$existing['ts'];
          $data['hits'] = (int)$existing['hits'];
        }
      }
    }
  }

  // Hit hinzufügen
  $data['hits']++;

  // prüfen
  $ok = true;
  if ($data['hits'] > $maxRequests) {
    $ok = false;
  }

  // Aufräumen/Zurücksetzen nach Fenster
  if (($now - $data['ts']) > $perSeconds) {
    $data = ['ts' => $now, 'hits' => 1];
    $ok   = true;
  }

  // speichern
  @file_put_contents($file, json_encode($data));

  return $ok;
}
