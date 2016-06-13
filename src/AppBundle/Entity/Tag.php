<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Tag
 *
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TagRepository")
 */
class Tag
{

    /**
     * @ORM\OneToMany(targetEntity="Video", mappedBy="Tag")
     */
    private $videos;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100)
     */
    private $title;

    /**
     * @var int
     *
     * @ORM\Column(name="results_count", type="integer")
     */
    private $resultsCount;

    /**
     * @var string
     *
     * @ORM\Column(name="avgrating", type="decimal", precision=5, scale=2)
     */
    private $AVGRating;
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Tag
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set resualtsCount
     *
     * @param integer $resualtsCount
     *
     * @return Tag
     */
    public function setResualtsCount($resualtsCount)
    {
        $this->resualtsCount = $resualtsCount;

        return $this;
    }

    /**
     * Get resualtsCount
     *
     * @return int
     */
    public function getResualtsCount()
    {
        return $this->resualtsCount;
    }

    /**
     * Set aVGRating
     *
     * @param string $aVGRating
     *
     * @return Tag
     */
    public function setAVGRating($aVGRating)
    {
        $this->AVGRating = $aVGRating;

        return $this;
    }

    /**
     * Get aVGRating
     *
     * @return string
     */
    public function getAVGRating()
    {
        return $this->AVGRating;
    }

    /**
     * Set resultsCount
     *
     * @param integer $resultsCount
     *
     * @return Tag
     */
    public function setResultsCount($resultsCount)
    {
        $this->resultsCount = $resultsCount;

        return $this;
    }

    /**
     * Get resultsCount
     *
     * @return integer
     */
    public function getResultsCount()
    {
        return $this->resultsCount;
    }

    /**
     * Add video
     *
     * @param \AppBundle\Entity\Video $video
     *
     * @return Tag
     */
    public function addVideo(\AppBundle\Entity\Video $video)
    {
        $this->videos[] = $video;

        return $this;
    }

    /**
     * Remove video
     *
     * @param \AppBundle\Entity\Video $video
     */
    public function removeVideo(\AppBundle\Entity\Video $video)
    {
        $this->videos->removeElement($video);
    }

    /**
     * Get videos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVideos()
    {
        return $this->videos;
    }
}
