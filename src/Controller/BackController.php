<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackController extends AbstractController
{
    /**
     * @Route("/addCategorie", name="addCategorie")
     * @Route("/updateCatagorie/{id}", name="updateCategorie")
     */
    public function addCategorie(Request $request,EntityManagerInterface $manager, CategorieRepository $repository, $id = null) //Create.R.U.D.
    {
        if($id=null): //si l'id est null on instancie une catégorie, sinon on fait un findoneby $id
        $categorie = new Categorie;
        else:
             $category = $repository->findOneBy($id);
        endif;
        $form = $this->createForm(CategorieType::class, $categorie);
        dump($request->request);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $manager->persist($categorie);//prepa requete
            $manager->flush(); //
            if ($id = null) :
                $this->addFlash('success', "La catégorie a été ajoutée");
            else:
                $this->addFlash('success', "La catégorie a bien été modifiée");
            return $this->redirectToRoute('home');
            endif;
        }

        return $this->render('back/addCategorie.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route ("/listeCategorie", name="listeCategorie")
     */
    public function listecategorie(CategorieRepository $repository) //C.Read.U.D.
    {
        $categories = $repository->findAll();
        

        return $this->render('back/listeCategorie.html.twig',[
            'categories'=>$categories
        ]);
    }
    
    /**
     * @Route ("/deleteCategorie/{id}", name = "deleteCategorie")
     */
    public function deleteCategorie(Categorie $categorie, EntityManagerInterface $manager)
    {
        $manager->remove($categorie); //suppression de la categorie
        $manager->flush();
        $this->addFlash('success', 'La catégorie a été supprimée');
        return $this->redirectToRoute('listeCategorie');
        
        
    }
}
