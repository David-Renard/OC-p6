<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\TrickCategory;
use App\Entity\TrickComment;
use App\Entity\TrickPicture;
use App\Entity\User;
use App\Entity\UserPicture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{


    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher, private readonly SluggerInterface $slugger)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // fixture users + userPicture
        $usersAsArray = [["firstname"=>"Océane","name"=>"Barbier","email"=>"oceanebarb@hotmail.com","username"=>"oceba","password"=>"Ocean84-Barb",],["firstname"=>"Milan","name"=>"Julien","email"=>"milanj@yahoo.fr","username"=>"milanju","password"=>"Mila42+Jul",],["firstname"=>"Xavier","name"=>"Dupont","email"=>"xavdu@hotmail.com","username"=>"xadupo","password"=>"Xavier75+Dupon",],["firstname"=>"Lucy","name"=>"Dupuy","email"=>"lucydupuy@yahoo.fr","username"=>"lucdupu","password"=>"Luc156-Dup",],["firstname"=>"Rose","name"=>"Gillet","email"=>"rgil@yahoo.fr","username"=>"roseg","password"=>"Ros88-Gill",],["firstname"=>"Alexis","name"=>"Marie","email"=>"alexmar@yahoo.fr","username"=>"alexmari","password"=>"Alexi77!Mar",],["firstname"=>"Émile","name"=>"Riviere","email"=>"emirivier@yahoo.com","username"=>"emilrivier","password"=>"emi123!Rivier",],["firstname"=>"Florence","name"=>"Bourgeois","email"=>"florencbourgeo@yahoo.com","username"=>"flob","password"=>"Floren7>Bourgeo",],["firstname"=>"Aliyah","name"=>"Poirier","email"=>"apoir@yahoo.fr","username"=>"alipoi","password"=>"Aliya106<Poi",],["firstname"=>"Romy","name"=>"Lemoine","email"=>"romylemo@hotmail.com","username"=>"romle","password"=>"Rom8>Lemoin",],["firstname"=>"Rose","name"=>"Colin","email"=>"roseco@hotmail.fr","username"=>"roco","password"=>"Ros13<Colin",],["firstname"=>"Victor","name"=>"Deschamps","email"=>"victdeschamp@hotmail.com","username"=>"vd","password"=>"Vic19>Deschamp",],["firstname"=>"Hubert","name"=>"Benoit","email"=>"huben@gmail.com","username"=>"hubben","password"=>"Huber43@Benoit",],["firstname"=>"Rosalie","name"=>"Francois","email"=>"rosafranc@yahoo.fr","username"=>"rosaliefranc","password"=>"Rosali84?Fran",],["firstname"=>"Léonie","name"=>"Hubert","email"=>"leonihube@yahoo.fr","username"=>"leonihub","password"=>"Leo6+Hub",],["firstname"=>"Ryan","name"=>"Fleury","email"=>"ryafle@hotmail.fr","username"=>"ryanfleu","password"=>"Rya81!Fleury",],["firstname"=>"Édouard","name"=>"Jacquet","email"=>"edouj@yahoo.com","username"=>"edjacq","password"=>"edouar133!Jacquet",],["firstname"=>"Ryan","name"=>"Michel","email"=>"rmi@hotmail.fr","username"=>"ryamichel","password"=>"Ryan77!Mich",],["firstname"=>"Livia","name"=>"Fleury","email"=>"livfle@gmail.com","username"=>"livifle","password"=>"Livia35?Fleu",],["firstname"=>"Ethan","name"=>"Fournier","email"=>"efournier@gmail.com","username"=>"etf","password"=>"Eth83?Fourni",],["firstname"=>"Charles","name"=>"Royer","email"=>"chroye@hotmail.fr","username"=>"charleroyer","password"=>"Char1@Roy",],["firstname"=>"Lillian","name"=>"Barre","email"=>"libarr@hotmail.com","username"=>"lilliaba","password"=>"Lill137>Bar",],["firstname"=>"David","name"=>"Gerard","email"=>"dagera@yahoo.fr","username"=>"dagerar","password"=>"Davi61@Gerar",],["firstname"=>"Charlotte","name"=>"Durand","email"=>"charloduran@yahoo.com","username"=>"charld","password"=>"Charlo15<Dur",],["firstname"=>"Nicolas","name"=>"Dubois","email"=>"nicoladub@hotmail.com","username"=>"nicduboi","password"=>"Nicola46!Dubois",],["firstname"=>"Julia","name"=>"Gauthier","email"=>"juligauthier@gmail.com","username"=>"jugauthie","password"=>"Julia154@Gauthier",],["firstname"=>"Sarah","name"=>"Dumont","email"=>"sarahd@yahoo.com","username"=>"sarahd","password"=>"Sarah153?Dumont",],["firstname"=>"Annabelle","name"=>"Lefevre","email"=>"annalefe@hotmail.com","username"=>"annlefev","password"=>"Anna107<Lefev",]];
        $users = [];
        $superAdmin = false;
        foreach ($usersAsArray as $userAsArray) {
            // fixtures unidirectionnal UserPicture
            $avatar = new UserPicture();
            $randomAvatar = random_int(0, 33);
            $avatar->setName("avatar " . $randomAvatar);
            if ($randomAvatar === 33) {
                $avatar->setUrl("/build/images/default-user-icon.jpg");
            } else {
                $avatar->setUrl("/build/images/trick". $randomAvatar + 1 . ".jpg");
            }


            // fixtures User
            $user = new User();
            $user->setEmail($userAsArray['email']);

            // set hashed user's password
            $plainPassword = $userAsArray['password'];

            $user->setPassword($this->passwordHasher->hashPassword($user,$plainPassword));
            $roles = ['ROLE_USER'];
            $randomRole = random_int(0,10);
            if ($randomRole <= 1) {
                $roles[]='ROLE_ADMIN';
            }
            elseif ($randomRole === 0 && !$superAdmin) {
                $roles[] = 'ROLE_SUPERADMIN';
                $superAdmin = true;
            }
            $user->setRoles($roles);
            $user->setUsername($userAsArray['username']);
            $user->setIsVerified(true);
            $user->setUserPicture($avatar);
            $avatar->setUserInfo($user);

            $manager->persist($avatar);
            $manager->persist($user);
            $users[] = $user;
        }
        $manager->flush();

        $listTricksName = ["Switch Rodéo 720 Japan","Switch Cork 540 Tail Grab","Misty 900 Mute","Switch Misty 1080 Mute","Switch 360 Stalefish","Switch Cork 1080 Mute","Cork 180 Seat Belt","Switch Rodéo 180 Indy","Switch Cork 180 Seat Belt","Cork 720 Indy","Switch 720 Japan","720 Stalefish","1080 Seat Belt","Switch 900 Indy","Switch 900 Mute","Misty 720 Tail Grab","900 Stalefish","Switch Cork 540 Mute","Misty 180 Sad",];

        // fixture trickPicture
        $mainPictures = [];
        $additionalPictures = [];
        for ($countPicture=0; $countPicture<33; $countPicture++) {
            $picture = new TrickPicture();
            $main = false;
            if ($countPicture<count($listTricksName)) {
                $main = true;
            }

            $url = "/build/images/trick". $countPicture + 1 . ".jpg";
            $name = "freestyle trick". $countPicture + 1;
            $picture->setMain($main);
            $picture->setName($name);
            $picture->setUrl($url);

            if ($main === true) {
                $mainPictures[] = $picture;
            }
            else {
                $additionalPictures[] = $picture;
            }
        }

        // fixture comment
        $loremDescription = ["Pellentesque rutrum nunc vel ligula eleifend, in bibendum dolor gravida.","Suspendisse dignissim nisl at dolor varius ornare.","Nullam dignissim odio ut eros vehicula hendrerit.","Proin eu dolor faucibus, sollicitudin lectus in, placerat nunc.","Praesent eu massa ullamcorper, gravida elit sollicitudin, vehicula massa.","Ut in purus scelerisque, volutpat nisi quis, commodo augue.","Donec vitae diam eget dolor convallis pretium id non justo.","Proin a erat eu est porta finibus eu vel lacus.","Ut in elit a quam eleifend aliquam.","Fusce ornare neque sit amet nunc ultrices, in consequat sem malesuada.","Suspendisse sed massa sollicitudin, interdum nibh eu, pulvinar tortor.","Morbi in lacus et ex lacinia tempus.","Suspendisse rhoncus nisl eu lacus condimentum, ac tempus est pellentesque.","Nunc sit amet felis egestas, blandit velit consectetur, tristique elit.","Sed pharetra enim a ultricies tincidunt.","Suspendisse imperdiet libero ac viverra rhoncus.","Maecenas congue diam eu ligula semper fermentum.","Suspendisse non ante et ipsum dictum faucibus.","Nulla tempor orci eget varius tincidunt.","Pellentesque eget sem egestas, venenatis nibh non, sodales nisl.","Curabitur lobortis lectus quis ullamcorper ultricies.","Donec porttitor augue non justo laoreet, nec sagittis nisl egestas.","Mauris eget mauris eu mauris pretium malesuada et a quam.","Mauris consequat justo sit amet nisi lacinia, vel dictum leo laoreet.","Mauris pretium ex at lectus dictum gravida.","Curabitur sit amet nisl rhoncus, ultrices erat at, interdum neque.","Etiam ultrices ante a libero posuere blandit.","Donec porttitor neque sed blandit sollicitudin.","Pellentesque at lorem vitae magna hendrerit hendrerit.","Curabitur nec ipsum et nulla varius condimentum in sit amet turpis.","Curabitur vehicula tortor vitae odio porttitor, et rutrum mauris facilisis.","Morbi commodo libero quis nibh dictum fermentum.","Quisque id nulla id libero facilisis elementum.","Integer facilisis sem a mi mollis, at elementum lacus scelerisque.","Nulla aliquet nisi viverra, varius ipsum at, porttitor odio.","Donec in velit dictum, fringilla ex sed, gravida nibh.","Duis eget dolor nec eros rutrum scelerisque a nec lectus.","Suspendisse consectetur est pellentesque tortor placerat laoreet.","Sed tristique risus vitae tellus accumsan, et sodales lorem consectetur.","Vivamus luctus nisl eget dui auctor, ut laoreet enim consectetur.","Donec at purus vestibulum, laoreet urna eget, laoreet mauris.","Vestibulum dignissim augue id ultrices pharetra.","Morbi ut nulla dapibus, maximus neque sed, posuere lectus.","Praesent accumsan ex faucibus lacinia ultrices.","Nullam nec leo pellentesque dui interdum tempor.","Vivamus quis ipsum sed arcu porttitor congue vel vitae quam.","Quisque commodo ipsum quis orci aliquet, a sollicitudin metus pulvinar.","Donec vel quam a felis scelerisque semper at id metus.","Vestibulum iaculis quam vel velit scelerisque vulputate.","Morbi cursus justo non neque mollis hendrerit vitae ac turpis.","Nullam porta nulla sed nunc sollicitudin vestibulum ac sit amet mauris.","Donec scelerisque arcu id nibh laoreet, a scelerisque est volutpat.","Phasellus elementum libero et tortor varius, at fringilla erat eleifend.","Cras suscipit metus faucibus, rutrum arcu ut, aliquet libero.","Mauris nec lorem quis augue sollicitudin rhoncus in eget leo.","Proin sit amet elit ultrices, venenatis augue eu, vehicula dolor.","Vivamus porttitor arcu mattis massa pulvinar condimentum.","Cras cursus metus vel nunc suscipit convallis.","Aliquam dictum augue a augue placerat, eu interdum tellus hendrerit.","Nullam posuere leo eu enim pretium, ut dapibus lorem efficitur.","Proin aliquet purus eu dignissim viverra.","Nam ac elit vel leo feugiat convallis nec consequat mi.","Vestibulum non sapien tempor, posuere felis ut, facilisis arcu.","Praesent vestibulum ligula ac tristique placerat.","Maecenas placerat enim eget ante tincidunt, non luctus libero tempus.",];
        $listComment = [];
        for ($countComment = 0; $countComment < 150; $countComment++) {
            $comment = new TrickComment();
            $content = "";
            for ($countLorem = 0; $countLorem < random_int(1, 4); $countLorem++) {
                $content = $content . $loremDescription[random_int(0,64)];
            }
            $now = new \DateTimeImmutable("now");
            $randomTime = rand(0,50);
            $comment->setContent($content);
            $comment->setUser($users[random_int(0,27)]);
            $comment->setCreatedAt($now->sub(new \DateInterval("P{$randomTime}D")));
            $listComment[] = $comment;
        }

        // fixture categories
        $listCategory = ["Japan","Tail Grab","Mute","Stalefish","Seat Belt","Indy","Sad"];
        $categories = [];
        foreach ($listCategory as $categoryArray) {
            $category = new TrickCategory();
            $category->setName($categoryArray);
            $manager->persist($category);
            $categories[] = $category;
        }
        $manager->flush();

        // fixture tricks
        for ($i=0; $i<count($listTricksName); $i++) {
            $trick = new Trick();
            $randomAuthor = random_int(1,10);

            $trick->setName($listTricksName[$i]);

            $slug = $this->slugger->slug($listTricksName[$i]);
            $trick->setSlug($slug);

            $trick->setDescription($loremDescription[random_int(0,64)] . $loremDescription[random_int(0,64)] . $loremDescription[random_int(0,64)]);
            $trick->setAuthor($users[$randomAuthor]);
            foreach ($categories as $category) {
                if (stristr($listTricksName[$i],$category->getName()) != false) {
                    $trick->setCategory($category);
                }
            }
            $trick->addPicture($mainPictures[$i]);

            $randomNbPictures = rand(0,5);
            for ($j=0; $j<$randomNbPictures; $j++) {
                if (!empty($additionalPictures)) {
                    $trick->addPicture(array_pop($additionalPictures));
                }
            }

            $randomNbComment = rand(0,15);
            for ($j=0; $j<$randomNbComment; $j++) {
                if (!empty($listComment)) {
                    $trick->addComment(array_pop($listComment));
                }
            }
            $manager->persist($trick);
        }
        $manager->flush();
    }
}
