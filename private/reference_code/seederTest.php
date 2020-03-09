<?php
    $Seeder = new Seeder();

    // English
    echo '<h1>Seeder Tester</h1>';
    echo '<h2>English</h2>';
    echo '$Seeder->word()' . "<br>";
    echo "<b>" . $Seeder->word() . "</b><br><br>";

    echo '$Seeder->words(5)' . "<br>";
    echo "<b>" . $Seeder->words(5) . "</b><br><br>";

    echo '$Seeder->sentence()' . "<br>";
    echo "<b>" . $Seeder->sentence() . "</b><br><br>";

    echo '$Seeder->max_char($Seeder->sentence(), 75, "!")' . "<br>";
    echo "<b>" . $Seeder->max_char($Seeder->sentence(), 75, "!") . "</b><br><br>";

    echo '$Seeder->max_char($Seeder->sentence(), 100)' . "<br>";
    echo "<b>" . $Seeder->max_char($Seeder->sentence(), 100) . "</b><br><br>";

    echo '$Seeder->sentences(3)' . "<br>";
    echo "<b>" . $Seeder->sentences(3) . "</b><br><br>";

    echo '$Seeder->paragraph()' . "<br>";
    echo "<b>" . $Seeder->paragraph() . "</b><br><br>";
    
    echo '$Seeder->paragraphs(3, "&lt;br&gt;&lt;br&gt;")' . "<br>";
    echo "<b>" . $Seeder->paragraphs(3, "<br><br>") . "</b><br><br>";

    echo '<h2>Id\'s</h2>';
    echo '$Seeder->id()' . "<br>";
    echo "<b>" . $Seeder->id() . "</b><br>";
    echo "<b>" . $Seeder->id() . "</b><br>";
    echo "<b>" . $Seeder->id() . "</b><br>";
    echo "<b>" . $Seeder->id() . "</b><br>";
    echo "<b>" . $Seeder->id() . "</b><br><br>";
    
    echo '$Seeder->id(555)' . "<br>";
    echo "<b>" . $Seeder->id(555) . "</b><br>";
    echo "<b>" . $Seeder->id(555) . "</b><br>";
    echo "<b>" . $Seeder->id(555) . "</b><br>";
    echo "<b>" . $Seeder->id() . "</b><br>";
    echo "<b>" . $Seeder->id() . "</b><br><br>";
    
    echo '<h2>Dates</h2>';
    echo '$Seeder->date()' . "<br>";
    echo "<b>" . $Seeder->date() . "</b><br><br>";
    echo '$Seeder->date("1/1/2020")' . "<br>";
    echo "<b>" . $Seeder->date('1/1/2020') . "</b><br><br>";
    echo '$Seeder->date("12/1/2019", "12/31/2019")' . "<br>";
    echo "<b>" . $Seeder->date('12/1/2019', '12/31/2019') . "</b><br><br>";
    echo '$Seeder->date("1/1/2019", "12/31/2020","F j, Y, g:i a")' . "<br>";
    echo "<b>" . $Seeder->date('1/1/2019', '12/31/2020','F j, Y, g:i a') . "</b><br><br>";

    echo '<h2>Address</h2>';
    echo '$Seeder->address()' . "<br>";
    echo "<b>" . $Seeder->address() . "</b><br><br>";
    echo '$Seeder->address()' . "<br>";
    echo "<b>" . $Seeder->address() . "</b><br><br>";
    echo '$Seeder->address("part")' . "<br>";
    echo "<b>" . $Seeder->address('part') . "</b><br><br>";
    echo '$Seeder->address("part")' . "<br>";
    echo "<b>" . $Seeder->address('part') . "</b><br><br>";

    echo '<h2>City</h2>';
    echo '$Seeder->city()' . "<br>";
    echo "<b>" . $Seeder->city() . "</b><br><br>";
    echo '$Seeder->city()' . "<br>";
    echo "<b>" . $Seeder->city() . "</b><br><br>";

    echo '<h2>State</h2>';
    echo '$Seeder->state()' . "<br>";
    echo "<b>" . $Seeder->state() . "</b><br><br>";
    echo '$Seeder->state()' . "<br>";
    echo "<b>" . $Seeder->state() . "</b><br><br>";
    echo '$Seeder->state("name")' . "<br>";
    echo "<b>" . $Seeder->state('name') . "</b><br><br>";
    echo '$Seeder->state("name")' . "<br>";
    echo "<b>" . $Seeder->state('name') . "</b><br><br>";

    echo '<h2>First and Last Name</h2>';
    echo '$Seeder->first_name() . " " . $Seeder->last_name()' . "<br>";
    echo "<b>" . $Seeder->first_name() . " " . $Seeder->last_name() . "</b><br><br>";
    echo '$Seeder->first_name() . " " . $Seeder->last_name()' . "<br>";
    echo "<b>" . $Seeder->first_name() . " " . $Seeder->last_name() . "</b><br><br>";

    echo '<h2>Email</h2>';
    echo '$Seeder->email()' . "<br>";
    echo "<b>" . $Seeder->email() . "</b><br><br>";
    echo '$Seeder->email()' . "<br>";
    echo "<b>" . $Seeder->email() . "</b><br><br>";
    echo '$Seeder->email("{$Seeder->first_name()}{$Seeder->last_name()}")' . "<br>";
    echo "<b>" . $Seeder->email("{$Seeder->first_name()}{$Seeder->last_name()}") . "</b><br><br>";
    echo '$Seeder->email("{$Seeder->first_name()}{$Seeder->last_name()}")' . "<br>";
    echo "<b>" . $Seeder->email("{$Seeder->first_name()}{$Seeder->last_name()}") . "</b><br><br>";

    echo '<h2>Zip Code</h2>';
    echo '$Seeder->zip()' . "<br>";
    echo "<b>" . $Seeder->zip() . "</b><br><br>";
    echo '$Seeder->zip()' . "<br>";
    echo "<b>" . $Seeder->zip() . "</b><br><br>";

    echo '<h2>Job Titles</h2>';
    echo '$Seeder->job_title()' . "<br>";
    echo "<b>" . $Seeder->job_title() . "</b><br><br>";
    echo '$Seeder->job_title()' . "<br>";
    echo "<b>" . $Seeder->job_title() . "</b><br><br>";
    echo '$Seeder->job_title(["webDevelopment"])' . "<br>";
    echo "<b>" . $Seeder->job_title(['webDevelopment']) . "</b><br><br>";
    echo '$Seeder->job_title(["webDevelopment", "db"])' . "<br>";
    echo "<b>" . $Seeder->job_title(['webDevelopment', 'db']) . "</b><br><br>";
    echo '$Seeder->job_title(["sales", "customerService"])' . "<br>";
    echo "<b>" . $Seeder->job_title(['sales', 'customerService']) . "</b><br><br>";
    echo '$Seeder->job_title(["notValid"])' . "<br>";
    echo "<b>" . $Seeder->job_title(['notValid']) . "</b><br><br>";
    echo '$Seeder->job_title(["notValid", "db"])' . "<br>";
    echo "<b>" . $Seeder->job_title(['notValid', 'db']) . "</b><br><br>";

    echo '<h2>Username</h2>';
    echo '$Seeder->username()' . "<br>";
    echo "<b>" . $Seeder->username() . "</b><br><br>";
    echo '$Seeder->username()' . "<br>";
    echo "<b>" . $Seeder->username() . "</b><br><br>";
    echo '$Seeder->username()' . "<br>";
    echo "<b>" . $Seeder->username() . "</b><br><br>";
    echo '$Seeder->username()' . "<br>";
    echo "<b>" . $Seeder->username() . "</b><br><br>";

    echo '<h2>Phone Number</h2>';
    echo '$Seeder->phone_number()' . "<br>";
    echo "<b>" . $Seeder->phone_number() . "</b><br><br>";
    echo '$Seeder->phone_number()' . "<br>";
    echo "<b>" . $Seeder->phone_number() . "</b><br><br>";
    echo '$Seeder->phone_number(" ")' . "<br>";
    echo "<b>" . $Seeder->phone_number(' ') . "</b><br><br>";
    echo '$Seeder->phone_number(".")' . "<br>";
    echo "<b>" . $Seeder->phone_number('.') . "</b><br><br>";
    echo '$Seeder->phone_number("")' . "<br>";
    echo "<b>" . $Seeder->phone_number('') . "</b><br><br>";

    echo '<h2>Select From Options</h2>';
    echo '$Seeder->options(["Fun", "No Fun"])' . "<br>";
    echo "<b>" . $Seeder->options(['Fun', 'No Fun']) . "</b><br><br>";
    echo '$Seeder->options(["M", "F"])' . "<br>";
    echo "<b>" . $Seeder->options(['M', 'F']) . "</b><br><br>";
    echo '$Seeder->options(["small", "media", "large", "x-large"])' . "<br>";
    echo "<b>" . $Seeder->options(['small', 'media', 'large', 'x-large']) . "</b><br><br>";
    echo '$Seeder->options(["Developer", "UX Designer", "Designer", "Graphic Designer", "Layout Specialist"])' . "<br>";
    echo "<b>" . $Seeder->options(['Developer', 'UX Designer', 'Designer', 'Graphic Designer', 'Layout Specialist']) . "</b><br><br>";
    echo '$Seeder->options(["Buzz LightYear", "Woody", "Bo Peep"])' . "<br>";
    echo "<b>" . $Seeder->options(['Buzz LightYear', 'Woody', 'Bo Peep']) . "</b><br><br>";

    // Русский 
    echo '<h2>Русский</h2>';
    echo '$Seeder->word_russian()' . "<br>";
    echo "<b>" . $Seeder->word_russian() . "</b><br><br>";

    echo '$Seeder->words_russian(5)' . "<br>";
    echo "<b>" . $Seeder->words_russian(5) . "</b><br><br>";

    echo '$Seeder->sentence_russian()' . "<br>";
    echo "<b>" . $Seeder->sentence_russian() . "</b><br><br>";

    // not working totally right come back and later
    // echo '$Seeder->max_char($Seeder->sentence_russian(), 75, "!")' . "<br>";
    // echo "<b>" . $Seeder->max_char($Seeder->sentence_russian(), 75, "!") . "</b><br><br>";

    // echo '$Seeder->max_char($Seeder->sentence_russian(), 100)' . "<br>";
    // echo "<b>" . $Seeder->max_char($Seeder->sentence_russian(), 100) . "</b><br><br>";

    echo '$Seeder->sentences_russian(3)' . "<br>";
    echo "<b>" . $Seeder->sentences_russian(3) . "</b><br><br>";

    echo '$Seeder->paragraph_russian()' . "<br>";
    echo "<b>" . $Seeder->paragraph_russian() . "</b><br><br>";
    
    echo '$Seeder->paragraphs_russian(3, "&lt;br&gt;&lt;br&gt;")' . "<br>";
    echo "<b>" . $Seeder->paragraphs_russian(3, "<br><br>") . "</b><br><br>";
    
?>