<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\DossierRepository;
use App\Entity\Dossier;
use App\Form\DossierType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class DossierController extends AbstractController
{
    /**
     * @Route("/api/dossier", name="api_dossier_index", methods={"GET"})
     */
    public function index(DossierRepository $dossierRepository)
    {
        //on recupere la liste des dossiers
        $dossiers = $dossierRepository-> myFindAll();


        // SERIELASATION

        //On utilise un encoder en json
        $encoders = [ new JsonEncoder()];

        // On instancie le "normaliseur" pour convertir la collection tableau
        $normalizers = [ new ObjectNormalizer()];

        //On fait la convertion en json
        //On instancie le convertisseur 
        $serialiser = new  Serializer ($normalizers,  $encoders);

        //On convertir en json  
        $jsonContenu = $serialiser->serialize($dossiers , 'json',[
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
     * Ajout d'un dossier
     * 
     * @Route("/api/dossier", name="api_create", methods={"POST"})
     */
    public function postDossier(Request $request,  EntityManagerInterface $em) 
    {
        
        //On instancie un dossier
        $dossier = new Dossier();
        $dossier->setDatedebut(new \DateTime());
        
        //On recupere le Json
        $jsonRecu = $request->getContent();

        //On decode le Json
        $data = json_decode($jsonRecu, true);
        
        //On hidrate le formulaire
        $form = $this->createForm(DossierType::class, $dossier);

        //On soumet le formulaire
            $form->submit($data);
            
            $em->persist($dossier);
            $em->flush();
            return $this->redirectToRoute('api_dossier_index'); 
        
      
    }

}
