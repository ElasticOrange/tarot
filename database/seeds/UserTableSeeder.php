<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	echo('Seeding UserTableSeeder');
        $newUser = new User;
        $newUser->fill([
        	'name' => 'Administrator',
        	'email' => 'admin@tarot.ro',
        	'type' => 1,
        	'active' => 1,

        ]);
        $newUser->password = 'admin';

        $result = $newUser->save();

        if (!$result) {
        	echo('Error: UserTableSeeder.run: could not add admin user');
        }
    }
}
