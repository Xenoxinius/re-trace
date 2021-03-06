<?php

namespace App\Http\Controllers;

use App\Building;
use App\Materiallist;
use App\Substance;
use App\MaterialFunction;
use App\Unit;
use App\User;
use bar\baz\source_with_namespace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
        //referencing foreign key
    {
        $userBuilding = Building::with('user')
            ->where('userid', Auth::id())
            ->get();

        $firstbuilding = Building::with('user')
            ->where('userid', Auth::id())
            ->first();

        //gets all the info out the substances database
        $substances = DB::table('substance')->get();

        //map stuff
        $buildings = Building::where('userid', Auth::user()->id)->get();

        $buildingLocations = $buildings->unique('city');

        $locations = [];

        foreach ($buildings as $building) {
            array_push($locations,
                \GoogleMaps::load('geocoding')
                    ->setParam(['address' => $building->address1 . ' ' . $building->city . ' ' . $building->postcode,
                    ])
                    ->get()
            );
        }
        $decodedarray = [];

        foreach ($locations as $location) {
            $location = json_decode($location, true);
            array_push($decodedarray, $location);
        }


        //dd($decodedarray[0]['results'][0]['address_components'][2]['long_name']);

        //move to class
        $headCategory = Substance::whereNull('parent')->get();

        $subCategory1 = DB::table('substance')
            ->whereRaw("parent IS NOT NULL AND parent IN (SELECT id FROM substance WHERE parent IS NULL) AND is_hazardous IS FALSE")->get();

        $subCategory2 = DB::table('substance')
            ->whereRaw("parent IS NOT NULL AND parent IN (SELECT id FROM substance WHERE parent IS NOT NULL)")->get();

        $functionHeadCategory = MaterialFunction::whereNull('parent')->get();

        $functionSubCategory1 = DB::table('materialFunction')
            ->whereRaw("parent IS NOT NULL AND parent IN (SELECT id FROM materialFunction WHERE parent IS NULL)")->get();

        $functionSubCategory2 = DB::table('materialFunction')
            ->whereRaw("parent IS NOT NULL AND parent IN (SELECT id FROM materialFunction WHERE parent IS NOT NULL)")->get();
        $unit = Unit::all();


        return view('profile-page.home', [
            'buildings' => $userBuilding,
            'buildingLocations' => $buildingLocations,
            'substances' => $substances,
            'firstbuilding' => $firstbuilding,
            'locations' => $locations,
            'headCategories' => $headCategory,
            'subCategories1' => $subCategory1,
            'subCategories2' => $subCategory2,
            'functionHeadCategory' => $functionHeadCategory,
            'functionSubCategory1' => $functionSubCategory1,
            'functionSubCategory2' => $functionSubCategory2,
            'units' => $unit,
            'decodedarray' => $decodedarray,
        ]);
    }

    //look up blade extensions laravel, put in separate class
    public static function getLng($location)
    {
        $decodeLocation = json_decode($location, true);
        if ($decodeLocation['results'] != null) {
            return $decodeLocation['results'][0]['geometry']['location']['lng'];
        } else {
            return back()->with('error', "Please make sure your projects have existing locations");
        }
    }

    public static function getLat($location)
    {
        $decodeLocation = json_decode($location, true);
        if ($decodeLocation['results'] != null) {
            return $decodeLocation['results'][0]['geometry']['location']['lat'];
        } else {
            return back()->with('error', "Please make sure your projects have existing locations");
        }
    }

    public function mysearch(Request $request)
    {
        $inputsearch = $request->input('mysearch');

        $substanceInput = $request->input('substance');

        $functionInput = $request->input('dbFunction');

        $locationInput = $request->input('dbLocation');

        if ($substanceInput == null && $functionInput == null && $inputsearch == null && $locationInput == null) {
            return back()->with('error', __('Please enter a search query'));
        }

        $searchterm = DB::table('building')
            ->whereRaw("city LIKE '%$inputsearch%' OR id IN (SELECT buildid FROM streams WHERE name LIKE '%$inputsearch%' OR description LIKE '%$inputsearch%' OR id IN (SELECT stream_id FROM tags WHERE material_id IN
                (SELECT id FROM substance WHERE CONCAT(name,name_fr,name_nl) LIKE '%$inputsearch%') OR function_id IN (SELECT id FROM materialFunction WHERE CONCAT(name,name_fr,name_nl) LIKE '%$inputsearch%') ))")->get();
        //Search by name and description + OR in different languages
        //how to get scrollbar to only show up when you need to scroll?

        if (count($searchterm) == 0) {
            return back()->with('error', __('Nothing found'));
        }
        /*        if ($substanceId == null && $functionInput == null) {
                    return back()->with('error', __('please select a material or function '));
                }*/

        $buildArray = [];

        $materialTagArray = [];
        $functionTagArray = [];
        $locationTagArray = [];

        if ($substanceInput != null && $locationInput == null) {
            foreach ($substanceInput as $substanceId) {
                $buildings = DB::table('building')
                    ->whereRaw("id IN (SELECT buildid FROM streams WHERE id IN (SELECT stream_id FROM tags WHERE material_id = " . $substanceId . "))")->get();
                if (count($buildings) == 0) {
                    return back()->with('error', __('Nothing found'));
                } else {
                    array_push($buildArray, $buildings);
                    array_push($materialTagArray, Substance::where('id', $substanceId)->first()->name);
                }
            }
        }

        if ($substanceInput != null && $locationInput != null) {
            foreach ($substanceInput as $substanceId) {
                foreach ($locationInput as $location) {
                    $buildings = DB::table('building')
                        ->whereRaw("city LIKE '%$location%' and id IN (SELECT buildid FROM streams WHERE id IN (SELECT stream_id FROM tags WHERE material_id = " . $substanceId . "))")->get();
                    if (count($buildings) == 0) {
                        return back()->with('error', __('Nothing found'));
                    } else {
                        array_push($buildArray, $buildings);
                    }
                }
                array_push($materialTagArray, Substance::where('id', $substanceId)->first()->name);
            }
        }

        if ($functionInput != null && $locationInput == null) {
            foreach ($functionInput as $functionId) {
                $buildings = DB::table('building')
                    ->whereRaw("id IN (SELECT buildid FROM streams WHERE id IN (SELECT stream_id FROM tags WHERE function_id = " . $functionId . "))")->get();
                if (count($buildings) == 0) {
                    return back()->with('error', __('Nothing found'));
                } else {
                    array_push($buildArray, $buildings);
                    array_push($functionTagArray, MaterialFunction::where('id', $functionId)->first()->name);
                }
            }
        }

        if ($functionInput != null && $locationInput != null) {
            foreach ($functionInput as $functionId) {
                foreach ($locationInput as $location) {
                    $buildings = DB::table('building')
                        ->whereRaw("city LIKE '%$location%' and id IN (SELECT buildid FROM streams WHERE id IN (SELECT stream_id FROM tags WHERE function_id = " . $functionId . "))")->get();
                    if (count($buildings) == 0) {
                        return back()->with('error', __('Nothing found'));
                    } else {
                        array_push($buildArray, $buildings);
                    }
                }
                array_push($functionTagArray, MaterialFunction::where('id', $functionId)->first()->name);
            }
        }

        if ($inputsearch != null && $locationInput == null) {
            $buildings = DB::table('building')
                ->whereRaw("city LIKE '%$inputsearch%' OR id IN (SELECT buildid FROM streams WHERE name LIKE '%$inputsearch%' OR description LIKE '%$inputsearch%' OR id IN (SELECT stream_id FROM tags WHERE material_id IN
                (SELECT id FROM substance WHERE CONCAT(name,name_fr,name_nl) LIKE '%$inputsearch%') OR function_id IN (SELECT id FROM materialFunction WHERE CONCAT(name,name_fr,name_nl) LIKE '%$inputsearch%') ))")->get();
            if (count($buildings) == 0) {
                return back()->with('error', __('Nothing found'));
            } else {
                array_push($buildArray, $buildings);
            }        }

        if ($inputsearch != null && $locationInput != null) {
            foreach ($locationInput as $location) {
                $buildings = DB::table('building')
                    ->whereRaw("city LIKE '%$location%' and (id IN (SELECT buildid FROM streams WHERE name LIKE '%$inputsearch%' OR description LIKE '%$inputsearch%' OR id IN (SELECT stream_id FROM tags WHERE material_id IN
                (SELECT id FROM substance WHERE CONCAT(name,name_fr,name_nl) LIKE '%$inputsearch%') OR function_id IN (SELECT id FROM materialFunction WHERE CONCAT(name,name_fr,name_nl) LIKE '%$inputsearch%') )))")->get();
                array_push($buildArray, $buildings);
                if (count($buildings) == 0) {
                    return back()->with('error', __('Nothing found'));
                } else {
                    array_push($buildArray, $buildings);
                }
            }
        }

        if ($locationInput != null && $substanceInput == null && $functionInput == null && $inputsearch == null) {
            foreach ($locationInput as $location) {
                $buildings = DB::table('building')
                    ->whereRaw("city LIKE '%$location%'")->get();
                if (count($buildings) == 0) {
                    return back()->with('error', __('Nothing found'));
                } else {
                    array_push($buildArray, $buildings);
                    array_push($locationTagArray, $location);
                }
            }
        }

        $materialLocations = [];
        $buildIds = [];

        if ($buildArray != null) {
            foreach ($buildArray as $buildings) {
                if (count($buildings) > 0) {
                    foreach ($buildings as $building) {
                        array_push($materialLocations,
                            \GoogleMaps::load('geocoding')
                                ->setParam(['address' => $building->address1 . ' ' . $building->city . ' ' . $building->postcode,
                                ])
                                ->get()
                        );
                        array_push($buildIds, $building->id);
                    }
                }
            }
        } else {
            return back()->with('error', __('Nothing found'));
        }

        return back()->with(
            ['mysearch' => $inputsearch,
                'materialLocations' => $materialLocations,
                'buildIds' => $buildIds,
                'materialTagArray' => $materialTagArray,
                'functionTagArray' => $functionTagArray,
                'inputsearch' => $inputsearch
            ]);
    }

    public function editUserInfo(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->first();
        if ($request->input('firstName') != null) {
            $user->setFirstName($request->input('firstName'));
        }

        if ($request->input('Email') != null) {
            $user->setEmail($request->input('Email'));
        }

        if ($request->input('lastName') != null) {
            $user->setLastName($request->input('lastName'));
        }
        $user->save();

        return back()->withErrors('success', __('successfully updated your info'));
    }
}
