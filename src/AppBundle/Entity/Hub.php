<?php
namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Hub
{
    /**
     * @Assert\NotBlank()
     */
    public $search;
    /**
     * @Assert\NotBlank()  
     * @Assert\Type("numeric")  
     */
    public $nrOfResult;
   
    public $sort;
      /**
     * @Assert\NotBlank()
     * @Assert\Type("numeric")
     */
    public $page;
    
    function getSearch() {
        return $this->search;
    }

    function getNrOfResult() {
        return $this->nrOfResult;
    }

    function getSort() {
        return $this->sort;
    }

    function getPage() {
        return $this->page;
    }

    function setSearch($search) {
        $this->search = $search;
    }

    function setNrOfResult($nrOfResult) {
        $this->nrOfResult = $nrOfResult;
    }

    function setSort($sort) {
        $this->sort = $sort;
    }

    function setPage($page) {
        $this->page = $page;
    }


    }

   
    


