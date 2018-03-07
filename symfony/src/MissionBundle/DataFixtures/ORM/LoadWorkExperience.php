<?php


namespace MissionBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use MissionBundle\Entity\WorkExperience;

// TODO : update to new value
class LoadWorkExperience extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $names = [
            'workexperience.create',
            'workexperience.standardization',
            'workexperience.transformation',
            'workexperience.scope',
            'workexperience.management',
            'workexperience.newfunction',
            'workexperience.redesignfunction',
            'workexperience.developmentroadmap',
            'workexperience.actionplan',
            'workexperience.growthdevelopment',
            'workexperience.diagnosisfunction',
            'workexperience.postmerger',
            'workexperience.potentialsynergies',
            'workexperience.economicmodelisations',
            'workexperience.studies',
            'workexperience.bestpracticesbenchmark',
            'workexperience.offshore',
            'workexperience.swap',
            'workexperience.outsourcing',
            'workexperience.rationalisation',
            'workexperience.riskmanagement',
            'workexperience.changemanagement',
            'workexperience.restructuringplan',
            'workexperience.performancediagnosis',
            'workexperience.operationalprocessredesign',
            'workexperience.decisionmakingprocess',
            'workexperience.kpi',
            'workexperience.acquisitionstrategyandexternalgrowthopportunities',
            'workexperience.targetanalysis',
            'workexperience.strategicduediligences',
            'workexperience.postacquisitionroadmap',
            'workexperience.jointventures',
            'workexperience.transfertoperationalmanagement',
            'workexperience.portfoliovaluation',
            'workexperience.cessionsstrategy',
            'workexperience.fundmanagementstrategy',
            'workexperience.investmentstrategy',
            'workexperience.transitionplan',
            'workexperience.costevaluation',
            'workexperience.costtransformation',
            'workexperience.cashandinvestmentmanagement',
            'workexperience.conceptionofbudgetprocessandimplementationofbudgettools',
            'workexperience.buildinganalyticalmodel',
            'workexperience.abcmethod',
            'workexperience.abmmethod',
            'workexperience.budgetplan',
            'workexperience.budgetprocessredesign',
            'workexperience.reducingreportingprocess',
            'workexperience.reducingaccountableprocess',
            'workexperience.cashoptimization',
            'workexperience.bankregulatorycompliance',
            'workexperience.insuranceregulatorycompliance',
            'workexperience.iriskmanagement',
            'workexperience.assetallocation',
            'workexperience.audit',
            'workexperience.customerknowledge',
            'workexperience.categorymanagement',
            'workexperience.pricing',
            'workexperience.salespolicyefficiency',
            'workexperience.salesdistributionefficiency',
            'workexperience.customerexperience',
            'workexperience.customerrelationshipmanagement',
            'workexperience.businessdevelopmentpriorities',
            'workexperience.strategicmarketingplan',
            'workexperience.salesgotomarketstrategy',
            'workexperience.newofferlaunches',
            'workexperience.testsandpilots',
            'workexperience.competitorsbenchmarkanalysis',
            'workexperience.relationshipofpartnershipsandstockholders',
            'workexperience.marketsegmentation',
            'workexperience.brandstrategy',
            'workexperience.coremodel',
            'workexperience.roadmap',
            'workexperience.itstrategy',
            'workexperience.itperformanceimprovement',
            'workexperience.projectitefficiency',
            'workexperience.itpoliciesgovernance',
            'workexperience.implementationofnewtechnologies',
            'workexperience.auditit',
            'workexperience.masterplan',
            'workexperience.alignsolutionandoperationalneeds',
            'workexperience.itarchitecture',
            'workexperience.itriskmanagement',
            'workexperience.systemsecurity',
            'workexperience.activitycontinuationplan',
            'workexperience.projectmanagementassistance',
            'workexperience.specificationandtesting',
            'workexperience.currentitsystemlayout',
            'workexperience.businessrequirements',
            'workexperience.statementofwork',
            'workexperience.bidsollicitationmanagement',
            'workexperience.solutionselection',
            'workexperience.digitalstrategy',
            'workexperience.e-commercestrategy',
            'workexperience.digitalperformancediagnosis',
            'workexperience.implementationofcollaborativeportalswebsites',
            'workexperience.retaildigitalisation',
            'workexperience.processdesign',
            'workexperience.reengineeringprocess',
            'workexperience.identificationofbestsupplychainpractices',
            'workexperience.sharecenterservicesimplementation',
            'workexperience.automatisation',
            'workexperience.stockoptimisation',
            'workexperience.deliveryoptimisation',
            'workexperience.ergonomicstandardofwarehouse',
            'workexperience.complexityreduction',
            'workexperience.internalcommunication',
            'workexperience.humanressourcescommunication',
            'workexperience.institutionalcommunication',
            'workexperience.evenementialcommunication',
            'workexperience.crisiscommunication',
            'workexperience.implementcommunicationplan',
            'workexperience.chainvalueoptimization',
            'workexperience.packaging',
            'workexperience.redesigntoreducecost',
            'workexperience.procurementmanagementoptimisation',
            'workexperience.costreduction',
            'workexperience.leanmanagement',
            'workexperience.callcenterimplementation',
            'workexperience.satisfactionsurvey',
            'workexperience.qualitymanagementcallcenter',
            'workexperience.successionplan',
            'workexperience.talentevaluation',
            'workexperience.talentdevelopment',
            'workexperience.prospectivetalent',
            'workexperience.administration',
            'workexperience.talentmanagement',
            'workexperience.internalmobility',
            'workexperience.recruitment',
            'workexperience.organizatiotransformation',
            'workexperience.strategyorganization',
            'workexperience.compensationbenefitsplanmanagement',
            'workexperience.functionimprovement',
            'workexperience.payrolloptimisation',
            'workexperience.informationsystem',
            'workexperience.payroll',
            'workexperience.processimplementation',
            'workexperience.efficiencyimprovement',
            'workexperience.performance',
            'workexperience.aligncompensation',
            'workexperience.reward',
            'workexperience.compensationbenefitsplan',
            'workexperience.workspace',
            'workexperience.worklife',
            'workexperience.psychosocial',
            'workexperience.managementseminar',
            'workexperience.learningprogram',
            'workexperience.learningdigitalisation',
            'workexperience.seriousgames',
            'workexperience.teambuilding',
            'workexperience.employerbranding',
            'workexperience.assessmentcenter',
            'workexperience.employmentprotection',
            'workexperience.socialclimate',
            'workexperience.privatefinanceinitiative',
            'workexperience.publicpolicyevaluation',
            'workexperience.agenda',
            'workexperience.privatesponsorship',
            'workexperience.standardimplementation',
            'workexperience.bigdata'
        ];

        $professionalExpertises = $manager->getRepository('MissionBundle:ProfessionalExpertise')->findAll();
        $missionKinds           = $manager->getRepository('MissionBundle:MissionKind')->findAll();
        $businessPractices      = $manager->getRepository('MissionBundle:BusinessPractice')->findAll();

        foreach ($names as $name) {
            $workExp = new WorkExperience();
            $workExp->setName($name);

            $workExp->addProfessionalExpertise($professionalExpertises[array_rand($professionalExpertises)]);
            $workExp->addBusinessPractice($businessPractices[array_rand($businessPractices)]);
            $workExp->addMissionKind($missionKinds[array_rand($missionKinds)]);

            $manager->persist($workExp);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 8;
    }
}
