<?php

namespace App\Console\Commands;

use App\InnorangeData;
use App\InnorangeMeasurementPoint;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class InnorangeImporter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:innorange';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports data from Innorage API';

    protected $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new Client;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $api_key = config::get('innorange.api_key');
        $endTime = Carbon::now()->subHour()->toIso8601String();
        $startTime = Carbon::now()->subHours(2)->toIso8601String();

        /* Haetaan vain edellisen tunnin tiedot yhdessä haussa, sillä aiemmat tiedot ovat oletettavasti jo kannassa. */
        $startTime = substr($startTime, 0, -4);
        $endTime   = substr($endTime, 0, -4);

        $measurementPoints = InnorangeMeasurementPoint::all();

        $dataAdded = 0;

        /* Haetaan jokaisen mittauspisteen datat peräjälkeen */
        foreach($measurementPoints as $measurementPoint)
        {
            $response = $this->client->request('GET',
                'https://customers.innorange.fi/api/v1/'.$measurementPoint->url.'/visitors/hourly/?sd='.$startTime.'&ed='.$endTime.'&api_key='.$api_key);

            /*
             *   Tässä välissä jos vastaus on muuta kuin 200, voitaisiin vaikka lokittaa virhe, mutta lopulta tämä prosessi
             *   joko toimii, tai sitten ei
             */
            $data = $response->getBody()->getContents();
            $data = json_decode($data, true);

            /* Katsotaan nyt vielä, ettei tule duplikaattia kantaan, koska miksipä ei */
            $dataExists = InnorangeData::where('measurement_point', $measurementPoint->id)
                ->where('timestamp', $data['results'][0]['timestamp'])
                ->count();

            if($dataExists == 0)
            {
                InnorangeData::create([
                    'measurement_point' => $measurementPoint->id,
                    'timestamp'         => $data['results'][0]['timestamp'],
                    'visitors'          => $data['results'][0]['visitors']
                ]);
                $dataAdded++;
            }
        }

        $this->info('['.Carbon::now().'] Innorangen datan importointi ajettiin, tuotiin ' . $dataAdded . ' tietuetta.');
    }
}
