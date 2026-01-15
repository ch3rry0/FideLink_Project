# Configuration Docker MongoDB pour FideLink

## Prérequis
- Docker Desktop installé et démarré
- MongoDB Compass (pour se connecter à la base de données)

## Démarrage

### 0. Modifier le fichier docker-compose.yml
    - Renommez le fichier docker-compose.yml.example en docker-compose.yml
    - Changez tous les placeholders par vos username et password

### 1. Lancer les conteneurs
```bash
docker-compose up -d
```

### 2. Vérifier que les conteneurs sont en cours d'exécution
```bash
docker-compose ps
```

### 3. Voir les logs
```bash
docker-compose logs -f mongodb
```

## Connexion à MongoDB

### Via MongoDB Compass
Utilisez cette chaîne de connexion :
```
mongodb://admin:admin123@localhost:27018/fidelink?authSource=admin
```

### Via Mongo Express (Interface Web)
Ouvrez votre navigateur à l'adresse :
```
http://localhost:8081
```
- Username: `admin`
- Password: `admin123`

### Via ligne de commande
```bash
docker exec -it fidelink_mongodb mongosh -u admin -p admin123 --authenticationDatabase admin
```

## Commandes utiles

### Arrêter les conteneurs
```bash
docker-compose down
```

### Arrêter et supprimer les volumes (⚠️ supprime toutes les données)
```bash
docker-compose down -v
```

### Redémarrer les conteneurs
```bash
docker-compose restart
```