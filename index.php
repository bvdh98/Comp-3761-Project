<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Swear Filter.io</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="banner">Swear Filter.IO</div>
    <div class="content">
        <div class="text-area-input">
            <form action="index.php" name="user-input" method="post">
                <div>
                    <label for="text-area">Enter text below:</label><br>
                </div>
                    <textarea required='required' id="text-area" name="text-area" rows="30" cols="30"></textarea>
                <div>
                    <input type="submit" value="submit" class="submitButton">
                </div>
            </form>
        </div>
    </div>

    <?php
        // 20 common swears, which will be censored
           $SWEARS = ["fuck","shit",'piss','poop','ass','bitch','dick','hell','gay','cock','boobs','tits','fag','faggot',
        'retard','dumbass','cunt','pussy','whore','slut'];

        //user input text
        $sampleText = null;

        //if the user input is posted it will be stored in sample text
        if(isset($_POST["text-area"]))
        {
            $sampleText = $_POST["text-area"];
        }

        //an associative array of swears and their hash values
        $hashTable = makeHashTable($SWEARS);

//        printHashTable($hashTable);

        //prints and censors user input
        printCensoredText($sampleText,$hashTable);

        //makes a hashtable out of an array of swears
        function makeHashTable($swearArray)
        {
            $hashTable = [];
            //iterate over the swear array and store each swear along with its hash value
            foreach ($swearArray as &$swear)
            {
                $hashTable = add_to_assoc_array($hashTable,$swear,SimpleHash($swear));

            }
            return $hashTable;
        }

        //adds new values to an associative array
        function add_to_assoc_array($array, $key, $value)
        {
            $array[$key] = $value;
            return $array;
        }

        //hashes a swear word by summing up each characters ASCII value multiplied by its position
        function SimpleHash($str)
        {
            $value = 0;

            // loop trough all letters and add the
            // ASCII value to a integer variable.
            for ($char=0; $char < strlen($str); $char++)
            {
//                $non_alpha_count = 0;
                //TODO: ignore non alphanumeric characters
//                if(!preg_match("/^[a-zA-Z]$/", $str[$c]));
//                {
//                    echo("ignoring special symbol") . "<br>";
//                    $non_alpha_count ++;
//                    continue;
//                }

                //each ASCII value will be multiplied by a power of 10
                $exponent = strlen($str) - $char - 1;
                $value += (((ord($str[$char]))*pow(10,$exponent)));
            }
            // After we went trough all letters
            // we have a number that represents the
            // content of the string

            //to prevent integer overflow, take the modulo of a large prime number
            return $value % 113;
        }

        //censors user input text
        function censor($userText,$hashTable)
        {
            //the final censored text which will be returned and printed
            $censored_text = $userText;

            //iterates over each word in the text, hashes it, and then compares the hash value to the hash values stored in
            //the hash table
            foreach ($censored_text as &$word)
            {
                //converts the word to all lowercase and hashes it
                $hashWord = SimpleHash(strtolower($word));

                //checks to see if the word is a swear based on its hash value
                if (array_search($hashWord,$hashTable))
                {
                    //censors the swear with *****
                    $censored_text = str_replace($word,'****',$censored_text);
                }

                //if the word isn`t a swear skip over it
                else
                {
                    continue;
                }
            }
            return $censored_text;
        }

        //prints the censored user text in the text area below
        function printCensoredText($userInput, $swearArray)
        {
            //converts the user input to a character array
            $string_array = explode(' ',$userInput);

            //censors characters of swear words
            $censored_text = censor($string_array,$swearArray);

            //converts the array back to a string
            $censored_text_string = implode(" ",$censored_text);

            //prints the string in a new text area
            echo ("<div class=\"text-area-output\">
                        <label>Censored text:</label>
                        <textarea required='required' rows=\"5\" cols=\"30\">$censored_text_string</textarea>
                   </div>\\");
        }

        //prints out each swear word in the hash table along with its hash value
        function printHashTable($hashTable)
        {
            foreach($hashTable as $swear=>$value)
            {
                echo $swear . " => " . $value . "<br>";
            }
        }

    ?>
</body>
</html>