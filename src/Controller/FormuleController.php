<?php

namespace App\Controller;

use App\Entity\Formule;
use App\Entity\User;
use App\Form\FormuleType;
use App\Repository\FormuleRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/formule")
 */
class FormuleController extends AbstractController
{
    /**
     * @Route("/", name="formule_index", methods={"GET"})
     * @param FormuleRepository $formuleRepository
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(FormuleRepository $formuleRepository, UserRepository $userRepository): Response
    {
        return $this->render('formule/index.html.twig', [
            'formules' => $formuleRepository->findAll(),
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="formule_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $formule = new Formule();
        $form = $this->createForm(FormuleType::class, $formule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formule);
            $entityManager->flush();

            return $this->redirectToRoute('formule_index');
        }

        return $this->render('formule/new.html.twig', [
            'formule' => $formule,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="formule_show", methods={"GET","POST"})
     * @param Formule $formule
     * @param UserRepository $repository
     * @param Request $request
     * @return Response
     */
    public function show(Formule $formule,UserRepository $repository,Request $request): Response
    {
        //On récupère un utilisateur de niveau 0 inscrit dont la date d'inscription est la plus anienne
        $getparrain= $repository->user_niveau_0()[0];
        //on récupère les informations de l'utilisateur connecté
        $user= $this->getUser();
        //Le formulaire de paiement
        $payer = $this->createFormBuilder()
            ->add('Envoyer',SubmitType::class,['label'=>'payer', ])
            ->getForm();
        $payer->handleRequest($request);

        if ($payer->isSubmitted())
        {
            //si cet utilisateur n'a pas de code parrain, on lui attribue un code parrain de cette formule
            $i=0;
            for($i==0;$i<=6;$i++)
            {
                if($getparrain->getFormules()[$i]==$formule->getNom())
                {
                    echo $getparrain->getFormules()[$i];
                    if($user->getCodeParrain() == null)
                    {
                        $user->setCodeParrain($getparrain->getCodeAdhesion());
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($user);
                        $entityManager->flush();
                    }

                }
            }
			
            $user->addFormule($formule);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

//            return $this->redirectToRoute('formule_index');
        }

        return $this->render('formule/show.html.twig', [
            'formule' => $formule,
            'payer'=> $payer->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="formule_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Formule $formule): Response
    {
        $form = $this->createForm(FormuleType::class, $formule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('formule_index');
        }

        return $this->render('formule/edit.html.twig', [
            'formule' => $formule,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="formule_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Formule $formule): Response
    {
        if ($this->isCsrfTokenValid('delete'.$formule->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($formule);
            $entityManager->flush();
        }

        return $this->redirectToRoute('formule_index');
    }
}
