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
        $users = [$this->generateRandomUserData('Tester', 'test', '1234567890localhost@gmail.com')];
        for ($i = 0; $i < 5; $i++) {
            $users[] = $this->generateRandomUserData();
        }

        DB::table('users')->insert($users);
    }

    private function generateRandomUserData(?string $name = null, ?string $surname = null, ?string $email = null)
    {
        $name = $name ?? $this->faker->unique()->name;
        $surname = $surname ?? $this->faker->unique()->name;

        return [
            'login' => lcfirst($name),
            'name' => $name,
            'surname' => $surname,
            'info' => $this->faker->realText(rand(20, 200)),
            'avatar' => User::DEFAULT_AVATAR,
            'email' => $email ?? $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ];
    }
}
