<?php

use App\User;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Faker\Factory as FakerFactory;
use Illuminate\Support\Facades\Hash;

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
        $fileContent = file_get_contents(__DIR__ . '/users.txt');
        $content = explode("\n", $fileContent);
        $fieldNames = explode("\t", array_shift($content));
        $usersData = array_map(function ($userData) use ($fieldNames) {
            return array_combine($fieldNames, explode("\t", $userData));
        }, $content);

        try {
            DB::connection()->beginTransaction();

            for ($i = 0; $i < count($usersData); $i++) {
                $userData = $usersData[$i];
                $user = new User();
                foreach ($userData as $field => $value) {
                    $user->{$field} = $value;
                }
                $user->avatar = User::DEFAULT_AVATAR;
                $user->password = Hash::make($this->faker->regexify('[A-Za-z0-9]{20}'));
                $user->email_verified_at = now();

                if (!$user->save()) {
                    throw new \Exception('Cannot save user');
                }

                if ($i > 0 && $i % 100 === 0) {
                    DB::connection()->commit();
                    DB::connection()->beginTransaction();
                }
            }

            DB::connection()->commit();

        } catch (\Exception $e) {
            DB::connection()->rollBack();

            dd($e->getMessage() . ': ' . $e->getLine());
        }
    }
}
