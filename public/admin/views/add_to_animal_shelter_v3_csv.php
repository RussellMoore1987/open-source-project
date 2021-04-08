<?php
    // TODO: to csv
    // ? https://www.php.net/manual/en/function.fputcsv.php
    $filePath = 'C:\Users\truth\Desktop\\';
    echo "-- **** CSV files will be populated to the desktop *** <br><br>";

    // get the seeder
    $Seeder = new seeder();

    // check for duplicates 
    // $breeds = array_merge(
    //     $Seeder->animal_info('horse', 'breed', $getList='yes'), 
    //     $Seeder->animal_info('bird', 'breed', $getList='yes'),
    //     $Seeder->animal_info('dog', 'breed', $getList='yes'),
    //     $Seeder->animal_info('cat', 'breed', $getList='yes')
    // );

    // var_dump($breeds);

    // var_dump(array_diff_assoc($breeds, array_unique($breeds)));
    
    // # TypeOfAnimals start
        echo "--TypeOfAnimals <br>";
        $typeOfAnimals = ['Horse', 'Bird', 'Dog', 'Cat'];
        for ($i=0; $i < count($typeOfAnimals); $i++) {
            $type_of_animal =  $typeOfAnimals[$i];
            echo "
                INSERT INTO TypeOfAnimals (type_of_animal) VALUES ('{$type_of_animal}');
            ";
        }
        echo "<br><br>";

        // send to csv
        $infoToCsv = [];
        $fp = fopen($filePath.'TypeOfAnimals.csv', 'w');
        $infoToCsv = $typeOfAnimals;
        array_unshift($infoToCsv, 'type_of_animal');
        foreach ($infoToCsv as $row) {
            fputcsv($fp, [$row]);
        }
        fclose($fp);
    // # TypeOfAnimals end

    // # Staff start
        echo "--Staff <br>";
        // @ num adjust
        $staffCount = 250; // 200 
        for ($i=0; $i < $staffCount; $i++) {
            $staff_first_name = replace_char_in_str(["'"], ["''"], $Seeder->first_name());
            $staff_last_name = replace_char_in_str(["'"], ["''"], $Seeder->last_name());
            $staff_address = $Seeder->address('part');
            $staff_state = $Seeder->state();
            $staff_city = $Seeder->city();
            $staff_zip = $Seeder->zip();
            $staff_address = $Seeder->address();
            $staff_phone_number = $Seeder->phone_number();
            $staff_email = $Seeder->email(strtolower("{$staff_first_name}{$staff_last_name}"));
            $staff_is_active = rand(0,100) > 60 ? 0 : 1;
            $staff_note = rand(0,100) > 50 ? replace_char_in_str(["'"], ["''"], $Seeder->max_char($Seeder->min_char($Seeder->sentences(rand(1,3)), 10), 500)) : '';
            $created_by = rand(1, $i + 1);
            $created_date = $Seeder->date($min='1998');
            // ' just helping the coloration of brackets
            if (rand(0, 100) > 60 || !$staff_is_active) {
                $updated_by = rand(1, $i + 1);
                $date = $Seeder->date($min=$created_date);
                $updated_date = "to_date('{$date}', 'YYYY-MM-DD')";
                $updated_by_for_array = $updated_by;
                $updated_date_for_array = $date;
            } else {
                $updated_by = 'NULL';
                $updated_date = 'NULL';
                $updated_by_for_array = NULL;
                $updated_date_for_array = NULL;
            }
            echo "
                INSERT INTO Staff (
                    staff_first_name,
                    staff_last_name,
                    staff_address,
                    staff_state,
                    staff_city,
                    staff_zip,
                    staff_phone_number,
                    staff_email,
                    staff_is_active,
                    staff_note,
                    created_by,
                    created_date,
                    updated_by,
                    updated_date
                ) 
                VALUES (
                    '{$staff_first_name}',
                    '{$staff_last_name}',
                    '{$staff_address}',
                    '{$staff_state}',
                    '{$staff_city}',
                    {$staff_zip},
                    '{$staff_phone_number}',
                    '{$staff_email}',
                    {$staff_is_active},
                    '{$staff_note}',
                    {$created_by},
                    to_date('{$created_date}', 'YYYY-MM-DD'),
                    {$updated_by},
                    {$updated_date}
                );
            ";
            // put in to array for csv
            $staff[] = [$staff_first_name, $staff_last_name, $staff_address, $staff_state, $staff_city, $staff_zip, $staff_phone_number, $staff_email, $staff_is_active, $staff_note, $created_by, $created_date, $updated_by_for_array, $updated_date_for_array];
        }
        echo "<br><br>";

        // send to csv
        $infoToCsv = [];
        $fp = fopen($filePath.'Staff.csv', 'w');
        $infoToCsv = $staff;
        array_unshift(
            $infoToCsv, 
            ['staff_first_name', 'staff_last_name', 'staff_address', 'staff_state', 'staff_city', 'staff_zip', 'staff_phone_number', 'staff_email', 'staff_is_active', 'staff_note', 'created_by', 'created_date', 'updated_by', 'updated_date']
        );
        foreach ($infoToCsv as $row) {
            fputcsv($fp, $row);
        }
        fclose($fp);
    // # Staff end

    // # locations start
        echo "--locations <br>";
        $locationsNames = ['Mountain Creek Animal Shelter', 'Longer Creek Animal Shelter', 'Willow Flats', 'Richmond Animal Shelter', 'Shallow Creek Animal Shelter'];
        for ($i=0; $i < count($locationsNames); $i++) { 
            $location_name = $locationsNames[$i];
            $location_address = $Seeder->address('part');
            $location_state = $Seeder->state();
            $location_city = $Seeder->city();
            $location_zip = $Seeder->zip();
            $location_phone_number = $Seeder->phone_number();
            $location_email = $Seeder->email(strtolower(remove_char_from_str([' '], $locationsNames[$i])));
            $location_is_active = 1;
            $location_note = $Seeder->max_char($Seeder->min_char($Seeder->sentences(rand(1,3)), 10), 500);
            $created_by = rand(1, $staffCount);
            $created_date = $Seeder->date($min='1998');
            if (rand(0, 100) > 80 || !$location_is_active) {
                $updated_by = rand(1, $staffCount);
                $date = $Seeder->date($min=$created_date);
                $updated_date = "to_date('{$date}', 'YYYY-MM-DD')";
                $updated_by_for_array = $updated_by;
                $updated_date_for_array = $date;
            } else {
                $updated_by = 'NULL';
                $updated_date = 'NULL';
                $updated_by_for_array = NULL;
                $updated_date_for_array = NULL;
            }
            echo "
                INSERT INTO Locations (
                    location_name, 
                    location_address,
                    location_state,
                    location_city,
                    location_zip, 
                    location_phone_number, 
                    location_email, 
                    location_is_active, 
                    location_note, 
                    created_by,
                    created_date,
                    updated_by,
                    updated_date
                ) 
                VALUES (
                    '{$location_name}', 
                    '{$location_address}',
                    '{$location_state}',
                    '{$location_city}',
                    {$location_zip },
                    '{$location_phone_number}', 
                    '{$location_email}', 
                    {$location_is_active}, 
                    '{$location_note}',
                    {$created_by},
                    to_date('{$created_date}', 'YYYY-MM-DD'),
                    {$updated_by},
                    {$updated_date}
                );
            ";
            // put in to array for csv
            $locations[] = [$location_name, $location_address, $location_state, $location_city, $location_zip, $location_phone_number, $location_email, $location_is_active, $location_note, $created_by, $created_date, $updated_by_for_array, $updated_date_for_array];
        }
        echo "<br><br>";
        
        // send to csv
        $infoToCsv = [];
        $fp = fopen($filePath.'Locations.csv', 'w');
        $infoToCsv = $locations;
        array_unshift(
            $infoToCsv, 
            ['location_name', 'location_address', 'location_state', 'location_city', 'location_zip', 'location_phone_number', 'location_email', 'location_is_active', 'location_note', 'created_by', 'created_date', 'updated_by', 'updated_date']
        );
        foreach ($infoToCsv as $row) {
            fputcsv($fp, $row);
        }
        fclose($fp);
    // # locations end

    // # StaffAtLocation start
        echo "--StaffAtLocation <br>";
        for ($i=0; $i < $staffCount; $i++) { 
            $staff_id = $i + 1;
            $location_id = rand(1, count($locationsNames));
            echo "
                INSERT INTO StaffAtLocation (
                    staff_id, 
                    location_id
                ) 
                VALUES (
                    {$staff_id}, 
                    {$location_id}
                );
            ";
            // put in to array for csv
            $staffAtLocation[] = [$staff_id, $location_id];
        }
        echo "<br><br>";

        // send to csv
        $infoToCsv = [];
        $fp = fopen($filePath.'StaffAtLocation.csv', 'w');
        $infoToCsv = $staffAtLocation;
        array_unshift(
            $infoToCsv, 
            ['staff_id', 'location_id']
        );
        foreach ($infoToCsv as $row) {
            fputcsv($fp, $row);
        }
        fclose($fp);
    // # StaffAtLocation end

    // # Breeds start
        echo "--Breeds";
        // setting default variables
            // array for storing breeds and IDs
            $dbBreeds = [];
            // $breedIdConter = breed id in db
            $breedIdConter = 1; 
            $animalTypeId = 1;
        foreach ($typeOfAnimals as $animalType) {
            echo "<br><br>--Breeds - {$animalType} <br>";
            // lowercase the variable $animalType
            $animalType = strtolower($animalType);
            // check to see if we need to set a particular type of animal array, this information will be used later to help set and maintain consistency in breed IDs
            !isset($dbBreeds[$animalType]) ? NULL : $dbBreeds[$animalType] = [];
            // get array of breeds
            $breeds = $Seeder->animal_info($animalType, 'breed', $getList='yes');
            // insert breed into database as well as make a copy for maintaining consistency
            foreach ($breeds as $breedName) {
                $dbBreeds[$animalType][$breedName] = $breedIdConter;
                $breedName = replace_char_in_str(["'"], ["''"], $breedName);
                echo "
                    INSERT INTO Breeds (breed_name, type_of_animal_id) VALUES ('{$breedName}', {$animalTypeId});
                ";
                // put in to array for csv
                $breedsTemp[] = [$breedName, $animalTypeId];
                $breedIdConter++;
            }
            $animalTypeId++;
        }
        echo "<br><br>";

        // send to csv
        $infoToCsv = [];
        $fp = fopen($filePath.'Breeds.csv', 'w');
        $infoToCsv = $breedsTemp;
        array_unshift(
            $infoToCsv, 
            ['breed_name', 'type_of_animal_id']
        );
        foreach ($infoToCsv as $row) {
            fputcsv($fp, $row);
        }
        fclose($fp);
    // # Breeds end

    // # Adopters start
        echo "--Adopters <br>";
        // @ num adjust
        $adopterCount = 500; // 300 
        for ($i=0; $i < $adopterCount; $i++) {
            $adopters_first_name = replace_char_in_str(["'"], ["''"], $Seeder->first_name());
            $adopters_last_name = replace_char_in_str(["'"], ["''"], $Seeder->last_name());
            $adopters_address = $Seeder->address('part');
            $adopters_state = $Seeder->state();
            $adopters_city = $Seeder->city();
            $adopters_zip = $Seeder->zip();
            $adopters_phone_number = $Seeder->phone_number();
            $adopters_email = $Seeder->email(strtolower("{$adopters_first_name}{$adopters_last_name}"));
            $adopter_is_active = rand(0,100) > 90 ? 0 : 1;
            $adopters_note = rand(0,100) > 50 ? replace_char_in_str(["'"], ["''"], $Seeder->max_char($Seeder->min_char($Seeder->sentences(rand(1,3)), 10), 500)) : '';
            $created_by = rand(1, $staffCount);
            $created_date = $Seeder->date($min='1998');
            if (rand(0, 100) > 70  || !$adopter_is_active) {
                $updated_by = rand(1, $staffCount);
                $date = $Seeder->date($min=$created_date);
                $updated_date = "to_date('{$date}', 'YYYY-MM-DD')";
                $updated_by_for_array = $updated_by;
                $updated_date_for_array = $date;
            } else {
                $updated_by = 'NULL';
                $updated_date = 'NULL';
                $updated_by_for_array = NULL;
                $updated_date_for_array = NULL;
            }
            echo "
                INSERT INTO Adopters (
                    adopters_first_name,
                    adopters_last_name,
                    adopters_address,
                    adopters_state,
                    adopters_city,
                    adopters_zip,
                    adopters_phone_number,
                    adopters_email,
                    adopter_is_active,
                    adopters_note,
                    created_by,
                    created_date,
                    updated_by,
                    updated_date 
                ) 
                VALUES (
                    '{$adopters_first_name}',
                    '{$adopters_last_name}',
                    '{$adopters_address}',
                    '{$adopters_state}',
                    '{$adopters_city}',
                    {$adopters_zip},
                    '{$adopters_phone_number}',
                    '{$adopters_email}',
                    {$adopter_is_active},
                    '{$adopters_note}',
                    {$created_by},
                    to_date('{$created_date}', 'YYYY-MM-DD'),
                    {$updated_by},
                    {$updated_date}
                );
            ";
            // put in to array for csv
            $adopters[] = [$adopters_first_name, $adopters_last_name, $adopters_address, $adopters_state, $adopters_city, $adopters_zip, $adopters_phone_number, $adopters_email, $adopter_is_active, $adopters_note, $created_by, $created_date, $updated_by_for_array, $updated_date_for_array];
        }
        echo "<br><br>";

        // send to csv
        $infoToCsv = [];
        $fp = fopen($filePath.'Adopters.csv', 'w');
        $infoToCsv = $adopters;
        array_unshift(
            $infoToCsv, 
            ['adopters_first_name', 'adopters_last_name', 'adopters_address', 'adopters_state', 'adopters_city', 'adopters_zip', 'adopters_phone_number', 'adopters_email', 'adopter_is_active', 'adopters_note', 'created_by', 'created_date', 'updated_by', 'updated_date']
        );
        foreach ($infoToCsv as $row) {
            fputcsv($fp, $row);
        }
        fclose($fp);
    // # Adopters end

    // # Vaccinations start
        echo "--Vaccinations";
        // setting default variables
            // array for storing vaccinations and IDs
            $dbVaccinations = [];
            // $vaccinationIdConter = vaccination id in db
            $vaccinationIdConter = 1; 
            $type_of_animal_id = 1;
        foreach ($typeOfAnimals as $animalType) {
            echo "<br><br>--Vaccinations - {$animalType} <br>";
            // lowercase the variable $animalType
            $animalType = strtolower($animalType);
            // check to see if we need to set a particular type of animal array, this information will be used later to help set and maintain consistency in vaccination IDs
            !isset($dbVaccinations[$animalType]) ? NULL : $dbVaccinations[$animalType] = [];
            // get array of vaccinations
            $vaccinations = $Seeder->animal_info($animalType, 'vaccination', $getList='yes');
            // insert vaccinations into database as well as make a copy for maintaining consistency
            foreach ($vaccinations as $vaccinationName) {
                $vaccination_name = replace_char_in_str(["'"], ["''"], $vaccinationName);
                $vaccination_is_active = 1;
                $vaccination_note = replace_char_in_str(["'"], ["''"], $Seeder->max_char($Seeder->min_char($Seeder->sentences(rand(1,3)), 10), 500));
                $created_by = rand(1, $staffCount);
                $created_date = $Seeder->date($min='1998');
                if (rand(0, 100) > 80  || !$vaccination_is_active) {
                    $updated_by = rand(1, $staffCount);
                    $date = $Seeder->date($min=$created_date);
                    $updated_date = "to_date('{$date}', 'YYYY-MM-DD')";
                    $updated_by_for_array = $updated_by;
                    $updated_date_for_array = $date;
                } else {
                    $updated_by = 'NULL';
                    $updated_date = 'NULL';
                    $updated_by_for_array = NULL;
                    $updated_date_for_array = NULL;
                }
                echo "
                    INSERT INTO Vaccinations (
                        vaccination_name,
                        type_of_animal_id,
                        vaccination_is_active,
                        vaccination_note,
                        created_by,
                        created_date,
                        updated_by,
                        updated_date 
                    ) 
                    VALUES (
                        '{$vaccination_name}',
                        {$type_of_animal_id},
                        {$vaccination_is_active},
                        '{$vaccination_note}',
                        {$created_by},
                        to_date('{$created_date}', 'YYYY-MM-DD'),
                        {$updated_by},
                        {$updated_date}
                    );
                ";
                // put in to array for csv
                $vaccinationsTemp[] = [$vaccination_name, $type_of_animal_id, $vaccination_is_active, $vaccination_note, $created_by, $created_date, $updated_by_for_array, $updated_date_for_array];
                $dbVaccinations[$animalType][$vaccinationName] = $vaccinationIdConter;
                $vaccinationIdConter++;
            }
            $type_of_animal_id++;
        }
        echo "<br><br>";

        // send to csv
        $infoToCsv = [];
        $fp = fopen($filePath.'Vaccinations.csv', 'w');
        $infoToCsv = $vaccinationsTemp;
        array_unshift(
            $infoToCsv, 
            ['vaccination_name', 'type_of_animal_id', 'vaccination_is_active', 'vaccination_note', 'created_by', 'created_date', 'updated_by', 'updated_date']
        );
        foreach ($infoToCsv as $row) {
            fputcsv($fp, $row);
        }
        fclose($fp);
    // # Vaccinations end

    // # PetStatus start
        echo "--PetStatus <br>";
        $petStatus = ['Available', 'Adopted', 'Euthanized'];
        foreach ($petStatus as $status) {
            echo "
                INSERT INTO PetStatus (
                    pet_status
                ) 
                VALUES (
                    '{$status}'
                );
            ";
            // put in to array for csv
            $petStatusTemp[] = [$status];
        }
        echo "<br><br>";
        
        // send to csv
        $infoToCsv = [];
        $fp = fopen($filePath.'PetStatus.csv', 'w');
        $infoToCsv = $petStatusTemp;
        array_unshift(
            $infoToCsv, 
            ['pet_status']
        );
        foreach ($infoToCsv as $row) {
            fputcsv($fp, $row);
        }
        fclose($fp);
    // # PetStatus end
    
    // # AdoptablePets start
        echo "--AdoptablePets <br>";
        $adoptions = [];
        $vaccinatedAnimals = [];
        // @ num adjust
        $adoptable_pets_count = 3000; // 2000
        $extenderCounter = 0;
        for ($i=0; $i < $adoptable_pets_count; $i++) { 
            // determine what type of animal is
            $randNum = rand(0,100);
            if ($randNum >= 90) {
                $typeOfAnimal = 'horse'; // Horse, 1
                $typeOfAnimalWeight = [500, 2500]; // [min, max]
                $typeOfAnimalHeight = [48, 90]; // [min, max]
            } elseif ($randNum >= 80) {
                $typeOfAnimal = 'bird'; // Bird, 2
                $typeOfAnimalWeight = [0, 10]; // [min, max]
                $typeOfAnimalHeight = [3, 24]; // [min, max]
            } elseif ($randNum >= 40) {
                $typeOfAnimal = 'dog'; // Dog, 3
                $typeOfAnimalWeight = [10, 250]; // [min, max]
                $typeOfAnimalHeight = [3, 48]; // [min, max]
            } elseif ($randNum >= 0) {
                $typeOfAnimal = 'cat'; // Cat, 4
                $typeOfAnimalWeight = [5, 90]; // [min, max]
                $typeOfAnimalHeight = [3, 36]; // [min, max]
            }
            // get animal name
            $adoptable_pet_name = replace_char_in_str(["'"], ["''"], $Seeder->animal_info($typeOfAnimal)); 
            // get correct breed
            $breed_id = $dbBreeds[$typeOfAnimal][$Seeder->animal_info($typeOfAnimal, 'breed')]; 
            $weight = floatval(rand($typeOfAnimalWeight[0],$typeOfAnimalWeight[1]) . "." . rand(0,99));
            $height = floatval(rand($typeOfAnimalHeight[0],$typeOfAnimalHeight[1]) . "." . rand(0,99));
            $available_date = $Seeder->date($min='1998');
            $date1 = new DateTime('01/02/2020');
            $date2 = new DateTime($available_date);
            // determining pet_status_id
            $randNum = rand(0,100);
            if ($randNum >= 50 && $date2 > $date1) {
                $pet_status_id = rand(1,2);
            } else {
                $pet_status_id = rand(2,3);
            }
            $location_id = rand(1, count($locationsNames));
            $adoptable_pet_note = rand(0,100) > 50 ? replace_char_in_str(["'"], ["''"], $Seeder->max_char($Seeder->min_char($Seeder->sentences(rand(1,3)), 10), 500)) : '';
            $created_by = rand(1, $staffCount);
            $created_date = $Seeder->date($min='1998');
            if (rand(0, 100) > 70  || $pet_status_id > 1) {
                $updated_by = rand(1, $staffCount);
                $date = $Seeder->date($min=$created_date);
                $updated_date = "to_date('{$date}', 'YYYY-MM-DD')";
                $updated_by_for_array = $updated_by;
                $updated_date_for_array = $date;
            } else {
                $updated_by = 'NULL';
                $updated_date = 'NULL';
                $updated_by_for_array = NULL;
                $updated_date_for_array = NULL;
            }
            echo "
                INSERT INTO AdoptablePets (
                    adoptable_pet_name,
                    breed_id,
                    weight,
                    height,
                    available_date,
                    pet_status_id,
                    location_id,
                    adoptable_pet_note,
                    created_by,
                    created_date,
                    updated_by,
                    updated_date 
                ) 
                VALUES (
                    '{$adoptable_pet_name}',
                    {$breed_id},
                    {$weight},
                    {$height},
                    to_date('{$available_date}', 'YYYY-MM-DD'),
                    {$pet_status_id},
                    {$location_id},
                    '{$adoptable_pet_note}',
                    {$created_by},
                    to_date('{$created_date}', 'YYYY-MM-DD'),
                    {$updated_by},
                    {$updated_date}
                );
            ";
            // put in to array for csv
            $adoptablePets[] = [$adoptable_pet_name, $breed_id, $weight, $height, $available_date, $pet_status_id, $location_id, $adoptable_pet_note, $created_by, $created_date, $updated_by_for_array, $updated_date_for_array];
            $mark300 = $i % 300 == 0 && $i != 0 ? '<br><br>--300 More AdoptablePets<br>' : '';
            echo $mark300;

            // save adopted pets in array
            if ($pet_status_id == 2) {
                $adoption['id'] = $i + 1;
                $adoption['typeOfAnimal'] = $typeOfAnimal;
                $adoption['date'] = $created_date;
                $date = new DateTime($created_date);
                $date->modify('+8 month');
                $date = $date->format('Y-m-d');
                $adoption['date_max'] = $date;
                $adoption['location_id'] = $location_id ;

                $adoptions[] = $adoption;
            }

            // make a collection of the vaccinated animals
            if (rand(0, 100) > 20) {
                $vaccinatedAnimal['id'] = $i + 1;
                $vaccinatedAnimal['typeOfAnimal'] = $typeOfAnimal;
                $vaccinatedAnimal['date'] = $created_date;
                $date = new DateTime($created_date);
                $date->modify('+4 month');
                $date = $date->format('Y-m-d');
                $vaccinatedAnimal['date_max'] = $date;
                $vaccinatedAnimal['location_id'] = $location_id ;

                $vaccinatedAnimals[] = $vaccinatedAnimal;
            }

            $extenderCounter = $i + 1;
        }
        echo "<br><br>";

        // send to csv
        $infoToCsv = [];
        $fp = fopen($filePath.'AdoptablePets.csv', 'w');
        $infoToCsv = $adoptablePets;
        array_unshift(
            $infoToCsv, 
            ['adoptable_pet_name', 'breed_id', 'weight', 'height', 'available_date', 'pet_status_id', 'location_id', 'adoptable_pet_note', 'created_by', 'created_date', 'updated_by', 'updated_date']
        );
        foreach ($infoToCsv as $row) {
            fputcsv($fp, $row);
        }
        fclose($fp);
    // # AdoptablePets end

    // # Current AdoptablePets start
        echo "--Current AdoptablePets <br>";
        // @ num adjust
        // reset var
        $adoptablePets = [];
        $adoptable_pets_count = 150; // 150
        for ($i=0; $i < $adoptable_pets_count; $i++) { 
            // determine what type of animal is
            $randNum = rand(0,100);
            if ($randNum >= 90) {
                $typeOfAnimal = 'horse'; // Horse, 1
                $typeOfAnimalWeight = [500, 2500]; // [min, max]
                $typeOfAnimalHeight = [48, 90]; // [min, max]
            } elseif ($randNum >= 80) {
                $typeOfAnimal = 'bird'; // Bird, 2
                $typeOfAnimalWeight = [0, 10]; // [min, max]
                $typeOfAnimalHeight = [3, 24]; // [min, max]
            } elseif ($randNum >= 40) {
                $typeOfAnimal = 'dog'; // Dog, 3
                $typeOfAnimalWeight = [10, 250]; // [min, max]
                $typeOfAnimalHeight = [3, 48]; // [min, max]
            } elseif ($randNum >= 0) {
                $typeOfAnimal = 'cat'; // Cat, 4
                $typeOfAnimalWeight = [5, 90]; // [min, max]
                $typeOfAnimalHeight = [3, 36]; // [min, max]
            }
            // get animal name
            $adoptable_pet_name = replace_char_in_str(["'"], ["''"], $Seeder->animal_info($typeOfAnimal)); 
            // get correct breed
            $breed_id = $dbBreeds[$typeOfAnimal][$Seeder->animal_info($typeOfAnimal, 'breed')]; 
            $weight = floatval(rand($typeOfAnimalWeight[0],$typeOfAnimalWeight[1]) . "." . rand(0,99));
            $height = floatval(rand($typeOfAnimalHeight[0],$typeOfAnimalHeight[1]) . "." . rand(0,99));
            $available_date = $Seeder->date($min='2019', $max='2021');
            // determining pet_status_id
            $pet_status_id = 1;
            $location_id = rand(1, count($locationsNames));
            $adoptable_pet_note = rand(0,100) > 50 ? replace_char_in_str(["'"], ["''"], $Seeder->max_char($Seeder->min_char($Seeder->sentences(rand(1,3)), 10), 500)) : '';
            $created_by = rand(1, $staffCount);
            $created_date = $Seeder->date($min='1998');
            if (rand(0, 100) > 70  || $pet_status_id > 1) {
                $updated_by = rand(1, $staffCount);
                $date = $Seeder->date($min=$created_date);
                $updated_date = "to_date('{$date}', 'YYYY-MM-DD')";
                $updated_by_for_array = $updated_by;
                $updated_date_for_array = $date;
            } else {
                $updated_by = 'NULL';
                $updated_date = 'NULL';
                $updated_by_for_array = NULL;
                $updated_date_for_array = NULL;
            }
            echo "
                INSERT INTO AdoptablePets (
                    adoptable_pet_name,
                    breed_id,
                    weight,
                    height,
                    available_date,
                    pet_status_id,
                    location_id,
                    adoptable_pet_note,
                    created_by,
                    created_date,
                    updated_by,
                    updated_date 
                ) 
                VALUES (
                    '{$adoptable_pet_name}',
                    {$breed_id},
                    {$weight},
                    {$height},
                    to_date('{$available_date}', 'YYYY-MM-DD'),
                    {$pet_status_id},
                    {$location_id},
                    '{$adoptable_pet_note}',
                    {$created_by},
                    to_date('{$created_date}', 'YYYY-MM-DD'),
                    {$updated_by},
                    {$updated_date}
                );
            ";
            // put in to array for csv
            $adoptablePets[] = [$adoptable_pet_name, $breed_id, $weight, $height, $available_date, $pet_status_id, $location_id, $adoptable_pet_note, $created_by, $created_date, $updated_by_for_array, $updated_date_for_array];
            $mark300 = $i % 300 == 0 && $i != 0 ? '<br><br>--300 More AdoptablePets<br>' : '';
            echo $mark300;

            // make a collection of the vaccinated animals
            $vaccinatedAnimal['id'] = $extenderCounter + 1;
            $vaccinatedAnimal['typeOfAnimal'] = $typeOfAnimal;
            $vaccinatedAnimal['date'] = $created_date;
            $date = new DateTime($created_date);
            $date->modify('+4 month');
            $date = $date->format('Y-m-d');
            $vaccinatedAnimal['date_max'] = $date;
            $vaccinatedAnimal['location_id'] = $location_id ;

            $vaccinatedAnimals[] = $vaccinatedAnimal;

            $extenderCounter++;
        }
        echo "<br><br>";

        // send to csv
        $infoToCsv = [];
        $fp = fopen($filePath.'Current-AdoptablePets.csv', 'w');
        $infoToCsv = $adoptablePets;
        array_unshift(
            $infoToCsv, 
            ['adoptable_pet_name', 'breed_id', 'weight', 'height', 'available_date', 'pet_status_id', 'location_id', 'adoptable_pet_note', 'created_by', 'created_date', 'updated_by', 'updated_date']
        );
        foreach ($infoToCsv as $row) {
            fputcsv($fp, $row);
        }
        fclose($fp);
    // # Current AdoptablePets end

    // # Adoptions start
        // for bracket color "
        echo "--Adoptions <br>";
        for ($i=0; $i < count($adoptions); $i++) { 
            $adoptable_pet_id = $adoptions[$i]['id'];
            $adopter_id = rand(1, $adopterCount);
            $date = $Seeder->date($min=$adoptions[$i]['date'], $max=$adoptions[$i]['date_max']);
            $adoption_date = "to_date('{$date}', 'YYYY-MM-DD')";
            switch ($adoptions[$i]['typeOfAnimal']) {
                case 'horse': $adoption_cost = floatval(rand(300, 1000) . '.' . rand(0, 99)); break;
                case 'bird': $adoption_cost = floatval(rand(10, 100) . '.' . rand(0, 99)); break;
                case 'dog': $adoption_cost = floatval(rand(50, 350) . '.' . rand(0, 99)); break;
                case 'cat': $adoption_cost = floatval(rand(35, 300) . '.' . rand(0, 99)); break;
            }
            $staff_id = rand(1, $staffCount);
            $location_id = $adoptions[$i]['location_id'];
            echo "
                INSERT INTO Adoptions (
                    adoptable_pet_id, 
                    adopter_id,
                    adoption_date,
                    adoption_cost,
                    staff_id,
                    location_id
                ) 
                VALUES (
                    {$adoptable_pet_id}, 
                    {$adopter_id},
                    {$adoption_date},
                    {$adoption_cost},
                    {$staff_id},
                    {$location_id}
                );
            ";
            // put in to array for csv
            $adoptionsTemp[] = [$adoptable_pet_id, $adopter_id, $date, $adoption_cost, $staff_id, $location_id];
            $mark300 = $i % 300 == 0 && $i != 0 ? '<br><br>--300 More Adoptions<br>' : '';
            echo $mark300;
        }
        echo "<br><br>";

        // send to csv
        $infoToCsv = [];
        $fp = fopen($filePath.'Adoptions.csv', 'w');
        $infoToCsv = $adoptionsTemp;
        array_unshift(
            $infoToCsv, 
            ['adoptable_pet_id', 'adopter_id', 'adoption_date', 'adoption_cost', 'staff_id', 'location_id']
        );
        foreach ($infoToCsv as $row) {
            fputcsv($fp, $row);
        }
        fclose($fp);
    // # Adoptions end

    // # AnimalVaccinations start
        // for bracket color "
        echo "--AnimalVaccinations <br>";
        // get vaccine counts
        $animalVaccinationCounter = 0;
        // looping over vaccinatedAnimals
        for ($j=0; $j < count($vaccinatedAnimals); $j++) { 
            // get total vaccines for type of animal, dbVaccinations contains lists of vaccines for each type of animal
            $vaccineTotalCount = count($dbVaccinations[$vaccinatedAnimals[$j]['typeOfAnimal']]);
            // get ids for vaccines
            $vaccineIds = [];
            foreach ($dbVaccinations[$vaccinatedAnimals[$j]['typeOfAnimal']] as $vaccinationName => $id) {
                $vaccineIds[] = $id;   
            }
            // vaccines to administer to each animal
            $vaccineCount = rand(1, $vaccineTotalCount);
            // produce the records for those vaccines
            for ($i=0; $i < $vaccineCount; $i++) { 
                $vaccination_id = $vaccineIds[$i];
                $adoptable_pet_id = $vaccinatedAnimals[$j]['id'];
                $date = $Seeder->date($min=$vaccinatedAnimals[$j]['date'], $max=$vaccinatedAnimals[$j]['date_max']);
                $date_administered = "to_date('{$date}', 'YYYY-MM-DD')";
                $staff_id = rand(1, $staffCount);
                $location_id = $vaccinatedAnimals[$j]['location_id'];
                echo "
                    INSERT INTO AnimalVaccinations (
                        vaccination_id, 
                        adoptable_pet_id,
                        date_administered,
                        staff_id,
                        location_id
                    ) 
                    VALUES (
                        {$vaccination_id}, 
                        {$adoptable_pet_id},
                        {$date_administered},
                        {$staff_id},
                        {$location_id}
                    );
                ";
                // put in to array for csv
                $animalVaccinations[] = [$vaccination_id, $adoptable_pet_id, $date, $staff_id, $location_id];
                $animalVaccinationCounter++;
                $mark300 = $animalVaccinationCounter % 300 == 0 && $animalVaccinationCounter != 0 ? '<br><br>--300 More AnimalVaccinations<br>' : '';
                echo $mark300;
            }
        }
        echo "<br><br>";

        // send to csv
        $infoToCsv = [];
        $fp = fopen($filePath.'AnimalVaccinations.csv', 'w');
        $infoToCsv = $animalVaccinations;
        array_unshift(
            $infoToCsv, 
            ['vaccination_id', 'adoptable_pet_id', 'date_administered', 'staff_id', 'location_id']
        );
        foreach ($infoToCsv as $row) {
            fputcsv($fp, $row);
        }
        fclose($fp);
    // # AnimalVaccinations end

    // var_dump($dbBreeds);
    // var_dump($dbVaccinations);
    // var_dump($animalVaccinationCounter);
?>