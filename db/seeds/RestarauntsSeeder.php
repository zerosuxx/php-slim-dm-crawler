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
        ];
        $this->execute('SET foreign_key_checks=0');
        $table = $this->table('restaurants');
        $table->truncate();
        $table
            ->insert($records)
            ->save();
    }
}
