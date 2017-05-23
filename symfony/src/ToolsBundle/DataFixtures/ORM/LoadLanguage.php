<?php

namespace ToolsBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use ToolsBundle\Entity\Language;

class LoadLanguage extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $names = [
            'language.english',
            'language.french',
            'language.german',
            'language.spanish',
            'language.portuguese',
            'language.japanese',
            'language.arabic',
            'language.italian',
            'language.korean',
            'language.mandarin',
            'language.bengali',
            'language.hindi',
            'language.russian',
            'language.wu',
            'language.javanese',
            'language.turkish',
            'language.vietamese',
            'language.telegu',
            'language.cantonese',
            'language.marathi',
            'language.tamil',
            'language.urdu',
            'language.minman',
            'language.jinyu',
            'language.gujarati',
            'language.polish',
            'language.ukrainian',
            'language.persian',
            'language.xiang',
            'language.malayalam',
            'language.hakka',
            'language.kannada',
            'language.oriya',
            'language.panjabi_west',
            'language.sunda',
            'language.panjabi_east',
            'language.romanian',
            'language.bhojpuri',
            'language.azerbaijani',
            'language.maithili',
            'language.hausa',
            'language.burmese',
            'language.serbo',
            'language.gan',
            'language.awadhi',
            'language.thai',
            'language.dutch',
            'language.yoruba',
        ];

        foreach ($names as $name) {
            $language = new Language();
            $language->setName($name);
            $manager->persist($language);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 2;
    }
}
