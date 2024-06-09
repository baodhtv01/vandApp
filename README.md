<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<h3 align="center">VandApp - Laravel 11</h3>
<h4>Run Project</h4>
note: file .env đính kèm trong email 

```
docker-compose up -d
docker-compose exec workspace composer install
docker-compose exec workspace php artisan migrate
docker-compose exec workspace php artisan db:seed
```

<h4>API Project</h4>
Postman Collection: <a href="https://www.postman.com/restless-astronaut-316794/workspace/vandapp/collection/17857152-a9d889d1-25ec-48e3-ba3a-c2027ba35c89?action=share&creator=17857152" target="_blank">Link</a> 
Authentication:
- Register
```
POST /api/register
```
- Login
```
POST /api/login
```
- Logout
```
POST /api/logout
```

Store:
- List All Store Of User
```
GET /api/stores
```
- Detail Store
```
GET /api/stores/{store_id}
```
- Create Store
```
POST /api/stores
```
- Update Store
```
PUT /api/stores/{store_id}
```
- Delete Store
```
DELETE /api/stores/{store_id}
```

Product:
- List All Product of User
```
GET /api/products
```
- Detail Product
```
GET /api/products/{product_id}
```
- Create Product
```
POST /api/products
```
- Update Product
```
PUT /api/products/{product_id}
```
- Delete Product
```
DELETE /api/products/{product_id}
```
