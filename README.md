## Développer le modèle avec Doctrine : relations OneToMany, ManyToMany, développer la couche de Repository

## Description du projet

- Site e-commerce
- Avec gestion simple des templates via Twig
- Développer la couche de Repository

### Models

- Produits
- Catégories
- User

Produits

| id  | Nom     | #id_category | Image | Ref | Prix | Qté  | Marque | Slug | Actif | 
| --- |:-------:| ------------:| ----- | --- |:----:| ----:| ------ | ---- | ----- |

Users

| id  | Nom     | #id_category | Ville | username | password | email |
| --- |:-------:| ------------:| ----- | -------- | -------- | ----- |

Catégories

| id  | Nom     | slug |
| --- |:-------:| ----:|

Attributs

| id  | Nom     |
| --- |:-------:|

Valeurs

| id  | Valeur  | #id_produit | #id_attribut |
| --- |:-------:| ----------- | ------------ |

Commandes

| id  | #id_user | 
| --- | -------- |

Commandes_Produits

| id | #id_commande | #id_produit | qté |
| -- | ------------ | ----------- | --- |




