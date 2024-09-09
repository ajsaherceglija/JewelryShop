# Online Jewelry Shop 'Zlatara Karalić'
This project represents a responsive jewelry store website made for 'Zlatara Karalić'. The
essential features for users include a dynamic product gallery, ability to rate and review
products, store liked items to their wishlist, place orders and make personal profiles.
The platform provides administrators with a wide range of tools to manage user accounts,
categories, brands and products. It makes order administration easier, keeps track of products
that are running low in stock and provides a visual chart for average product ratings.
This website utilizes strong administrative tools with user-friendly features to improve the
store's performance and customer satisfaction.
## The Project Includes
- Browsing products
- Search functionality 
- User registration and login
- Profile management
- Manage wishlist
- Manage cart
- Checkout
- Product management
- Order management system
## Database Implementation
The database was carefully designed with a top-down approach, starting with identifying the
essential components and tables before defining relationships, keys, and attributes.

### Business rules that have been identified:
- Each product belongs to one category and each category can have many products
- Each product belongs to one brand and each brand can have many products
- Each product can have many reviews and each review belongs to only one product
- Each person can have many products in a wishlist and each product can be in wishlists
of various people
- Each person can have many addresses and each address belongs to one person
- Each order has one address to be shipped to and each address can be related to many
orders
- Each order can have many products and each product can be in many orders

## Cloud
The Jewelry Shop Karalić project was deployed on an Ubuntu instance created on Oracle Cloud. 
Utilizing Infrastructure-as-aService, a physical design of the platform was designed.

http://130.61.95.88/home_page.php

## Credentials
### Admin
Username: eminakaralic

Password: emina
### User
Username: ajsaherceglija

Password: ajsa
