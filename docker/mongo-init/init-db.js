// Script d'initialisation de la base de données MongoDB
db = db.getSiblingDB('fidelink');

// Création des collections principales
db.createCollection('merchants');
db.createCollection('customers');
db.createCollection('transactions');
db.createCollection('admins');

// Création des index pour optimiser les performances
db.merchants.createIndex({ "email": 1 }, { unique: true });
db.customers.createIndex({ "email": 1 }, { unique: true });
db.admins.createIndex({ "email": 1 }, { unique: true });
db.transactions.createIndex({ "userId": 1 });
db.transactions.createIndex({ "createdAt": -1 });

// Insertion de données de test (optionnel)
db.admins.insertOne({
  email: "admin@fidelink.com",
  name: "Admin",
  role: "admin",
  createdAt: new Date()
});

print('Base de données FideLink initialisée avec succès !');