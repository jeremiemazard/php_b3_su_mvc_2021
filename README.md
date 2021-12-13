# Projet fin php

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

(On ne gère pas la marque d'un produit comme une entité entière mais comme une simple chaine de caractères). En effet le même code que pour les catégories serait utilisé.

Utilisateurs // Users

| id  | name    | username | password | email |
| --- |:-------:| -------- | -------- | ----- |

Clients // Client

| #user_id | client_num | is_premium |
| -------- | ---------- | ---------- |

> Les entités Client et Employé seront des héritages gérés avec Doctrine : [Single Table Inheritance](https://www.doctrine-project.org/projects/doctrine-orm/en/2.9/reference/inheritance-mapping.html#single-table-inheritance)

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

| id  | value  | #attribute_id |
| --- |:------:| ------------- |

Produits_Valeurs // AttributeValues_Products

| #product_id | #attributevalue_id |
| ----------- | ------------------ |
> Table intermédiaire qui lie les valeurs d'attributs et les produits

Commandes // Orders

| id  | #user_id | order_date |
| --- | -------- | ---- |

Commandes_Produits // Order_Products  
> Permet de stocker les informations de prix et de quantité d'une commande sur le long terme, même si les prix changent.

| id  | #order_id | #product_id | qty | price |
| --- | --------- | ----------- | --- | ----- |


---

Étape 1
------
### Créer les entités 

On crée les entités décrites plus haut grâce à Doctrine (à la main parce que si on pouvait utiliser le makerbundle ce serait trop facile)
Pour 'Produits', 'Catégories', 'Attributs', 'Valeurs', 'Commandes' et 'Commandes_Produits', il suffit de créer leurs classes respectives avec les bonnes annotations DocBlock.

On renseigne les metadata de chaque champ avec @ORM\Column(type="...", nullable=true,...).

Pour travailler avec les clés étrangères, on utilise le mapping associatif de Doctrine.
Si un produit a une catégorie, on utilisera une relation OneToOne et on écrira :

````php
class Product
{
    /**
     * Unidirectionnel
     *
     * @ManyToOne(targetEntity="Category")
     */
    protected $category;
}
````
> Référence Mapping Associatif de Doctrine : [Association Mapping] (https://www.doctrine-project.org/projects/doctrine-orm/en/2.9/reference/association-mapping.html)

On crée les setters et getters

````php
    /**
     * @param Category $category
     *
     * @return void
     */
    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }
````

Et on réitère l'opération pour chaque association :
#### Entity/Order.php
-> $client | OneToOne
-> $products | ManyToMany

-> setClient() & getClient()
-> addProduct() & removeProduct()

#### Entity/Product.php
-> $category | ManyToOne
-> $values (fait référence aux valeurs d'attributs) | ManyToMany

-> getCategory() & setCategory()
-> getValues() & addValue() & removeValue()

#### Entity/User.php
-> $orders | OneToMany

-> getOrders() & addOrder() & removeOrder()

#### Entity/Category.php
-> $products | OneToMany

-> getProducts() & addProduct() & removeProduct()

#### Entity/Attribute.php
-> $attributeValues | OneToMany

-> getAttributeValues() & addAttributeValue() & removeAttributeValue()

#### Entity/AttributeValue.php
-> $attribute | ManyToOne
-> $products | ManyToMany

-> getAttribute() & setAttribute()
-> getProducts() & addProduct() & removeProduct()

Pour certaines entités, il est nécessaire d'initialiser des ArrayCollections dans le constructeur de l'entité.






