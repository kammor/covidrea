<?php

use App\User;
use App\Service;
use App\Etablissement;
use Illuminate\Database\Seeder;

class SimulationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(User::class)->make([
            'name'  => 'Romain',
            'email' => 'legerrom@gmail.com',
        ]);

        factory(Etablissement::class, 50)->create()->each(function ($etablissement) use ($user) {
            factory(Service::class, 10)->create(['etablissement_id' => $etablissement->id])->each(function ($service) use ($user) {
                $service->users()->save($user);
            });
        });
    }
}
