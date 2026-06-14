# KIVO Framework

**KIVO Framework** est un framework PHP natif orienté ERP, créé par **Yao Ulrich AMANI** en Côte d’Ivoire.

Objectif : faciliter fortement la création d'applications métier, ERP, CRM, portails, sites web et API mobiles, avec une logique proche des frameworks classiques mais orientée modules métier.

## Ce que KIVO doit générer

- Socle ERP complet : utilisateurs, permissions, RH, portail, site web.
- Modules natifs : tickets, CRM, finance, logistique, colisage, transit, documentation, formation, etc.
- API mobile JSON générée automatiquement.
- Vues `.ublade.php` avec moteur de vue natif type Blade.
- Composants UI réutilisables.
- Templates applicatifs : enterprise, transit, BTP, médical, école, CRM.
- À terme : interface graphique no-code/low-code.

## Installation locale

```bash
composer dump-autoload
php -S localhost:8080 -t public
```

## CLI française

```bash
php bin/kivo creer:module Personnel
php bin/kivo creer:api Personnel
php bin/kivo creer:composant select-search
php bin/kivo installer:erp
php bin/kivo installer:module rh
php bin/kivo lister:modules
```

Alias anglais disponibles :

```bash
php bin/kivo make:module Personnel
php bin/kivo install:module tickets
```

## Exemple de vue

```php
@extends('layouts.app')

@section('content')
<x-page-header title="Utilisateurs" subtitle="Gestion des comptes et accès" />
<x-select-search name="service_id" label="Service" placeholder="Rechercher un service" />
@endsection
```

## Exemple API

```php
$router->apiResource('users', UserApiController::class);
```

Endpoints générés :

```txt
GET    /api/users
POST   /api/users
GET    /api/users/{id}
POST   /api/users/{id}
POST   /api/users/{id}/delete
```

## Philosophie

KIVO n’est pas un simple mini-Laravel. C’est un **ERP Application Framework** : il génère une base métier complète, puis permet de créer des modules, des API mobiles et des interfaces propres avec très peu de code.
