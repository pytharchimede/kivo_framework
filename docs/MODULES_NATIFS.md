# Modules natifs KIVO

KIVO est un framework ERP-first : il ne génère pas seulement des CRUD, il génère des modules métiers prêts à connecter entre eux.

## Socle ERP de base

```bash
php bin/kivo installer:erp
```

Installe :

- Utilisateurs
- Permissions
- Ressources Humaines
- Site web
- Portail de sélection

## Modules métier évolutifs

```bash
php bin/kivo installer:module tickets
php bin/kivo installer:module crm
php bin/kivo installer:module finance
php bin/kivo installer:module logistique
```

## Interactions entre modules

Les interactions doivent passer par des événements métier :

- `ticket.created`
- `payment.approved`
- `personnel.created`
- `mission.created`

Exemple futur : quand un ticket est assigné à un utilisateur, le module Notifications peut créer une notification système, et l’API mobile peut exposer cette notification automatiquement.
