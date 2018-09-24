<?php


use Phinx\Seed\AbstractSeed;

class RestarauntsSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $records = [
            [
                'name' => 'Bonnie',
                'url' => 'http://bonnierestro.hu'
            ],
            [
                'name' => 'KajaHu',
                'url' => 'https://www.kajahu.com/'
            ],
            [
                'name' => 'Nika',
                'url' => 'http://www.nikarestaurant.hu/'
            ],
            [
                'name' => 'Véndiák',
                'url' => 'http://www.vendiaketterem.hu/'
            ],
            [
                'name' => 'Muzikum',
                'url' => 'http://muzikum.hu'
            ]
        ];
        $table = $this->table('restaurants');
        $table->truncate();
        $table
            ->insert($records)
            ->save();
    }
}
