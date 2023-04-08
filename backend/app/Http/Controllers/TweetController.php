<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Atymic\Twitter\Facade\Twitter;

class TweetController extends Controller
{
    public function fetchTweets(Request $request)
    {
        $hashtag = $request->input('hashtag');

        $tweets = Twitter::getSearch([
            'q' => $hashtag,
            'count' => 300,
            'result_type' => 'mixed',
        ]);

        // Sort the tweets by the number of likes and retweets
        usort($tweets->statuses, function ($a, $b) {
            $a_score = $a->retweet_count + $a->favorite_count;
            $b_score = $b->retweet_count + $b->favorite_count;

            return $b_score - $a_score;
        });

        // Create an array of tweet objects with the required information
        $tweet_objects = [];
        foreach ($tweets->statuses as $tweet) {
            $tweet_object = [
                'text_content' => $tweet->text,
                'likes' => $tweet->favorite_count,
                'retweets' => $tweet->retweet_count,
                'images' => [],
                'user' => [
                    'handle' => $tweet->user->screen_name,
                    'profile_picture' => $tweet->user->profile_image_url_https,
                ],
            ];

            // If the tweet has images, add them to the array of images in the tweet object
            if (isset($tweet->extended_entities) && isset($tweet->extended_entities->media)) {
                foreach ($tweet->extended_entities->media as $media) {
                    if ($media->type == 'photo') {
                        $tweet_object['images'][] = $media->media_url_https;
                    }
                }
            }

            $tweet_objects[] = $tweet_object;
        }

        return response()->json($tweet_objects);
    }
}
