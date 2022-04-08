<?php

namespace App\Controller;

use App\Repository\ExerciceRepository;
use App\Entity\Exercice;
use App\Entity\Operation;
use App\Form\ExerciceType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class ExerciceController extends AbstractController
{
    /**
     * @Route("/api/exercice", name="api_exercice_index", methods={"GET"})
     */
    public function index(ExerciceRepository $exerciceRepository)
    {
         //on recupere la liste des exercices
         $exercices = $exerciceRepository-> myFindAll();


         // SERIELASATION
 
         //On utilise un encoder en json
         $encoders = [ new JsonEncoder()];
 
         // On instancie le "normaliseur" pour convertir la collection tableau
         $normalizers = [ new ObjectNormalizer()];
 
         //On fait la convertion en json
         //On instancie le convertisseur 
         $serialiser = new  Serializer ($normalizers,  $encoders);
 
         //On convertir en json  
         $jsonContenu = $serialiser->serialize($exercices , 'json',[
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
     * @Route("/api/exercice", name="api_exercice_create", methods={"POST"})
     */
    public function postExercice(Request $request,EntityManagerInterface $em)
    {
        //On instancie un Exercice
        $exercice = new Exercice();
        $exercice-> setDatedebut(new \DateTime());
        $exercice-> setDatefin(new \DateTime());
        $exercice-> setAnneecivil(new \DateTime());
      
        
        //On recupere le Json
        $jsonRecu = $request->getContent();

        //On decode le Json
        $data = json_decode($jsonRecu, true);
        
        //On hidrate le formulaire
        $form = $this->createForm(EcritureType::class, $exercice);

        //On soumet le formulaire
            $form->submit($data);
            
            $em->persist($exercice);
            $em->flush();
            return $this->redirectToRoute('api_exercice_index'); 
        
    }


     /**
     * Enregistrement d'une opération comtable dans un exercice
     * @Route("/api/exercice/{id}", name="api_exercice_modif", methods={"PUT"})
     */
    public function putExercice(Request $request, Exercice $exercice, EntityManagerInterface $em)
    {
        $operation = new Operation();
        $codeJesonRecu = $request->getContent();
        $exercice->addOperation($operation);

        $data = json_decode($codeJesonRecu , true);

        $form = $this->createForm(ExerciceType::class, $exercice);
        $form->submit($data);
        $em->persist($exercice);
        $em->flush();
        return $this->redirectToRoute('api_exercice_index'); 
     
    }



}
