<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\YouTube\YouTubeSearcher;

class APIController extends Controller
{

    /**
     * @Route("/youtube-callback", name="youtubeCallback")
     */
    public function youtubeCallbackAction(Request $request)
    {
        $youTubeSearcher = $this->get('app.youtube_search');
        $client = $youTubeSearcher->initGoogleClient();
        
        if( $request->query->has('code') ) {
            $youTubeSearcher->handleAuthCallBack( $client, $request->query->get('code') );
        }

//        $response = new JsonResponse();
//        return $response;
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/auth-url.json", name="ajax_getAuthUrl")
     */
    public function getAuthUrlAction(Request $request)
    {
        $youTubeSearcher = $this->get('app.youtube_search');

        $client = $youTubeSearcher->initGoogleClient();
        $url = $youTubeSearcher->getAuthUrl($client);

        $response = new JsonResponse();
        $response->setData( array( 'url' => $url ) );

        return $response;
    }





}
