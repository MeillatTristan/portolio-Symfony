<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostFormType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/posts", name="posts")
     */
    public function postView(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();
        return $this->render('admin/adminPosts.html.twig',[
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/admin/posts/add", name="addPosts")
     */
    public function addPost(Request $request, EntityManagerInterface $manager): Response
    {
        $post = new Post();

        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $post->setAuthor($user->getPseudo());
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($post);
            $manager->flush();
            $this->addFlash("success", "L'article à bien été ajouté !");
            return $this->redirectToRoute("posts");
        }
        return $this->render('admin/adminPostForm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/posts/{id}/edit", name="editPosts")
     */
    public function editPost(Post $post, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $post->setAuthor($user->getPseudo());
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($post);
            $manager->flush();
            $this->addFlash("success", "L'article à bien été modifié !");
            return $this->redirectToRoute("posts");
        }
        return $this->render('admin/adminPostForm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/posts/{id}/delete", name="Deleteposts")
     */
    public function deletePost(Post $post, EntityManagerInterface $manager): Response
    {
        $manager->remove($post);
        $manager->flush();

        $this->addFlash("success", "L'article à bien été supprimé !");
        return $this->redirectToRoute('posts');
    }
}
