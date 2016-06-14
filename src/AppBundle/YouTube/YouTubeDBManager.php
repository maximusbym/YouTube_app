<?php
namespace AppBundle\YouTube;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Tag;
use AppBundle\Entity\Video;

class YouTubeDBManager
{
    public $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function updateTags(array $tags)
    {
        $em = $this->em;

        $query = $em->createQuery('DELETE AppBundle:Tag t');
        $query->execute();

        $tagsRes = array();
        
        foreach( $tags as $tagData ) {
            
            $tag = new Tag();
            $tagsRes[] = $tag;
            $tag->setTitle($tagData['tag']);
            $tag->setResultsCount( count($tagData['videos']) );
            
            $ratingSum = 0;
            
            foreach( $tagData['videos'] as $videoId => $videoData ) {
                
                $video = new Video();
                $video->setLink($videoId);
                $video->setTitle($videoData['title']);
                $video->setDescription($videoData['description']);
                $video->setRating( $videoData['rating'] == null ? 0 : $videoData['rating'] );
                $video->setTag($tag);

                $ratingSum += $videoData['rating'];
                
                $em->persist($video);
            }

            $tag->setAVGRating( round( $ratingSum / count($tagData['videos']), 2 ) );
            
            $em->persist($tag);
        }

        $em->flush();

        return $tagsRes;
    }







}