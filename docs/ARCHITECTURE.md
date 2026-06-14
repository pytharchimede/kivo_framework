# Ulrich Framework PHP natif

Objectif : garder le contrôle du PHP natif tout en obtenant une structure framework-like.

## Piliers
- `core/` : noyau réutilisable du framework.
- `app/` : code métier de l'application.
- `resources/views/*.ublade.php` : moteur de vues natif inspiré de Blade.
- `resources/views/components/` : composants réutilisables.
- `routes/api.php` : API JSON générable pour mobile.
- `bin/uf make:module Nom` : générateur Controller API + Repository + Resource.

## Exemple API mobile
```php
$router->apiResource('users', UserApiController::class);
```
Génère les endpoints :
- GET `/api/users`
- POST `/api/users`
- GET `/api/users/{id}`
- POST `/api/users/{id}`
- POST `/api/users/{id}/delete`

## Exemple composant
```html
<x-select-search name="service_id" label="Service" placeholder="Rechercher un service" />
```
