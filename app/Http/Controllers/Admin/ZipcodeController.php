<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyZipcodeRequest;
use App\Http\Requests\StoreZipcodeRequest;
use App\Http\Requests\UpdateZipcodeRequest;
use App\Zipcode;
use Gate;
use Session;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Stream\Stream;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag;
use Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ZipcodeController extends Controller
{
    public function connect(Request $request)
    {
        $apikey = "99b3203b-8d00-c02b-84e5-78a247fae069";
        $authid = "eBjLgD4GlkhfeNZLT5Jc";

        /**
         * check to see if ajax data has been posted.
         */
        if (request()->ajax()) {

            $postedArray = [];

            $session_cs = $request->session()->get('citystate');

            if($request->city) {
                $city = $request->city;
                $postedArray['city'] = $request->city;
                Log::info("request->city: " . $request->city);
            } else {
                Log::info("no city entered");
            }

            if($request->state) {
                $state = $request->state;
                $postedArray['state'] = $request->state;
                Log::info("request->state: " . $request->state);
            } else {
                Log::info("no state entered");
            }

            if($request->city && $request->state) {

                 /**
                 * used to check if current search is same as last
                 * @var [type]
                 */
                $cityState = http_build_query($postedArray);
                $storedcityState = http_build_query($request->session()->get('citystate'));


                    if($cityState == $storedcityState){

                        if($request->session()->has('curldata'))
                        {
                            $data = $request->session()->get('curldata');

                            $cachedzips = [];
                            foreach ($data as $zipcode)
                            {
                                Log::info("Cached Zipcode: " . $zipcode);
                                $cachedzips[]  = $zipcode;
                            }
                            Log::info($cachedzips);
                            $zipcodes = $cachedzips;
                        }
                    } else {


                        try {

                                $client = new Client([
                                    'headers' => [
                                        'content-type' => 'application/json',
                                        'Accept'  => 'application/json'
                                    ],
                                ]);

                                $request = $client->request('GET', 'https://us-zipcode.api.smartystreets.com/lookup?auth-id='. $apikey .'&auth-token='.$authid.'&'.$cityState);

                                $data = json_decode($request->getBody()->getContents(), false);

                                $zips = [];

                                foreach ($data[0]->zipcodes as $zipcodes) {

                                    Log::info("Zipcode: " . $zipcodes->zipcode);

                                    $zips[]  = $zipcodes->zipcode;
                                }

                                $zipcodes = $zips;

                                session(['curldata' => $zipcodes, 'citystate' => $postedArray]);


                            } catch (Exception $e) {
                                Log::error($e);
                            }
                    }

                return response()->json(['zipcodes' => $zipcodes]);

            }




        } else {
            return "not ajax";
        }

    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('zipcode_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->session()->forget(['city_check', 'state_check']);

        $zipcodes = Zipcode::all();

        return view('admin.zipcodes.index', compact('zipcodes'));
    }

}
