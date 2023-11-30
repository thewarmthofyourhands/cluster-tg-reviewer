Json Schema helper

types: null, string, integer, number, boolean, object, array
property description: properties, patternProperties
attributes: type, [format, pattern, enum, default], [minimal, minLength], description, example
oneOf, anyOf, not

patternProperties example:
````
"patternProperties": {
    "^[a-zA-Z]+$": {
      "type": "string"
    }
}
````

Example:
````
{
  "properties": {
    "nickname": {
      "type": "string",
      "minLength": 3,
      "pattern": "^[a-zA-Z_-0-9]+$",
      "description": "Nickname",
      "example": "John_124"
    },
    "email": {
      "type": "string",
      "format": "email",
      "description": "Email",
      "example": "test@test.com"
    },
    "id": {
      "type": "integer",
      "description": "id",
      "minimal": 1,
      "example": 6023341
    },
    "role": {
      "type": "string",
      "enum": ["admin", "client"],
      "default": "client",
      "description": "Role of user",
      "example": "admin"
    },
    "projectId": {
      "oneOf": [
        {
          "type": "null"
        },
        {
          "type": "integer",
          "minimum": 1
        }
      ],
      "description": "Project id",
      "example": 421
    },
    "friendIdList": {
        "type": "array",
        "minItems": 1,
        "uniqueItems": true,
        "items": {
            "type": "integer"
        },
      "description": "List of friend id",
      "example": [6431412, 12412451, 634633]
    }
  },
  "required": [
    "id",
    "nickname",
    "email",
    "role",
    "projectId",
    "friendIdList"
  ],
  "additionalProperties": false
}
````
