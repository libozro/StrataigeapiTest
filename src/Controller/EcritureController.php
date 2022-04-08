<?php

namespace App\Controller;

use App\Repository\EcritureRepository;
use App\Entity\Ecriture;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

class EcritureController extends AbstractController
{
    /**
     * @Route("/api/journal", name="api_ecriture_index", methods={"GET"})
     */
    public function index(EcritureRepository $ecritureRepository)
    {
        $ecritures = $ecritureRepository-> myFindAll();

        // Serialisation
        // creation d"une veritable response
        $response = $this->json($ecritures,200, [] , ['groups' =>'ecriture:read']);
        return  $response;
    }

    
    /**
     * @Route("/api/ecriture", name="api_ecri_create", methods={"POST"})
     */
    public function postEcriture(Request $request, SerializerInterface $serialiser, EntityManagerInterface $em)
    {
        //DESERIALISATION

        // On recupere les donnees en json
        $codeJesonRecu = $request->getContent();

        try {

            // Deserialisation du Json 
            $ecriture =$serialiser->deserialize($codeJesonRecu , Ecriture::class,'json');
            $ecriture->setDate(new \Date());
          
            
             // Verification si erreur avant de persister  
            $validator = $this->get('validator');
            $errors = $validator->validate($exercice);

            if (count($errors) > 0) {
                $errorsString = (string) $errors;

                return new JsonResponse($errorsString);
            }
            $em->persist($ecriture);
            $em->flush();
            $response = $this->json($ecriture,201,[], ['groups'=>'ecriture:read']); 
        
        } catch (NotEncodableValueException $e){
            return  $this->json( [
                'status'=> 400,
                'message'=>  $e->getMessage() ,
            ],400) ;

        }
    }

}
