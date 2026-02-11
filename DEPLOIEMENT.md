# Guide de Déploiement Production

## Mise à jour de la production

### 1. Sur le serveur de production, récupérez les dernières modifications :

```bash
cd /chemin/vers/projet
git pull origin main
```

### 2. Reconstruisez les containers avec les nouvelles modifications :

```bash
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

> **Important** : Utilisez `--no-cache` pour forcer la reconstruction complète et appliquer les changements de permissions sur le dossier uploads.

### 3. Vérifiez que les containers fonctionnent :

```bash
docker-compose ps
docker-compose logs backend
```

## Configuration Production

### Variables d'environnement

Créez un fichier `.env` à la racine du projet avec les vraies valeurs de production :

```env
# Database
POSTGRES_USER=votre_user
POSTGRES_PASSWORD=mot_de_passe_securise
POSTGRES_DB=maraicher_db

# Backend
NODE_ENV=production
BACKEND_PORT=5000
JWT_SECRET=generer_un_secret_unique_et_long
JWT_EXPIRE=7d

# Frontend
VITE_API_URL=https://votre-domaine.com/api
FRONTEND_URL=https://votre-domaine.com

# Brevo Email (optionnel)
BREVO_SMTP_HOST=smtp-relay.brevo.com
BREVO_SMTP_PORT=587
BREVO_SMTP_USER=votre_email_brevo
BREVO_SMTP_PASS=votre_cle_api_brevo
BREVO_FROM_EMAIL=noreply@votre-domaine.com
```

### Permissions uploads (si problème persiste)

Si l'upload de photos ne fonctionne toujours pas après reconstruction :

```bash
# Entrez dans le container backend
docker-compose exec backend sh

# Vérifiez et corrigez les permissions
ls -la uploads/
chmod -R 777 uploads/
exit
```

### Sauvegardes

#### Base de données

```bash
# Créer une sauvegarde
docker-compose exec postgres pg_dump -U postgres maraicher_db > backup_$(date +%Y%m%d).sql

# Restaurer une sauvegarde
docker-compose exec -T postgres psql -U postgres maraicher_db < backup_20260211.sql
```

#### Images produits

```bash
# Les images sont dans le volume backend_uploads
docker run --rm -v projet_backend_uploads:/data -v $(pwd):/backup alpine tar czf /backup/uploads_backup.tar.gz -C /data .
```

## Sécurité Production

⚠️ **Avant de mettre en production** :

1. **Changez tous les mots de passe par défaut**
2. **Générez un JWT_SECRET fort** :
   ```bash
   node -e "console.log(require('crypto').randomBytes(32).toString('hex'))"
   ```
3. **Configurez un reverse proxy** (Nginx/Traefik) avec HTTPS
4. **Limitez l'accès à PostgreSQL** (ne pas exposer le port 5432 publiquement)

## Commandes utiles

```bash
# Voir les logs en temps réel
docker-compose logs -f backend

# Redémarrer un service
docker-compose restart backend

# Vérifier l'espace disque des volumes
docker system df -v

# Nettoyer les anciennes images
docker system prune -a
```

## Résolution de problèmes

### Les photos ne s'affichent pas
- Vérifiez les permissions : `docker-compose exec backend ls -la uploads/products/`
- Reconstruisez avec : `docker-compose build --no-cache backend`

### Erreurs de connexion base de données
- Vérifiez que le container postgres est running : `docker-compose ps`
- Consultez les logs : `docker-compose logs postgres`

### Page blanche frontend
- Vérifiez que VITE_API_URL pointe vers le bon domaine/IP
- Reconstruisez le frontend : `docker-compose build --no-cache frontend`
