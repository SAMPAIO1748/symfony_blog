<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/post", name="post")
     */
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    /**
     * @Route("hello", name="hello")
     */
    public function hello()
    {
        return new Response("Hello World");
    }

    /**
     * @Route("posts", name="post_list")
     */                         //autowire : permet de simplifier le code en évitant de créer une nouvelle instance
    public function postList(PostRepository $postRepository)
    {
        $posts = $postRepository->findAll(); // fonction qui récupère tous les post de la base de données
        return $this->render('posts.html.twig', ['posts' => $posts]);
    }

    /**
     * @Route("post/{id}", name="post_show")
     */
    public function postShow($id, PostRepository $postRepository)
    {
        $post = $postRepository->find($id);
        // fonction find permet de récupérer un élement 
        // de la base de données grâce à son id
        return $this->render('post.html.twig', ['post' => $post]);
    }

    /**
     * @Route("update/post/{id}", name="post_update")
     */
    public function postUpdate(
        $id,
        PostRepository $postRepository,
        EntityManagerInterface $entityManagerInterface,
        Request $request
    ) {
        $post = $postRepository->find($id);

        // Création du formulaire
        $postForm = $this->createForm(PostType::class, $post);

        // Utilisation de handleRequest pour demander au formulaire de traiter les infos
        // rentrées dans le formulaire
        // Utilisation de request pour récupérer les informations rentrées dans le fromulaire
        $postForm->handleRequest($request);

        if ($postForm->isSubmitted() && $postForm->isValid()) {
            $entityManagerInterface->persist($post);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('post_list');
        }

        // redirige vers la page où le formulaire est affiché.
        return $this->render('postupdate.html.twig', ['postForm' => $postForm->createView()]);
    }

    /**
     * @Route("add/post/", name="post_add")
     */
    public function addPost(
        EntityManagerInterface $entityManagerInterface,
        Request $request
    ) {

        $post = new Post();

        $postForm = $this->createForm(PostType::class, $post);

        $postForm->handleRequest($request);

        if ($postForm->isSubmitted() && $postForm->isValid()) {
            $post->setDate(new \DateTime("NOW"));
            $entityManagerInterface->persist($post);
            $entityManagerInterface->flush();

            return $this->redirectToRoute("post_list");
        }

        return $this->render('postupdate.html.twig', ['postForm' => $postForm->createView()]);
    }

    /**
     * @Route("/delete/post/{id}", name="post_delete")
     */
    public function deletePost($id, PostRepository $postRepository, EntityManagerInterface $entityManagerInterface)
    {
        $post = $postRepository->find($id);
        $entityManagerInterface->remove($post); // fonction remove supprime le post sélectionné
        $entityManagerInterface->flush();
        $this->addFlash(
            'notice',
            'Votre post a été supprimé'
        );

        return $this->redirectToRoute("post_list");
    }

    // faire une fonction qui va supprimer un tag en fonction de son id.
}
