<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function tagUpdate(
        $id,
        TagRepository $tagRepository,
        EntityManagerInterface $entityManagerInterface,
        Request $request
    ) {
        $tag = $tagRepository->find($id);

        $tagForm = $this->createForm(TagType::class, $tag);

        $tagForm->handleRequest($request);

        if ($tagForm->isSubmitted() && $tagForm->isValid()) {
            $entityManagerInterface->persist($tag);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('tag_list');
        }

        return $this->render('tagupdate.html.twig', ['tagForm' => $tagForm->createView()]);
    }

    // fonction pour ajouter un tag avec les éléments name "super tag", 
    //description "le super tag de la mort qui tue"
    // color "black"

    /**
     * @Route("add/tag", name="tag_add")
     */
    public function addTag(EntityManagerInterface $entityManagerInterface, Request $request)
    {
        $tag = new Tag();

        $tagForm = $this->createForm(TagType::class, $tag);

        $tagForm->handleRequest($request);

        if ($tagForm->isSubmitted() && $tagForm->isValid()) {
            $entityManagerInterface->persist($tag);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('tag_list');
        }

        return $this->render('tagupdate.html.twig', ['tagForm' => $tagForm->createView()]);
    }

    /**
     * @Route("delete/tag/{id}", name="delete_tag")
     */
    public function deleteTag($id, TagRepository $tagRepository, EntityManagerInterface $entityManagerInterface)
    {
        $tag = $tagRepository->find($id);
        $entityManagerInterface->remove($tag);
        $entityManagerInterface->flush();
        $this->addFlash(
            'notice',
            'Votre tag a été supprimé'
        );

        return $this->redirectToRoute("tag_list");
    }
}
