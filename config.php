<?php 
// config.php – Versand über Swizzonic Mailserver
return [
  // Empfänger
  'to_email'   => 'info@schwarzer-baeren.ch',
  'to_name'    => 'Restaurant Schwarzer Bären',

  // Absender (muss dieselbe Domain sein)
  'from_email' => 'info@schwarzer-baeren.ch',
  'from_name'  => 'Website Formular',

  // SMTP-Konfiguration für Swizzonic
  'smtp' => [
    'host'       => 'mail.swizzonic.ch',  // offizieller Mailserver von Swizzonic
    'port'       => 587,                  // für STARTTLS
    'encryption' => 'tls',                // bei Problemen alternativ 'ssl' mit Port 465
    'username'   => 'info@schwarzer-baeren.ch',
    'password'   => '18kaomWm1*', // E-Mail-Passwort aus dem Swizzonic-Panel
  ],

  'max_message_len' => 5000,
  'honeypot_field'  => 'website',
  'rate_limit'      => ['requests' => 4, 'per_sec' => 3600],
];

