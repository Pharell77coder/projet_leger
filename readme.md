# Mon Projet

Ce projet est une application PHP qui utilise **MailDev** pour gérer l'envoi d'e-mails en local. Il permet de configurer un serveur SMTP local, pour tester les fonctionnalités liées à l'envoi de mails.

## Prérequis

- **Node.js** installé sur votre machine.
- **PHP** version 7 ou supérieure.
- **MailDev** installé globalement via npm pour simuler un serveur SMTP en local.

## Installation

### 1. Installer MailDev

Installez **MailDev** globalement avec npm :

```txt
npm install -g maildev
[mail function]
; For Win32 only.
; https://php.net/smtp
SMTP=localhost
; https://php.net/smtp-port
smtp_port=1025
```

### executer

```bash
npm install -g maildev
```
