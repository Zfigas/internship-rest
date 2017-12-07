<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use AppBundle\Entity\Hub;
use AppBundle\Entity\Result;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\Controller\Annotations\RequestParam;

class RestController extends FOSRestController {
    
     /**
     *getSearch method returns data inserted into url example "http://localhost:8000/search/tea/amountOfresults/25/page/2". 
     *getSearch and postSearch are nearly the same one of them takes data from session other is sent by request.
     *I should have made it into one method. I am sorry I didnt have time to do it.
     * 
     * @ApiDoc(
     *   resource = true,
     *   description = "Data sent from query",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when error"
     *   }
     * )
     * @RequestParam(name="search", nullable=false, strict=true, description="search")
     * @RequestParam(name="nrOfResult", nullable=false, strict=true, description="nrOfResult")
     * @RequestParam(name="sort", nullable=true, strict=true, description="sort")
     * @RequestParam(name="page", nullable=false, strict=true, description="page")
     * @return View
     */ 
 public function getSearchAction() {   
             $session = $this->getRequest()->getSession();
             $data = $session->get('hub');
            if ($data !=null){
             $hub = new Hub();
             $hub->setSearch($data['search']);
             $hub->setNrOfResult($data['nrOfResult']);
             $hub->setSort(null);
             $hub->setPage($data['page']);
            if ($hub->getSort()!=null){
             $url = 'https://api.github.com/search/repositories?q='.$hub->getSearch().'&sort='.$hub->getSort();
            }
            else if ($hub->getSort()==null){
             $url = 'https://api.github.com/search/repositories?q='.$hub->getSearch();                         
            }
            $ch = curl_init();            
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [  "Accept: application/vnd.github.v3+json",
            "Content-Type: text/plain",
            "User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 YaBrowser/16.3.0.7146 Yowser/2.5 Safari/537.36" ]);
            curl_setopt($ch, CURLOPT_URL, $url);           
            $result = curl_exec($ch);
            curl_close($ch);
            
            $decode = json_decode($result,true);             
             $list = array(); 
             $nrOfResult = $hub->getNrOfResult();
             $page =$hub->getPage();
            $count= count($decode['items']);
               for ($x = 1; $x <= $nrOfResult; $x++) {
                if ($page==1){               
                $results= new Result();
                $results->setName($decode['items'][$x]['owner']['login']);
                $results->setRepoName($decode['items'][$x]['full_name']);
                $results->setFileName($decode['items'][$x]['name']);
                  $list[]=$results;
                }
                if($page>=2 && $count>$x+($nrOfResult*($page-1))){
                $results= new Result();
                $results->setName($decode['items'][$x+($nrOfResult*($page-1))]['owner']['login']);                
                $results->setRepoName($decode['items'][$x+($nrOfResult*($page-1))]['full_name']);
                $results->setFileName($decode['items'][$x+($nrOfResult*($page-1))]['name']);
                  $list[]=$results;
                }
                else{
                    // do nothing
                }
                }         
           return new JsonResponse(array($list), 200);
    }
    return new JsonResponse(array('message' => 'no'), 404);
    
     
 }
 
    /**
     * postSearch method takes data sent from DefaultController, creates from it github link and sends back data to default controller. 
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Data sent from default controller",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when error"
     *   }
     * )
     * @RequestParam(name="search", nullable=false, strict=true, description="search")
     * @RequestParam(name="nrOfResult", nullable=false, strict=true, description="nrOfResult")
     * @RequestParam(name="sort", nullable=true, strict=true, description="sort")
     * @RequestParam(name="page", nullable=false, strict=true, description="page")
     * @return View
     */
    public function postSearchAction(Request $request) {
           $data = $request->request->all();       
           if ($data !=null){
             $hub = new Hub();
             $hub->setSearch($data['hub']['search']);
             $hub->setNrOfResult($data['hub']['nrOfResult']);
             $hub->setSort($data['hub']['sort']);
             $hub->setPage($data['hub']['page']);
             if ($hub->getSort()!=null){
             $url = 'https://api.github.com/search/repositories?q='.$hub->getSearch().'&sort='.$hub->getSort();
        }
             else if ($hub->getSort()==null){
            $url = 'https://api.github.com/search/repositories?q='.$hub->getSearch(); 
                        
            }
            $ch = curl_init();            
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [  "Accept: application/vnd.github.v3+json",
            "Content-Type: text/plain",
            "User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 YaBrowser/16.3.0.7146 Yowser/2.5 Safari/537.36" ]);
            curl_setopt($ch, CURLOPT_URL, $url);           
            $result = curl_exec($ch);
            curl_close($ch);
            
            $decode = json_decode($result,true);             
            $list = array(); 
            $nrOfResult = $hub->getNrOfResult();
            $page =$hub->getPage();
            $count= count($decode['items']);
               for ($x = 1; $x <= $nrOfResult; $x++) {
                if ($page==1){               
                $results= new Result();
                $results->setName($decode['items'][$x]['owner']['login']);
                $results->setRepoName($decode['items'][$x]['full_name']);
                $results->setFileName($decode['items'][$x]['name']);
                  $list[]=$results;
                }
                if($page>=2 && $count>$x+($nrOfResult*($page-1))){
                $results= new Result();
                $results->setName($decode['items'][$x+($nrOfResult*($page-1))]['owner']['login']);                
                $results->setRepoName($decode['items'][$x+($nrOfResult*($page-1))]['full_name']);
                $results->setFileName($decode['items'][$x+($nrOfResult*($page-1))]['name']);
                  $list[]=$results;
                }
                else{
                   // do nothing
                }
                }
           return new JsonResponse(array($list), 200);
    }
    return new JsonResponse(array('message' => 'no'), 404);
    
    }

 

}
