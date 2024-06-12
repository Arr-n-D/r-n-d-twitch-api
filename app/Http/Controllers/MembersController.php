<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Testing\Fakes\Fake;

class MembersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Member::all();
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Member::create([
            'user_id' => $request->user_id,
            'display_name' => $request->displayName,
            'avatar' => $request->avatar,
            // date now in Montreal timezone EST
            'followed_at' => Date::now('America/Montreal')
        ]);

        return response()->json($user, 201);
    }

    public function getFakeData() {
        $fake = fake();
        // create fake data to return to the user too, for the application
        $fakeData = [
            "last_name" => $fake->lastName(),
            "address" => $fake->address(),
            "phone" => $fake->phoneNumber(),
            "email" => $fake->email(),
            // Random bool value
            "license" => $fake->boolean(),
            // fake license class
            "license_class" => $fake->randomElement(['A', 'B', 'C', 'D', 'E', 'F']),
            // fake boolean
            "license_suspended" => $fake->boolean(),
            "revoking_reason" => "I'm a terrible driver, really",
            "current_employer" => $fake->company(),
            "employer_name" => $fake->name(),
            "employer_address" => $fake->address(),
            "employer_postcode" => $fake->postcode(),
            "employer_email" => $fake->email(),
            "employer_phone" => $fake->phoneNumber(),
            "references" => [
                $fake->name(),
                $fake->name(),
                $fake->name(),
                $fake->name(),
                $fake->name(),
                $fake->name(),
                "R&D"
            ]
            
        ];
        
        return  response()->json($fakeData, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        //
    }

    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        //
    }
}
