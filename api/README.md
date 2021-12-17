# Cart-Shop API

## What does it do?
---
It handles an e-Commerce site's cart, including items.

## How do I use it?
---
First you need to create an account to get an API key, here's how:

```
    http://localhost/cart-shop/api/user

    with the following information in the body:
        email => "email@domain.ext"
        password => "password"

    The following JSON data will be returned, if successful:

        {
            "status": 200,
            "token": "API_KEY"
        }

    else, you will receive, caused by overlapping emails:

        {
            "status": 400,
            "message": "Invalid Request"
        }

```

Once your account is created you have the entire API at your disposal.

### Item
---
Items are the backbone of the system, as they are essential for the cart to function.

```
    POST - Create an item:
        http://localhost/cart-shop/api/item?key={API_KEY}

        Body:
        item_name => The item's name
        description => The item's description [Optional | null]
        price => The item's price [Optional | 0.00]
        picture => The path to the item's image [Optional | null]
        tag => Tags to categorize the item [Optional | null]
        stock => The amoutn of this item in stock [Optional | 0]

        Sample Response:

        {
            "status": 201,
            "message": "Item Created"
        }

    GET - Get item(s):
        http://localhost/cart-shop/api/item?key={API_KEY}

        Body: 
        Empty

        Sample Response:
        
        {
            "status": 200,
            "items": [
                {
                    "item_id": 0,
                    "item_name": "This is an Item",
                    "description": "This is the item's description",
                    "price": "3.99",
                    "picture": "https://cnkbucket.s3.amazonaws.com/8d5a1_itemimage.png?",
                    "tag": null,
                    "stock": "25"
                },
                ...
            ]
        }

        http://localhost/cart-shop/api/item/{id}?key={API_KEY}

        Body: 
        Empty

        Sample Response:

        {
            "status": 200,
            "item": {
                "item_id": 0,
                "item_name": "An Alternative Item",
                "description": "An item that is being sold in an alternative shop.",
                "price": "8.75",
                "picture": null,
                "tag": null,
                "stock": "35"
            }
        }   
```