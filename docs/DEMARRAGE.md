# Démarrage KIVO

## Premier réflexe après clone

```powershell
php kivo
```

Cette commande affiche le parcours recommandé.

## Installer un ERP

```powershell
php kivo installer
```

KIVO crée `.env`, `kivo.json`, la clé applicative, les dossiers `storage`, les tables SQL et le compte admin si demandé.

## Créer un projet depuis le framework

```powershell
php kivo nouveau mon_erp
cd mon_erp
php kivo installer
php kivo servir
```

## Installer la commande globale

```powershell
php kivo installer:global
```

Après réouverture de PowerShell :

```powershell
kivo aide
```
