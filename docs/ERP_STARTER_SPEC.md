# Spécification ERP Starter

Une application créée avec KIVO Framework doit pouvoir démarrer avec une base ERP directement exploitable.

## Modules installés par défaut

### 1. Authentification

- Connexion
- Déconnexion
- Mot de passe oublié
- Session sécurisée
- Protection CSRF

### 2. Utilisateurs

- Liste des utilisateurs
- Création / modification
- Activation / désactivation
- Profil utilisateur
- Association service / fonction

### 3. Permissions

- Rôles
- Permissions
- Matrice ACL
- Protection des routes
- Protection des boutons/actions

### 4. RH de base

- Personnel
- Services
- Fonctions
- Documents RH

### 5. Portail de sélection

- Liste des modules autorisés
- Recherche de modules
- Favoris
- Dernières activités

### 6. Site web

- Accueil
- À propos
- Services
- Contact
- Administration des contenus

## API mobile de base

```txt
POST /api/login
POST /api/logout
GET  /api/me
GET  /api/modules
GET  /api/notifications
GET  /api/users
GET  /api/rh/personnel
```
