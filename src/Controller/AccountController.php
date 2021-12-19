<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleCreationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
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
     *
     */
    public function index(): Response
    {
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

    /**
     * @Route("/mon-compte/administration" , name="dashboard")
     * @IsGranted("ROLE_ADMIN")
     *
    */
    public function adminDashboard()
    {

        return $this->render('account/admin.html.twig');
    }

    /**
     * @Route("/mon-compte/administration/creation-article", name="creationArticle")
     * @IsGranted("ROLE_ADMIN")
     */
    public function createArticle(Request $request,SluggerInterface $slugger):Response
    {
        //creation formulaire pour creation d'article
        $article = new Article();
        $date = new \DateTime('NOW');
        $form = $this->createForm(ArticleCreationType::class,$article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $image = $form->get('image')->getData();
            if($image)
            {
                $originalFileName = pathinfo($image->getClientOriginalName(),PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFileName);
                $newFileName = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
                try {
                    $image->move(
                        $this->getParameter('imageArticles_directory'),
                        $newFileName
                    );
                } catch(FileException $e)
                {

                }
                $article->setImage($newFileName);

            }

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
     *
     * @Route("/mon-compte/administration/mes-articles" , name="articleCrud")
     * @IsGranted("ROLE_ADMIN")
     */
    public function articlesCrud():Response
    {
        $articles = $this->entityManager->getRepository(Article::class)->findBy([
            'auteur'=>$this->getUser()->getId()
        ]);

        return $this->render('account/listeArticles.html.twig',[
            'articles'=>$articles
        ]);
    }

    /**
     *
     * @Route("/mon-compte/administration/delete/article/{article}" , name="articleDelete")
     * @IsGranted("ROLE_ADMIN")
     *
     */
    public function articleDelete(Article $article):Response
    {
        $this->getDoctrine()->getManager()->remove($article);
        $this->getDoctrine()->getManager()->flush($article);

        return $this->redirectToRoute('articleCrud');
    }

    /**
     *
     * @Route("/mon-compte/administration/modifier/article/{id}" , name="articleEdit")
     * @IsGranted("ROLE_ADMIN")
     */
    public function articleEdit(Request $request, $id):Response
    {
        $article = $this->entityManager->getRepository(Article::class)->findOneBy([
            'id'=>$id]);

        $form = $this->createForm(ArticleCreationType::class,$article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->entityManager->flush();
            return $this->redirectToRoute('articleCrud');
        }

        return $this->render('account/createArticle.html.twig',[
            'form'=>$form->createView()
        ]);
    }



}
