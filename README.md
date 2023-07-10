# API Spec

## Authentication

All API must use this authentication

Request :
- Header :
    - token : "your secret api key with JWT"

## Register

Request :
- Method : POST
- Endpoint : `/api/registerAdmin`
- Header :
    - Content-Type: application/json
    - Accept: application/json
- Body :

```json 
{
    "name" : "string",
    "email" : "string, unique",
    "password" : "string",
    "token" : "text"
}
```

Response :

```json
{
    "status": "success",
    "message": "successfully save data."
}
"status": "fail",
    "message": {
        "email": [
            "The email has already been taken."
        ]
    }
```

## LOGIN

Request :
- Method : post
- Endpoint : `/api/authenticatedAdmin`
- Header :
    - Accept: application/json
    - body
       {
            'email': yusrilmahendri@gmail.com
            'password': 12345678
       }
Response :

```json 
{
    "status": "success",
    "message": "success authenticated.",
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHRpbWUiOjE2ODg5OTE4MDAsImlkIjoxfQ.drj4yZmXNJS-mAv5BiNWwlnQDHqDF-9eV4lU8xOlOfA"
}
```

## Delete Admin

Request :
- Method : POST
- Endpoint : `api/admin-destroy`
- Header :
    - Content-Type: application/json
    - Accept: application/json
- Body :

```json 
{
    "id" : "integer",
    "token" : "string",
}
```

Response :

```json 
{
    "status": "success",
    "message": "data successfully to delete."
}
```

## List Admin

Request :
- Method : post
- Endpoint : `/api/admins`
- Header :
    - Accept: application/json
Response :

```json 
{
    "code" : "number",
    "status" : "string",
    "data" : [
        {
             "id" : "string, unique",
             "name" : "string",
             "email" : "string, enique",
             "password" : "string",
             "createdAt" : "date",
             "updatedAt" : "date"
        },
    ]
}
```

## List Content

Request :
- Method : POST
- Endpoint : `/api/contents`
- Header :
    - Accept: application/json
- Query Param :
    - size : numbe,
    - page : number

Response :

```json 
 "data": [
        {
            "title": "naruto vs madara",
            "description": "narutoa vs madara episode lanjutan",
            "link_thumbnail": "https://havecamerawilltravel.com/youtube-thumbnail-size/",
            "link_video": "https://youtu.be/DmD7QAxY9zg",
            "status": 1,
            "view": 0,
            "store_at": "37 minutes ago"
        }
    ],
    "meta": {
        "total_content": 1,
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
                "url": "http://127.0.0.1:8000/api/contents?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http://127.0.0.1:8000/api/contents",
        "per_page": 6,
        "to": 1,
        "total": 1
    },
    "links": {
        "first": "http://127.0.0.1:8000/api/contents?page=1",
        "last": "http://127.0.0.1:8000/api/contents?page=1",
        "prev": null,
        "next": null
    }
```

## Delete content

Request :
- Method : POST
- Endpoint : `/content-destroy`
- Header :
    - Accept: application/json
    - body: token

Response :

```json 
{
    "code" : "number",
    "status" : "string"
}
```
