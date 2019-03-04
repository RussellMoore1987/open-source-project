<?php
    // @ API specific queries start
            // todo:
                // do regular sql then strip down for json
                // possibly store images after query
                // possibly function for extended data
                // possibly make these functions part of the database object

                // main data
                // media content
                // class spesific
                

            // get api data plus extended data
            // todo: gets all get_api_data() plus extended data images
            public function get_full_api_data() {
                return NULL;
            }
        
            // get api data
            public function get_basic_api_data() {
                $data_array = $this->attributes();
                // add full date and sort date
                $data_array['fullDate'] = $this->fullDate;
                $data_array['shortDate'] = $this->shortDate;
                // * fake data for now
                // todo: change catIds list to key = id, value = value, array, sort alphabetically for the real thing
                $data_array['categories'] = [
                    22 => 'car',
                    34 => 'other',
                    2 => 'handyman',
                    1 => 'Technology'
                ];
                // todo: change labelIds list to key = id, value = value, array, sort alphabetically for the real thing
                $data_array['labels'] = [
                    3 => 'big',
                    4 => 'small',
                    5 => 'soso',
                    89 => 'gogo'
                ];
                // todo: change tagIds list to key = id, value = value, array, sort alphabetically for the real thing
                $data_array['tags'] = [
                    9 => 'sam',
                    8 => 'bob',
                    5 => 'joe',
                    56 => 'jill'
                ];
                // todo: add image path thumbnail small medium large original if available
                $data_array['featuredImagePaths'] = [
                    'thumbnail' => 'https://i0.wp.com/mooredigitalsolutions.com/wp-content/uploads/2018/03/php_cms_featured_image.jpg?fit=1024%2C852&ssl=1',
                    'small' => 'https://i0.wp.com/mooredigitalsolutions.com/wp-content/uploads/2018/03/php_cms_featured_image.jpg?fit=1024%2C852&ssl=1',
                    'medium' => 'https://i0.wp.com/mooredigitalsolutions.com/wp-content/uploads/2018/03/php_cms_featured_image.jpg?fit=1024%2C852&ssl=1',
                    'large' => '',
                    'original' => 'https://i0.wp.com/mooredigitalsolutions.com/wp-content/uploads/2018/03/php_cms_featured_image.jpg?fit=1024%2C852&ssl=1'
                ];
                // return data
                return $data_array;
            }

            // get data and turn it into json
            public function get_api_data($type = 'basic') {
                // check to see which api data to use
                if ($type == 'basic') {
                    $data_array = $this->get_basic_api_data();
                } else {
                    $data_array = $this->get_full_api_data();
                }
                
                // turn array into Jason
                $jsonData_array = json_encode($data_array);

                // return data
                return $jsonData_array;
            }
        // @ API specific queries end
?>