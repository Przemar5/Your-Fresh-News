<?php

use App\User;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Faker\Factory as FakerFactory;

class UserSeeder extends Seeder
{
    private Faker $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->generateRandomUserData('Tester', 'test', '1234567890localhost@gmail.com')->save();
        for ($i = 0; $i < 5; $i++) {
            $this->generateRandomUserData()->save();
        }
    }

    private function generateRandomUserData(?string $name = null, ?string $surname = null, ?string $email = null)
    {
        $name = $name ?? $this->faker->unique()->name;
        $surname = $surname ?? $this->faker->unique()->name;

        $user = new User();
        $user->login = lcfirst($name) . '-' . lcfirst($surname);
        $user->name = $name;
        $user->surname = $surname;
        $user->info = $this->faker->realText(rand(20, 200));
        $user->avatar = User::DEFAULT_AVATAR;
        $user->email = $email ?? $this->faker->unique()->safeEmail;
        $user->email_verified_at = now();
        $user->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // password

        return $user;
    }
}
