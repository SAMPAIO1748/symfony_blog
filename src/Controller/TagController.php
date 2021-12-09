<?php

namespace App\Controller;

use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    /**
     * @Route("/tag", name="tag")
     */
    public function index(): Response
    {
        return $this->render('tag/index.html.twig', [
            'controller_name' => 'TagController',
        ]);
    }

    /**
     * @Route("/tags/", name="tag_list")
     */
    public function tagList(TagRepository $tagRepository)
    {
        $tags = $tagRepository->findAll();
        return $this->render('tags.html.twig', ['tags' => $tags]);
    }

    /**
     * @Route("/tag/{id}", name="tag_show")
     */
    public function tagShow($id, TagRepository $tagRepository)
    {
        $tag = $tagRepository->find($id);

        return $this->render("tag.html.twig", ['tag' => $tag]);
    }

    // faire la fonction qui modifie la description d'un tag et qui devient "Les articles du super tag n° " . $id

    /**
     * @Route("/update/tag/{id}", name="tag_update")
     */
    public function tagUpdate($id, TagRepository $tagRepository, EntityManagerInterface $entityManagerInterface)
    {
        $tag = $tagRepository->find($id);
        $tag->setDescription("Les articles du super tag n° " . $id);
        $entityManagerInterface->flush();

        return $this->redirectToRoute("tag_list");
    }

    // fonction pour ajouter un tag avec les éléments name "super tag", 
    //description "le super tag de la mort qui tue"
    // color "black"

}
