<?php

use App\InnorangeLocation;
use App\InnorangeMeasurementPoint;
use Illuminate\Database\Seeder;

class InnorangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Hatanpään valtatie - Hämeenkatu',
                'url' => 'Hatanp%C3%A4%C3%A4n%20valtatie%20-%20H%C3%A4meenkatu',
				'point' => [61.498053, 23.764827],
			],
			[
                'name' => 'Keskustori - Hämeenkatu',
                'url' => 'Keskustori%20-%20H%C3%A4meenkatu',
				'point' => [61.497769, 23.761873],
			],
			[
                'name' => 'Hämeenpuisto - Hämeenkatu',
                'url' => 'H%C3%A4meenpuisto%20-%20H%C3%A4meenkatu',
				'point' => [61.497175, 23.752399],
			],
			[
                'name' => 'Hatanpään valtatie - Koskikeskus',
                'url' => 'Hatanp%C3%A4%C3%A4n%20valtatie%20-%20Koskikeskus',
				'point' => [61.495672, 23.768829],
			]
        ];

        InnorangeMeasurementPoint::truncate();
        InnorangeLocation::truncate();

        foreach($data as $point)
        {
            $location = InnorangeLocation::create(['latitude' => $point['point'][0], 'longitude' => $point['point'][1]]);

            InnorangeMeasurementPoint::create([
                'name'     => $point['name'],
                'url'      => $point['url'],
                'location' => $location->id
            ]);
        }


    }
}
