<?php
    // TODO: test sql inserts
    // ! start testing on # Adoptions - **********making inserts

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
    
    // # TypeOfAnimals
    echo "--TypeOfAnimals <br>";
    $typeOfAnimals = ['Horse', 'Bird', 'Dog', 'Cat'];
    for ($i=0; $i < count($typeOfAnimals); $i++) {
        $type_of_animal =  $typeOfAnimals[$i];
        echo "
            INSERT INTO TypeOfAnimals (type_of_animal) VALUES ('{$type_of_animal}');
        ";
    }
    echo "<br><br>";

    // # Staff
    echo "--Staff <br>";
    $staffCount = 200;  
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
        } else {
            $updated_by = 'NULL';
            $updated_date = 'NULL';
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
    }
    echo "<br><br>";

    // # locations
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
        } else {
            $updated_by = 'NULL';
            $updated_date = 'NULL';
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
    }
    echo "<br><br>";

    // # StaffAtLocation
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
    }
    echo "<br><br>";
    
    // # Breeds
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
            $breedIdConter++;
        }
        $animalTypeId++;
    }
    echo "<br><br>";

    // # Adopters
    echo "--Adopters <br>";
    $adopterCount = 300;  
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
        } else {
            $updated_by = 'NULL';
            $updated_date = 'NULL';
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
                {$adopters_zip },
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
    }
    echo "<br><br>";

    // # Vaccinations
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
            } else {
                $updated_by = 'NULL';
                $updated_date = 'NULL';
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
            $dbVaccinations[$animalType][$vaccinationName] = $vaccinationIdConter;
            $vaccinationIdConter++;
        }
        $type_of_animal_id++;
    }
    echo "<br><br>";
    
    // # AdoptablePets
    echo "--AdoptablePets <br>";
    $adoptions = [];
    $vaccinatedAnimals = [];
    $adoptable_pets_count = 2000;
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
        // determining status
        $randNum = rand(0,100);
        if ($randNum >= 50 && $date2 > $date1) {
            $status = rand(1,2);
        } else {
            $status = rand(2,3);
        }
        $location_id = rand(1, count($locationsNames));
        $adoptable_pet_note = rand(0,100) > 50 ? replace_char_in_str(["'"], ["''"], $Seeder->max_char($Seeder->min_char($Seeder->sentences(rand(1,3)), 10), 500)) : '';
        $created_by = rand(1, $staffCount);
        $created_date = $Seeder->date($min='1998');
        if (rand(0, 100) > 70  || $status > 1) {
            $updated_by = rand(1, $staffCount);
            $date = $Seeder->date($min=$created_date);
            $updated_date = "to_date('{$date}', 'YYYY-MM-DD')";
        } else {
            $updated_by = 'NULL';
            $updated_date = 'NULL';
        }
        echo "
            INSERT INTO AdoptablePets (
                adoptable_pet_name,
                breed_id,
                weight,
                height,
                available_date,
                status,
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
                {$status},
                {$location_id},
                '{$adoptable_pet_note}',
                {$created_by},
                to_date('{$created_date}', 'YYYY-MM-DD'),
                {$updated_by},
                {$updated_date}
            );
        ";
        $mark300 = $i % 300 == 0 && $i != 0 ? '<br><br>--300 More AdoptablePets<br>' : '';
        echo $mark300;

        // save adopted pets in array
        if ($status == 2) {
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
    }
    echo "<br><br>";

    // # Adoptions
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
    }
    echo "<br><br>";

    // # AnimalVaccinations
    // for bracket color "
    echo "--AnimalVaccinations <br>";
    // get vaccine counts

    for ($j=0; $j < count($vaccinatedAnimals); $j++) { 
        // get total for animal
        $vaccineTotalCount = count($dbVaccinations[$vaccinatedAnimals['typeOfAnimal']]);
        // get ids for vaccines
        $vaccineIds = [];
        foreach ($dbVaccinations[$vaccinatedAnimals['typeOfAnimal']] as $name => $id) {
            $vaccineIds[] = $id;   
        }
        $vaccineCount = rand(1, $vaccineTotalCount);
        for ($i=0; $i < $vaccineCount; $i++) { 
            $vaccination_id = $vaccineIds[$i];
            $adoptable_pet_id = $vaccinatedAnimals[$i]['id'];
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
        }
    }
    echo "<br><br>";

    // var_dump($dbBreeds);
    // var_dump($dbVaccinations);
?>