<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;



class AppFixtures
    extends Fixture implements DependentFixtureInterface
{
    public function load( ObjectManager $manager ): void
    {

        $user = $manager->getRepository(User::class)->findOneBy([
            'id'=>1
        ]);
        dd($user);

        for( $i = 0; $i < 10; $i ++ ) {
            $article = new Article();

            $date = new \DateTime( 'NOW' );

            $article->setTitre( 'ArticleTitle' . $i );
            $article->setDescription('Ceci est un article'.$i);
            $article->setCreationDate($date);
            $article->setContenu( ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean vel elit malesuada nulla volutpat commodo eu id odio. Fusce ut quam neque. Donec ac ante vulputate, convallis ante ut, cursus enim. Integer egestas ipsum sed egestas tempus. Fusce turpis ante, posuere sit amet eros in, porttitor malesuada erat. Donec vel elit vel neque congue ornare. Curabitur at odio sodales, tincidunt nunc at, pretium lorem. Vivamus luctus rutrum felis vitae iaculis. Phasellus fringilla mollis leo, a molestie libero porta ac. Nunc hendrerit magna nibh, sit amet varius erat dictum imperdiet. Aliquam eget aliquam mauris.

            Cras a euismod libero, in convallis lorem. Sed tempor pharetra felis id condimentum. Etiam leo lacus, molestie in elementum vitae, volutpat eget urna. Aliquam erat volutpat. Vestibulum eu tincidunt lacus, sed rutrum nunc. Nam bibendum viverra erat, ut blandit ligula. Morbi justo ipsum, ultricies vel ligula sed, pharetra tempus tortor. In lobortis leo ac velit sollicitudin dignissim. Cras tincidunt, lacus id mattis mattis, ante orci commodo risus, vitae mattis augue eros vehicula orci. Nam auctor velit quam, at venenatis elit vestibulum a. Donec tempor, leo sed aliquam cursus, mauris ligula varius ligula, a rutrum ligula nisi quis dui. ' );
            $article->setIsPremium(false);
            $article->setCreationDate( $date );
            $article->setImage('http://placehold.it/200x100');
            $article->setAuteur($user);
            $manager->persist( $article );
            $manager->flush($article);
        }


    }

    public function getDependencies()
    {
        return[
            UserFixtures::class,
        ];
    }
}
