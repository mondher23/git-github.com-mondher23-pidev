<?php

namespace App\Controller;
use App\Entity\Personnel;
use App\Entity\Fonction;
use App\Form\PersonnelType;
use App\Repository\PersonnelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class PersonnelController extends AbstractController
{
    /**
     * @Route("/personnel", name="personnel")
     */
    public function index(): Response
    {
        return $this->render('personnel/index.html.twig', [
            'controller_name' => 'PersonnelController',
        ]);
    }


    
    /**
     * @Route("/listPersonnel", name="listPersonnel")
     */
    public function listPersonnel()
    {
        $personnels = $this->getDoctrine()->getRepository(Personnel::class)->findAll();
        return $this->render('personnel/list.html.twig', ["personnels" => $personnels]);
    }
    

     /**
     * @Route("/ajouterpersonnel", name="ajouterpersonnel")
     */
    public function ajouterpersonnel(Request $request)
    {
        $personnel = new personnel();
        $form = $this->createForm(PersonnelType::class, $personnel);
        $form->add("Ajouter", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($personnel);
            $em->flush();
            return $this->redirectToRoute('listPersonnel');
        }
        return $this->render("personnel/ajouter.html.twig", array('form' => $form->createView()));
    }

    /**
     * @Route("/supprimer/{id}", name="supprimerPersonnel")
     */
    public function supprimertudent($id)
    {
        $personnel = $this->getDoctrine()->getRepository(Personnel::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($personnel);
        $em->flush();
        return $this->redirectToRoute("listPersonnel");
    } 

     /**
     * @Route("/modifierPersonnel/{id}", name="modifierPersonnel")
     */
    public function modifierPersonnel(Request $request, $id)
    {
        $personnel = $this->getDoctrine()->getRepository(Personnel::class)->find($id);
        $form = $this->createForm(PersonnelType::class, $personnel);
        $form->add("Modifier", SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listPersonnel');
        }
        return $this->render("personnel/modifier.html.twig", array('form' => $form->createView()));
    }

}
