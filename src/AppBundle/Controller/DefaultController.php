<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Form\TagsType;
use AppBundle\Utils\YouTubeSearcher;

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

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
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

            $apiKey = $this->getParameter('google_api_key');
            $clientSecretPath = $request->server->get('DOCUMENT_ROOT') . "/.." . $this->getParameter('google_client_secret_path');
            $youTubeSearcher = new YouTubeSearcher($apiKey, $clientSecretPath);
            $tags = $youTubeSearcher->searchTags($data['tags']);

            echo $tags['error'];

            var_dump($tags);
//            $this->get('app.comments_manager')->saveComment($data);

        }

        $response = new JsonResponse();

        $response->setData( array( 'data' => $tags ) );

        return $response;
    }


    
    
    
}
