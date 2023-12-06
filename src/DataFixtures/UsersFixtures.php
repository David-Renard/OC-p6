<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserPicture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixtures extends Fixture
{

    private int $count = 1;

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {

    }

    public function load(ObjectManager $manager): void
    {
        // Fixture users + userPicture
        $usersAsArray =
            [
             ["firstname" => "Océane","name" => "Barbier","email" => "oceanebarb@hotmail.com","username" => "ocebar","password" => "Ocean84-Barb",],
             ["firstname" => "Milan","name" => "Julien","email" => "milanj@yahoo.fr","username" => "milanju","password" => "Mila42+Jul",],
             ["firstname" => "Xavier","name" => "Dupont","email" => "xavdu@hotmail.com","username" => "xadupo","password" => "Xavier75+Dupon",],
             ["firstname" => "Lucy","name" => "Dupuy","email" => "lucydupuy@yahoo.fr","username" => "lucdupu","password" => "Luc156-Dup",],
             ["firstname" => "Rose","name" => "Gillet","email" => "rgil@yahoo.fr","username" => "rosegi","password" => "Ros88-Gill",],
             ["firstname" => "Alexis","name" => "Marie","email" => "alexmar@yahoo.fr","username" => "alexmari","password" => "Alexi77!Mar",],
             ["firstname" => "Émile","name" => "Riviere","email" => "emirivier@yahoo.com","username" => "emilrivier","password" => "emi123!Rivier",],
             ["firstname" => "Florence","name" => "Bourgeois","email" => "florencbourgeo@yahoo.com","username" => "flobour","password" => "Floren7>Bourgeo",],
             ["firstname" => "Aliyah","name" => "Poirier","email" => "apoir@yahoo.fr","username" => "alipoi","password" => "Aliya106<Poi",],
             ["firstname" => "Romy","name" => "Lemoine","email" => "romylemo@hotmail.com","username" => "romlem","password" => "Rom8>Lemoin",],
             ["firstname" => "Rose","name" => "Colin","email" => "roseco@hotmail.fr","username" => "rocori","password" => "Ros13<Colin",],
             ["firstname" => "Victor","name" => "Deschamps","email" => "victdeschamp@hotmail.com","username" => "vdescham","password" => "Vic19>Deschamp",],
             ["firstname" => "Hubert","name" => "Benoit","email" => "huben@gmail.com","username" => "hubben","password" => "Huber43@Benoit",],
             ["firstname" => "Rosalie","name" => "Francois","email" => "rosafranc@yahoo.fr","username" => "rosaliefranc","password" => "Rosali84?Fran",],
             ["firstname" => "Léonie","name" => "Hubert","email" => "leonihube@yahoo.fr","username" => "leonihub","password" => "Leo6+Hub",],
             ["firstname" => "Ryan","name" => "Fleury","email" => "ryafle@hotmail.fr","username" => "ryanfleu","password" => "Rya81!Fleury",],
             ["firstname" => "Édouard","name" => "Jacquet","email" => "edouj@yahoo.com","username" => "edjacq","password" => "edouar133!Jacquet",],
             ["firstname" => "Ryan","name" => "Michel","email" => "rmi@hotmail.fr","username" => "ryamichel","password" => "Ryan77!Mich",],
             ["firstname" => "Livia","name" => "Fleury","email" => "livfle@gmail.com","username" => "livifle","password" => "Livia35?Fleu",],
             ["firstname" => "Ethan","name" => "Fournier","email" => "efournier@gmail.com","username" => "etfourniret","password" => "Eth83?Fourni",],
             ["firstname" => "Charles","name" => "Royer","email" => "chroye@hotmail.fr","username" => "charleroyer","password" => "Char1@Roy",],
             ["firstname" => "Lillian","name" => "Barre","email" => "libarr@hotmail.com","username" => "lilliaba","password" => "Lill137>Bar",],
             ["firstname" => "David","name" => "Gerard","email" => "dagera@yahoo.fr","username" => "dagerar","password" => "Davi61@Gerar",],
             ["firstname" => "Charlotte","name" => "Durand","email" => "charloduran@yahoo.com","username" => "charld","password" => "Charlo15<Dur",],
             ["firstname" => "Nicolas","name" => "Dubois","email" => "nicoladub@hotmail.com","username" => "nicduboi","password" => "Nicola46!Dubois",],
             ["firstname" => "Julia","name" => "Gauthier","email" => "juligauthier@gmail.com","username" => "jugauthie","password" => "Julia154@Gauthier",],
             ["firstname" => "Sarah","name" => "Dumont","email" => "sarahd@yahoo.com","username" => "sarahd","password" => "Sarah153?Dumont",],
             ["firstname" => "Annabelle","name" => "Lefevre","email" => "annalefe@hotmail.com","username" => "annlefev","password" => "Anna107<Lefev",]
            ];

        $superAdmin = false;
        foreach ($usersAsArray as $userAsArray) {
            $avatar = new UserPicture();
            $randomAvatar = random_int(0, 33);
            $avatar->setName("avatar ".$randomAvatar);
            if ($randomAvatar === 33) {
                $avatar->setUrl("default-user-icon.jpg");
            } else {
                $avatarNb = $randomAvatar + 1;
                $avatar->setUrl($avatarNb.".jpg");
            }

            // Fixtures User
            $user = new User();
            $user->setEmail($userAsArray['email']);
            $plainPassword = $userAsArray['password'];

            $user->setPassword($this->passwordHasher->hashPassword($user, $plainPassword));
            $roles = [];
            $randomRole = random_int(0,10);
            if ($randomRole <= 1) {
                $roles[] = 'ROLE_ADMIN';
            }
            else if ($randomRole === 0 && $superAdmin == false) {
                $roles[] = 'ROLE_SUPERADMIN';
                $superAdmin = true;
            }

            $user->setRoles($roles);
            $user->setUsername($userAsArray['username']);
            $user->setIsVerified(true);
            $user->setUserPicture($avatar);
            $avatar->setUserInfo($user);

            $manager->persist($avatar);
            $this->addReference('user-'.$this->count, $user);
            $this->count++;
            $manager->persist($user);
        }
        $manager->flush();
    }
}
