# WhattsUp - Architecture du Projet

Application de messagerie instantanée en PHP avec une interface moderne.

## Structure du Projet

```
whatsup/
├── .htaccess            # Configuration Apache
├── assets/             # Fichiers statiques (CSS, JS, images)
├── components/         # Composants réutilisables
├── logic/             # Logique métier
├── routes/            # Gestion des routes
├── storage/           # Données persistantes (XML, avatars)
├── template/          # Templates et layouts
├── utils.php          # Fonctions utilitaires
└── README.md          # Documentation
```

## Architecture

### 1. Routes (`routes/`)

Gestion des URL et des points d'entrée de l'application.
- `index.php`: Route principale
- `auth.php`: Routes d'authentification
- `chat.php`: Routes de chat
- `groups.php`: Routes de gestion des groupes
- `api.php`: API REST

### 2. Logique (`logic/`)

Contient toute la logique métier de l'application.
- `auth.php`: Gestion de l'authentification
- `chat.php`: Logique des conversations
- `groups.php`: Gestion des groupes
- `contacts.php`: Gestion des contacts
- `search_contacts.php`: Recherche des contacts
- `poll.php`: Système de sondages
- `utils.php`: Fonctions utilitaires

### 3. Templates (`template/`)

Système de templates basé sur PHP.
- `layout.php`: Layout principal
- `auth/`: Templates d'authentification
- `protected/`: Templates protégés
  - `protected.layout.php`: Layout protégé
  - `chat_private.template.php`: Template de chat privé
  - `chat_group.template.php`: Template de chat de groupe

### 4. Components (`components/`)

Composants réutilisables.
- `sidebar/`: Composants de la barre latérale
  - `header.php`: En-tête utilisateur
  - `search.php`: Composant de recherche
  - `contacts.php`: Liste des contacts
  - `groups.php`: Liste des groupes
- `chat_private/`: Composants de chat privé
  - `empty.php`: État vide
  - `with_contact.php`: Chat actif
- `chat_group/`: Composants de chat de groupe
  - `empty.php`: État vide
  - `with_group.php`: Chat actif
- `modals/`: Modales réutilisables

### 5. Storage (`storage/`)

Données persistantes.
- `avatars/`: Images des avatars
- `xml/`: Données en XML
  - `users.xml`: Utilisateurs
  - `groups.xml`: Groupes
  - `messages.xml`: Messages
- `polls/`: Données des sondages

### 6. Assets (`assets/`)

Ressources statiques.
- `css/`: Styles
- `js/`: Scripts
- `images/`: Images
- `fonts/`: Polices

## Configuration Apache

Le fichier `.htaccess` gère les redirections et les règles de réécriture d'URL.

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [QSA,L]
```

## Installation

1. Configuration du serveur
   - Apache avec mod_rewrite activé
   - PHP 7.4+
   - Extension SimpleXML

2. Structure des dossiers
   ```bash
   mkdir -p storage/{avatars,xml,polls}
   chmod 755 storage/
   chmod 755 storage/*
   ```

3. Configuration
   - Créer les fichiers XML dans `storage/xml/`
   - Configurer les permissions des dossiers
   - Vérifier la configuration Apache

## Développement

### Structure des Templates

Les templates suivent une structure hiérarchique :
1. Layout principal (`layout.php`)
2. Layout spécifique (`auth.layout.php`, `protected.layout.php`)
3. Templates (`*.template.php`)
4. Composants (`components/`)

### Gestion des États

Les composants gèrent différents états :
- État vide (`empty.php`)
- État chargé (`with_contact.php`, `with_group.php`)
- État de recherche
- États d'erreur

### Système de Recherche

La recherche est implémentée avec :
- Filtrage client côté
- Support du mode clair/sombre
- Messages d'état
- Réinitialisation automatique

## Sécurité

- Validation des entrées
- Protection contre les injections
- Gestion des sessions
- Sanitization des données XML
- Protection des fichiers sensibles

## Technologies

- PHP 7.4+
- Tailwind CSS
- SimpleXML
- JavaScript moderne
- AJAX pour les interactions
- Mode clair/sombre natif
