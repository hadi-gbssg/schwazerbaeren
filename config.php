<?php
// config.php – Versand über Gmail SMTP
return [
  // Empfänger (wo die Nachricht ankommen soll)
  'to_email'   => 'hadi.toufayli@ict-scouts.ch',
  'to_name'    => 'Hadi Toufayli',

  // Absender MUSS bei Gmail = deine Gmail-Adresse sein
  'from_email' => 'h.j.toufayli@gmail.com',
  'from_name'  => 'Website Formular',

  'smtp' => [
    'host'       => 'smtp.gmail.com',
    'port'       => 587,      // STARTTLS
    'encryption' => 'tls',
    'username'   => 'h.j.toufayli@gmail.com',   // volle Gmail-Adresse
    'password'   => 'tslk fens btov wfer',     // 16-stelliges App-Passwort
  ],

  'max_message_len' => 5000,
  'honeypot_field'  => 'website',
  'rate_limit'      => ['requests' => 4, 'per_sec' => 3600],
];
