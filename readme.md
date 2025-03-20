

>>> npm install -g maildev

php.ini


[mail function]
; For Win32 only.
; https://php.net/smtp
SMTP=localhost
; https://php.net/smtp-port
smtp_port=1025

/mon_projet
│── /classes
│   │── Cart.php
│   │── Avis.php
│   │── Search.php
│   │── Footer.php
│── /controllers
│   │── cartController.php
│   │── avisController.php
│   │── searchController.php
│── /views
│   │── accueil.php
│   │── footer.php
│── index.php
│── config.php
