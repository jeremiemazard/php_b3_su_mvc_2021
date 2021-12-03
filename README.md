## Développer le modèle avec Doctrine : relations OneToMany, ManyToMany, développer la couche de Repository

## Description du projet

- Site e-commerce
- Avec gestion simple des templates via Twig
- Développer la couche de Repository

### Entités

Produits

| id  | Nom     | #id_category | Image | Ref | Prix | Qté  | Marque | Slug | Actif | 
| --- |:-------:| ------------:| ----- | --- |:----:| ----:| ------ | ---- | ----- |

Users

| id  | Nom     | Ville | username | password | email |
| --- |:-------:| ----- | ----- | -------- | -------- |

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

| id  | #id_user | date | 
| --- | -------- | ---- |

Commandes_Produits

| id | #id_commande | #id_produit | qte | prix |
| -- | ------------ | ----------- | --- | ---- |

Suggestions : 
- Héritage Doctrine sur l'entité User : Entité Client (n° client, premium ou pas (booléen)) + Entité Employe (n° employe, date d'embauche)
- Event Listeners / Subscribers : automatiser la création d'un slug dans un produit, lors de son insertion (event prePersist) ou bien mise à jour (event preUpdate)



