# CLI française KIVO

KIVO reste compatible avec les conventions classiques, mais la CLI principale est en français.

## Commandes principales

```bash
php bin/kivo creer:module Personnel
php bin/kivo creer:api Personnel
php bin/kivo creer:composant select-search
php bin/kivo installer:erp
php bin/kivo installer:module rh
php bin/kivo lister:modules
```

## Alias anglais

```bash
php bin/kivo make:module Personnel
php bin/kivo make:api Personnel
php bin/kivo make:component select-search
php bin/kivo install:erp
php bin/kivo install:module rh
php bin/kivo list:modules
```

## Philosophie

- Les commandes françaises rendent l’outil accessible aux équipes francophones.
- Les alias anglais gardent une compatibilité avec les habitudes Laravel/Symfony.
- Les modules natifs installent automatiquement contrôleurs, repositories, resources API et vues.
