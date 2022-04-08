<?php

namespace App\Controller;


use App\Repository\OperationRepository;
use App\Entity\Operation;
use App\Form\OperationType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class OperationController extends AbstractController
{
    /**
     * @Route("/api/operation", name="api_operation_index", methods={"GET"})
     */
    public function index(OperationRepository $operationRepository)
    {
        //on recupere la liste des exercices
        $operations = $operationRepository-> myFindAll();


        // SERIELASATION

        //On utilise un encoder en json
        $encoders = [ new JsonEncoder()];

        // On instancie le "normaliseur" pour convertir la collection tableau
        $normalizers = [ new ObjectNormalizer()];

        //On fait la convertion en json
        //On instancie le convertisseur 
        $serialiser = new  Serializer ($normalizers,  $encoders);

        //On convertir en json  
        $jsonContenu = $serialiser->serialize($operations , 'json',[
            'circulaire_reference_hander' => function($object){
                return $object-> getId() ; 
            }
        ]) ;
        
        //On instancie la reponse
        $response = new Response($jsonContenu);
         
        //On ajoute l'entete la HTTP
        $response->headers->set('Content-Type', 'application/json');

         //On envoie la reponse
         return  $response ;
        
    }

     /**
     * @Route("/api/operation", name="api_operation_create", methods={"POST"})
     */
    public function postOperation(Request $request,EntityManagerInterface $em )
    {


        //On instancie un Operation
        $operation = new Operation();
        $operation->getEcriture()->setMontant(null);
       
      
        
        //On recupere le Json
        $jsonRecu = $request->getContent();

        //On decode le Json
        $data = json_decode($jsonRecu, true);
        
        //On hidrate le formulaire
        $form = $this->createForm(OperationType::class, $operation);

        //On soumet le formulaire
            $form->submit($data);
            
            $em->persist($operation);
            $em->flush();
            return $this->redirectToRoute('api_operation_index'); 
        
       
    }
}
