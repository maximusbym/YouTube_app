<?php
namespace AppBundle\Utils;


use Google_Client, Google_Service_YouTube, Google_Service_Exception, Google_Exception;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Request;

class YouTubeSearcher
{
    public $DEVELOPER_KEY;
    public $CLIENT_SECRET_PATH;
    public $request;

    public function __construct($api_key, $client_secret)
    {
        $this->DEVELOPER_KEY = $api_key;
        $this->CLIENT_SECRET_PATH = $client_secret;
        $this->request = new Request();
    }

    public function searchTags($tagsString)
    {
        $tags = explode(',', $tagsString);

        $client = $this->initGoogleClient();
        $youtube = new Google_Service_YouTube($client);

        foreach ( $tags as $key=>$val ) {
            $tags[$key] = trim($val);

            try {
                $searchResponse = $youtube->search->listSearch('id,snippet', array(
                    'q' => $tags[$key],
                    'maxResults' => 10,
                    'order' => 'rating'
                ));

                $videos = array();

                foreach ($searchResponse['items'] as $searchResult) {
                    switch ($searchResult['id']['kind']) {
                        case 'youtube#video':
                            //var_dump($searchResult);
                            $videos[] = array(
                                'id' => $searchResult['id']['videoId'],
                                'description' => $searchResult['snippet']['title'],
                                'rating' => $youtube->videos->getRating($searchResult['id']['videoId']),
                            );


//                        $videos .= sprintf('<li>%s (%s)</li>',
//                            $searchResult['snippet']['title'], $searchResult['id']['videoId']);
                            break;
                    }
                    if (count($videos) > 10) break;
                }

                $tags[$key]['videos'] = $videos;
                
            } catch (Google_Service_Exception $e) {
                $tags['error'] = sprintf('<p>A service error occurred: <code>%s</code></p>',
                    htmlspecialchars($e->getMessage()));
            } catch (Google_Exception $e) {
                $tags['error'] = sprintf('<p>An client error occurred: <code>%s</code></p>',
                    htmlspecialchars($e->getMessage()));
            }
        }
        
        
        return $tags;
    }


    public function initGoogleClient()
    {

        $client = new Google_Client();
        $client->setDeveloperKey($this->DEVELOPER_KEY);
        $client->setAuthConfigFile($this->CLIENT_SECRET_PATH);

        if( $this->request->getSession()->has('youtube_token') ) {
            
        }
        else {
            $this->auth($client);
        }

        return $client;
    }

    public function auth($client)
    {
        
        $client->addScope(Google_Service_YouTube::YOUTUBE_READONLY);
        $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php');


        $auth_url = $client->createAuthUrl();
        header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
        
    }





}