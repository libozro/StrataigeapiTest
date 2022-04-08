<?php

namespace App\Controller;

use App\Repository\EcritureRepository;
use App\Entity\Ecriture;
use App\Form\EcritureType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class EcritureController extends AbstractController
{
    /**
     * @Route("/api/journal", name="api_ecriture_index", methods={"GET"})
     */
    public function index(EcritureRepository $ecritureRepository)
    {
        //on recupere la liste des ecritures
        $ecritures = $ecritureRepository-> myFindAll();


        // SERIELASATION

        //On utilise un encoder en json
        $encoders = [ new JsonEncoder()];

        // On instancie le "normaliseur" pour convertir la collection tableau
        $normalizers = [ new ObjectNormalizer()];

        //On fait la convertion en json
        //On instancie le convertisseur 
        $serialiser = new  Serializer ($normalizers,  $encoders);

        //On convertir en json  
        $jsonContenu = $serialiser->serialize($ecritures , 'json',[
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
     * @Route("/api/ecriture", name="api_ecri_create", methods={"POST"})
     */
    public function postEcriture(Request $request, EntityManagerInterface $em)
    {
         //On instancie une Ecriture
         $ecriture = new Ecriture();
         $ecriture->setDate(new \DateTime());
         
         //On recupere le Json
         $jsonRecu = $request->getContent();
 
         //On decode le Json
         $data = json_decode($jsonRecu, true);
         
         //On hidrate le formulaire
         $form = $this->createForm(EcritureType::class, $ecriture);
 
         //On soumet le formulaire
             $form->submit($data);
             
             $em->persist($ecriture);
             $em->flush();
             return $this->redirectToRoute('api_ecriture_index'); 
         
       
     }

}
