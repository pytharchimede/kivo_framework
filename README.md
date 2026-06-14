# KIVO Framework

KIVO est un framework PHP natif orienté ERP : composants, moteur de vues, API mobile, modules métier, permissions, portail et CLI française.

## Démarrage le plus simple

Après avoir cloné le projet :

```powershell
git clone https://github.com/ton-compte/kivo-framework mon_erp
cd mon_erp
php kivo
```

KIVO affiche immédiatement les commandes utiles.

Pour installer le projet :

```powershell
php kivo installer
```

Puis lancer le serveur local :

```powershell
php kivo servir
```

Ouvre ensuite :

```txt
http://localhost:8080
```

## Installation guidée

La commande :

```powershell
php kivo installer
```

crée automatiquement :

- `.env` depuis `.env.example` ;
- `APP_KEY` ;
- `kivo.json` ;
- les dossiers `storage/` ;
- la base MySQL si l’utilisateur possède le droit `CREATE DATABASE` ;
- les tables noyau via migrations SQL ;
- le compte administrateur si demandé.

L’assistant demande :

```txt
Nom du projet
Nom de l’entreprise
Template : enterprise/transit/rh/vide
URL locale
Base de données
Utilisateur MySQL
Mot de passe MySQL
Compte administrateur
```

Sur WAMP en local, `root` sans mot de passe fonctionne souvent. Sur cPanel/hébergement mutualisé, crée d’abord la base dans cPanel/phpMyAdmin, puis renseigne ses accès dans l’assistant.

## Commande globale optionnelle

Au début, il faut utiliser :

```powershell
php kivo installer
```

Pour pouvoir écrire simplement `kivo` partout :

```powershell
php kivo installer:global
```

Sous Windows, cette commande crée automatiquement :

```txt
%LOCALAPPDATA%\Kivo\kivo.bat
```

puis tente d’ajouter ce dossier au PATH utilisateur. Ferme puis rouvre PowerShell après l’installation globale.

Ensuite :

```powershell
kivo aide
kivo nouveau mon_erp
kivo installer
```

## Créer un nouvel ERP

Depuis le dossier du framework cloné :

```powershell
php kivo nouveau transitpro
cd transitpro
php kivo installer
php kivo servir
```

## Commandes principales

```powershell
php kivo aide
php kivo installer
php kivo installer:global
php kivo nouveau mon_erp
php kivo servir
php kivo creer:module Personnel
php kivo creer:api Personnel
php kivo creer:composant select-search
php kivo lister:modules
```

Alias anglais disponibles :

```powershell
php kivo help
php kivo install
php kivo global:install
php kivo new mon_erp
php kivo serve
php kivo make:module Personnel
php kivo make:api Personnel
php kivo make:component select-search
```

## Vision

KIVO vise à générer des ERP complets à partir de modules natifs : RH, utilisateurs, permissions, tickets, CRM, finance, documents, logistique, transit, site web et API mobile.
