<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     *
     * @Route("/article/{id}", name="article")
     */
    public function index(Request $request,$id): Response
    {
        $article = $this->entityManager->getRepository(Article::class)->findOneBy([
            'id'=>$id
        ]);

        return $this->render('article/index.html.twig', [
            'article'=>$article
        ]);
    }
}
