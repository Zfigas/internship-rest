<?php
namespace AppBundle\Entity;

class Result
{

    public $name;
    public $repoName;   
    public $fileName;
    
    function getName() {
        return $this->name;
    }

    function getRepoName() {
        return $this->repoName;
    }

    function getFileName() {
        return $this->fileName;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setRepoName($repoName) {
        $this->repoName = $repoName;
    }

    function setFileName($fileName) {
        $this->fileName = $fileName;
    }


    }

   
    


