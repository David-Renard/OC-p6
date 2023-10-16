<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\TrickComment;
use App\Entity\TrickPicture;
use App\Entity\UserInfo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // fixture tricks
        $listTrickName = ["Switch Rodéo 720 Japan","Switch Cork 540 Tail Grab","Misty 900 Mute","Switch Misty 1080 Mute","Switch 360 Stalefish","Switch Cork 1080 Mute","Cork 180 Seat Belt","Switch Rodéo 180 Indy","Switch Cork 180 Seat Belt","Cork 720 Indy","Switch 720 Japan","720 Stalefish","1080 Seat Belt","Switch 900 Indy","Switch 900 Mute","Misty 720 Tail Grab","900 Stalefish","Switch Cork 540 Mute","Misty 180 Sad",];
        $listTrickSlug = ["switch-rodeo-720-japan","switch-cork-540-tail-grab","misty-900-mute","switch-misty-1080-mute","switch-360-stalefish","switch-cork-1080-mute","cork-180-seat-belt","switch-rodeo-180-indy","switch-cork-180-seat-belt","cork-720-indy","switch-720-japan","720-stalefish","1080-seat-belt","switch-900-indy","switch-900-mute","misty-720-tail-grab","900-stalefish","switch-cork-540-mute","misty-180-sad",];
        $listTrickDescription = ["Pellentesque rutrum nunc vel ligula eleifend, in bibendum dolor gravida.","Suspendisse dignissim nisl at dolor varius ornare.","Nullam dignissim odio ut eros vehicula hendrerit.","Proin eu dolor faucibus, sollicitudin lectus in, placerat nunc.","Praesent eu massa ullamcorper, gravida elit sollicitudin, vehicula massa.","Ut in purus scelerisque, volutpat nisi quis, commodo augue.","Donec vitae diam eget dolor convallis pretium id non justo.","Proin a erat eu est porta finibus eu vel lacus.","Ut in elit a quam eleifend aliquam.","Fusce ornare neque sit amet nunc ultrices, in consequat sem malesuada.","Suspendisse sed massa sollicitudin, interdum nibh eu, pulvinar tortor.","Morbi in lacus et ex lacinia tempus.","Suspendisse rhoncus nisl eu lacus condimentum, ac tempus est pellentesque.","Nunc sit amet felis egestas, blandit velit consectetur, tristique elit.","Sed pharetra enim a ultricies tincidunt.","Suspendisse imperdiet libero ac viverra rhoncus.","Maecenas congue diam eu ligula semper fermentum.","Suspendisse non ante et ipsum dictum faucibus.","Nulla tempor orci eget varius tincidunt.","Pellentesque eget sem egestas, venenatis nibh non, sodales nisl.","Curabitur lobortis lectus quis ullamcorper ultricies.","Donec porttitor augue non justo laoreet, nec sagittis nisl egestas.","Mauris eget mauris eu mauris pretium malesuada et a quam.","Mauris consequat justo sit amet nisi lacinia, vel dictum leo laoreet.","Mauris pretium ex at lectus dictum gravida.","Curabitur sit amet nisl rhoncus, ultrices erat at, interdum neque.","Etiam ultrices ante a libero posuere blandit.","Donec porttitor neque sed blandit sollicitudin.","Pellentesque at lorem vitae magna hendrerit hendrerit.","Curabitur nec ipsum et nulla varius condimentum in sit amet turpis.","Curabitur vehicula tortor vitae odio porttitor, et rutrum mauris facilisis.","Morbi commodo libero quis nibh dictum fermentum.","Quisque id nulla id libero facilisis elementum.","Integer facilisis sem a mi mollis, at elementum lacus scelerisque.","Nulla aliquet nisi viverra, varius ipsum at, porttitor odio.","Donec in velit dictum, fringilla ex sed, gravida nibh.","Duis eget dolor nec eros rutrum scelerisque a nec lectus.","Suspendisse consectetur est pellentesque tortor placerat laoreet.","Sed tristique risus vitae tellus accumsan, et sodales lorem consectetur.","Vivamus luctus nisl eget dui auctor, ut laoreet enim consectetur.","Donec at purus vestibulum, laoreet urna eget, laoreet mauris.","Vestibulum dignissim augue id ultrices pharetra.","Morbi ut nulla dapibus, maximus neque sed, posuere lectus.","Praesent accumsan ex faucibus lacinia ultrices.","Nullam nec leo pellentesque dui interdum tempor.","Vivamus quis ipsum sed arcu porttitor congue vel vitae quam.","Quisque commodo ipsum quis orci aliquet, a sollicitudin metus pulvinar.","Donec vel quam a felis scelerisque semper at id metus.","Vestibulum iaculis quam vel velit scelerisque vulputate.","Morbi cursus justo non neque mollis hendrerit vitae ac turpis.","Nullam porta nulla sed nunc sollicitudin vestibulum ac sit amet mauris.","Donec scelerisque arcu id nibh laoreet, a scelerisque est volutpat.","Phasellus elementum libero et tortor varius, at fringilla erat eleifend.","Cras suscipit metus faucibus, rutrum arcu ut, aliquet libero.","Mauris nec lorem quis augue sollicitudin rhoncus in eget leo.","Proin sit amet elit ultrices, venenatis augue eu, vehicula dolor.","Vivamus porttitor arcu mattis massa pulvinar condimentum.","Cras cursus metus vel nunc suscipit convallis.","Aliquam dictum augue a augue placerat, eu interdum tellus hendrerit.","Nullam posuere leo eu enim pretium, ut dapibus lorem efficitur.","Proin aliquet purus eu dignissim viverra.","Nam ac elit vel leo feugiat convallis nec consequat mi.","Vestibulum non sapien tempor, posuere felis ut, facilisis arcu.","Praesent vestibulum ligula ac tristique placerat.","Maecenas placerat enim eget ante tincidunt, non luctus libero tempus.",];
        for ($i=0; $i<count($listTrickName); $i++) {
            $trick = new Trick();
            $trick->setName($listTrickName[$i]);
            $trick->setSlug($listTrickSlug[$i]);
            $trick->setDescription($listTrickDescription[random_int(0,64)] . $listTrickDescription[random_int(0,64)]);

            $manager->persist($trick);
        }

        // fixture users
        $listUser = [["firstname"=>"Liam","name"=>"Morel","email"=>"lmo@hotmail.fr","login"=>"limo","password"=>"Liam104?Morel",],["firstname"=>"Gabriel","name"=>"Marchand","email"=>"gamarc@hotmail.fr","login"=>"gabmarch","password"=>"Gab13?Marcha",],["firstname"=>"Océane","name"=>"Gilbert","email"=>"ocgil@yahoo.fr","login"=>"ocgilb","password"=>"Oceane54+Gil",],["firstname"=>"Caleb","name"=>"Lacroix","email"=>"caleblacroi@hotmail.fr","login"=>"cla","password"=>"Cal68-Lacr",],["firstname"=>"Louka","name"=>"Faure","email"=>"louf@gmail.com","login"=>"loukf","password"=>"Louk41+Fau",],["firstname"=>"Camila","name"=>"Gaillard","email"=>"cg@yahoo.com","login"=>"camilagail","password"=>"Cami20+Gailla",],["firstname"=>"Édouard","name"=>"Boulanger","email"=>"éboulanger@yahoo.com","login"=>"eb","password"=>"edoua97-Boulange",],["firstname"=>"Julia","name"=>"Gautier","email"=>"julgautie@gmail.com","login"=>"jgauti","password"=>"Juli21<Gautie",],["firstname"=>"Béatrice","name"=>"Fournier","email"=>"béatrifourni@yahoo.com","login"=>"beatrf","password"=>"Beatric3<Fourni",],["firstname"=>"Gabriel","name"=>"Guerin","email"=>"gabguerin@hotmail.fr","login"=>"gabriegue","password"=>"Gabrie104?Guerin",],["firstname"=>"Ryan","name"=>"Brun","email"=>"rybr@hotmail.com","login"=>"rb","password"=>"Rya120?Bru",],["firstname"=>"Alexis","name"=>"Besson","email"=>"abesson@yahoo.com","login"=>"albesson","password"=>"Alexis136>Bes",],["firstname"=>"Annabelle","name"=>"Deschamps","email"=>"annabedesc@hotmail.fr","login"=>"ande","password"=>"Annab151@Desc",],["firstname"=>"Alexis","name"=>"Monnier","email"=>"alemonn@gmail.com","login"=>"alexim","password"=>"Ale57?Monni",],["firstname"=>"Livia","name"=>"Picard","email"=>"livipica@hotmail.com","login"=>"livipic","password"=>"Livi59@Pica",],["firstname"=>"Édouard","name"=>"Prevost","email"=>"édoprevo@hotmail.fr","login"=>"eprev","password"=>"edou52-Prevos",],["firstname"=>"Éléonore","name"=>"Lemoine","email"=>"élélem@yahoo.com","login"=>"eleonorelemoi","password"=>"eleonor54@Lemoin",],["firstname"=>"Élizabeth","name"=>"Dumont","email"=>"élizdumo@gmail.com","login"=>"elizabd","password"=>"eliz49!Dum",]];
        foreach ($listUser as $user) {
            $userInfo = new UserInfo();
            $userInfo->setName($user['name']);
            $userInfo->setFirstname($user['firstname']);
            $userInfo->setEmail($user['email']);
            $userInfo->setPassword(password_hash($user['password'], PASSWORD_BCRYPT));
            $userInfo->setLogin($user['login']);

            $manager->persist($userInfo);
        }

        // fixture comment
        $listComment = $listTrickDescription;

        for ($i=0; $i<50; $i++) {
            $trickId = random_int(1,19);
            $userId = random_int(1,18);

            $comment = new TrickComment();
            $comment->setContent($listComment[random_int(0,64)] . $listComment[random_int(0,64)] . $listComment[random_int(0,64)]);
            $comment->setTrickId($trickId);
            $comment->setAuthor($userId);

            $manager->persist($comment);
        }

        // fixture trick_picture
        for ($trickId=1; $trickId<20; $trickId++) {
            $trickPic = [];
            $countPicTrick = random_int(2,5);
            for ($picCount=1; $picCount<$countPicTrick; $picCount++) {
                $pictures = new TrickPicture();
                $main = false;
                $randomPic = random_int(1,33);
                while (in_array($randomPic, $trickPic)) {
                    $randomPic = random_int(1,33);
                }
                $trickPic[] = $randomPic;
                $filename = "img/tricks-img/trick" . $randomPic . ".jpg";
                if ($picCount == 1) {
                    $main = true;
                }

                $pictures->setFilename($filename);
                $pictures->setMain($main);
                $pictures->setTrickId($trickId);
                $manager->persist($pictures);
            }
        }

        $manager->flush();
    }
}
