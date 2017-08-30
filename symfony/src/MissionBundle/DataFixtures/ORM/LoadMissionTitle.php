<?php

namespace MissionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use MissionBundle\Entity\BusinessPractice;
use MissionBundle\Entity\MissionTitle;

class LoadMissionTitle extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $data = [
            // title title
            [ 'mission_title.title.acquisition' ,'mission_title.title.acquisition'],
            [ 'mission_title.title.acquisition', 'mission_title.sub_category.external_growth'],
            [ 'mission_title.title.acquisition', 'mission_title.sub_category.post_merger_integration'],

            [ 'mission_title.title.go_to_market', 'mission_title.title.go_to_market'],
            [ 'mission_title.title.go_to_market', 'mission_title.sub_category.new_teritory'],
            [ 'mission_title.title.go_to_market', 'mission_title.sub_category.new_offer'],
            [ 'mission_title.title.go_to_market', 'mission_title.sub_category.parternship_strategy'],

            [ 'mission_title.title.operational_excellence', 'mission_title.title.operqtionql_excellence'],
            [ 'mission_title.title.operational_excellence', 'mission_title.sub_category.best_pra_implem'],
            [ 'mission_title.title.operational_excellence', 'mission_title.sub_category.cost_reduction'],
            [ 'mission_title.title.operational_excellence', 'mission_title.sub_category.processed_optimization'],
            [ 'mission_title.title.operational_excellence', 'mission_title.sub_category.organization_organization'],

            [ 'mission_title.title.business_transformation', 'mission_title.title.business_tranformation'],
            [ 'mission_title.title.business_transformation', 'mission_title.sub_category.new_business_model'],
            [ 'mission_title.title.business_transformation', 'mission_title.sub_category.digital_innovation'],
            [ 'mission_title.title.business_transformation', 'mission_title.sub_category.growth_support'],
            [ 'mission_title.title.business_transformation', 'mission_title.sub_category.globalisation'],

            [ 'mission_title.title.is_it', 'mission_title.title.is_it'],
            [ 'mission_title.title.is_it', 'mission_title.sub_category.roadmap_vision'],
            [ 'mission_title.title.is_it', 'mission_title.sub_category.rfi'],
            [ 'mission_title.title.is_it', 'mission_title.sub_category.implementation'],
            [ 'mission_title.title.is_it', 'mission_title.sub_category.parternship'],

            [ 'mission_title.title.studies', 'mission_title.title.studie'],
            [ 'mission_title.title.studies', 'mission_title.sub_category.benchmark'],
            [ 'mission_title.title.studies', 'mission_title.sub_category.market'],
            [ 'mission_title.title.studies', 'mission_title.sub_category.innovation'],

            [ 'mission_title.title.governance_issues', 'mission_title.title.governance_issues'],
            [ 'mission_title.title.restructuring', 'mission_title.title.restructuring'],
            [ 'mission_title.title.long_term_finance', 'mission_title.title.long_term_finance'],
            [ 'mission_title.title.cash_optimisation', 'mission_title.title.cash_optimisation'],
            [ 'mission_title.title.security_asset', 'mission_title.title.security_asset'],
            [ 'mission_title.title.other_services', 'mission_title.title.other_services'],
        ];

        foreach ($data as $missionTitle) {
            $title = new MissionTitle();
            $title->setCategory($missionTitle[0])->setTitle($missionTitle[1]);
            $manager->persist($title);
        }
        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 9;
    }
}
