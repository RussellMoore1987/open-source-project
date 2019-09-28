// @ internal API documentation
    // ? looking for a place to put system/Corporation specific documentation, the best place is here

    // # general documentation
        // a = {...} is just there so no errors will show up in the code, you just need the {...}
        a = {
            "allUsers": {
                // type options are getter, setter, makeRequest, registeredClass 
                "type": "getter", // * required    
                "method": "get_all_users", // * required
                "data": "", // * required, what ever type of data the method is looking for, it can be a blank string
                // the request can contain extra parameters according to the developers desires
                // ! password is required if you are trying to access make request directly
                "password": "fklsdanf!mkcxvk@&*(4"
            },
        }
    
    // # specific documentation

    a = {
        "allUsers": {
            "type": "getter",
            "method": "get_all_users",
            "data": "",
            "password": "fklsdanf!mkcxvk"
        },
        "allUsers22": {
            "type": "getter",
            "method": "get_users_paginated",
            "data": {
                "page": 2,
                "perPage": 5
            }
        },
        "sqlUserData": {
            "type": "getter",
            "method": "get_users_sql_paginated",
            "data": {
                "page": 2,
                "perPage": 5
            }
        },
        "users": {
            "type": "registeredClass",
            "method": "find_where",
            "options": {

            },
            "data": {
                "page": 2,
                "perPage": 5
            }
        }
    }

    a= {"allUsers": {"type": "getter","method": "get_all_users","data": "","password": "fklsdanf!mkcxvk"}, "allUsers22": {"type": "getter","method": "get_users_paginated","data": {"page": 2, "perPage": 5}},"sqlUserData": {"type": "getter","method": "get_users_sql_paginated","data": {"page": 2, "perPage": 5}}}