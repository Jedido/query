# QUERY API Documentation
This API allows you to query the database given and try passwords.

## Query
**Request Format:** query.php with POST parameters `SELECT`, `FROM`, `WHERE`, `SORTBY`, and `LIMIT`

**Request Type:** POST

**Returned Data Format**: JSON

**Description:** Queries data from the


**Example Request:** query.php with the following:
* `SELECT=*`
* `FROM=Names`
* `WHERE=true`
* `SORTBY=*`
* `LIMIT=3`

**Example Response:**
```json
{
    "rows": 3,
    "columns": ["name", "size"],
    "name": ["Hints", "Names", "Password"],
    "size": ["15", "4", "begingame"]
}
```

**Error Handling:**
If the query fails, the following text will be returned as a 400 error:
```
No values found.
```
If the query could not be performed, the following error will be sent:
```
Query failed.
```

## Submit Password
**Request Format:** query.php with POST parameter `password`

**Request Type**: POST

**Returned Data Format**: JSON

**Description:** Submits a password and receives a key response (to unlock queries).

**Example Request:** query.php with `password=begingame`

**Example Response:**
```json
{
    "message": "Welcome to the game. Unlocked key: FROM",
    "key": "FROM",
    "post": "from"
}
```

**Error Handling:**
If the password is invalid, the following 400 error is returned:
```
Not a password. Please try again.
```
