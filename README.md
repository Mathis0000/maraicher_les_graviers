# Maraîcher E-Commerce Platform

Une plateforme e-commerce complète pour maraîcher, permettant aux clients de commander des produits frais en ligne avec paiement à la livraison.

## Fonctionnalités

### Pour les Clients
- Inscription et authentification
- Navigation dans le catalogue de produits
- Ajout de produits au panier
- Passation de commandes (paiement à la livraison)
- Historique des commandes
- Gestion du profil

### Pour les Administrateurs
- Dashboard d'administration
- Ajout, modification et suppression de produits
- Upload d'images de produits
- Visualisation de toutes les commandes
- Mise à jour du statut des commandes (en attente, confirmée, livrée, annulée)

## Stack Technologique

- **Backend**: Node.js + Express
- **Frontend**: React + Vite
- **Base de données**: PostgreSQL 15
- **Containerisation**: Docker + Docker Compose

## Prérequis

- Docker (v20.10+)
- Docker Compose (v2.0+)
- Git

## Installation

### 1. Cloner le dépôt
```bash
git clone <repository-url>
cd projet
```

### 2. Configurer les variables d'environnement
```bash
cp .env.example .env
# Éditer .env avec vos valeurs de configuration
```

### 3. Démarrer l'application
```bash
docker-compose up --build
```

Cette commande va :
- Construire et démarrer le conteneur PostgreSQL
- Construire et démarrer le conteneur Backend API
- Construire et démarrer le conteneur Frontend
- Initialiser la base de données avec le schéma et les données de test

### 4. Accéder à l'application

- **Frontend**: http://localhost:3000
- **Backend API**: http://localhost:5000/api
- **PostgreSQL**: localhost:5432

### Comptes par défaut

**Compte Administrateur**:
- Email: admin@maraicher.com
- Mot de passe: admin123

**Compte Client**:
- Email: customer@example.com
- Mot de passe: customer123

## Développement

### Backend
```bash
cd backend
npm install
npm run dev
```

### Frontend
```bash
cd frontend
npm install
npm run dev
```

### Gestion de la base de données

**Se connecter à PostgreSQL**:
```bash
docker exec -it maraicher_postgres psql -U postgres -d maraicher_db
```

**Sauvegarder la base de données**:
```bash
docker exec maraicher_postgres pg_dump -U postgres maraicher_db > backup.sql
```

**Restaurer la base de données**:
```bash
docker exec -i maraicher_postgres psql -U postgres maraicher_db < backup.sql
```

## Structure du Projet

```
projet/
├── backend/          # API Node.js/Express
├── frontend/         # Application React/Vite
├── postgres/         # Configuration PostgreSQL
└── docker-compose.yml
```

## API Documentation

Voir `/backend/README.md` pour la documentation complète de l'API.

## Déploiement en Production

Pour le déploiement en production :

1. Mettre à jour les variables d'environnement dans `.env`
2. Définir `NODE_ENV=production`
3. Utiliser un JWT_SECRET fort et sécurisé
4. Configurer les origines CORS appropriées
5. Mettre en place des certificats HTTPS/SSL
6. Utiliser une configuration PostgreSQL adaptée à la production

## Licence

MIT
