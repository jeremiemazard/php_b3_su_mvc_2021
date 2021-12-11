#Projet fin php

### Sujet : Développer le modèle avec Doctrine : relations OneToMany, ManyToMany, développer la couche de Repository

## Description du projet

- Site e-commerce
- Avec gestion simple des templates via Twig
- Développer la couche de Repository

  Suggestions pour aller plus loin :
- Héritage Doctrine sur l'entité User : Entité Client (n° client, premium ou pas (booléen)) + Entité Employe (n° employe, date d'embauche)
- Event Listeners / Subscribers : automatiser la création d'un slug dans un produit, lors de son insertion (event prePersist) ou bien mise à jour (event preUpdate)


### Modelisation BDD

Produits // Products

| id  | name     | #category_id | image | ref | price | brand | slug | active |
| --- |:--------:| ------------:| ----- | --- |:-----:| ----- | ---- | ------ |

(On ne gère pas la marque d'un produit comme une entité entière mais comme une simple chaine de caractères).

Utilisateurs // Users

| id  | name    | username | password | email |
| --- |:-------:| -------- | -------- | ----- |

Clients // Client
> Les entités Client et Employé seront des héritages gérés avec Doctrine : [Single Table Inheritance](https://www.doctrine-project.org/projects/doctrine-orm/en/2.9/reference/inheritance-mapping.html#single-table-inheritance)

| #user_id  | client_num | is_premium |
| -------- | ---------- | ---------- |

Employés // Employees

| #user_id  | employee_num | salary |
| -------- | ------------ | ------ |

Catégories // Categories

| id  | name    | slug |
| --- |:-------:| ----:|

Attributs // Attributes

| id  | name    |
| --- |:-------:|

Valeurs // AttributeValues

| id  | value  | #product_id | #attribute_id |
| --- |:------:| ----------- | ------------- |

Commandes // Orders

| id  | #user_id | date |
| --- | -------- | ---- |

Commandes_Produits // Order_Products  
> Permet de stocker les informations de prix et de quantité d'une commande sur le long terme, même si les prix changent.

| id  | #order_id | #product_id | qty | price |
| --- | --------- | ----------- | --- | ----- |


---

Étape 1
------
### Créer les entités 

On crée les entités décrites plus haut grâce à Doctrine. 
Pour 'Produits', 'Catégories', 'Attributs', 'Valeurs', 'Commandes' et 'Commandes_Produits', il suffit de créer leurs classes respectives avec les bonnes annotations DocBlock.

On renseigne les metadata de chaque champ avec @ORM\Column(type="...", nullable=true,...).

Pour travailler avec les clés étrangères, on utilise le mapping associatif de Doctrine.
Si un produit a une catégorie, on utilisera une relation OneToOne et on écrira :

````php
class Product
{
    /**
     * @OneToOne(targetEntity="Category")  <- On renseigne l'entité cible
     * @JoinColumn(name="category_id", referencedColumnName="id") <- On indique le nom de la colonne dans la base + la colonne visée 
     */
    protected $categoryId;
}
````
> Référence Mapping Associatif de Doctrine : [Association Mapping] (https://www.doctrine-project.org/projects/doctrine-orm/en/2.9/reference/association-mapping.html)





