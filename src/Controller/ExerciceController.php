<?php

namespace App\Controller;

use App\Repository\ExerciceRepository;
use App\Entity\Exercice;
use App\Entity\Operation;
use App\Form\ExerciceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

class ExerciceController extends AbstractController
{
    /**
     * @Route("/api/exercice", name="api_exercice_index", methods={"GET"})
     */
    public function index(ExerciceRepository $exerciceRepository)
    {
        $exercices = $exerciceRepository-> myFindAll();

        // Serialisation
        // creation d"une veritable response
        $response = $this->json($exercices,200, [] , ['groups' =>'exercice:read']);
        return  $response;
    }


    /**
     * @Route("/api/exercice", name="api_exercice_create", methods={"POST"})
     */
    public function postExercice(Request $request, SerializerInterface $serialiser, EntityManagerInterface $em)
    {
        //DESERIALISATION

        // On recupere les donnees en json
        $codeJesonRecu = $request->getContent();

        try {

            // Deserialisation du Json 
            $exercice =$serialiser->deserialize($codeJesonRecu , Exercice::class,'json');
            $exercice->setDatedebut(new \Date());
            $exercice->setDatefin(new \Date());
            $exercice->setAnneecivil(new \Date());
            
             // Verification si erreur avant de persister  
            $validator = $this->get('validator');
            $errors = $validator->validate($exercice);

            if (count($errors) > 0) {
                $errorsString = (string) $errors;

                return new JsonResponse($errorsString);
            }
            $em->persist($exercice);
            $em->flush();
            $response = $this->json($exercice,201,[], ['groups'=>'exercice:read']); 
        
        } catch (NotEncodableValueException $e){
            return  $this->json( [
                'status'=> 400,
                'message'=>  $e->getMessage() ,
            ],400) ;

        }
    }


     /**
     * @Route("/api/exercice/{id}", name="api_exercice_create", methods={"PUT"})
     */
    public function putExercice(Request $request, Exercice $exercice, EntityManagerInterface $em)
    {
        $operation = new Operation();
        $codeJesonRecu = $request->getContent();
        $exercice->addOperation($operation);

        $data = json_decode($codeJesonRecu , true);

        $form = $this->createForm(ExerciceType::class, $exercice);
        $form->submit($data);
        $validator = $this->get('validator');
        $errors = $validator->validate($exercice);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new JsonResponse($errorsString);
        }
        $em->persist($exercice);
        $em->flush();
        return $this->view(['data' => $exercice], 200);
    }



}
