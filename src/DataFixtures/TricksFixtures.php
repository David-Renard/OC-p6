<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\TrickCategory;
use App\Entity\TrickComment;
use App\Entity\TrickPicture;
use App\Entity\TrickVideo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class TricksFixtures extends Fixture implements DependentFixtureInterface
{


    public function __construct(private readonly SluggerInterface $slugger)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $listTricksName
            = [
            "Switch Rodéo 720 Japan",
            "Switch Cork 540 Tail Grab",
            "Misty 900 Mute",
            "Switch Misty 1080 Mute",
            "Switch 360 Stalefish",
            "Switch Cork 1080 Mute",
            "Cork 180 Seat Belt",
            "Switch Rodéo 180 Indy",
            "Switch Cork 180 Seat Belt",
            "Cork 720 Indy",
        ];
        // Fixture trickPicture
        $mainPictures = [];
        $additionalPictures = [];
        for ($countPicture = 0; $countPicture < 33; $countPicture++) {
            $picture = new TrickPicture();
            $main = false;
            if ($countPicture < count($listTricksName)) {
                $main = true;
            }

            $numPic = $countPicture + 1;

            $url = $numPic.".jpg";
            $name = "freestyle trick".$numPic;
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

        // Fixture comments
        $loremDescription
            = [
                "Pellentesque rutrum nunc vel ligula eleifend, in bibendum dolor gravida.",
                "Suspendisse dignissim nisl at dolor varius ornare.",
                "Nullam dignissim odio ut eros vehicula hendrerit.",
                "Proin eu dolor faucibus, sollicitudin lectus in, placerat nunc.",
                "Praesent eu massa ullamcorper, gravida elit sollicitudin, vehicula massa.",
                "Ut in purus scelerisque, volutpat nisi quis, commodo augue.",
                "Donec vitae diam eget dolor convallis pretium id non justo.",
                "Proin a erat eu est porta finibus eu vel lacus.",
                "Ut in elit a quam eleifend aliquam.",
                "Fusce ornare neque sit amet nunc ultrices, in consequat sem malesuada.",
                "Suspendisse sed massa sollicitudin, interdum nibh eu, pulvinar tortor.",
                "Morbi in lacus et ex lacinia tempus.",
                "Suspendisse rhoncus nisl eu lacus condimentum, ac tempus est pellentesque.",
                "Nunc sit amet felis egestas, blandit velit consectetur, tristique elit.",
                "Sed pharetra enim a ultricies tincidunt.",
                "Suspendisse imperdiet libero ac viverra rhoncus.",
                "Maecenas congue diam eu ligula semper fermentum.",
                "Suspendisse non ante et ipsum dictum faucibus.",
                "Nulla tempor orci eget varius tincidunt.",
                "Pellentesque eget sem egestas, venenatis nibh non, sodales nisl.",
                "Curabitur lobortis lectus quis ullamcorper ultricies.",
                "Donec porttitor augue non justo laoreet, nec sagittis nisl egestas.",
                "Mauris eget mauris eu mauris pretium malesuada et a quam.",
                "Mauris consequat justo sit amet nisi lacinia, vel dictum leo laoreet.",
                "Mauris pretium ex at lectus dictum gravida.",
                "Curabitur sit amet nisl rhoncus, ultrices erat at, interdum neque.",
                "Etiam ultrices ante a libero posuere blandit.",
                "Donec porttitor neque sed blandit sollicitudin.",
                "Pellentesque at lorem vitae magna hendrerit hendrerit.",
                "Curabitur nec ipsum et nulla varius condimentum in sit amet turpis.",
                "Curabitur vehicula tortor vitae odio porttitor, et rutrum mauris facilisis.",
                "Morbi commodo libero quis nibh dictum fermentum.",
                "Quisque id nulla id libero facilisis elementum.",
                "Integer facilisis sem a mi mollis, at elementum lacus scelerisque.",
                "Nulla aliquet nisi viverra, varius ipsum at, porttitor odio.",
                "Donec in velit dictum, fringilla ex sed, gravida nibh.",
                "Duis eget dolor nec eros rutrum scelerisque a nec lectus.",
                "Suspendisse consectetur est pellentesque tortor placerat laoreet.",
                "Sed tristique risus vitae tellus accumsan, et sodales lorem consectetur.",
                "Vivamus luctus nisl eget dui auctor, ut laoreet enim consectetur.",
                "Donec at purus vestibulum, laoreet urna eget, laoreet mauris.",
                "Vestibulum dignissim augue id ultrices pharetra.",
                "Morbi ut nulla dapibus, maximus neque sed, posuere lectus.",
                "Praesent accumsan ex faucibus lacinia ultrices.",
                "Nullam nec leo pellentesque dui interdum tempor.",
                "Vivamus quis ipsum sed arcu porttitor congue vel vitae quam.",
                "Quisque commodo ipsum quis orci aliquet, a sollicitudin metus pulvinar.",
                "Donec vel quam a felis scelerisque semper at id metus.",
                "Vestibulum iaculis quam vel velit scelerisque vulputate.",
                "Morbi cursus justo non neque mollis hendrerit vitae ac turpis.",
                "Nullam porta nulla sed nunc sollicitudin vestibulum ac sit amet mauris.",
                "Donec scelerisque arcu id nibh laoreet, a scelerisque est volutpat.",
                "Phasellus elementum libero et tortor varius, at fringilla erat eleifend.",
                "Cras suscipit metus faucibus, rutrum arcu ut, aliquet libero.",
                "Mauris nec lorem quis augue sollicitudin rhoncus in eget leo.",
                "Proin sit amet elit ultrices, venenatis augue eu, vehicula dolor.",
                "Vivamus porttitor arcu mattis massa pulvinar condimentum.",
                "Cras cursus metus vel nunc suscipit convallis.",
                "Aliquam dictum augue a augue placerat, eu interdum tellus hendrerit.",
                "Nullam posuere leo eu enim pretium, ut dapibus lorem efficitur.",
                "Proin aliquet purus eu dignissim viverra.",
                "Nam ac elit vel leo feugiat convallis nec consequat mi.",
                "Vestibulum non sapien tempor, posuere felis ut, facilisis arcu.",
                "Praesent vestibulum ligula ac tristique placerat.",
                "Maecenas placerat enim eget ante tincidunt, non luctus libero tempus.",
            ];
        $listComment = [];
        for ($countComment = 0; $countComment < 150; $countComment++) {
            $comment = new TrickComment();
            $content = "";
            for ($countLorem = 0; $countLorem < random_int(1, 4); $countLorem++) {
                $content = $content.$loremDescription[random_int(0,64)];
            }
            $now = new \DateTimeImmutable("now");
            $randomTime = rand(0,50);
            $comment->setContent($content);
            // Look for a user reference
            $user = $this->getReference('user-'.random_int(1,28));
            $comment->setUser($user);
            $comment->setCreatedAt($now->sub(new \DateInterval("P{$randomTime}D")));
            $listComment[] = $comment;
        }

        // Fixture categories
        $listCategory =
            [
                "Japan",
                "Tail Grab",
                "Mute",
                "Stalefish",
                "Seat Belt",
                "Indy",
                "Sad"
            ];
        $categories = [];
        foreach ($listCategory as $categoryArray) {
            $category = new TrickCategory();
            $category->setName($categoryArray);
            $manager->persist($category);
            $categories[] = $category;
        }
        $manager->flush();

        // Fixture tricks
        $tricksList = [];
        for ($i = 0; $i < count($listTricksName); $i++) {
            $trick = new Trick();
            $randomAuthor = random_int(1,10);

            $trick->setName($listTricksName[$i]);

            $slug = $this->slugger->slug($listTricksName[$i]);
            $trick->setSlug($slug);

            $trick->setDescription($loremDescription[random_int(0,64)].$loremDescription[random_int(0,64)].$loremDescription[random_int(0,64)]);
            $trick->setAuthor($this->getReference('user-'.$randomAuthor));
            foreach ($categories as $category) {
                if (stristr($listTricksName[$i],$category->getName()) !== false) {
                    $trick->setCategory($category);
                }
            }
            $trick->addPicture($mainPictures[$i]);

            $randomNbPictures = rand(0,5);
            for ($j = 0; $j < $randomNbPictures; $j++) {
                if (empty($additionalPictures) === false) {
                    $trick->addPicture(array_pop($additionalPictures));
                }
            }

            $randomNbComment = rand(0,15);
            for ($j = 0; $j < $randomNbComment; $j++) {
                if (empty($listComment) === false) {
                    $trick->addComment(array_pop($listComment));
                }
            }
            $tricksList[] = $trick;

            $manager->persist($trick);
        }
        $manager->flush();

        // Fixtures videos
        $listVideos =
            [
                "https://www.youtube.com/embed/aINlzgrOovI",
                "https://www.youtube.com/embed/kI2G81QRfyk",
                "https://www.youtube.com/embed/8KotvBY28Mo",
                "https://www.youtube.com/embed/T92n4e5bEpE",
                "https://www.dailymotion.com/embed/video/xggf1u",
                "https://www.dailymotion.com/embed/video/xnii6r",
                "https://www.dailymotion.com/embed/video/x8gerr2",
                "https://www.dailymotion.com/embed/video/x19aigu",
            ];
        foreach ($listVideos as $videoArray) {
            $video = new TrickVideo();
            $video->setUrl($videoArray);

            $randomTrick = rand(0, count($tricksList) - 1);
            $video->setTrick($tricksList[$randomTrick]);

            $manager->persist($video);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UsersFixtures::class,
        ];
    }
}
