<?php

namespace App\Jobs;

use App\Models\Member;
use Date;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ProcessNewTwitchFollowers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $apiURL = "https://api.twitch.tv/helix/channels/followers?broadcaster_id=565636132";
    private array $followers = [];
    private string $token;
    private string $clientId;
    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->token = config('app.twitch_api_token');
        $this->clientId = config('app.twitch_client_id');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->getFollowers();
        $this->replaceWithMissingFollowers();
    }

    public function getFollowers(): void
    {

        $response = Http::withHeaders([
            'Client-Id' => $this->clientId,
            'Authorization' => 'Bearer ' . $this->token,
        ])->get($this->apiURL);
        
        $this->followers = array_merge($this->followers, $response->json()['data']);

        if (isset($response->json()['pagination']['cursor'])) {
            $this->apiURL .= "&after=" . $response->json()['pagination']['cursor'];
            $this->getFollowers();
        }

        // sort the followers by followed_at ASC
        usort($this->followers, function ($a, $b) {
            return $a['followed_at'] <=> $b['followed_at'];
        });
    }

    public function replaceWithMissingFollowers(): void
    {
        /** @var \Illuminate\Database\Eloquent\Collection */
        $followers = Member::whereIn('user_id', array_column($this->followers, 'user_id'))->get();
        // find those followers that are not in the database
        $missingFollowers = collect($this->followers)->filter(function ($follower) use ($followers) {
            return !$followers->contains('user_id', $follower['user_id']);
        });

        // chunk the followers into 100s
        $missingFollowers->chunk(100)->each(function ($followers) {

            // since the twitch API can only get 100 users at a time, build a query string with all the user_ids 
            $userIds = $followers->pluck('user_id')->toArray();
            print_r($userIds);
            $queryString = "?id=" . implode("&id=", $userIds);   


            $response = Http::withHeaders([
                'Client-Id' => $this->clientId,
                'Authorization' => 'Bearer ' . $this->token,
            ])->get(sprintf('https://api.twitch.tv/helix/users%s', $queryString));

            // print the response
            print_r($response->json());
            
            
            // $followers->each(function ($follower) {
            //     $response = Http::withHeaders([
            //         'Client-Id' => $this->clientId,
            //         'Authorization' => 'Bearer ' . $this->token,
            //     ])->get(sprintf('https://api.twitch.tv/helix/users?login=%s', $follower->display_name));
        
            //     $responseData = $response->json()['data'][0];
            //     $follower->avatar = $responseData["profile_image_url"];
            //     $follower->save();
            // });
        });





        // $temp = [];
        // foreach ($this->followers as $follower) {
        //     $response = Http::withHeaders([
        //         'Client-Id' => $this->clientId,
        //         'Authorization' => 'Bearer ' . $this->token,
        //     ])->get(sprintf('https://api.twitch.tv/helix/users?login=%s', $follower['user_login']));
    
        //     $responseData = $response->json()['data'][0];
        //     $follower["profile_image_url"] = $responseData["profile_image_url"];

        //     // Find a member with the user_id
        //     $member = Member::where('user_id', $follower['user_id'])->first();

        //     if ($member) {
        //         if ($member->avatar !== $responseData['profile_image_url']) {
        //             $member->update([
        //                 'avatar' => $responseData['profile_image_url']
        //             ]);
        //         }
        //     } else {
        //         // if there is no member, create a new member
        //         Member::create([
        //             'user_id' => $follower['user_id'],
        //             'display_name' => $follower['user_name'],
        //             'avatar' => $responseData['profile_image_url'],
        //             'followed_at' => Date::parse($follower['followed_at'])
        //         ]);
        //     }
        // };
    }
}
