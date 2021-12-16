<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProduitRepository $produitrepository)
    {
        $produits = $produitrepository->findAll();
        return $this->render('front/index.html.twig', [ //on crée un tableau avec toute les variable dont on aura besoin ds le twig
            'produits' => $produits,

        ]);
    }

    //___________________________________________________________AJOUT________________________________________________________________________________

    /**
     * @Route("/addProduit", name="addProduit")
     */
    public function addProduit(Request $request, EntityManagerInterface $manager)
    {
        $produit = new Produit;
        $form = $this->createForm(ProduitType::class , $produit,array('add'=>true)); // le array sera l'option
        
        dump($request->request);
        $form->handleRequest($request); // récupération des infos du formulaire (en buffer)

        if($form->isSubmitted() && $form->isValid()){
            $picture=$form->get('picture')->getData(); // récupération du File
            //dd($picture);
            if($picture){
                $pictureName=date('YmdHis')."-".uniqid()."-".$picture->getClientOriginalName(); //renommage du fichier
                dump($pictureName);
                $picture->move($this->getParameter('pictures_directory'),$pictureName); //déplacement de l'image dans le dossier upload, avec son nouveau nom
                $produit->setPicture($pictureName); // on attribut le nom au produit
                $manager->persist($produit); //préparation de la requete en mémoire tampon
                $manager->flush(); // envoi ds la base

                $this->addFlash('success',"Le produit a été ajouté");

                return $this->redirectToRoute('listeProduit');
            }
        }
        return $this->render('front/addProduit.html.twig',[
            'form'=> $form->createView()
        ]);
    }

    //_________________________________________________________UPDATE______________________________________________________________________________

    /**
     * @Route("updateProduit/{id}", name="updateProduit")
     */
    public function updateProduit(EntityManagerInterface $manager, Produit $produit, Request $request)
    {
        $form = $this->createForm(ProduitType::class,$produit, array('update'=>true));
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()):
            //dd($request);
            $picture = $form->get('updatePicture')->getData(); // récupération du File
            //dd($picture);
            if ($picture) { //ça veut dire qu'on a rempli l'input pour la modifier, on doit copier la photo et la renommer comme pour l'ajout
                $pictureName = date('YmdHis') . "-" . uniqid() . "-" . $picture->getClientOriginalName(); //renommage du fichier
                dump($pictureName);
                $picture->move($this->getParameter('pictures_directory'), $pictureName); //déplacement de l'image dans le dossier upload, avec son nouveau nom
                unlink($this->getParameter('pictures_directory').'/'.$produit->getPicture());
                $produit->setPicture($pictureName);// on attribut le nom au produit
                
                
             } 
$manager->persist($produit);
                $manager->flush(); //foncion qui bazarde l'ancienne photo
            $this->addFlash('success','Le produit a bien été modifié');
            return $this->redirectToRoute('listeProduit');
        endif;
      

        return $this->render('front/updateProduit.html.twig',[
            'form'=>$form->createView(),
            'produit'=>$produit
        ]); 

    }
    
    //___________________________________________________________DELETE___________________________________________________________________________________

    /**
     * @Route("/deleteProduit/{id}", name="deleteProduit")
     */
    public function deleteProduit(Produit $produit, EntityManagerInterface $manager)
    {
        $manager->remove($produit);
        $manager->flush();
        $this->addFlash('success','Le produit a été supprimé');
        return $this->redirectToRoute('listeProduit');
        
    }

    //___________________________________________________________LISTE______________________________________________________________________________

    /**
     * @Route("/listeProduit", name="listeProduit")
     */
     public function listeProduit(ProduitRepository $produitRepository, EntityManagerInterface $manager)
     { 
         $produits = $produitRepository->findAll();

        return $this->render('front/listeProduit.html.twig',[ // retour de la vue
        'produits'=>$produits
    ]) ;
        
     }

}
 