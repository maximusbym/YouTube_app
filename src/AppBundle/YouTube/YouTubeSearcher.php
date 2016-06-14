<?php
namespace AppBundle\YouTube;


use Google_Client, Google_Service_YouTube, Google_Service_Exception, Google_Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class YouTubeSearcher
{
    public $DEVELOPER_KEY;
    public $CLIENT_SECRET_PATH;
    public $TOKEN;
    public $request;

    public function __construct($api_key, $client_secret_path, RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->DEVELOPER_KEY = $api_key;
        $this->CLIENT_SECRET_PATH = $this->request->server->get('DOCUMENT_ROOT') . "/.." . $client_secret_path;
        $this->TOKEN = $this->request->getSession()->has('youtube_token') ?
            $this->request->getSession()->get('youtube_token') : null ;
    }

    public function searchTags($tagsString)
    {
        $tags = explode(',', $tagsString);
        $tagsRes = array();

        $client = $this->initGoogleClient();
        
        if( !$client->getAccessToken() ) {
            $this->auth($client);
        }
        
        $youtube = new Google_Service_YouTube($client);
        
        foreach ( $tags as $key=>$val ) {
            $tag = trim($val);
            $tagsRes[$key] = array(
                'tag' => $tag,
                'videos' => array(),
                'error' => null,
            );


            try {
                $searchResponse = $youtube->search->listSearch('id,snippet', array(
                    'q' => $tag,
                    'maxResults' => 10,
//                    'order' => 'rating'
                ));

                $videos = array();
                $videoIDs = '';

                foreach ($searchResponse['items'] as $searchResult) {
                    switch ($searchResult['id']['kind']) {
                        case 'youtube#video':
                            $videos[$searchResult['id']['videoId']] = array(
                                'description' => $searchResult['snippet']['title'],
                            );
                            $videoIDs .= $searchResult['id']['videoId'].',';
                            break;
                    }
                    if (count($videos) > 10) break;
                }

                $videoRatings = $youtube->videos->listVideos('id,statistics', array(
                    'id' => $videoIDs
                ));

                foreach( $videoRatings as $videoRating ) {
                    $videos[$videoRating->id]['rating'] = $videoRating->statistics->likeCount;
                }

                $tagsRes[$key]['videos'] = $videos;
                
            } catch (Google_Service_Exception $e) {
                $tagsRes[$key]['error'] = sprintf('<p>A service error occurred: <code>%s</code></p>',
                    htmlspecialchars($e->getMessage()));
            } catch (Google_Exception $e) {
                $tagsRes[$key]['error'] = sprintf('<p>An client error occurred: <code>%s</code></p>',
                    htmlspecialchars($e->getMessage()));
            }
        }
        
        
        return $tagsRes;
    }


    public function initGoogleClient()
    {
        $client = new Google_Client();
        $client->setDeveloperKey($this->DEVELOPER_KEY);
        $client->setAuthConfigFile($this->CLIENT_SECRET_PATH);

        if( $this->TOKEN ) {
            $client->setAccessToken($this->TOKEN);
        }
        else {
            $client->addScope(Google_Service_YouTube::YOUTUBE);
            $client->setRedirectUri('http://' . $this->request->server->get('HTTP_HOST') . '/youtube-callback');
            $client->setAccessType("offline");
        }
        
        return $client;
    }

    public function auth(Google_Client $client)
    {
        $client->addScope(Google_Service_YouTube::YOUTUBE_READONLY);
        $client->setRedirectUri('http://' . $this->request->server->get('HTTP_HOST') . '/youtube-callback');
        $auth_url = $client->createAuthUrl();
        return new RedirectResponse( filter_var($auth_url, FILTER_SANITIZE_URL) );
    }
    
    public function getAuthUrl(Google_Client $client)
    {
        $auth_url = $client->createAuthUrl();
        return filter_var($auth_url, FILTER_SANITIZE_URL);
    }

    public function handleAuthCallBack(Google_Client $client, $code)
    {
        $client->authenticate( $code );
        $access_token = $client->getAccessToken();
        if( $access_token != null ) {
            $this->request->getSession()->set('youtube_token', $access_token);
        }

        return array('token'=>$access_token);
    }





}