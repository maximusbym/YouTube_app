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
        $tags = $this->getDoctrine()->getRepository('AppBundle:Tag')->findAll();
        
        return $this->render('default/index.html.twig', [
            'form' => $form->createView(), 
//            'token' => $request->getSession()->has('youtube_token'),
            'tags' => $tags
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

            $youTubeDBManager = $this->get('app.youtube_dbmanager');
            $tags = $youTubeDBManager->updateTags($tags);
            
        }

        $template = $this->render('YouTube/tagsRows.html.twig', array('tags'=>$tags))->getContent();

        $response = new JsonResponse();
        $response->setData( array( 'newTagsTemplate' => $template ) );

        return $response;
    }


    
    
    
}
