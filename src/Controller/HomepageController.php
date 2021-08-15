<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\ContactType;
use App\Repository\PostRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $datas = $form->getData();
            $email = (new TemplatedEmail())
            ->from('tristan.meillat28@gmail.com')
            ->to('tristan.meillat28@gmail.com')
            ->subject('Nouveau message sur www.tristan.meillat.fr')
            ->htmlTemplate('email/templateContact.html.twig')
            ->context([
                "name" => $datas['name'],
                "firstname" => $datas['firstname'],
                "emailInput" => $datas['email'],
                "content" => $datas['content']
            ]);
            $mailer->send($email);
        }
        return $this->render('homepage/homepage.html.twig',[
            'form' => $form->createView()
        ]
    );
    }

    /**
     * @Route("/posts", name="showPosts")
     */
    public function showPosts(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();
        return $this->render('homepage/posts.html.twig',[
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/posts/{id}", name="showSinglePost")
     */
    public function singlePost(Post $post): Response
    {
        return $this->render('homepage/singlePost.html.twig',[
            'post' => $post
        ]);
    }

    /**
     * @Route("/mentions-legales", name="mentionsLegales")
     */
    public function mentionsLegales(): Response
    {
        return $this->render('homepage/mentionsLegales.html.twig');
    }
}
