<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\DossierRepository;
use App\Entity\Dossier;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;

class DossierController extends AbstractController
{
    /**
     * @Route("/api/dossier", name="api_dossier_index", methods={"GET"})
     */
    public function index(DossierRepository $dossierRepository)
    {
        $dossiers = $dossierRepository-> myFindAll();

        // Serialisation
        // creation d"une veritable response
        $response = $this->json($dossiers,200,[]);
        return  $response;
    }

    /**
     * @Route("/api/dossier", name="api_dossier_create", methods={"POST"})
     */
    public function postDossier(Request $request, SerializerInterface $serialiser, EntityManagerInterface $em)
    {
        //DESERIALISATION

        // On recupere les donnees en json
        $codeJesonRecu = $request->getContent();

        try {

            // Deserialisation du Json 
            $dossier =$serialiser->deserialize($codeJesonRecu , Dossier::class,'json');
            $dossier->setDatedebut(new \Date());
            
             // Verification si erreur avant de persister  
            $validator = $this->get('validator');
            $errors = $validator->validate($dossier);

            if (count($errors) > 0) {
                $errorsString = (string) $errors;

                return new JsonResponse($errorsString);
            }
            $em->persist($dossier);
            $em->flush();
            $response = $this->json($dossier,201,[]); 
        
        } catch (NotEncodableValueException $e){
            return  $this->json( [
                'status'=> 400,
                'message'=>  $e->getMessage() ,
            ],400) ;

        }
    }

}
