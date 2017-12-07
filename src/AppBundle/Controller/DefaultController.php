<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\HubType;
use AppBundle\Entity\Hub;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Serializer\SerializerInterface;

class DefaultController extends Controller
{    
    
/*
 * 
 *Index action which takes data from fields sends it to rest controller. Controller generates github api and returns array.
 * 
*/
    public function indexAction()
    {
        $hub = new Hub();
        $form = $this->createForm(new HubType, $hub);
        $form->get('nrOfResult')->setData('25');
        $form->get('page')->setData('1');
        $request = $this->get('request');
        $form->handleRequest($request);        
        if ($request->getMethod() == 'POST'){
            if ($form->isValid())
                {        
        $data = $request->request->all();                  
        $array = array( 
            'search'=> $data['hub']['search'], 
            'nrOfResult' => $data['hub']['nrOfResult'], 
            'sort' => $data['hub']['sort'], 
            'page' => $data['hub']['page'], );  
           
         $response = $this->forward('AppBundle:Rest:postSearch', array('hub' =>$array));  
        $result = explode("\r\n\r\n", $response, 2);
        $results = json_decode($result[1],true);
        //echo '<pre>'; print_r($response); echo '</pre>';    
        return $this->forward('AppBundle:Result:index', array('results' =>$results));
        }
    }
     return $this->render('default/index.html.twig', array('form'=>$form->createView()));
    }


}