<?php

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $admin_email = 'admin@mail.com';
        $admin_password = '123456';

        if (!User::where('email', '=', $admin_email)->first()) {
            $user = User::create([
                'name'                           => $faker->userName,
                'first_name'                     => $faker->firstName,
                'last_name'                      => $faker->lastName,
                'email'                          => $admin_email,
                'password'                       => Hash::make($admin_password),
                'token'                          => str_random(64),
                'verified'                      => true,
                'confirmation_ip_address'        => $faker->ipv4,
                'admin_signup_ip_address'        => $faker->ipv4,
            ]);

            $user->profile()->save(new Profile());
            $user->assignRole('admin');
            $user->save();
        }


        $user_email = 'user@mail.com';
        $user_password = '123456';

        if (!User::where('email', '=', $user_email)->first()) {
            $user = User::create([
                'name'                           => $faker->userName,
                'first_name'                     => $faker->firstName,
                'last_name'                      => $faker->lastName,
                'email'                          => $user_email,
                'password'                       => Hash::make($user_password),
                'token'                          => str_random(64),
                'verified'                      => true,
                'signup_ip_address'              => $faker->ipv4,
                'confirmation_ip_address' => $faker->ipv4,
            ]);

            $user->profile()->save(new Profile());
            $user->assignRole('user');
            $user->save();
        }
    }
}
