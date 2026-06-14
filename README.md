# KIVO Framework

Framework PHP natif orienté ERP : composants, moteur de vues, API mobile, modules métier et CLI française.

## Démarrage sous Windows/WAMP

À la racine du projet :

```powershell
php kivo aide
php kivo installer
```

Alternative :

```powershell
php bin/kivo.php installer
bin\kivo.bat installer
```

La commande `kivo installer` seule ne fonctionne que si `bin/` est ajouté au PATH Windows.

## Installation

```powershell
php kivo installer
```

L'assistant crée automatiquement :

- `.env` depuis `.env.example` ;
- `APP_KEY` ;
- dossiers `storage/` ;
- base MySQL si l'utilisateur possède le droit `CREATE DATABASE` ;
- tables noyau via migrations SQL.

Sur cPanel/hébergement mutualisé, crée d'abord la base dans cPanel/phpMyAdmin, puis renseigne les accès dans l'assistant.

## Commandes

```powershell
php kivo creer:module Personnel
php kivo creer:api Personnel
php kivo creer:composant select-search
php kivo lister:modules
php kivo nouveau mon_erp
```

Alias anglais disponibles :

```powershell
php kivo make:module Personnel
php kivo make:api Personnel
php kivo make:component select-search
php kivo new mon_erp
```

## Lancer le serveur local

```powershell
php -S localhost:8080 -t public
```

Puis ouvrir : `http://localhost:8080`.

## Vision

KIVO vise à générer des ERP complets à partir de modules natifs : RH, utilisateurs, permissions, tickets, CRM, finance, documents, logistique, transit, site web et API mobile.
