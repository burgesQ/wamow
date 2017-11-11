<?php

namespace MissionBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\ProgressBar;

class MigrationToBubbleV2Command extends ContainerAwareCommand
{
    private $newValues = [

        //
        // Bellow the "merged" one
        //

        'workexperience.projectmanagementassistance' => [
            [
                'mission_title.title.is_it',
                'mission_title.title.business_transformation'
            ],
            true
        ],
        'workexperience.specificationandtesting'     => [['mission_title.title.is_it'], false],
        'workexperience.businessrequirements'        => [['mission_title.title.is_it'], false],
        'workexperience.statementofwork'             => [['mission_title.title.is_it'], false],

        'workexperience.institutionalcommunication' => [['mission_title.title.operational_excellence'], true],
        'workexperience.evenementialcommunication'  => [['mission_title.title.'], false],
        'workexperience.crisiscommunication'        => [['mission_title.title.'], false],
        'workexperience.implementcommunicationplan' => [['mission_title.title.'], false],

        'workexperience.internalcommunication'        => [['mission_title.title.operational_excellence'], true],
        'workexperience.humanressourcescommunication' => [['mission_title.title.'], false],

        'workexperience.coremodel'                                    => [
            [
                'mission_title.title.operational_excellence',
                'mission_title.title.is_it'
            ],
            true
        ],
        'workexperience.roadmap'                                      => [['mission_title.title.'], false],
        'workexperience.implementationofnewtechnologies'              => [['mission_title.title.'], false],
        'workexperience.implementationofcollaborativeportalswebsites' => [['mission_title.title.'], false],

        'workexperience.performancediagnosis'  => [['mission_title.title.operational_excellence'], true],
        'workexperience.decisionmakingprocess' => [['mission_title.title.'], false],

        'workexperience.digitalperformancediagnosis' => [
            [
                'mission_title.title.is_it',
                'mission_title.title.business_transformation'
            ],
            true
        ],
        'workexperience.retaildigitalisation'        => [['mission_title.title.'], false],

        'workexperience.salespolicyefficiency'       => [
            ['mission_title.title.go_to_market'],
            true
        ],
        'workexperience.salesdistributionefficiency' => [
            ['mission_title.title.'],
            false
        ],

        'workexperience.economicmodelisations'        => [
            ['mission_title.title.studies'],
            true
        ],
        'workexperience.studies'                      => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.bestpracticesbenchmark'       => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.competitorsbenchmarkanalysis' => [
            ['mission_title.title.'],
            false
        ],

        'workexperience.customerknowledge'              => [
            ['mission_title.title.go_to_market'],
            true
        ],
        'workexperience.customerexperience'             => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.customerrelationshipmanagement' => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.satisfactionsurvey'             => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.qualitymanagementcallcenter'    => [
            ['mission_title.title.'],
            false
        ],

        'workexperience.managementseminar' => [
            ['mission_title.title.operational_excellence'],
            true
        ],
        'workexperience.learningprogram'   => [
            ['mission_title.title.'],
            false
        ],

        'workexperience.administration'                     => [
            ['mission_title.title.operational_excellence'],
            true
        ],
        'workexperience.compensationbenefitsplanmanagement' => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.payrolloptimisation'                => [
            ['mission_title.title.'],
            false
        ],

        'workexperience.payroll'                  => [
            [
                'mission_title.title.operational_excellence',
                'mission_title.title.cash_optimisation',
                'mission_title.title.security_asset'
            ],
            true
        ],
        'workexperience.aligncompensation'        => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.reward'                   => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.compensationbenefitsplan' => [
            ['mission_title.title.'],
            false
        ],

        'workexperience.talentmanagement'      => [
            ['mission_title.title.operational_excellence'],
            true
        ],
        'workexperience.successionplan'        => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.talentevaluation'      => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.talentdevelopment'     => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.prospectivetalent'     => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.internalmobility'      => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.processimplementation' => [
            ['mission_title.title.'],
            false
        ],

        'workexperience.portfoliovaluation'          => [
            [
                'mission_title.title.security_asset',
                'mission_title.title.cash_optimisation',
                'mission_title.title.long_term_finance'
            ],
            true
        ],
        'workexperience.cashandinvestmentmanagement' => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.assetallocation'             => [
            ['mission_title.title.'],
            false
        ],

        'workexperience.developmentroadmap' => [
            [
                'mission_title.title.go_to_market',
                'mission_title.title.operational_excellence',
                'mission_title.title.business_transformation',
                'mission_title.title.is_it',
                'mission_title.title.acquisition',
                'mission_title.title.restructuring',
                'mission_title.title.cash_optimisation',
            ],
            true
        ],
        'workexperience.actionplan'         => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.transitionplan'     => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.costtransformation' => [
            ['mission_title.title.'],
            false
        ],

        'workexperience.standardization'            => [
            ['mission_title.title.operational_excellence'],
            true
        ],
        'workexperience.operationalprocessredesign' => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.processdesign'              => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.reengineeringprocess'       => [
            ['mission_title.title.'],
            false
        ],

        'workexperience.redesigntoreducecost'              => [
            [
                'mission_title.title.operational_excellence',
                'mission_title.title.restructuring'
            ],
            true
        ],
        'workexperience.procurementmanagementoptimisation' => [['mission_title.title.'], false],
        //        'workexperience.costkilling'                       => [
        //            [''],
        //            false
        //        ],

        'workexperience.auditit'                  => [
            ['mission_title.title.is_it'],
            true
        ],
        'workexperience.itperformanceimprovement' => [
            ['mission_title.title.'],
            false
        ],

        'workexperience.stockoptimisation'            => [
            ['mission_title.title.operational_excellence'],
            true
        ],
        'workexperience.deliveryoptimisation'         => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.ergonomicstandardofwarehouse' => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.complexityreduction'          => [
            ['mission_title.title.'],
            false
        ],

        'workexperience.conceptionofbudgetprocessandimplementationofbudgettools' => [
            [
                'mission_title.title.operational_excellence',
                'mission_title.title.is_it',
            ],
            true
        ],
        'workexperience.buildinganalyticalmodel'                                 => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.abcmethod'                                               => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.abmmethod'                                               => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.budgetplan'                                              => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.budgetprocessredesign'                                   => [
            ['mission_title.title.'],
            false
        ],

        'workexperience.riskmanagement'           => [
            [
                'mission_title.title.operational_excellence',
                'mission_title.title.governance_issues',
                'mission_title.title.security_asset',
                'mission_title.title.is_it'
            ],
            true
        ],
        'workexperience.iriskmanagement'          => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.audit'                    => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.itriskmanagement'         => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.activitycontinuationplan' => [
            ['mission_title.title.'],
            false
        ],

        'workexperience.reducingreportingprocess'   => [
            [
                'mission_title.title.operational_excellence',
                'mission_title.title.governance_issues'
            ],
            true
        ],
        'workexperience.reducingaccountableprocess' => [
            ['mission_title.title.'],
            false
        ],

        'workexperience.worklife'     => [
            [
                'mission_title.title.operational_excellence',
                'mission_title.title.governance_issues',
                'mission_title.title.business_transformation'
            ],
            true
        ],
        'workexperience.psychosocial' => [
            ['mission_title.title.'],
            false
        ],

        'workexperience.recruitment'      => [
            ['mission_title.title.operational_excellence'],
            true
        ],
        'workexperience.employerbranding' => [
            ['mission_title.title.'],
            false
        ],

        'workexperience.bankregulatorycompliance'      => [
            [
                'mission_title.title.security_asset',
                'mission_title.title.long_term_finance'
            ],
            true
        ],
        'workexperience.insuranceregulatorycompliance' => [
            ['mission_title.title.'],
            false
        ],

        'workexperience.restructuringplan'    => [
            [
                'mission_title.title.business_transformation',
                'mission_title.title.restructuring'
            ],
            true
        ],
        'workexperience.employmentprotection' => [
            ['mission_title.title.'],
            false
        ],

        'workexperience.marketsegmentation' => [
            ['mission_title.title.governance_issues'],
            true
        ],
        'workexperience.brandstrategy'      => [
            ['mission_title.title.'],
            false
        ],

        'workexperience.growthdevelopment'                                 => [
            [
                'mission_title.title.acquisition',
                'mission_title.title.governance_issues'
            ],
            true
        ],
        'workexperience.potentialsynergies'                                => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.swap'                                              => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.acquisitionstrategyandexternalgrowthopportunities' => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.targetanalysis'                                    => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.transfertoperationalmanagement'                    => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.cessionsstrategy'                                  => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.fundmanagementstrategy'                            => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.investmentstrategy'                                => [
            ['mission_title.title.'],
            false
        ],

        'workexperience.digitalstrategy'    => [
            [
                'mission_title.title.go_to_market',
                'mission_title.title.business_transformation',
                'mission_title.title.is_it',
            ],
            true
        ],
        'workexperience.e-commercestrategy' => [
            ['mission_title.title.'],
            false
        ],

        'workexperience.itstrategy'           => [
            ['mission_title.title.is_it'],
            true
        ],
        'workexperience.itpoliciesgovernance' => [
            ['mission_title.title.'],
            false
        ],
        'workexperience.masterplan'           => [
            ['mission_title.title.'],
            false
        ],

        'workexperience.businessdevelopmentpriorities' => [['mission_title.title.go_to_market'], true],
        'workexperience.strategicmarketingplan'        => [['mission_title.title.'], false],
        'workexperience.salesgotomarketstrategy'       => [['mission_title.title.'], false],

        'workexperience.strategyorganization'  => [['mission_title.title.operational_excellence'], true],
        'workexperience.functionimprovement'   => [['mission_title.title.'], false],
        'workexperience.efficiencyimprovement' => [['mission_title.title.'], false],

        'workexperience.diagnosisfunction' => [
            [
                'mission_title.title.operational_excellence',
                'mission_title.title.business_transformation'
            ],
            true
        ],
        'workexperience.redesignfunction'  => [['mission_title.title.'], false],
        'workexperience.newfunction'       => [['mission_title.title.'], false],

        //
        // Bellow the renamed
        //

        'workexperience.agenda'                                    => [['mission_title.title.other_services'], true],
        'workexperience.itarchitecture'                            => [['mission_title.title.is_it'], true],
        'workexperience.automatisation'                            => [
            [
                'mission_title.title.is_it',
                'mission_title.title.business_transformation'
            ],
            true
        ],
        'workexperience.bigdata'                                   => [
            [
                'mission_title.title.is_it',
                'mission_title.title.business_transformation',
                'mission_title.title.operational_excellence'
            ],
            true
        ],
        'workexperience.alignsolutionandoperationalneeds'          => [['mission_title.title.is_it'], true],
        'workexperience.scope'                                     => [
            [
                'mission_title.title.is_it',
                'mission_title.title.operational_excellence',
                'mission_title.title.business_transformation',
                'mission_title.title.acquisition'
            ],
            true
        ],
        'workexperience.currentitsystemlayout'                     => [
            [
                'mission_title.title.is_it',
                'mission_title.title.business_transformation'
            ],
            true
        ],
        'workexperience.cashoptimization'                          => [['mission_title.title.cash_optimisation'], true],
        'workexperience.solutionselection'                         => [
            [
                'mission_title.title.is_it',
                'mission_title.title.business_transformation'
            ],
            true
        ],
        'workexperience.socialclimate'                             => [
            ['mission_title.title.operational_excellence'],
            true
        ],
        'workexperience.changemanagement'                          => [
            [
                'mission_title.title.business_transformation',
                'mission_title.title.is_it'
            ],
            true
        ],
        'workexperience.callcenterimplementation'                  => [
            [
                'mission_title.title.operational_excellence',
                'mission_title.title.business_transformation',
                'mission_title.title.is_it'
            ],
            true
        ],
        'workexperience.standardimplementation'                    => [
            [
                'mission_title.title.operational_excellence',
                'mission_title.title.security_asset'
            ],
            true
        ],
        'workexperience.strategicduediligences'                    => [
            [
                'mission_title.title.acquisition',
                'mission_title.title.restructuring',
                'mission_title.title.long_term_finance'
            ],
            true
        ],
        'workexperience.projectitefficiency'                       => [['mission_title.title.is_it'], true],
        'workexperience.assessmentcenter'                          => [
            ['mission_title.title.operational_excellence'],
            true
        ],
        'workexperience.publicpolicyevaluation'                    => [['mission_title.title.other_services'], true],
        'workexperience.outsourcing'                               => [
            [
                'mission_title.title.operational_excellence',
                'mission_title.title.business_transformation',
                'mission_title.title.is_it',
                'mission_title.title.restructuring'
            ],
            true
        ],
        'workexperience.learningdigitalisation'                    => [
            ['mission_title.title.operational_excellence'],
            true
        ],
        'workexperience.seriousgames'                              => [
            ['mission_title.title.operational_excellence'],
            true
        ],
        'workexperience.teambuilding'                              => [
            ['mission_title.title.operational_excellence'],
            true
        ],
        'workexperience.bidsollicitationmanagement'                => [
            [
                'mission_title.title.is_it',
                'mission_title.title.business_transformation'
            ],
            true
        ],
        'workexperience.identificationofbestsupplychainpractices'  => [
            ['mission_title.title.operational_excellence'],
            true
        ],
        'workexperience.offshore'                                  => [['mission_title.title.is_it'], true],
        'workexperience.postacquisitionroadmap'                    => [['mission_title.title.acquisition'], true],
        'workexperience.jointventures'                             => [['mission_title.title.acquisition'], true],
        'workexperience.newofferlaunches'                          => [['mission_title.title.go_to_market'], true],
        'workexperience.testsandpilots'                            => [['mission_title.title.go_to_market'], true],
        'workexperience.leanmanagement'                            => [
            ['mission_title.title.operational_excellence'],
            true
        ],
        'workexperience.privatesponsorship'                        => [['mission_title.title.other_services'], true],
        'workexperience.sharecenterservicesimplementation'         => [
            [
                'mission_title.title.operational_excellence',
                'mission_title.title.is_it',
                'mission_title.title.business_transformation'
            ],
            true
        ],
        'workexperience.kpi'                                       => [
            [
                'mission_title.title.operational_excellence',
                'mission_title.title.is_it'
            ],
            true
        ],
        'workexperience.postmerger'                                => [
            [
                'mission_title.title.operational_excellence',
                'mission_title.title.business_transformation',
            ],
            true
        ],
        'workexperience.categorymanagement'                        => [['mission_title.title.go_to_market'], true],
        'workexperience.pricing'                                   => [['mission_title.title.go_to_market'], true],
        'workexperience.chainvalueoptimization'                    => [
            ['mission_title.title.operational_excellence'],
            true
        ],
        'workexperience.workspace'                                 => [
            [
                'mission_title.title.operational_excellence',
                'mission_title.title.business_transformation'
            ],
            true
        ],
        'workexperience.packaging'                                 => [
            [
                'mission_title.title.operational_excellence',
                'mission_title.title.go_to_market'
            ],
            true
        ],
        'workexperience.privatefinanceinitiative'                  => [['mission_title.title.other_services'], true],
        'workexperience.relationshipofpartnershipsandstockholders' => [['mission_title.title.go_to_market'], true],
        'workexperience.transformation'                            => [
            ['mission_title.title.business_transformation'],
            true
        ],
        'workexperience.organizatiotransformation'                 => [['mission_title.title.go_to_market'], true],
        'workexperience.informationsystem'                         => [
            [
                'mission_title.title.operational_excellence',
                'mission_title.title.cash_optimisation',
                'mission_title.title.security_asset',
                'mission_title.title.is_it'
            ],
            true
        ],
        'workexperience.performance'                               => [
            ['mission_title.title.operational_excellence'],
            true
        ],
        'workexperience.rationalisation'                           => [
            [
                'mission_title.title.operational_excellence',
                'mission_title.title.business_transformation',
                'mission_title.title.restructuring'
            ],
            true
        ],
        'workexperience.costevaluation'                            => [
            [
                'mission_title.title.restructuring',
                'mission_title.title.operational_excellence',
                'mission_title.title.cash_optimisation',
                'mission_title.title.is_it'
            ],
            true
        ],
        'workexperience.systemsecurity'                            => [
            [
                'mission_title.title.is_it',
                'mission_title.title.governance_issues',
                'mission_title.title.security_asset'
            ],
            true
        ],
        'workexperience.management'                                => [
            [
                'mission_title.title.is_it',
                'mission_title.title.operational_excellence',
                'mission_title.title.business_transformation'
            ],
            true
        ]
    ];

    protected function configure()
    {
        $this
            ->setName('mission:migrate_to_v2:execute')
            ->setDescription('Rename work experience; link them to mission title.')
            ->setHelp('This command mod the work experience value and link them to mission title for the need of the v2');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Migration bubble v2.');

        $em = $this->getContainer()->get('doctrine')->getManager();

        $missionTitleRepo   = $em->getRepository('MissionBundle:MissionTitle');
        $workExperienceRepo = $em->getRepository('MissionBundle:WorkExperience');
        $progress           = new ProgressBar($output, count($this->newValues));
        $progress->start();

        $io->section('Update Values');

        $last = null;

        /**
         * @var \MissionBundle\Entity\WorkExperience $workExperience
         */
        foreach ($this->newValues as $key => $val) {

            if ($workExperience = $workExperienceRepo->findOneBy(['name' => $key])) {

                if (!$val[1]) { // if workExp is to delete

                    $userWorkExp = $workExperience->getUserWorkExperiences();

                    /**
                     * @var \MissionBundle\Entity\UserWorkExperience $oneUserWorkExp
                     */
                    foreach ($userWorkExp as $oneUserWorkExp) {
                        $oneUserWorkExp->setWorkExperience($last);
//                        $em->remove($oneUserWorkExp);
                    }

                    /**
                     * @var \MissionBundle\Entity\Mission $oneMission
                     */
                    foreach ($workExperience->getMissions() as $oneMission) {
                        $oneMission->setWorkExperience($last);
                        //                        $em->remove($oneUserWorkExp);
                    }



                    $em->remove($workExperience);
                } else {

                    // link title <-> workExp
                    foreach ($val[0] as $oneTitle) {

                        /** @var \MissionBundle\Entity\MissionTitle $title */
                        if (($title = $missionTitleRepo->findOneBy(['title' => $oneTitle])))
                            if (!$workExperience->getMissionTitles()->contains($title))
                                $workExperience->addMissionTitle($title);

                    }
                    $last = $workExperience;
                }

            } else
                dump("not found :(");

            $progress->advance();
        }

        $progress->finish();
        $em->flush();
    }

}
