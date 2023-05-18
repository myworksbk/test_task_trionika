# test task trionika

## Install
#### Prepare the php8.1, mysql8 and elasticsearch8!
#### Configure .env file to connect a database!
#### Launch the next commands:
Composer dependencies install:
```
composer install
```

Run DB migrations:
```
php artisan migrate
```
Clear the index of elasticsearch:
```
curl -X DELETE "http://localhost:9200/orders"
```

Run DB seeds:
```
php artisan db:seed
```

Run the php server:
```
php artisan serve
```

Launch Postman or any REST API supportable app. 
With the Postman application on your computer.

Set the Request Method and URL:

    Set the request method to POST.
    Enter the URL of your Laravel API endpoint for creating orders. For example, if your API is hosted locally, the URL might be http://localhost:8000/api/orders.

Set the Request Headers (if required):

    If your API requires specific headers, such as authorization headers or content type headers, set them accordingly. Refer to your API documentation for any required headers.

Set the Request Body:

    Select the "Body" tab in Postman.
    Choose the "Raw" option.
    Set the body format to JSON (application/json).
    Provide the necessary data for creating an order in JSON format. For example:

### Login Request
Open the database using config from the env file in the project root dir.
Chouse any email field from `users` table. For intance: german03@example.net
All users have the same password `password`. Request get type the next url:
`http://localhost:8000/login` with params:
```json
{
    "email": "german03@example.net",
    "password": "password"
}
```
then copy `token` response like: `1|7VRJXgj3lVyY1YJ4sIkMOGBbsyADWuJtOtNbdE2y`.

### Get Orders Request
Open GET type `http://localhost:8000/api/orders` url with Bearer token header from login response.
You may see something like this:
```json
{
    "data": [
        {
            "product_name": "Omari Nolan",
            "total_price": 743,
            "total_quantity": 13
        },
        {
            "product_name": "Jeramie Lebsack",
            "total_price": 313.010009765625,
            "total_quantity": 3
        }
    ],
    "links": {
        "first": "http://localhost:8000/api/orders?page=1",
        "last": "http://localhost:8000/api/orders?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://localhost:8000/api/orders?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http://localhost:8000/api/orders",
        "per_page": 50,
        "to": 10,
        "total": 10
    }
}
```

### Create Order Request
Open POST type `http://localhost:8000/api/orders` url with Bearer token header from login response.
Pass the next request params:
```json
{
  "product_id": 1,
  "price": 100.1,
  "quantity": 5,
  "status": "pending"
}
```

### Create Product Request
Open POST type `http://localhost:8000/api/products` url with Bearer token header from login response.
Pass the next request params:
```json
{
  "name": "Product Name",
  "description": "Product Description",
  "price": 200.99,
  "discount": 100.50
}
```