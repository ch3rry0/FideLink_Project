## Merchants
    - _id: MongoDB id
    - name: Nom du commerçant
    - pfp: Photo de profil
    - loc: Liste contenant l'adresse en entier avec :
        - address: Nom et numéro de la rue
        - zip: Code postal
        - city: Ville
    - bio: Une biographie du commerçant décrivant son activité
    - pointVal: La valeur du point de fidélité chez ce commerçant
    - createdAt: Date de création du compte
## Customers
    - _id: MongoDB id
    - name: Nom et Prénom du client
    - fdl_id: FideLink ID, unique à chaque customer
    - email: E-mail du client
    - pfp: Photo de Profil
    - pointsBal: Solde total des points disponibles
    - createdAt: Date de création du compte

## Transactions
    - _id: MongoDB id
    - customer_id: Référence à customers._id
    - merchant_id: Référence à merchants._id
    - type: Quand le client dépense un point la valeur est de 0 quand le client gagne un point la valeur est de 1. Si la transaction n'entraîne aucun/e perte/gain de point la valeur est de None
    - amount: montant de l'achat en € (information uniquement pour les comptes merchants)
    - pts: nombre de points gagnés ou utilisés
    - tx_pts: taux de conversion qui transforme le nombre de points donnés par le marchand en points réels de l'utilisateur utilisable entre chaque marchand. Cette valeur ne bouge pas et est fixe (c'est une constante 1pts = 0,01€ ). Exemple : si le marchand dit que son point vaut 1€ alors l'utilisateur récupère 100 points FDL (appelé sur l'application web "100 FDL") sur son compte.
    - note: commentaire libre de la transaction ajouté par le commerçant et visible par les 2 (commerçants et acheteurs)
    - transacDate: date de la Transaction