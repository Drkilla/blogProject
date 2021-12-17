<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleCreationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Validator\Constraints\Date;

class AccountController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/mon-compte", name="compte")
     */
    public function index(): Response
    {
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

    /**
     * IsGranted('ROLE_ADMIN')
     * @Route("/mon-compte/administration" , name="dashboard")
     *
    */
    public function adminDashboard():Response
    {

        return $this->render('account/admin.html.twig');
    }

    /**
     * isGranted('ROLE_ADMIN')
     * @Route("/mon-compte/administration/creation-article", name="creationArticle")
     *
     */
    public function createArticle(Request $request):Response
    {
        //creation formulaire pour creation d'article
        $article = new Article();
        $date = new \DateTime('NOW');
        $form = $this->createForm(ArticleCreationType::class,$article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $article->setAuteur($this->getUser());
            $article->setCreationDate($date);

            $this->entityManager->persist($article);
            $this->entityManager->flush($article);
            return $this->redirectToRoute('home');
        }


        return $this->render('account/createArticle.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * isGranted('ROLE_ADMIN')
     * @Route("/mon-compte/administration/mes-articles" , name="articleCrud")
     */
    public function articlesCrud():Response
    {
        $articles = $this->entityManager->getRepository(Article::class)->findBy([
            'auteur'=>$this->getUser()->getId()
        ]);
        /*dd($articles);*/
        return $this->render('account/listeArticles.html.twig',[
            'articles'=>$articles
        ]);
    }



}
