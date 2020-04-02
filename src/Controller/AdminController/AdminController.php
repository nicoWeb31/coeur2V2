<?php

namespace App\Controller\AdminController;

use App\Entity\Post;
use App\Entity\Photo;
use App\Form\PhotoType;
use App\Form\PostFormType;
use App\Repository\PostRepository;
use App\Repository\PhotoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{


    // =========================================================================
    // Post admin
    // =========================================================================

    /**
     * @Route("/admin/post", name="admin.post")
     */
    public function showAllPost(PostRepository $repo)
    {
        $posts = $repo->findAll();
        return $this->render('admin/postAdmin/showAll.html.twig',[
            "posts"=>$posts
        ]);
    }

    /**
     * @Route("/admin/post/create", name="admin.post.create")
     * @Route("/admin/post/modifier/{id}", name="admin.post.modifier",methods ="GET|POST")
     * 
     */
    public function creatMod(Post $post = null,EntityManagerInterface $man,Request $req)
    {
        if(!$post){
            $post = new Post();
        }
        //recup du fromualaire
        $form = $this->createForm(PostFormType::class,$post);

        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $modif = $post->getId() !== null;
            $man->persist($post);
            $man->flush();
            $this->addFlash("success",($modif) ? "Modification effectué avec succes" : "Ajouter avec succés");
            return $this->redirectToRoute("admin.post");
        }


        return $this->render('admin/postAdmin/creaMod.html.twig',[
            "post"=>$post,
            "form"=>$form->createView(),
            "modif"=> $post->getId() !== null   //test pour mon h1

        ]);
    }

    /**
     * @Route("/admin/post/suppr/{id}", name="admin.post.suppr",methods="sup")
     */
    public function suppr(Post $post, Request $req, EntityManagerInterface $man)
    {
        if($this->isCsrfTokenValid("sup".$post->getId(), $req->get("_token"))){
            $man->remove($post);
            $man->flush();
            $this->addFlash('success', "Supprimer avec succes");
            return $this->redirectToRoute('admin.post');
        }
    }

    // =========================================================================
    // photos administration
    // =========================================================================

    /**
     * @Route("/admin/photo", name="admin.photo")
     */
    public function showAllPhoto(PhotoRepository $repo)
    {
        $photos = $repo->findAll();
        return $this->render('admin/photoAdmin/showAllPhoto.html.twig',[
            "photos"=>$photos
        ]);
    }


    /**
     * @Route("/admin/photo/create", name="admin.photo.create")
     * @Route("/admin/photo/modifier/{id}", name="admin.photo.modifier",methods ="GET|POST")
     */
    public function ModCreatPhoto(Photo $photo = null,EntityManagerInterface $man, Request $req)
    {
        if(!$photo){
            $photo = new Photo();
        }

        $form = $this->createForm(PhotoType::class,$photo);
        $form->handleRequest($req);
        
        if($form->isSubmitted() && $form->isValid()){
            $modif = $photo->getId() !== null;
            $man->persist($photo);
            $man->flush();
            $this->addFlash("success",($modif) ? "Modification effectuer avec succes" : "Ajouter avec succés");
            return $this->redirectToRoute('admin.photo');
        }
        return $this->render('admin/photoAdmin/ModCreatPhoto.html.twig',[
            "photo"=>$photo,
            "form"=> $form->createView(),
            "modif"=> $photo->getId() !== null
        ]);

    }

    /**
     * @Route("/admin/photo/suppr/{id}", name="admin.photo.suppr",methods="sup")
     */
    public function supprPhoto(Photo $photo, Request $req, EntityManagerInterface $man)
    {
        if($this->isCsrfTokenValid("sup".$photo->getId(), $req->get("_token"))){
            $man->remove($photo);
            $man->flush();
            $this->addFlash('success', "Supprimer effectués avec succes");
            return $this->redirectToRoute('admin.photo');
        }
    }



    // =========================================================================
    // Users adminstraion
    // =========================================================================

}
