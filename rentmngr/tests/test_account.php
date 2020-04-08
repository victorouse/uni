<?php
    // Inlude functions to test
    include(dirname(__DIR__) . "/api/account.php");

    // Phactory library
    require_once("Phactory/lib/Phactory.php");

    /**
     * Tests the account API.
     *
     * @backupGlobals disabled 
     * @backupStaticAttributes disabled
     */
    class AccountTest extends PHPUnit_Framework_TestCase {
        public static function setUpBeforeClass() {
            // New PDO connection to play with
            $pdo = new PDO("mysql:host=localhost;dbname=wallfly;charset=utf8", "deco", "3801");
            Phactory::setConnection($pdo);

            // Reset any existing blueprints and empty any tables Phactory has used
            Phactory::reset();

            // Table blueprint
            Phactory::define('users', array(
                'email_address' => 'testuser$n@example.com',
                'first_name' => 'firstName_$n',
                'last_name' => 'lastName_$n',
                'type' => 'tester',
                'password' => 'test',
                'salt' => 'salty'
            ));
        }

        public static function tearDownAfterClass() {
            // Clean slate
            Phactory::reset();
        }

        /*
         * Tests the login functionality.
         * 
         * Inputs (pdo, email address, password):
         *  - Non-existent user
         *  - Valid user with correct credentials
         *  - Valid user with incorrect credentials (wrong password)
         *
         * Outputs:
         *  - Assert false if user does not exist
         *  - Assert false if user's password is wrong
         *  - Assert true if credentials are correct
         */
        public function testLogin() {
            // Test #1: Non-existent user
            echo "\nTest #1 - Non-existent user\n";
            $expected = "false";
            $actual = login(Phactory::getConnection(), "none", "none");
            $this->assertFalse($actual);
            printf("Input(s): (%s) \t Expected: (%s) \t Actual: (%s)\n", 
                "none, none", $expected, var_export($actual, true));
            
            // Test #2: Valid user with incorrect credentials 
            echo "Test #2 - Valid user with incorrect credentials\n";
            $user1 = Phactory::create('users');
            $expected = "false";
            $actual = login(Phactory::getConnection(), $user1->email_address, "incorrect password");
            $this->assertFalse($actual);
            printf("Input(s): (%s, %s) \t Expected: (%s) \t Actual: (%s)\n", 
                $user1->email_address, "incorrect password", $expected, var_export($actual, true));

            // Test 3: Valid user with correct credentials 
            echo "Test #3 - Valid user with correct credentials\n";
            $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
            $password = hash_password("pass", $random_salt); // spoof password
            $user2 = Phactory::create('users', 
                array(
                    'email_address' => 'valid@email.com', 
                    'password' => $password,
                    'salt' => $random_salt
                ));
            $expected = "true";
            $actual = login(Phactory::getConnection(), "valid@email.com", "pass");
            $this->assertTrue($actual);
            printf("Input(s): (%s, %s) \t Expected: (%s) \t Actual: (%s)\n", 
                $user2->email_address, $user2->password, $expected, var_export($actual, true));
        }
    }
?>
