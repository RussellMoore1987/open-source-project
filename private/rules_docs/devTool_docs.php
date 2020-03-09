<?php 
    // @ seeder
        // # DB seeder
            // ? a test document can be found in root/private/reference_code/seederTest.php
                // this code will need to be put into the view folder, in the public directory to be seen
            // The database seeder is a tool used to populate databases for testing purposes
                // It can also be used for populating websites with fake information
            // create a new seeder in this fashion
                // $Seeder = new Seeder();
            // helper functions
                // * max char
                    // this function allows you to specify particular character length with the option of an ending character
                    // $Seeder->max_char($Seeder->sentence(), 75, "!")
                        // Amet id lobortis consectetur metus eleifend Sed sapien libero nisi!
                    // $Seeder->max_char(string $string, int $max=25, string $ending="")
                    // this helper function does not work well with Russian characters (words, sentences, paragraphs in Russian)
            // right now we have seeders for:
            // ? all seeder code is located in root/private/classes/seeder/
                // * address
                    // $Seeder->address() to get address
                        // 19 N. Arrowhead Street Navarre, FL 32566
                    // $Seeder->address('part') to get partial address, it removes the comma, city, and ZIP Code
                        // 19 N. Arrowhead Street Navarre
                // * city
                    // $Seeder->city() to get a us city
                        // Maryville
                // * date
                    // $Seeder->date()
                        // $Seeder->date() retrieves a date between the specified dates, defaults are shown below
                        // $Seeder->date(string $min='1970', string $max='now', string $dateFormat='m-d-Y')
                            // $Seeder->date("12/1/2019", "12/31/2019")
                                // 12-09-2019
                            // $Seeder->date('1/1/2019', '12/31/2020','F j, Y, g:i a')
                                // October 17, 2020, 2:33 am
                            // date format follows the PHP documentation // ? https://www.php.net/manual/en/function.date.php
                // * email
                    // $Seeder->email() to get an email
                        // ndnhqvjpinrx9quao5rfbsp_1@yahoo.com
                    // $Seeder->email("{$Seeder->first_name()}{$Seeder->last_name()}") to get an email with the specified string
                        // KelseyBrown_2@gmail.com
                // * first name
                    // $Seeder->first_name() to get first name
                        // Kacey 
                    // $Seeder->first_name() . " " . $Seeder->last_name()
                        // Kacey Walker
                // * id
                    // $Seeder->id() this starts an ID count from one to the life of the object, 1 to ... 
                        // $Seeder->id(), example for each time id is used
                            // 1
                            // 2
                            // 3
                    // if you wish to start the from a higher id just specify it in the function $Seeder->id(555)
                        // $Seeder->id(555), example for each time id is used
                            // 555
                            // 556
                            // 557
                // * job title
                    // $Seeder->job_title() to get a job title, searches through all available job titles
                        // Salon Manager
                    // $Seeder->job_title(['webDevelopment', 'db']) to get job titles only from these categories
                        // Data Architect 
                        // ? all job title categories are listed in the job title trait for the seeder, in the $arrayOfJobTitles array
                            // ? located at root/private/classes/seeder/seederJobTitle.trait.php
                    // $Seeder->job_title(['notValid']) = $Seeder->job_title() 
                    // this is because there is no array of job titles called notValid
                        // Help Desk 
                    // ? options is a great substitute feeder for this one if you desire only specific job titles
                // * last name
                    // $Seeder->last_name() to get last name
                        // Walker 
                    // $Seeder->first_name() . " " . $Seeder->last_name()
                        // Kacey Walker
                // * options
                    // $Seeder->options(['M', 'F']) to get an option
                        // M
                    // $Seeder->options(['small', 'medium', 'large', 'x-large'])
                        // large
                    // $Seeder->options(['Developer', 'UX Designer', 'Designer', 'Graphic Designer', 'Layout Specialist'])
                        // UX Designer
                // * phone number
                    // $Seeder->phone_number() to get a number, "-" is the default separator
                        // 886-830-1262
                    // $Seeder->phone_number(' ')
                        // 886 830 1262
                    // $Seeder->phone_number('.')
                        // 886.830.1262 
                    // $Seeder->phone_number('') 
                        // 8868301262 
                // * words, sentences, paragraphs in Russian
                    // all of the following below except they are in Russian, not Latin
                        // $Seeder->word_russian() 
                        // $Seeder->words_russian(3) 
                        // $Seeder->sentence_russian() 
                        // $Seeder->sentences_russian(5) 
                        // $Seeder->paragraph_russian() 
                        // $Seeder->paragraphs_russian(2) 
                // * words, sentences, paragraphs
                    // $Seeder->word() to get a word
                    // $Seeder->words() to get words
                        // default count for words is 1, to specify more add a numeric to the function, $Seeder->words(3) for 3 words
                    // $Seeder->sentence() to get a sentence
                        // the beginning of all sentences are uppercase and end with a period 
                        // sentences are compiled by 5 to 25 words, per sentence
                    // $Seeder->sentences() to get sentences
                        // default count for sentences is 1, to specify more add a numeric to the function, $Seeder->sentences(3) for 3 sentences
                    // $Seeder->paragraph() to get a paragraph
                        // paragraphs are compiled by 2 to 25 sentences, per paragraph
                    // $Seeder->paragraphs() to get paragraphs
                        // default count for paragraphs is 1, to specify more add a numeric to the function, $Seeder->paragraphs(3) for 3 paragraphs
                        // if separation between the paragraphs is desired you will need to put some type of the separator
                            // $Seeder->paragraphs(3, "<br><br>")
                // * state
                    // $Seeder->state() to get a us state abbreviation
                        // UT
                    // $Seeder->state("name") to get a us state name
                        // Utah
                // * username
                    // $Seeder->username() to get a username
                        // cookiekite3
                // * zip code
                    // $Seeder->zip() 
                        // 54194
        // # use DB seeder
            // often the seeder will be used either inside the class or in an external trait of the class that extends the database object. The main premise is to supply the database with the needed information to populate a record. this will look different for every table and will be based on the table constraints. In this particular example I point out some main features.
                // $seederDefaultRecordCount by default is set to 10 records. By putting this $seederDefaultRecordCount in a class or in a trait connected to the class you can adjust the default number of records that are created each time you send a request.
                // to set up seeder information create a function called seeder_setter(object $Seeder){} this function will be passed the seeder object. It must be named seeder_setter(object $Seeder). The information that needs to be returned is an array of data comparable to what you would pass in to the class to have it create a new record. or in other words provide an associative array that would create a new record based off of the class structure system that we use to update the public records. This documentation assumes that you use an active record model which means that the keys of the associative array should be named the same as the table column names and provided the values accordingly.
            // example below
                // <?php
                //     trait UserSeeder {

                //         // get seeder default record count
                //         static public $seederDefaultRecordCount = 42;

                //         // sql feeder
                //         static public function seeder_setter(object $Seeder) {
                //             // setting some variables
                //             $fName = $Seeder->first_name();
                //             $lName = $Seeder->last_name();
                //             // build array
                //             $seederInfo = [
                //                 'address' => $Seeder->address(),
                //                 'adminNote' =>  $Seeder->max_char($Seeder->sentences(rand(0,3)), 255, "."),
                //                 'createdBy' => rand(1,20),
                //                 'createdDate' => $Seeder->date($min='1/01/19' , $max='1/01/20'),
                //                 'emailAddress' => $Seeder->email("{$fName}{$lName}"),
                //                 'firstName' => $fName,
                //                 'lastName' => $lName,
                //                 'mediaContentId' => 0,
                //                 'note' => $Seeder->max_char($Seeder->sentences(rand(1,3)), 255, "."),
                //                 'password' => "Sdkvldsg$#@!%$!!!",
                //                 'phoneNumber' => $Seeder->phone_number(),
                //                 'showOnWeb' => rand(0,1),
                //                 'title' => remove_char_from_str(['.','/','\\'], $Seeder->job_title()),
                //                 'username' => $Seeder->username()
                //             ];
                            
                //             // return data
                //             return $seederInfo;
                //         }
                //     }
                // ?> 
?>