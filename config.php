<?php
// config.php – Versand über Gmail SMTP
return [
  // Empfänger (wo die Nachricht ankommen soll)
  'to_email'   => 'info@schwarzer-baeren.ch',
  'to_name'    => 'jan Belohrasky',

  // Absender MUSS bei Gmail = deine Gmail-Adresse sein
  'from_email' => 'h.j.toufayli@gmail.com',
  'from_name'  => 'Website Formular',

  'smtp' => [
    'host'       => 'smtp.gmail.com',
    'port'       => 587,      // STARTTLS
    'encryption' => 'tls',
    'username'   => 'schwarzerbaren@gmail.com',   // volle Gmail-Adresse
    'password'   => 'lesx ezqz tkjf dctr',     // 16-stelliges App-Passwort
  ],

  'max_message_len' => 5000,
  'honeypot_field'  => 'website',
  'rate_limit'      => ['requests' => 4, 'per_sec' => 3600],
];
