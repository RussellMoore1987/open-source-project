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
            "data": {
                "password": "fklsdanf!mkcxvk"
            },
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
        }
    }

    devTool = {
        "login": {
            "type": "devTool",
            "method": "devTool_login",
            "data": {
                "username": "test",
                "password": "Test@the9" 
            }
        },
        "tables": {
            "type": "devTool",
            "method": "devTool_get_all_class_tables",
            "data": ""
        },
        "tableRecords": {
            "type": "devTool",
            "method": "devTool_get_table_records",
            "data": {
                "table": "",
                "page": 1,
                "perPage": 20
            }
        },
        "singleRecord": {
            "type": "devTool",
            "method": "devTool_find_record",
            "data": {
                "table": "",
                "id": 2
            }
        },
        "tables2": {
            "devTool::devTool_get_all_class_tables": {"data":""}
        },
        "tables3": {
            "devTool::devTool_get_all_class_tables": ""
        },
        "tables4": "devTool::devTool_get_all_class_tables",
        "login2": {
            "devTool::devTool_login": {
                "data": {
                    "username": "test",
                    "password": "Test@the9" 
                }
            }
        }
    }

    // newer
    devTool = {
        "tables": {
            "devTool::devTool_get_all_class_tables": {"data":""}
        },
        "tables2": {
            "devTool::devTool_get_all_class_tables": ""
        },
        "tables3": "devTool::devTool_get_all_class_tables",
        "login": {
            "devTool::devTool_login": {
                "data": {
                    "username": "test",
                    "password": "Test@the9" 
                }
            }
        }
    }





    // v2 ********************************************    
    // alias switch to PHP eventually
    // alias options
    a = [
        'offset',
        'orderedBy',
        'limit',
        'perPage',
        'page',
        'where'
    ]
    // alias request
    a = {
        'images': {
            'request': 'mediaContent', // * required, specify real request
            'where': 'type::PNG,JPEG,JPG,GIF', // where request can be accumulative or add on further ones
            'parent': { // this is a way to specify functionality based off of the parent request, or what parent path they used
                'post': { // post parent options
                    'set': 'mainId::id',
                    'where': 'posts_to_media_content.postId::{{mainId}}',
                    'join': 'media_content.id::posts_to_media_content.mediaContentId',
                    'orderedBy': "sort,tile"
                },
                'user': { // user paired options
                    'set': 'mainId::mediaContentId',
                    'where': 'id::{{mainId}}'
                },
                'default': { // this runs if no other parent options are found
                    'error': 'The subcategory or sub selection of images only works on XYZ requests'
                }
            }
        },
        'image': {
            'request': 'mediaContent', // * required, specify real request
            'where': 'type::PNG,JPEG,JPG,GIF', // where request can be accumulative or add on further ones
            'parent': { 
                'post': { // post parent options
                    'set': 'mainId::id',
                    'where': 'posts_to_media_content.postId::{{mainId}}',
                    'join': 'media_content.id::posts_to_media_content.mediaContentId',
                    'orderedBy': "sort,tile",
                    'limit': 1
                },
                'user': { // user paired options
                    'set': 'mainId::mediaContentId',
                    'where': 'id::{{mainId}}'
                },
                'default': { // this runs if no other parent options are found
                    'error': 'The subcategory or sub selection of image only works on XYZ requests'
                }
            }
        },
        'users': {
            'request': 'user', // * required, specify real request
        }
    }


    // request
    a = {
        "authToken": "jdkfsdGDFHJ76436yhdbsd!#$%", // if using authentication, can be set here as well
        "login": {
            "type": "auth",
            "method": "token_login",
            "data": {
                "username": "test",
                "password": "Test@the9"
            },
            // "result": "setGlobal@authToken::result" // if you want to set your result and use it globally
        },
        "posts": {
            // "authToken": "{{authToken}}", // if using authentication
            "post": [ // call, class registered, method if using type, empty array means bring back everything
                "id",
                "title",
                "imageName",
                "status",
                "perPage::5",
                "page::2",
                // "set@postId::id",
                // [ // array means that it is sub query connection to be connected with the individual record
                //     "request@mediaContent",
                //     "match@{{postId}}::id",
                //     // "id::12,13,14", // another way to specify individual things
                //     "where@type::png,jpeg", // use validation to check value, and be able to set custom validation like rest API
                //     "id",
                //     "alt", 
                //     "name",
                //     "limit::3"
                // ]
                // or
                [
                    "request@images",
                    "id",
                    "alt", 
                    "name" 
                ]
            ]
        },
        "usersNew": {
            // just send "user" - gets all
            // just send "user(id::22)" - gets one
            "user(id::22, showOnWeb::1, perPage::5, page::2, set@mediaId::mediaContentId, setGlobal@userId::id)": [ // call, class registered, method if using type
                "id", 
                "emailAddress", 
                "firstName", 
                "imageName", 
                "lastName", 
                "mediaContentId", 
                "note", 
                "phoneNumber", 
                "title",
                {
                    "image(id::{{mediaId}}, limit::3)": [
                        "id",
                        "alt", 
                        "name"
                    ]
                }
            ]
        },
        "setUser": {
            // just send "user" - gets all
            // just send "user(id::22)" - gets one
            "user(id::22)": { // call, class registered, method if using type 
                "emailAddress": "Russell@gmail.com", 
                "firstName": "Russell", 
                "imageName": "fun.PNG", 
                "lastName": "Moore", 
                "mediaContentId": "{{mediaId}}", 
                "note": "new note", 
                "phoneNumber": "435-116-2677", 
                "title": "Ice cream truck driver",
                "image(id::{{mediaId}})": {
                    "alt": "new alt", 
                    "name": "fun.PNG"
                }
            }
        },
        "users": {
            // just send "user(id::22)" - gets all
            // options "user(id::22)", "user(id::22, where@showOnWeb::1, perPage::5, page::2, set@mediaId::mediaContentId, setGlobal@userId::id)"
            "user": [ // call, class registered, method if using type
                "id", 
                "emailAddress", 
                "firstName", 
                "imageName", 
                "lastName", 
                "mediaContentId", 
                "note", 
                "phoneNumber", 
                "title",
                "where@showOnWeb::1",
                "perPage::5",
                "page::2",
                "set@mediaId::mediaContentId", // makes an associative array with variables
                "setGlobal@userId::id", // makes an associative array with global variables
                [ // array means that it is sub query connection to be connected with the individual record
                    "request@image", // alias for mediaContent that will select only one image 
                    "match@{{mediaId}}::id",
                    // "id::12,13,14", // another way to specify individual things
                    "where@type::png,jpeg", // use validation to check value, and be able to set custom validation like rest API
                    "id",
                    "alt", 
                    "name",
                    "limit::3"
                ]
            ]
        },
        "allUsers": {
            "type": "getter", // * optional, register by default
            "method": "get_users_paginated",
            "data": {
                "page": 2,
                "perPage": 5
            }
        },
    }

    // default options
    a = [
        "offset",
        "orderedBy",
        "limit",
        "perPage",
        "page",
        "where"
    ]

    a = [
        "type, (class)", // * optional
        "className, (class registered)", // call, class registered, method if using type
        "data", // gets passed in to method
        "controller", // ???
        "method" // * optional
    ]

    // options on requests
        // columns
        // preset options
            // offset
            // ordered by
            // limit
            // like = user(like@lastName::mit) 
            // in = user(in@id::1,2,3,4,77,99,197)
            // per page
            // page
            // where = user(id::22)
            // set variable
            // set global variable
        // aliases
        // option aliases = image(size::large) = returns large image path or url or request ??? I like it, the possibility to add properties on the fly as well???, also enable you to pass parameters through filters like truncating sentences or removing HTML, api user set there own properties, ability to utilize inset PHP functions and option aliases

    // on all requests
        // properties accepted
        // properties rejected
        // errors
        // status 200 or 400 B stock of whether or not it was successful
    
    // like
        // Access for developers
        // access based off of session and/or token
        // access for the public
        // RPC APIs functionality = class/method
        // *** good documentation, for public, for developers
        // Restrictions based off of session, token, public