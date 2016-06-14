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
     * @Route("/get-tag/{id}.json", name="ajax_getTagData")
     */
    public function getTagDataAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $tag = $em->getRepository('AppBundle:Tag')->find($id);

        $templateTag = $this->render('YouTube/tagDetailsRow.html.twig', array('tag'=>$tag))->getContent();
        $templateVideos = $this->render('YouTube/videoRows.html.twig', array('tag'=>$tag))->getContent();
        
        
        $response = new JsonResponse();
        $response->setData( array( 'templateTag' => $templateTag,  'templateVideos' => $templateVideos  ) );

        return $response;
    }


    /*
     * Methods below for YouTube OAuth2 auth are not used.
     */

//    /**
//     * YouTube OAuth2 CallBack
//     * @Route("/youtube-callback", name="youtubeCallback")
//     */
//    public function youtubeCallbackAction(Request $request)
//    {
//        $youTubeSearcher = $this->get('app.youtube_search');
//        $client = $youTubeSearcher->initGoogleClient();
//        
//        if( $request->query->has('code') ) {
//            $youTubeSearcher->handleAuthCallBack( $client, $request->query->get('code') );
//        }
//
//        return $this->redirectToRoute('homepage');
//    }

//    /**
//     * YouTube OAuth2 URL
//     * @Route("/auth-url.json", name="ajax_getAuthUrl")
//     */
//    public function getAuthUrlAction(Request $request)
//    {
//        $youTubeSearcher = $this->get('app.youtube_search');
//
//        $client = $youTubeSearcher->initGoogleClient();
//        $url = $youTubeSearcher->getAuthUrl($client);
//
//        $response = new JsonResponse();
//        $response->setData( array( 'url' => $url ) );
//
//        return $response;
//    }

}
