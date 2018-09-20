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
                'url' => 'http://bonnierestro.hu/hu/napimenu/'
            ],
            [
                'name' => 'KajaHu',
                'url' => 'https://appif.kajahu.com/jdmenu?jseat=-&jlang=hu'
            ]
        ];
        $table = $this->table('restaurants');
        $table->truncate();
        $table
            ->insert($records)
            ->save();
    }
}
