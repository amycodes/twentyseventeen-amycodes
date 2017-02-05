<?php

/**
 * Description of TwitterFeed
 *
 * @author amynegrette
 */
include_once 'iSocialFeed.php';
include_once 'lib/Twitter-API/TwitterAPIExchange.php';
include_once 'TumblrPost.php';

class TumblrFeed implements iSocialFeed {

    private $settings;

    public function __construct() {
        $this->settings = array(
            'oauth_access_token' => "XupWkzhmfOP3kjEyISRGhTBUn54xEoxk4HJYtdgcwa7azhfnRd",
            'oauth_access_token_secret' => "l3PP4JFtVS9ujojOymoM5fcQsDMufCnxtmSoLE5cvTfKXmCaxz",
            'consumer_key' => "XupWkzhmfOP3kjEyISRGhTBUn54xEoxk4HJYtdgcwa7azhfnRd",
            'consumer_secret' => "l3PP4JFtVS9ujojOymoM5fcQsDMufCnxtmSoLE5cvTfKXmCaxz"
            );
    }

    public function getFeedPosts() {
        $url = 'api.tumblr.com/v2/blog/amycodes.tumblr.com/posts';
        $getfield = '?api_key=' . $this->settings['consumer_key'];
        $requestMethod = 'GET';

        $tumblr = new TwitterAPIExchange($this->settings);
        $response = json_decode($tumblr->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest());
        $posts = array();
        foreach ($response as $r) {
            if (isset($r->posts)) { 
                foreach ( $r->posts as $post ) {
                    $type = $post->type;
                    $content = NULL;
                    if ( $type == "quote") {
                       $content = array ( "text" => $post->text, "source" => $post->source );
                    } else if ( $type == "text" ) {
                       $content = array ( "body" => $post->body );
                    } else if ( $type == "photo" ) {
                        $photos = array();
                        foreach ( $post->photos as $photo ) {
                            $photos[] = $photo->original_size;
                        }
                        $content = array ( "caption" => $post->caption, "photos" => $photos );
                    } else if ( $type == "chat" ) {
                        $content = array ( "body" => $post->body );
                    }
                    if ( $content != NULL ) {
                        $tumblr_post = new TumblrPost(date_create($post->date), "Tumblr", $post->post_url, $post->type, $content);
                        $posts[] = $tumblr_post;
                    }
                }
            }
        }
        return $posts;
    }

}
