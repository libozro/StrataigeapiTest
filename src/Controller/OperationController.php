<?php

namespace App\Controller;


use App\Repository\OperationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class OperationController extends AbstractController
{
    /**
     * @Route("/api/operation", name="api_operation_index", methods={"GET"})
     */
    public function index(OperationRepository $operationRepository)
    {
        $operations = $operationRepository-> myFindAll();

        // Serialisation
        // creation d"une veritable response
        $response = $this->json($operations,200, [] , ['groups' =>'operation:read']);
        return  $response;
    }

     /**
     * @Route("/api/operation", name="api_operation_create", methods={"POST"})
     */
    public function postOperation(Request $request, SerializerInterface $serialiser, EntityManagerInterface $em)
    {
        //DESERIALISATION

        // On recupere les donnees en json
        $codeJesonRecu = $request->getContent();

        try {

            // Deserialisation du Json 
            $operation =$serialiser->deserialize($codeJesonRecu , Operation::class,'json');
            $operation->getEcriture()->setMontant(null);
          
             // Verification si erreur avant de persister  
            $validator = $this->get('validator');
            $errors = $validator->validate($exercice);

            if (count($errors) > 0) {
                $errorsString = (string) $errors;

                return new JsonResponse($errorsString);
            }
            $em->persist($operation);
            $em->flush();
            $response = $this->json($operation,201,[], ['groups'=>'operation:read']); 
        
        } catch (NotEncodableValueException $e){
            return  $this->json( [
                'status'=> 400,
                'message'=>  $e->getMessage() ,
            ],400) ;

        }
    }
}
