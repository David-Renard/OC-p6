<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\TrickComment;
use App\Entity\UserInfo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // fixture tricks
        $listTrickName = ["Switch Misty 360 Seat Belt","Switch Misty 180 Mute","Switch 360 Japan","Cork 540 Indy","Cork 900 Tail Grab","720 Tail Grab","180 Sad","Rodéo 360 Stalefish","Rodéo 1080 Tail Grab","Misty 900 Indy","Misty 900 Japan","1080 Stalefish"];
        $listTrickSlug = ["switch-misty-360-seat-belt","switch-misty-180-mute","switch-360-japan","cork-540-indy","cork-900-tail-grab","720-tail-grab","180-sad","rodeo-360-stalefish","rodeo-1080-tail-grab","misty-900-indy","misty-900-japan","1080-stalefish"];
        $listTrickDescription = ["Vivamus vitae ante et arcu pharetra varius.","Nam imperdiet eros ac metus ultrices, a pharetra tortor interdum.","Etiam ac lacus at felis ornare tincidunt.","Proin ultrices nisl eget varius consequat.","Phasellus sed est in dui fringilla sodales.","Suspendisse non mauris sagittis, ornare enim vel, ornare justo.","Aenean dapibus elit eget velit auctor, at pharetra libero ultricies.","Praesent at tellus feugiat, malesuada sem vulputate, lobortis est.","Integer vel neque sed turpis cursus pretium.","Aliquam vulputate nulla dictum turpis pellentesque molestie.","Nam et velit eget lacus blandit volutpat in et ante.","Pellentesque vel orci vitae odio tempor blandit.","Vestibulum sed felis eget arcu facilisis pharetra.","Fusce porttitor mi nec quam tempor, ac consequat ligula dictum.","Nulla viverra neque sit amet facilisis condimentum.","Aenean finibus lorem eu viverra euismod.","Ut congue nisl nec eros accumsan, sodales venenatis quam condimentum."];
        for ($i=0; $i<count($listTrickName); $i++) {
            $trick = new Trick();
            $trick->setName($listTrickName[$i]);
            $trick->setSlug($listTrickSlug[$i]);
            $trick->setDescription($listTrickDescription[random_int(0,16)] . $listTrickDescription[random_int(0,16)]);

            $manager->persist($trick);
        }

        // fixture users
        $listUser = [["firstname" => "Adam","name" => "Marchand","email" => "adammarchan@hotmail.fr","login" => "adammarcha","password" => "Adam111<Marchan",],["firstname" => "Jacob","name" => "Gilbert","email" => "jgi@hotmail.fr","login" => "jacogilb","password" => "Jac158>Gilber",],["firstname" => "Rosalie","name" => "Langlois","email" => "rlanglo@yahoo.com","login" => "rosalilangloi","password" => "Rosalie4!Langlo",],["firstname" => "Xavier","name" => "Julien","email" => "xajulien@gmail.com","login" => "xavierjulie","password" => "Xavie120<Julie",],["firstname" => "Nicolas","name" => "Guillaume","email" => "nicolguillaum@yahoo.com","login" => "nicguilla","password" => "Nicolas11@Guilla",],["firstname" => "Arnaud","name" => "Menard","email" => "ame@hotmail.com","login" => "arnmen","password" => "Arnau34?Menard",],["firstname" => "Maélie","name" => "Louis","email" => "maéllouis@gmail.com","login" => "mlou","password" => "Maéli89-Louis",],["firstname" => "Rosalie","name" => "Guerin","email" => "rosalgue@yahoo.com","login" => "rgueri","password" => "Rosali73@Guerin",],["firstname" => "Charlotte","name" => "Vasseur","email" => "charlottevasseu@hotmail.fr","login" => "charlvasseur","password" => "Charl102!Vasse",],["firstname" => "Audrey","name" => "Camus","email" => "audrcam@gmail.com","login" => "audrca","password" => "Audr75@Cam",],["firstname" => "Alice","name" => "Simon","email" => "alisimon@yahoo.fr","login" => "alicsimo","password" => "Alice144>Sim",],["firstname" => "Raphaelle","name" => "Moulin","email" => "ramo@yahoo.fr","login" => "rmo","password" => "Rap136>Mou",]];
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
            $trickId = random_int(1,13);
            $userId = random_int(1,12);

            $comment = new TrickComment();
            $comment->setContent($listComment[random_int(0,16)] . $listComment[random_int(0,16)] . $listComment[random_int(0,16)]);
            $comment->setTrickId($trickId);
            $comment->setAuthor($userId);

            $manager->persist($comment);
        }

        $manager->flush();
    }
}
