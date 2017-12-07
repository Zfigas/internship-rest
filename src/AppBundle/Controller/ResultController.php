<?php

namespace AppBundle\Controller;
use AppBundle\Entity\Hub;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
class ResultController extends Controller
{
    
 /*
  * indexAction returns data for Default controller.
  */
    public function indexAction(array $results)
    {   
     return $this->render('default/result.html.twig', array ('results'=>$results));
    }
    
 /*
  * resultAction is made for searching by typing in url.
  */   
    public function resultAction($search, $amountOfResults, $page)
    {
    $hub = new Hub();
    $hub->setSearch($search);
    $hub->setNrOfResult($amountOfResults);   
    $hub->setSort('null');
    $hub->setPage($page);  
    $array = array( 
            'search'=> $search, 
            'nrOfResult' => $amountOfResults, 
            'sort' => $hub->getSearch(), 
            'page' => $page, );  
    $session = $this->getRequest()->getSession();
    $session->set('hub', $array);
    $response = $this->forward('AppBundle:Rest:getSearch', array('hub' =>$array));         
     $result = explode("\r\n\r\n", $response, 2);
     $results = json_decode($result[1],true);
           
     return $this->render('default/result.html.twig', array ('results'=>$results));
    }
   }