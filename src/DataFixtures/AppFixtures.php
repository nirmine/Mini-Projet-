<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Region;
use App\Entity\Owner;
use App\Entity\Room;
class AppFixtures extends Fixture
{ // définit un nom de référence pour une instance de Region
    public const IDF_REGION_REFERENCE = 'idf-region';
    public const IDF_ROOM_REFERENCE ='';
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        //$manager->flush();
        $region = new Region();
        $region->setCountry("FR");
        $region->setName("Ile de France");
        $region->setPresentation("La région française capitale");
        $manager->persist($region);
    
        $manager->flush();
        // Une fois l'instance de Region sauvée en base de données,
        // elle dispose d'un identifiant généré par Doctrine, et peut
        // donc être sauvegardée comme future référence.
        $this->addReference(self::IDF_REGION_REFERENCE, $region);
        $owner=new Owner();
        $owner->setFirstName("nermine");
        $owner->setFamilyName("khaled");
        $owner->setAddress("14 Rue Charles fourier");
        $owner->setcountry("Fr");
      
        $manager->persist($owner);
        $manager->flush();
        $this->addReference(self::IDF_ROOM_REFERENCE, $owner);
        $room = new Room();
        $room->setSummary("Beau poulailler ancien à Évry");
        $room->setDescription("très joli espace sur paille");
        $room->setCapacity(2);
        $room->setSuperficy(100.2);
        $room->setPrice(200);
        $room->setAddress('5 rue charles fourier TSP à vendre');
        //$room->addRegion($region);
        // On peut plutôt faire une référence explicite à la référence
        // enregistrée précédamment, ce qui permet d'éviter de se
        // tromper d'instance de Region :
        $room->addRegion($this->getReference(self::IDF_REGION_REFERENCE));     
        $room->setOwner($this->getReference(self::IDF_ROOM_REFERENCE));     
        $manager->persist($room);
    
        $manager->flush();
       
      
    }
}
