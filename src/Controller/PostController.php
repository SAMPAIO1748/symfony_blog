<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function postUpdate($id, PostRepository $postRepository, EntityManagerInterface $entityManagerInterface)
    {
        $post = $postRepository->find($id);
        $post->setContent("Contenu du super article n° " . $id);
        $entityManagerInterface->flush(); // flush modifie dans la base de données

        return $this->redirectToRoute('post_list');
    }
}
