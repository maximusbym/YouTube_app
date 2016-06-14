<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Form\TagsType;
use AppBundle\YouTube\YouTubeSearcher;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(TagsType::class);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();

//            $this->get('app.comments_manager')->saveComment($data);

            unset($form);
            $form = $this->createForm(TagsType::class);
        }

//        var_dump($request->getSession()->remove('youtube_token'));
        
        return $this->render('default/index.html.twig', [
            'form' => $form->createView(), 'token' => $request->getSession()->has('youtube_token')
        ]);
    }
    
    /**
     * @Route("/search-tags.json", name="ajax_tagsSearch")
     */
    public function tagsSearchAction(Request $request)
    {
        $tags = array();
        
        $form = $this->createForm(TagsType::class);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();

            $youTubeSearcher = $this->get('app.youtube_search');
            $tags = $youTubeSearcher->searchTags($data['tags']);

            echo $tags[0]['error'];

//            var_dump($tags);
//            $this->get('app.comments_manager')->saveComment($data);

        }

        $response = new JsonResponse();

        $response->setData( array( 'data' => $tags ) );

        return $response;
    }


    
    
    
}
