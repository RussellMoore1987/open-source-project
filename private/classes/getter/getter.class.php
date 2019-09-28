<?php
    class Getter {

        public function request(array $request = []) {
            // we should have it we checked work before this point in time
            $method = $request['method'] ?? "";
            $data = $request['data'] ?? "";
            // make call to method
            $requestInfo = $this->$method($data);

            // return request info
            return $requestInfo;
        }

        // @ custom getters below this point
            public function get_all_users($data = []) {
                // make call
                $requestInfo = User::find_all();

                // return request info
                return $requestInfo;
            }

            public function get_users_paginated($data = []) {
                // get data points
                $page = (int) $data['page'] ?? 1;
                $perPage = (int) $data['perPage'] ?? 10;
                // get offset
                $offset = ($page - 1) * $perPage;

                $sqlOptions['columnOptions'] = ['id', 'emailAddress', 'firstName', 'imageName', 'lastName', 'mediaContentId', 'phoneNumber', 'title'];
                $sqlOptions['whereOptions'] = ["showOnWeb = 1"];
                $sqlOptions['sortingOptions'] = ["LIMIT {$perPage} OFFSET {$offset}"];

                // make call
                $requestInfo['content'] = User::find_where($sqlOptions);
                // data sent 
                $requestInfo['sentData'] = $data;

                // return request info
                return $requestInfo;
            }

            public function get_users_sql_paginated($data = []) {
                 // set defaults
                 $requestInfo['errors'] = [];

                // get data points
                $page = (int) $data['page'] ?? 1;
                $perPage = (int) $data['perPage'] ?? 10;

                // this validation for page and perPage
                $validation_page = [
                    'name' => "page",
                    'required' => 'yes',
                    'type' => 'int', // type of int
                    'num_min'=> 1 // min number
                ];
                $validation_perPage = [
                    'name' => "perPage",
                    'required' => 'yes',
                    'type' => 'int', // type of int
                    'num_min'=> 1 // min number
                ];

                // reset validation array
                $validationError_array = [];
                // Validate the value
                $validationError_array = val_validation($page, $validation_page, 'parameter');
                if (($validationError_array)) {
                    // loop over errors and put them in the right spot
                    foreach ($validationError_array as $value) {
                        $requestInfo['errors'][] = $value;
                    }
                }

                // reset validation array
                $validationError_array = [];
                // Validate the value
                $validationError_array = val_validation($perPage, $validation_perPage, 'parameter');
                if (($validationError_array)) {
                    // loop over errors and put them in the right spot
                    foreach ($validationError_array as $value) {
                        $requestInfo['errors'][] = $value;
                    }
                }

                // check for errors
                if (!$requestInfo['errors']) {
                    // get offset
                    $offset = ($page - 1) * $perPage;
    
                    $sqlOptions['columnOptions'] = ['id', 'emailAddress', 'firstName', 'imageName', 'lastName', 'mediaContentId', 'phoneNumber', 'title'];
                    $sqlOptions['whereOptions'] = ["showOnWeb = 1"];
                    $sqlOptions['sortingOptions'] = ["LIMIT {$perPage} OFFSET {$offset}"];
    
                    // make call
                    $result = User::run_sql("
                        SELECT id, emailAddress, firstName, imageName, lastName, mediaContentId, phoneNumber, title
                        FROM users
                        WHERE showOnWeb = 1
                        LIMIT {$perPage} OFFSET {$offset}
                    ");
                    // loop through query
                    while ($record = $result->fetch_assoc()) {
                        $requestInfo['content'][] = $record;    
                    }
                }

                // data sent 
                $requestInfo['sentData'] = $data;

                // return request info
                return $requestInfo;
            }
        // @ custom getters above this point
    }

?>