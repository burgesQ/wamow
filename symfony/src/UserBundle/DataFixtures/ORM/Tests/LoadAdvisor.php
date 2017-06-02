<?php

namespace MissionBundle\DataFixtures\ORM\Tests;

use MissionBundle\Entity\UserWorkExperience;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use MissionBundle\Entity\ExperienceShaping;
use ToolsBundle\Entity\PhoneNumber;
use ToolsBundle\Entity\Address;
use UserBundle\Entity\User;

class LoadAdvisor extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var array
     */
    private $arrayDatas = [
        [
            "Albek", "Vanessa", "vanessa.albek@gt-executive.com",
            "vanessa.albeck@gmail.com",
            ['language.french', 'language.english'], "password",
            "Directeur Digital
            V.A. Conseil Directrice Générale Adjointe
            Adconion Media Group Directrice Marketing
            Stratégie et Développement
            Gong Media International Directrice des Solutions Marketing
            Yahoo!
            Responsable Marketing
            TF1 Publici,
            Alumni HEC,
            Alumni IEP Paris",

            [
                "businesspractice.media"
            ],
            [
                "typemissions.communication",
                "typemissions.strategic",
                "typemissions.execution",
                "typemissions.crm"
            ],
            [
                "professionalexpertises.businessunitmanager",
                "professionalexpertises.marketinginnovation",
                "professionalexpertises.communication",
                "professionalexpertises.digital"
            ],
            [
                "workexperience.customerrelationshipmanagement",
                "workexperience.digitalperformancediagnosis",
                "workexperience.implementcommunicationplan",
                "workexperience.salesgotomarketstrategy",
                "workexperience.strategicmarketingplan",
                "workexperience.salespolicyefficiency",
                "workexperience.customerexperience",
                "workexperience.newofferlaunches",
                "workexperience.digitalstrategy"
            ],
            [
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true]
            ],
            [ '+33', '612345678', '19', 'rue Vasco de Gama', '', '75015', 'Paris', 'FR']
         ], [
            "Anfriani", "Christophe", "chirstophe.anfriani@gt-executive.com",
            "c.anfriani@orange.fr",
            ['language.french'], "password",
            "Directeur du Développement (Afrique & Europe) Safran Morpho
            Directeur du Développement (Afrique & Océan Indien) Oberthur Fiduciaire
            Directeur Général & Directeur Commercial ALJC",

            [
                "businesspractice.services",
                "businesspractice.industry",
                "businesspractice.public"
            ],
            [
                'typemissions.organisationtransformation',
                'typemissions.strategic',
                'typemissions.execution'
            ],
            [
                "professionalexpertises.salesbusinessdevelopment",
                "professionalexpertises.operations"
            ],
            [
                "workexperience.implementationofnewtechnologies",
                "workexperience.management"
            ],
            [
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Bailly-Turchi", "Maud", "maud.bailly-turchi@gt-executive.com",
            "mbturchi@gmail.com",
            ['language.french'], "password",
            "Directeur de ministère
            Président des Houillères du Nord Pas de Calais
            Délégué interministériel à la réindustrialisation de l’Indre
            PDG de Sodie
            Administrateur BPI Franc",

            [
                "businesspractice.services",
                "businesspractice.industry",
                "businesspractice.public"
            ],
            [
                'typemissions.organisationtransformation',
                'typemissions.strategic',
                'typemissions.execution'
            ],
            [
                "professionalexpertises.salesbusinessdevelopment",
                "professionalexpertises.operations"
            ],
            [
                "workexperience.businessdevelopmentpriorities",
                "workexperience.humanressourcescommunication",
                "workexperience.changemanagement"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]

            ]
        ], [
            "Ballu", "Yann", "yann.ballu@gt-executive.com",
            "yann1ballu@gmail.com",
            ['language.french', 'language.english'], "password",
            "Cabinet BALLU, Directeur
            DAF SPB
            Directeur Financier Paine Webber
            Contôleur de gestion Hexlett-Packard",

            [
                "businesspractice.finance"
            ],
            [
                'typemissions.strategic',
                'typemissions.execution',
                'typemissions.audit'
            ],
            [
                "professionalexpertises.assetmanagement"
            ],
            [
                "workexperience.cashandinvestmentmanagement"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, true]
            ]
        ], [
            "Bellone", "Noelle", "nbellone@gt-executive.com",
            "",
            ['language.french'], "password",
            "DG leader Jet Service
            SG puis PDG, Vie Claire et Bernard Tapie Finance
            Directeur Général Filiales RH Formation et IFMA (Groupe CRIT)
            Déléguée Générale, Fédération des entreprises et entrepreneurs de France (FEEF)
            Maitre de conference HEC entrepreneurs",

            [
                "businesspractice.industry",
                "businesspractice.retail",
                "businesspractice.media"
            ],
            [
                'typemissions.organisationtransformation',
                'typemissions.strategic',
                'typemissions.execution',
                'typemissions.change'
            ],
            [
                "professionalexpertises.administrativemanagement"
            ],
            [
                "workexperience.salesgotomarketstrategy"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Benoit", "Olivier", "olivier.benoit@gt-executive.com",
            "olivier.benoit@delcrea.com",
            ['language.french'], "password",
            "Dirigeant actuel de Delcrea et Crossngo Production
            Président de Cube Research ltd
            Responsable de Recherches et Développement et DG du Groupe SVS
            DG de différentes Sociétés : Newpartner, Methaudit International, Infoscriptel",

            [
                "businesspractice.services",
                "businesspractice.finance",
                "businesspractice.media"
            ],
            [
                'typemissions.organisationtransformation',
                'typemissions.knowledgemanagement',
                'typemissions.strategic',
                'typemissions.change',
                'typemissions.crm'
            ],
            [
                "professionalexpertises.researchdevelopment"
            ],
            [
                "workexperience.implementationofnewtechnologies",
                "workexperience.informationsystem"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false]
            ]
        ], [
            "Berge", "Gerald", "gerald.berge@gt-executive.com",
            "berge.gerald@gmail.com",
            ['language.french'], "password",
            "Directeur Financier de transition- Groupe Devoteam (SSII)
            Directeur Financier Groupe Altran
            Senior Director chez Alvarez & Marsal
            DAF - Groupes  Amaury, Socpresse-Figaro
            ESSEC Alumni
            Ecole Central Lyon",

            [
                "businesspractice.industry",
                "businesspractice.media"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.strategic"
            ],
            [
                "professionalexpertises.researchdevelopment",
                "professionalexpertises.financeaccounting"
            ],
            [
                "workexperience.restructuringplan"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, true]
            ]
        ], [
            "Bergeret", "Anne", "ann.bergeret@gt-executive.com",
            "anne.bergeret@gmail.com",
            ['language.french'], "password",
            "Entreprenariat et intérim Management, Bike and Spa, Stade de France, Fortuneo Banque
            Directrice Marketing et Communication, Groupe Pierre et Vacances Center Parcs
            Directrice Produits Crédit, EGG Banking",

            [
                "businesspractice.finance",
                "businesspractice.hotel"
            ],
            [
                "typemissions.interimmanagement",
                "typemissions.communication",
                "typemissions.crm"
            ],
            [
                "professionalexpertises.marketinginnovation",
                "professionalexpertises.communication"
            ],
            [
                "workexperience.strategicmarketingplan",
                "workexperience.e-commercestrategy"
            ],
            [
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Berrebi", "Franklin", "franklin.berrebi@gt-executive.com",
            "franklin.berrebi@orange.fr",
            ['language.french'], "password",
            "Directeur Général Europe l'Oréal
            Responsable clients Publicis
            Senior Advisor Activa Capital
            Enseigne le Marketing Strategique a HEC, Dauphine et L'Ecole Centrale de Paris
            Membre du Comité de Sélection de l'Incubateur de l'Ecole Centrale de Paris",

            [
                "businesspractice.industry",
                "businesspractice.retail",
            ],
            [
                "typemissions.knowledgemanagement",
                "typemissions.strategic",
                "typemissions.execution",
                "typemissions.crm"
            ],
            [
                "professionalexpertises.marketinginnovation"
            ],
            [
                "workexperience.strategicmarketingplan"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, true]
            ]
        ], [
            "Bizien", "Marc", "marc.bizien@gt-executive.com",
            "marc.bizien@gmail.com",
            ['language.french'], "password",
            "DG EUROPORTE (ex VEOLIA CARGO France)
            DG DB Schenker Rail France
            Président SOCORAIL
            Directeur d’usines et de production BONNA-SABLA
            Pont Alumni",

            [
                "businesspractice.construction",
                "businesspractice.services",
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.businessreingeniering",
                "typemissions.strategic",
                "typemissions.execution"
            ],
            [
                "professionalexpertises.salesbusinessdevelopment"
            ],
            [
                "workexperience.humanressourcescommunication"
            ],
            [
                [false, true, false, false, false, false, true, 12, 2000, false]
            ]
        ], [
            "Blond", "Frederic", "frederic.blond@gt-executive.com",
            "blond.frederic@gmail.com",
            ['language.french'], "password",
            "Directeur Financier et juridique – Groupe Editions Atlas
            Directeur de la Division B2C – Groupe 3 Suisses international
            Directeur des Opérations et du Développement du Groupe Morgan
            Directeur Financier et des Opérations – Groupe LVMH Christian Lacroix",

            [
                "businesspractice.finance"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.costkilling",
                "typemissions.execution",
                "typemissions.migration",
                "typemissions.audit"
            ],
            [],
            [
                "workexperience.transfertoperationalmanagement",
                "workexperience.cashandinvestmentmanagement",
                "workexperience.e-commercestrategy",
                "workexperience.informationsystem"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Bonnafont", "Jacques", "jacques.bonnafont@gt-executive.com",
            "jacques.bonnafont@gmail.com",
            ['language.french'], "password",
            "Auditeur (PWC), consultant (EY)
            DAF, Secrétaire général (Chargeurs, DHL, CapGemini, Triangle Interim
            ANF Immobilier, Orco Property)",

            [
                "businesspractice.construction",
                "businesspractice.services"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.change",
                "typemissions.costkilling",
                "typemissions.strategic",
                "typemissions.execution"
            ],
            [
                "professionalexpertises.businessunitmanager"
            ],
            [
                "workexperience.currentitsystemlayout",
                "workexperience.restructuringplan"
            ],
            [
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Bouchet", "Clotilde", "clotilde.bouchet@gt-executive.com",
            "clobouchet@hotmail.com",
            ['language.french'], "password",
            "DAF (COMEX): AXA IM, AMUNDI, ABN Amro/Neuflize OBC, CBP, Isodev
            Directeur de la Stratégie Crédit Agricole SA
            Directeur Financements structurés et de projets Dexia, CA CIB
            Présidente DFCG Ile de France, DG C-Cube, Administrateur
            Resaux DFCG, IFA, OFPE/AFG, IEP Paris",

            [
                "businesspractice.finance",
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.implementation",
                "typemissions.costkilling",
                "typemissions.strategic",
                "typemissions.execution",
                "typemissions.audit",
            ],
            [
                "professionalexpertises.financeaccounting",
                "professionalexpertises.assetmanagement"
            ],
            [
                "workexperience.transfertoperationalmanagement",
                "workexperience.cashoptimization",
                "workexperience.riskmanagement"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Broders", "Olivier", "olivier.broders@gt-executive.com",
            "olivier.broders@gmail.com",
            ['language.french'], "password",
            "Directeur Général Opérationnel  Mc Company Monaco
            Directeur Général Dorotennis  Paris",

            [
                "businesspractice.retail",
            ],
            [
                "typemissions.strategic",
                "typemissions.execution",
                "typemissions.organisationtransformation",
                "typemissions.audit",
                "typemissions.change"
            ],
            [
                "professionalexpertises.financeaccounting",
                "professionalexpertises.operations",
            ],
            [
                "workexperience.operationalprocessredesign",
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Caloni", "Edouard-Vincent", "edouard-vincent.caloni@gt-executive.com",
            "caloniev@yahoo.fr",
            ['language.french'], "password",
            "Directeur du marketing Roland-Garros/Directeur communication FFT
            Directeur général adjoint Harrison&Wolf
            Chargé de mission (Plan et stratégie) Elf Aquitaine",

            [
                "businesspractice.services",
                "businesspractice.finance",
                "businesspractice.energy"
            ],
            [
                "typemissions.strategic",
                "typemissions.execution",
                "typemissions.crm",
                "typemissions.knowledgemanagement"
            ],
            [
                "professionalexpertises.marketinginnovation",
            ],
            [
                "workexperience.strategicmarketingplan",
                "workexperience.crisiscommunication",
            ],
            [
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Cappe", "Charles", "charles.cappe@gt-executive.com",
            "charlescappe@gmail.com",
            ['language.french', 'language.english'], "password",
            "CFO Findus, CFO Apogee (ex Sagem Com),
            DG Finance APV ( Invensys puis SPX Corp.)
            Directeur des Opérations Financières du Groupe Antalis
            CFO Lagardère Services, Travel Retail, Publishing, Espagne et UK
            Audencia, HEC, DFCG, Fi+ (co-fondateur),
            Chambre de Commerce d’Espagne en France, Arjo Antalis Alumni.",

            [
                "businesspractice.finance",
                "businesspractice.retail"
            ],
            [
                "typemissions.strategic",
                "typemissions.execution",
                "typemissions.organisationtransformation",
                "typemissions.change",
                "typemissions.audit",
                "typemissions.csp",
                "typemissions.businessreingeniering"
            ],
            [
                "professionalexpertises.operations"
            ],
            [
                "workexperience.reengineeringprocess",
                "workexperience.processdesign",
            ],
            [
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Caufman", "Olivier", "olivier.caufman@gt-executive.com",
            "",
            ['language.french'], "password",
            "Trader Banque Shearson Lehman (New-York)
            Banque Demewil Leble Paris
            Agent Assurance AXA
            Groupe Jean-Claude Darmon",

            [
                "businesspractice.finance",
            ],
            [
                "typemissions.communication",
                "typemissions.execution",
                "typemissions.costkilling",
                "typemissions.crm"
            ],
            [
                "professionalexpertises.financeaccounting",
                "professionalexpertises.assetmanagement",
                "professionalexpertises.communication",
                "professionalexpertises.controlling",
            ],
            [
                "workexperience.bankregulatorycompliance",
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Chauvin", "Jacques-Olivier", "jacques-olivier.chauvin@gt-executive.com",
            "jo.chauvin@gmail.com",
            ['language.french'], "password",
            "Président fondateur - Colbert Management et Conseil
            Vice Président Europe – Van Cleef & Arpels
            Directeur Général – Relais & Châteaux
            Alumni HEC",

            [
                "businesspractice.tourism",
                "businesspractice.hotel",
                "businesspractice.retail"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.execution",
                "typemissions.strategic",
            ],
            [
                "professionalexpertises.salesbusinessdevelopment",
                "professionalexpertises.marketinginnovation",
                "professionalexpertises.communication"
            ],
            [
                "workexperience.strategicmarketingplan",
                "workexperience.developmentroadmap",
                "workexperience.brandstrategy",
                "workexperience.management"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Chene Nordin", "Delphine", "delphine.chene@gt-executive.com",
            "delphinechene@hotmail.com",
            ['language.french'], "password",
            "Directrice Générale Groupe VLA  (Le Moci, La Vie du Rail)
            Directrice du Développement Groupe VLA
            Directrice Adjointe – Intelligence Marchés – Business France
            NOA pour Le Monde, The New York Times - Directrice Pays
            Membre du conseil d'Administration Syndicat National de la Press Professionnelle",

            [
                "businesspractice.services",
                "businesspractice.retail",
                "businesspractice.media"
            ],
            [
                "typemissions.communication",
                "typemissions.change",
                "typemissions.strategic",
                "typemissions.execution",
                "typemissions.organisationtransformation",
            ],
            [
                "professionalexpertises.salesbusinessdevelopment",
                "professionalexpertises.businessunitmanager",
                "professionalexpertises.operations"
            ],
            [
                "workexperience.organizatiotransformation",
                "workexperience.evenementialcommunication",
                "workexperience.e-commercestrategy",
                "workexperience.digitalstrategy",
                "workexperience.transitionplan",
                "workexperience.outsourcing",
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true]
            ]
        ], [
            "Citroen", "Philippe", "philippe.citroen@gt-executive.com",
            "phi.citroen@gmail.com",
            ['language.french'], "password",
            "Directeur Général de SONY France
            Vice-président Consumer Marketing SONY Europe (Londres)
            Président de SIMAVELEC, syndicat des constructeurs de matériels électroniques
            Membre du Club IVY, asso. de chef d'entreprise de l'univers high-tech",

            [
                "businesspractice.industry",
                "businesspractice.retail",
            ],
            [
                "typemissions.communication",
                "typemissions.change",
                "typemissions.strategic",
                "typemissions.execution",
                "typemissions.organisationtransformation",
                "typemissions.crm",
            ],
            [
                "professionalexpertises.salesbusinessdevelopment",
                "professionalexpertises.marketinginnovation",
                "professionalexpertises.communication",
                "professionalexpertises.operations"
            ],
            [
                "workexperience.strategicmarketingplan",
                "workexperience.customerknowledge",
                "workexperience.itstrategy",
                "workexperience.actionplan",
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Colin", "Paul", "paul.colin@gt-executive.com",
            "paul.colin@wanadoo.fr",
            ['language.french'], "password",
            "Président Fondateur agence Komando
            Directeur Général Saatchi&Saatchi Paris
            Directeur Général Leagas Delaney Paris
            Directeur Commercial DDB Paris
            ISG Alumni",

            [
                "businesspractice.retail"
            ],
            [
                "typemissions.communication",
                "typemissions.strategic",
                "typemissions.execution",
            ],
            [
                "professionalexpertises.marketinginnovation",
                "professionalexpertises.communication"
            ],
            [
                "workexperience.strategicmarketingplan",
                "workexperience.targetanalysis",
                "workexperience.brandstrategy"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Culleron", "Paul", "paul.culleron@fr.gt.com",
            "",
            ['language.french'], "password",
            "Directeur Financier Heuliez, Helice (SSII)
            DAF de transition – DI Finances
            Contrôleur de Gestion Valeo",

            [
                "businesspractice.industry",
                "businesspractice.finance",
                "businesspractice.retail",
                "businesspractice.it"
            ],
            [
                "typemissions.recruitment",
                "typemissions.organisationtransformation",
                "typemissions.audit",
                "typemissions.implementation",
                "typemissions.costkilling",
            ],
            [
                "professionalexpertises.financeaccounting",
                "professionalexpertises.operations",
                "professionalexpertises.it"
            ],
            [
                "workexperience.itpoliciesgovernance",
                "workexperience.restructuringplan",
                "workexperience.actionplan",
                "workexperience.packaging"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "de Genne", "Frederic", "frederic.degennes@gt-executive.com",
            "fdegennes@hotmail.fr",
            ['language.french'], "password",
            "DGA Omega TV - communication vidéo
            Consulting performance commerciale et alignement stratégique
            RRH - Monoprix",

            [
                "businesspractice.services",
                "businesspractice.retail"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.recruitment",
                "typemissions.execution",
                "typemissions.change"
            ],
            [
                "professionalexpertises.salesbusinessdevelopment",
                "professionalexpertises.humanresources",
                "professionalexpertises.communication",
                "professionalexpertises.distribution"
            ],
            [
                "workexperience.humanressourcescommunication",
                "workexperience.changemanagement",
                "workexperience.management"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "de Lambilly", "Patrick", "patrick.delambilly@gt-executive.com",
            "pdelambilly@gmail.com",
            ['language.french'], "password",
            "CEO Radical Beauty
            Président Coty Asie
            General Manager LVMH
            PWC Alumni",

            [
                "businesspractice.retail"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.strategic",
                "typemissions.execution"
            ],
            [
                "professionalexpertises.salesbusinessdevelopment",
                "professionalexpertises.administrativemanagement",
                "professionalexpertises.marketinginnovation",
                "professionalexpertises.communication",
                "professionalexpertises.distribution",
                "professionalexpertises.operations",
            ],
            [
                "workexperience.retaildigitalisation",
                "workexperience.targetanalysis"
            ],
            [
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "de Zelicourt", "Hubert", "hubert.dezelicourt@gt-executive.com",
            "hubert.de-zelicourt@orange.fr",
            ['language.french'], "password",
            "DRH, IBA Molecular
            Senior VP Projets RH Europe, Abbot Products
            Senior VP RH Opérations commerciales mondiales, Solvay Pharmaceuticals
            VP RH France, groupe Solvay Chemicals",

            [],
            [],
            [],
            [],
            []

        ], [
            "Delorme d'Armaille", "Carole", "carole.delorme-darmaille@gt-executive.com",
            "cdarmaille@ocbf.com",
            ['language.french'], "password",
            "Présidente Fondatrice Athys Finances
            Directeur de la Communication, Paris Europlace
            Vice-Président, Global Markets, JP Morgan Paris
            Responsable Dérivés Taux et change, BATIF Banque
            Reseaux KEDGE, Vox Femina, SFAF, IFA, DAR France
            Certification AMF en FRance et FCA au Royaume-Uni",

            [
                "businesspractice.finance",
                "businesspractice.public",
                "businesspractice.retail"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.communication",
                "typemissions.execution",
                "typemissions.strategic"
            ],
            [
                "professionalexpertises.salesbusinessdevelopment",
                "professionalexpertises.administrativemanagement",
                "professionalexpertises.financeaccounting"
            ],
            [
                "workexperience.cashandinvestmentmanagement",
                "workexperience.strategicmarketingplan",
                "workexperience.assetallocation",
                "workexperience.administration",
                "workexperience.management",
                "workexperience.actionplan"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, true, false, false, false, false, true, 12, 2000, false]
            ]
        ], [
            "Deveau", "Olivier", "olivier.deveaud@gt-executive.com",
            "odeveaud@hotmail.com",
            ['language.french'], "password",
            "Direction du développement et Key accounts (Fraikin, Rentokil)
            Direction opérationnelle (Rentokil, Elior)
            Dirigeant repreneur (PKD Group, Xavier Gourmet)
            HEC Campus (management strategis MSRE ex.IGIA",

            [
                "businesspractice.retail",
                "businesspractice.hotel"
            ],
            [
                "typemissions.businessreingeniering",
                "typemissions.execution",
                "typemissions.strategic",
                "typemissions.change"
            ],
            [
                "professionalexpertises.salesbusinessdevelopment",
                "professionalexpertises.marketinginnovation"
            ],
            [
                "workexperience.management",
                "workexperience.scope"
            ],
            [
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Dodin", "Arnaud", "arnaud.dodin@fr.gt.com",
            "",
            ['language.french'], "password",
            "DAF de Transition - DI Finances Grant Thornton (Daher Aérostructures, PCAS, Mikit, Afic, Cornilleau, Visiomed, Direct Energie…)
            Secrétaire Général de Transition - AFIC
            Directeur Contrôle de Gestion Groupe (Holcim Granulats, Lafarge Aluminates, Redland Granulats…)
            HEC Alumni
            Dauphine Alumni
            Reseaux Mazars, KPMG, AFIC",

            [
                "businesspractice.construction",
                "businesspractice.finance"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.audit",
                "typemissions.costkilling",
                "typemissions.execution",
                "typemissions.strategic",
                "typemissions.implementation"
            ],
            [
                "professionalexpertises.controlling",
            ],
            [
                "workexperience.cashandinvestmentmanagement",
                "workexperience.performancediagnosis",
                "workexperience.restructuringplan",
                "workexperience.cashoptimization"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Doiteau", "Jean-Rene", "jrd@niel-mcp.com",
            "",
            ['language.french'], "password",
            "PDG Fondateur associé du Groupe France - Manutention
            PDG Fondateur associé du Groupe France - Logistique Services
            PDG - Daher Logistics",

            [
                "businesspractice.industry",
                "businesspractice.services"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.businessreingeniering",
                "typemissions.strategic",
                "typemissions.execution",
                "typemissions.change"
            ],
            [
                "professionalexpertises.salesbusinessdevelopment",
                "professionalexpertises.businessunitmanager"
            ],
            [
                "workexperience.businessdevelopmentpriorities",
                "workexperience.organizatiotransformation",
                "workexperience.crisiscommunication",
                "workexperience.restructuringplan"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Duban", "Gregoire", "gregoire.duban@gt-executive.com",
            "g.duban@live.fr",
            ['language.french', 'language.english'], "password",
            "Management de transition, Alvarez & Marsal
            DAF Dekra Automotive SA, Diana Ingredients, Seviver Groupe Intek
            DG Délégué Groupe, Groupe Gravograph/Technifor
            Reseaux Harvard",

            [
                "businesspractice.finance",
                "businesspractice.energy",
                "businesspractice.it"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.businessreingeniering",
                "typemissions.costkilling",
                "typemissions.execution",
                "typemissions.strategic",
                "typemissions.change",
                "typemissions.audit"
            ],
            [
                "professionalexpertises.businessunitmanager",
                "professionalexpertises.financeaccounting",
                "professionalexpertises.communication"
            ],
            [
                "workexperience.organizatiotransformation",
                "workexperience.decisionmakingprocess",
                "workexperience.crisiscommunication",
                "workexperience.redesignfunction",
                "workexperience.changemanagement"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Dumas", "Laurent", "laurent.dumas@gt-executive.com",
            "dumaslaurent@orange.fr",
            ['language.french'], "password",
            "Directeur Financier : SNCF Geodis, Sony France
            Consultant : KPMG Peat Marwick Consultants",

            [
                "businesspractice.services",
                "businesspractice.it"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.costkilling",
                "typemissions.execution",
                "typemissions.strategic",
                "typemissions.migration",
                "typemissions.businessreingeniering",
                "typemissions.change"
            ],
            [
                "professionalexpertises.businessunitmanager",
                "professionalexpertises.operations"
            ],
            [
                "workexperience.organizatiotransformation",
                "workexperience.postacquisitionroadmap",
                "workexperience.restructuringplan",
                "workexperience.cashoptimization",
                "workexperience.costreduction",
                "workexperience.postmerger",
                "workexperience.scope"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Dupuy", "Emmanuel", "emmanuel.dupuy@gt-executive.com",
            "dupuy_eo@yahoo.fr",
            ['language.french'], "password",
            "Consultant Senior Strategy and Transformation CapGemini : BNPParibas, Natixis, LCL, HSBC, CDC, … : direction de projets, conception de systèmes ou d’architectures, due diligences, réorganisations, …
            DSI Apax Partners et conseil digital aux sociétés du portefeuille du fonds
            DSI Freshfields Paris
            ESSEC Alumni
            Reseaux CapGemini",

            [
                "businesspractice.finance",
            ],
            [
                "typemissions.infrastructure",
                "typemissions.crm",
                "typemissions.migration",
                "typemissions.implementation",
            ],
            [
                "professionalexpertises.salesbusinessdevelopment",
                "professionalexpertises.financeaccounting",
                "professionalexpertises.digital",
                "professionalexpertises.law",
                "professionalexpertises.it"
            ],
            [
                "workexperience.efficiencyimprovement",
                "workexperience.internalmobility",
                "workexperience.systemsecurity"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Etienne", "Francis", "francis.etienne@gt-executive.com",
            "francisetienne57@gmail.com",
            ['language.french'], "password",
            "Directeur des relations humaines Daher
            Directeur des ressources humaines Bormiolli Rocco
            Directeur des ressources humaines ATT
            ANDRH
            Cercle Humania",

            [
                "businesspractice.services",
                "businesspractice.media"
            ],
            [
                "typemissions.businessreingeniering",
                "typemissions.organisationtransformation",
                "typemissions.execution",
                "typemissions.recruitment",
                "typemissions.change",
            ],
            [
                "professionalexpertises.administrativemanagement",
                "professionalexpertises.humanresources"
            ],
            [
                "workexperience.acquisitionstrategyandexternalgrowthopportunities",
                "workexperience.humanressourcescommunication",
                "workexperience.postacquisitionroadmap",
                "workexperience.postmerger"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Faure", "Hubert", "hubert.faure@gt-executive.com",
            "faurehubert@free.fr",
            ['language.french', 'language.english'], "password",
            "AIRBUS, SVP Corporate Communication, VP Engineering Opérations
            AEROSPATIALE, Directeur Controlling, Organisation & SI,
            Directeur Général Finances
            Dauphine Alumni
            INSEAD Alumni
            Trésorier Toulous Ambition 21 think tank",

            [
                "businesspractice.industry",
                "businesspractice.services",
                "businesspractice.finance"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.change"
            ],
            [
                "professionalexpertises.businessunitmanager",
                "professionalexpertises.financeaccounting",
                "professionalexpertises.controlling"
            ],
            [
                "workexperience.chainvalueoptimization",
                "workexperience.redesignfunction"
            ],
            [
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Fenouil", "Alain", "alain.fenouil@gt-executive.com",
            "alainfenouil@yahoo.fr",
            ['language.french'], "password",
            "Vice Président Logistics & Opérations , Groupe ARROW
            Directeur Supply Chain et Facilities, GEMALTO
            PDG, GEOLOGISTICS
            HEC Alumni",

            [
                "businesspractice.services",
                "businesspractice.industry"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.change",
                "typemissions.crm",
                "typemissions.execution"
            ],
            [
                "professionalexpertises.salesbusinessdevelopment"
            ],
            [
                "workexperience.salesgotomarketstrategy",
                "workexperience.performancediagnosis",
                "workexperience.changemanagement",
                "workexperience.talentmanagement"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Gannevast", "Arnaud", "arnaud.gannevast@gt-executive.com",
            "gannevast.arnaud@9online.fr",
            ['language.french'], "password",
            "Associé, Red2Green, DI Finances Grant Thornton
            DG Finances Nexus Technologies
            DAF Veolia Water, AMOR, SICOBAIL
            Auditeur Interne et Contrôleur de Gestion L'OREAL
            IEP Paris Alumni, AFIC",

            [
                "businesspractice.finance",
                "businesspractice.retail",
                "businesspractice.energy"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.execution",
                "typemissions.audit",
                "typemissions.costkilling"
            ],
            [
                "professionalexpertises.salesbusinessdevelopment",
                "professionalexpertises.financeaccounting"
            ],
            [
                "workexperience.businessdevelopmentpriorities",
                "workexperience.performancediagnosis",
                "workexperience.diagnosisfunction",
                "workexperience.restructuringplan"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Gautier", "Emmanuelle", "emmanuelle.gautier@gt-executive.com",
            "gautier.emmanuelle@gmail.com",
            ['language.french', 'language.english'], "password",
            "Directeur Business Unit Automobile monde, Acome
            Directeur R&D/ Innovation, Valeo, Yazaki,
            VP – Directeur Général filiale, Safran, Valeo",

            [
                "businesspractice.industry"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.businessreingeniering",
                "typemissions.execution",
                "typemissions.change"
            ],
            [
                "professionalexpertises.salesbusinessdevelopment",
                "professionalexpertises.marketinginnovation",
                "professionalexpertises.researchdevelopment",
                "professionalexpertises.operations",
            ],
            [
                "workexperience.organizatiotransformation",
                "workexperience.crisiscommunication",
                "workexperience.management"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Giraud", "Emmanuel", "emmanuel.giraud@gt-executive.com",
            "emgiraud92@gmail.com",
            ['language.french'], "password",
            "Directeur marketing et communication, Photomaton
            Dirigeant Repreneur, ATP (objets publicitaires)
            Responsable marketing Fujifilm",

            [
                "businesspractice.retail",
                "businesspractice.hotel",
            ],
            [
                "typemissions.communication",
                "typemissions.execution"
            ],
            [
                "professionalexpertises.marketinginnovation",
                "professionalexpertises.communication",
                "professionalexpertises.distribution",
            ],
            [
                "workexperience.salesgotomarketstrategy",
                "workexperience.strategicduediligences",
                "workexperience.strategicmarketingplan",
                "workexperience.automatisation"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Gregoire", "Jean-Louis", "jean-louis.gregoire@gt-executive.com",
            "jl.gregoire@orange.fr",
            ['language.french', 'language.english'], "password",
            "VP EMEA and Chief of Corporate Strategy, Canon Europe
            PDG (bureautique) Canon France et Gestetner
            30 ans dans groupes internationaux (dont 10 ans à l'étranger)
            Directeur General G20 des Entrepreneurs",

            [
                "businesspractice.services",
                "businesspractice.retail"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.execution",
                "typemissions.strategic"
            ],
            [
                "professionalexpertises.businessunitmanager",
            ],
            [
                "workexperience.acquisitionstrategyandexternalgrowthopportunities",
                "workexperience.restructuringplan",
                "workexperience.management"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Guillemin", "Jean-Roch", "jean-roch.guillemin@gt-executive.com",
            "jr.guillemin@bbox.fr",
            ['language.french'], "password",
            "Gestion des Ressources Humaines
            Accompagnement du Changement
            Formation & Développement
            Relations sociales",

            [
                "businesspractice.industry"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.mediation",
                "typemissions.coaching"
            ],
            [
                "professionalexpertises.humanresources"
            ],
            [
                "workexperience.strategyorganization",
                "workexperience.talentmanagement"
            ],
            [
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Hua", "Olivier", "olivier.hua@gt-executive.com",
            "ohua@orange.fr",
            ['language.french', 'language.english'], "password",
            "Dirigeant de fonds d’investissement (Oddo PE, Turenne Capital)
            DG de PME industrielles (Dürr, Sietam, GMM)
            Banquier commercial et d’affaires (Citicorp, Crédit Lyonnais)
            Reims Management School
            INSEAD Alumni
            AFIC
            Association Innocherche
            Reseau Entreprendre Paris",

            [
                "businesspractice.finance"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.coaching",
                "typemissions.strategic",
                "typemissions.execution",
            ],
            [
                "professionalexpertises.businessunitmanager",
                "professionalexpertises.financeaccounting",
                "professionalexpertises.assetmanagement",
                "professionalexpertises.operations"
            ],
            [
                "workexperience.acquisitionstrategyandexternalgrowthopportunities",
                "workexperience.investmentstrategy"
            ],
            [
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Jordan", "Isabelle", "isabelle.jordan@gt-executive.com",
            "ijg@free.fr",
            ['language.french'], "password",
            "Marketing chez l’OREAL et DANONE
            Communication internationale ( Louis Vuitton, Lancôme, Chanel)
            Lancement de la marque MBT à Paris ( 2009)
            HEC Alumni",

            [
                "businesspractice.retail"
            ],
            [
                "typemissions.communication",
                "typemissions.strategic"
            ],
            [
                "professionalexpertises.salesbusinessdevelopment",
                "professionalexpertises.businessunitmanager",
                "professionalexpertises.marketinginnovation",
                "professionalexpertises.communication"
            ],
            [
                "workexperience.strategicmarketingplan"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Juteau", "Jean-Pierre", "jean-pierre.juteau@gt-executive.com",
            "juteau.jean-pierre@orange.fr", ['language.french', 'language.english'], "password",
            "SNCF Geodis, Directeur des Opérations, Directeur International.
             Compass Group France, Directeur de l’Intégration
             Otis, Directeur Organisation et SI
             Ecole Polytechnique
             Ensead",

            [
                "businesspractice.services"
            ],
            [
                "typemissions.implementation",
                "typemissions.strategic",
                "typemissions.change"
            ],
            [
                "professionalexpertises.businessunitmanager",
                "professionalexpertises.supplychain",
                "professionalexpertises.operations"
            ],
            [
                "workexperience.acquisitionstrategyandexternalgrowthopportunities",
                "workexperience.transfertoperationalmanagement",
                "workexperience.postacquisitionroadmap",
                "workexperience.itpoliciesgovernance",
                "workexperience.cessionsstrategy",
                "workexperience.management"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Kaiser", "Andre", "andre.kaiser@gt-executive.com",
            "ankaiser@hotmail.com",
            ['language.french', 'language.german'], "password",
            "Manager de transition, Steiner, Qualis-Akerys, FVS, Eurazeo
            CFO/CRO Groupee Altimate
            DAF Groupe Faconnable
            IEP Paris Alumni
            Enseignant EDHEC
            Resaux Daubigny",

            [
                "businesspractice.finance",
                "businesspractice.retail"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.strategic",
                "typemissions.change"
            ],
            [
                "professionalexpertises.financeaccounting",
                "professionalexpertises.production"
            ],
            [
                "workexperience.transfertoperationalmanagement",
                "workexperience.complexityreduction",
                "workexperience.restructuringplan",
                "workexperience.cessionsstrategy",
                "workexperience.transitionplan"
            ],
            [
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Lafargue", "Yves", "yves.lafargue@gt-executive.com",
            "yves.lafargue@cofinter.com", ['language.french'], "password",
            "Ingénieur recherche, CEA
            Directeur Central, Groupe Promodis
            Directeur Général, FDS groupe Bolloré",

            [
                "businesspractice.services",
                "businesspractice.finance"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.strategic",
                "typemissions.execution",
                "typemissions.coaching",
            ],
            [
                "professionalexpertises.businessunitmanager",
            ],
            [
                "workexperience.projectmanagementassistance",
                "workexperience.management",
                "workexperience.scope"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Larzilliere", "Franck", "franck.larzilliere@gt-executive.com",
            "franck.larzilliere@sfr.fr",
            ['language.french'], "password",
            "Groupe SEB: Vice-president Retailing Networks
            LA REDOUTE: Directeur Général de la Vente Directe
            RODIER: Directeur Général
            DESCAMPS: Directeur Commercial International
            HISTOIRE D’OR: Directeur Marketing & Achats
            ESCP Europe
            IMD Lausanne Alumni",

            [
                "businesspractice.services",
                "businesspractice.retail"
            ],
            [
                "typemissions.communication",
                "typemissions.execution",
                "typemissions.strategic",
                "typemissions.coaching",
                "typemissions.change"
            ],
            [
                "professionalexpertises.salesbusinessdevelopment",
                "professionalexpertises.administrativemanagement",
                "professionalexpertises.businessunitmanager",
                "professionalexpertises.digital"
            ],
            [
                "workexperience.businessdevelopmentpriorities",
                "workexperience.redesignfunction",
                "workexperience.management",
                "workexperience.postmerger"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "le Chevalier", "Patrick", "patrick.lechevalier@gt-executive.com",
            "patrick.le.chevalier@wanadoo.fr",
            ['language.french'], "password",
            "Conseil en Gestion de Patrimoine
            Accompagnement dirigeant propriétaire petite et moyenne entreprise
            Administratuer et membre du Bureau Federation Reseau Entreprendre",

            [
                "businesspractice.services",
                "businesspractice.finance"
            ],
            [
                "typemissions.strategic",
                "typemissions.execution",
                "typemissions.audit"
            ],
            [
                "professionalexpertises.administrativemanagement",
                "professionalexpertises.assetmanagement"
            ],
            [
                "workexperience.transfertoperationalmanagement",
                "workexperience.cessionsstrategy",
                "workexperience.successionplan"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "le Gouvello", "Eric", "eric.legouvello@gt-executive.com",
            "legouvello@edaxis.com",
            ['language.french'], "password",
            "Gérant, Edaxis Sarl
            Président de transition, Financière GOA
            PDG, Girard-Sudron",

            [
                "businesspractice.industry",
                "businesspractice.finance",
                "businesspractice.energy"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.execution",
                "typemissions.strategic",
                "typemissions.coaching",
                "typemissions.change",
                "typemissions.audit"
            ],
            [
                "professionalexpertises.administrativemanagement",
                "professionalexpertises.humanresources",
                "professionalexpertises.operations"
            ],
            [
                "workexperience.postacquisitionroadmap",
                "workexperience.changemanagement",
                "workexperience.transitionplan",
                "workexperience.postmerger"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Lecca", "Stephane", "stephane.lecca@gt-executive.com",
            "stephane.lecca@yahoo.fr",
            ['language.french'], "password",
            "DG de Publicis Events – Filiale Evénementielle du Groupe Publicis
            DG associé et créateur de Byzance Communication, conseil en Evénement
            DG associé et créateur d’OMV, agence conseil en Street Marketing
            President de la delegation Communication Evenementielle de L'AACC (Association des Agences Conseil en Communication",

            [
                "businesspractice.media",
                "businesspractice.hotel"
            ],
            [
                "typemissions.communication",
                "typemissions.execution",
                "typemissions.strategic"
            ],
            [
                "professionalexpertises.administrativemanagement",
                "professionalexpertises.businessunitmanager"
            ],
            [
                "workexperience.administration",
                "workexperience.management"
            ],
            [
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Lemaire", "Pierre", "pierre.lemaire@gt-executive.com",
            "p.lemaireroquette@gmail.com",
            ['language.french'], "password",
            "DGD de Holding et Directeur Financier, Juridique et Informatique
            Directeur Unité Opérationnelle \"Pharmacie Cosmétologie\"
            Responsable Gestion du BFR
            IEP Paris
            IHEDN Alumni
            IMD Alumni
            Reseau Fondation Hopale",

            [
                "businesspractice.industry",
                "businesspractice.services"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.strategic",
                "typemissions.execution",
                "typemissions.mediation",
                "typemissions.change"
            ],
            [
                "professionalexpertises.businessunitmanager",
                "professionalexpertises.financeaccounting",
            ],
            [
                "workexperience.acquisitionstrategyandexternalgrowthopportunities",
                "workexperience.strategicmarketingplan",
                "workexperience.cessionsstrategy",
                "workexperience.postmerger",
                "workexperience.management"
            ],
            [
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Levy", "Laurent", "laurent.levy@gt-executive.com",
            "laurentxlevy@hotmail.fr",
            ['language.french'], "password",
            "Secrétaire Général en charge des M&A, Photo-Me International plc
            Directeur Financier Groupe (participation Eurazeo)
            Directeur Financier Groupe, Sol's Europe
            Directeur Financier Europe, Publicis Groupe
            Arthur Andersen Alumni",

            [
                "businesspractice.industry",
                "businesspractice.retail",
                "businesspractice.media"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.communication",
                "typemissions.strategic",
                "typemissions.execution",
                "typemissions.change",
                "typemissions.audit"
            ],
            [
                "professionalexpertises.administrativemanagement",
                "professionalexpertises.financeaccounting",
                "professionalexpertises.communication"
            ],
            [
                "workexperience.acquisitionstrategyandexternalgrowthopportunities",
                "workexperience.strategicmarketingplan",
                "workexperience.postacquisitionroadmap",
                "workexperience.postmerger"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Lointier", "Claude", "claude.lointier@gt-executive.com",
            "cllointier@gmail.com",
            ['language.french'], "password",
            "Directeur Financier, Daher Aerospace
            Directeur du Contrôle de Gestion Groupe, Daher
            Management de transition, DI Finances",

            [
                "businesspractice.industry",
                "businesspractice.finance"
            ],
            [
                "typemissions.execution",
                "typemissions.organisationtransformation",
                "typemissions.audit"
            ],
            [
                "professionalexpertises.salesbusinessdevelopment",
                "professionalexpertises.financeaccounting",
                "professionalexpertises.assetmanagement",
                "professionalexpertises.controlling",
                "professionalexpertises.humanresources"
            ],
            [
                "workexperience.acquisitionstrategyandexternalgrowthopportunities",
                "workexperience.cashandinvestmentmanagement",
                "workexperience.postacquisitionroadmap",
                "workexperience.reengineeringprocess",
                "workexperience.strategyorganization",
                "workexperience.performance",
                "workexperience.postmerger"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Lopez", "Jacques", "jacques.lopez@gt-executive.com",
            "jaclopez@gmail.com",
            ['language.french'], "password",
            "Manager de Transition, groupe H3O et Groupe L'Occitane
            DAF puis Directeur Général, AXIOHM
            DAF Dynatech
            Reseau DFCG",

            [
                "businesspractice.retail",
                "businesspractice.media"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.strategic",
                "typemissions.execution",
                "typemissions.audit",
                "typemissions.change"
            ],
            [
                "professionalexpertises.financeaccounting",
            ],
            [
                "workexperience.crisiscommunication",
                "workexperience.restructuringplan"
            ],
            [
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Mamet", "Alexandre", "alexandre.mamet@gt-executive.com",
            "alexandre_mamet@yahoo.com",
            ['language.french'], "password",
            "Président Directeur Général - Holding Financière Goa
            Directeur Général - Groupe La Brosse et Dupont (LVMH)
            Vice-Président - Chocolat Weiss
            Asie du Sud Est et pays emergents",

            [
                "businesspractice.retail",
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.businessreingeniering",
                "typemissions.outsourcing",
                "typemissions.communication",
            ],
            [
                "professionalexpertises.salesbusinessdevelopment",
                "professionalexpertises.administrativemanagement",
                "professionalexpertises.marketinginnovation"
            ],
            [
                "workexperience.businessdevelopmentpriorities",
                "workexperience.strategicmarketingplan",
                "workexperience.restructuringplan"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Marques", "Thierry", "thierry.marques@gt-executive.com",
            "thierry.marques@ponts.org", ['language.french', 'language.english'], "password",
            "Directeur Général, Swiss Life Immobilier
            Directeur Général en charge de l'international, Foncia
            Directeur Général du réseau de salles, Pathé
            Directeur Général, Cni
            Poont Alumni
            RICS (Royal Institution of Chartered Surveyors
            Les anciens d'Arthur (ex Arthur Andesen)",

            [
                "businesspractice.realestate",
                "businesspractice.services",
                "businesspractice.finance",
                "businesspractice.retail"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.execution",
                "typemissions.strategic"
            ],
            [
                "professionalexpertises.salesbusinessdevelopment",
                "professionalexpertises.businessunitmanager",
                "professionalexpertises.marketinginnovation",
                "professionalexpertises.assetmanagement",
                "professionalexpertises.purchasing"
            ],
            [
                "workexperience.acquisitionstrategyandexternalgrowthopportunities",
                "workexperience.postacquisitionroadmap",
                "workexperience.restructuringplan",
                "workexperience.assetallocation",
                "workexperience.postmerger"
            ],
            [
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Mellerio", "Olivier", "olivier.mellerio@gt-executive.com",
            "mellerio.olivier@wanadoo.fr",
            ['language.french', 'language.english'], "password",
            "Directeur Général
            Chef d'entreprise
            Elu a la CCIR (Chambre de Commerce Refional Paris IDF)
            HEC Columbia Alumni
            Comite Colbert",

            [
                "businesspractice.industry"
            ],
            [
                "typemissions.execution",
                "typemissions.strategic"
            ],
            [
                "professionalexpertises.administrativemanagement",
                "professionalexpertises.humanresources"
            ],
            [
                "workexperience.acquisitionstrategyandexternalgrowthopportunities",
                "workexperience.postacquisitionroadmap",
                "workexperience.postmerger",
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Misteli", "Philippe", "philippe.misteli@gt-executive.com",
            "philippe.misteli@gmail.com", ['language.french'], "password",
            "DAF AkzoNobel Trade France
            DGA, Kaufman&Broard, EuroDisney
            DAF Unilever ( Paris, Amsterdam, NYC et Londres",

            [
                "businesspractice.construction",
                "businesspractice.services"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.costkilling",
                "typemissions.execution",
                "typemissions.change",
                "typemissions.audit"
            ],
            [
                "professionalexpertises.administrativemanagement",
                "professionalexpertises.controlling"
            ],
            [
                "workexperience.acquisitionstrategyandexternalgrowthopportunities",
                "workexperience.postacquisitionroadmap",
                "workexperience.transitionplan",
                "workexperience.postmerger"
            ],
            [

                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Nouveau", "Jacques-Edouard", "jacques-edouard.nouveau@gt-executive.com",
            "jacques-edouard.nouveau@fr.gt.com",
            ['language.french', 'language.english'], "password",
            "Directeur Général, Associé Grant Thornton
            DAF,  Groupe Daher
            Directeur Financier, Géodis / Bolloré
            HEC alumni
            Reseaux Mazars/PWC, AFIC, FNMT",

            [
                "businesspractice.services",
                "businesspractice.industry",
                "businesspractice.finance",
                "businesspractice.media"
            ],
            [
                "typemissions.knowledgemanagement",
                "typemissions.strategic",
                "typemissions.execution",
            ],
            [
                "professionalexpertises.salesbusinessdevelopment",
                "professionalexpertises.businessunitmanager",
                "professionalexpertises.financeaccounting",
                "professionalexpertises.riskmanagement",
                "professionalexpertises.operations"
            ],
            [
                "workexperience.acquisitionstrategyandexternalgrowthopportunities",
                "workexperience.transfertoperationalmanagement",
                "workexperience.customerrelationshipmanagement",
                "workexperience.businessdevelopmentpriorities",
                "workexperience.postacquisitionroadmap",
                "workexperience.restructuringplan",
                "workexperience.cessionsstrategy",
                "workexperience.iriskmanagement",
                "workexperience.postmerger"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]

            ]

        ], [
            "Pankwoski", "Eric-Jean", "eric-jean.pankowski@gt-executive.com",
            "ejpankowski@steering3.fr",
            ['language.french'], "password",
            "VP Finances & organisation groupe LEOSPHERE
            Directeur associé CSC Peat Marwick
            Co-Président groupe services (Montréal)
            Adjoint VP Finances Dassault Système
            Ingenieur HEI, ESSEC, MBA, IHEDN, ENM",

            [
                "businesspractice.industry",
                "businesspractice.services",
                "businesspractice.finance"
            ],
            [
                "typemissions.outsourcing",
                "typemissions.strategic",
                "typemissions.execution"
            ],
            [
                "professionalexpertises.financeaccounting",
            ],
            [
                "workexperience.privatefinanceinitiative"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Petrou", "Philippe", "philippe.petrou@gt-executive.com",
            "ppetrou@mytikas.fr",
            ['language.french', 'language.english'], "password",
            "Country Manager Groupe ALPHA BANK
            Membre du Directoire de Neuflize Schlumberger Mallet Demachy
            Directeur à la Direction Générale d'OBC - Odier Bungener Courvoisier
            HEC Alumni",

            [
                "businesspractice.finance",
                "businesspractice.retail"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.strategic",
                "typemissions.execution"
            ],
            [
                "professionalexpertises.salesbusinessdevelopment"
            ],
            [
                "workexperience.businessdevelopmentpriorities",
                "workexperience.restructuringplan"
            ],
            [
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Pinot", "Eric", "eric.pinot@gt-executive.com",
            "ericpinot@orange.fr",
            ['language.french'], "password",
            "Président du Directoire, SIA International
            PDG, Textiles Well S.A.
            Vice Président Strategic Development, SLBA-E
            ESSEC Alumni",

            [
                "businesspractice.industry",
                "businesspractice.retail"
            ],
            [
                "typemissions.execution",
                "typemissions.crm"
            ],
            [
                "professionalexpertises.marketinginnovation",
                "professionalexpertises.researchdevelopment"
            ],
            [
                "workexperience.fundmanagementstrategy",
                "workexperience.strategicmarketingplan",
                "workexperience.restructuringplan"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Puchois", "Bernard", "bernard.puchois@gt-executive.com",
            "bpuchois@wanadoo.fr",
            ['language.french', 'language.english'], "password",
            "DAF, DGA Finances (Comex) : Afone, Zucchi, Jalla
            DG, DGA : O2I, Descamps group
            Direction organisation et Contrôle de gestion : F.A,VEV
            Pilotage de grands projets
            Reseaux IHFI, INSEAD, ICAM",

            [
                "businesspractice.retail",
                "businesspractice.media",
                "businesspractice.it",
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.execution",
                "typemissions.strategic"
            ],
            [
                "professionalexpertises.salesbusinessdevelopment",
                "professionalexpertises.businessunitmanager",
                "professionalexpertises.financeaccounting",
                "professionalexpertises.supplychain",
                "professionalexpertises.controlling",
                "professionalexpertises.operations",
                "professionalexpertises.it"
            ],
            [
                "workexperience.businessdevelopmentpriorities",
                "workexperience.buildinganalyticalmodel",
                "workexperience.rationalisation",
                "workexperience.management"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Reisz", "Frederic", "frederic.reisz@gt-executive.com",
            "frederic.reisz@incomeo.com", ['language.french'], "password",
            "Directeur Général ENERGIA-DGEM
            Cabinet du Président et du Directeur Général SNCF
            Directeur des participations COMELEC
            Reseaux ESSEC UTC",

            [
                "businesspractice.services",
                "businesspractice.industry"
            ],
            [
                "typemissions.strategic",
                "typemissions.execution",
                "typemissions.organisationtransformation",
                "typemissions.change"
            ],
            [
                "professionalexpertises.controlling",
                "professionalexpertises.operations",
            ],
            [
                "workexperience.acquisitionstrategyandexternalgrowthopportunities",
                "workexperience.transfertoperationalmanagement",
                "workexperience.humanressourcescommunication",
                "workexperience.operationalprocessredesign",
                "workexperience.postacquisitionroadmap",
                "workexperience.successionplan",
                "workexperience.socialclimate",
                "workexperience.postmerger"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Renondin", "Guillaume", "guillaume.renondin@gt-executive.com",
            "g.renondin@yahoo.fr",
            ['language.french'], "password",
            "Clinvest
            Daher
            Sami
            HEC, ISG
            Business Angel",

            [
                "businesspractice.industry",
                "businesspractice.finance",
                "businesspractice.it"
            ],
            [
                "typemissions.outsourcing",
                "typemissions.execution",
                "typemissions.strategic"
            ],
            [
                "professionalexpertises.businessunitmanager",
                "professionalexpertises.financeaccounting",
                "professionalexpertises.operations"
            ],
            [
                "workexperience.privatefinanceinitiative",
                "workexperience.investmentstrategy"
            ],
            [
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Rodriguez-Vigil", "Alicia", "alicia.rodriguez-vigil@gt-executive.com",
            "arvigilg@gmail.com",
            ['language.french'], "password",
            "Directeur Expert Contrôle de Gestion – GTE (Eutelsat, Lafarge)
            Partner OnetoOne (Parclick.com)
            Manager de transition – Agile Finance (Plastic Omnium, Visteon, Vallourec, FCI..)
            Resp. Contrôle de Gestion – DAF BU (ArcelorMittal Lux., France, Esp.)
            Louvain Mnagement School, ICADE Madrid, EPWN",

            [
                "businesspractice.industry",
                "businesspractice.finance"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.execution",
                "typemissions.change",
                "typemissions.audit"
            ],
            [
                "professionalexpertises.salesbusinessdevelopment",
                "professionalexpertises.businessunitmanager",
                "professionalexpertises.financeaccounting",
                "professionalexpertises.controlling",
                "professionalexpertises.purchasing",
            ],
            [
                "workexperience.acquisitionstrategyandexternalgrowthopportunities",
                "workexperience.transfertoperationalmanagement",
                "workexperience.operationalprocessredesign",
                "workexperience.postacquisitionroadmap",
                "workexperience.cessionsstrategy",
                "workexperience.cashoptimization",
                "workexperience.postmerger",
                "workexperience.management",
                "workexperience.audit"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Rongiere", "Philippe", "philippe.rongieres@fr.gt.com",
            "",
            ['language.french'], "password",
            "DAF de transition DI Finances – Grant Thornton
            DAF France  - Sab Wabco et Healthco
            Manager Deloitte & Touche",

            [
                "businesspractice.industry",
                "businesspractice.media"
            ],
            [
                "typemissions.implementation",
                "typemissions.execution",
                "typemissions.audit",
            ],
            [
                "professionalexpertises.salesbusinessdevelopment",
                "professionalexpertises.financeaccounting",
                "professionalexpertises.controlling",
                "professionalexpertises.operations"
            ],
            [
                "workexperience.acquisitionstrategyandexternalgrowthopportunities",
                "workexperience.transfertoperationalmanagement",
                "workexperience.postacquisitionroadmap",
                "workexperience.crisiscommunication",
                "workexperience.cessionsstrategy",
                "workexperience.postmerger",
                "workexperience.budgetplan"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, true],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Schricke", "Herve", "herve.schricke@gt-executive.com",
            "herve.schricke@gmail.com ",
            ['language.french'], "password",
            "Fondateur XAnge Private Equity/ capital innovation et capital Developpement
            Co-fondateur Meilleurtaux.com (courtier CREDIT immobilier)
            Directeur Général Natexis Private Equity
            Directeur de l’exploitation Crédit National/NATIXIS
            Directeur BIAO (banque en Afrique)
            Capital investissement Europe Afrique (ancien President Afic)",

            [
                "businesspractice.finance"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.strategic",
                "typemissions.execution",
            ],
            [
                "professionalexpertises.salesbusinessdevelopment",
                "professionalexpertises.financeaccounting",
                "professionalexpertises.assetmanagement",
                "professionalexpertises.operations"
            ],
            [
                "workexperience.cashandinvestmentmanagement",
                "workexperience.privatefinanceinitiative",
                "workexperience.assetallocation",
                "workexperience.outsourcing"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Simon", "Bernard", "bernard.simon@gt-executive.com",
            "bh.simon@free.fr",
            ['language.french'], "password",
            "DAF de transition, DI Finances-Grant Thornton
            Responsable Diversification, Giat Industries
            Directeur Financier, Groupe Verney
            ESSEC, Ecole des Ponts Alumni",

            [
                "businesspractice.services",
                "businesspractice.industry",
                "businesspractice.energy",
                "businesspractice.media",
                "businesspractice.it"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.execution",
                "typemissions.change"
            ],
            [
                "professionalexpertises.financeaccounting",
                "professionalexpertises.humanresources",
                "professionalexpertises.communication",
                "professionalexpertises.purchasing",
                "professionalexpertises.operations",
                "professionalexpertises.digital"
            ],
            [
                "workexperience.organizatiotransformation",
                "workexperience.decisionmakingprocess",
                "workexperience.crisiscommunication",
                "workexperience.redesignfunction",
                "workexperience.management"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ],
        [
            "Stroe", "Madalina", "madalina.stroe@gt-executive.com",
            "madalina_stroe@yahoo.com",
            ['language.french'], "password",
            "Entrepreneur & CEO Eliad Technologies, Silicon Valley, brevets US, solutions mobilité Nokia, Vodafone, Telefonica, Otis, Dalkia, Veolia, Kiabi
            Manager de transition  JP Morgan, Deutsche Bank Londres
            Responsable progiciels de gestion Concept SA France
            Entrepreneurs high-tech",

            [
                "businesspractice.finance",
                "businesspractice.retail",
                "businesspractice.media"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.infrastructure",
                "typemissions.implementation",
                "typemissions.migration",
                "typemissions.change"
            ],
            [
                "professionalexpertises.researchdevelopment",
                "professionalexpertises.marketinginnovation",
                "professionalexpertises.supplychain",
                "professionalexpertises.digital",
                "professionalexpertises.it"
            ],
            [
                "workexperience.transformation"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ],
        [
            "Taieb", "Florence", "florence.taieb@gt-executive.com",
            "taieb.florence@wanadoo.fr",
            ['language.french'], "password",
            "Directrice conseil et création d’un pôle corporate en agence
            Directrice de la communication et du marketing de Paris Première
            Chargée de mission chez McDonald’s",

            [
                "businesspractice.media"
            ],
            [
                "typemissions.strategic",
                "typemissions.execution",
                "typemissions.organisationtransformation",
                "typemissions.communication",
            ],
            [
                "professionalexpertises.marketinginnovation",
                "professionalexpertises.communication",
                "professionalexpertises.operations"
            ],
            [
                "workexperience.salesgotomarketstrategy",
                "workexperience.strategicmarketingplan",
                "workexperience.management"
            ],
            [
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Terrasson", "Vincent", "vincent.terrasson@gt-executive.com",
            "vincent.terrasson@m4x.org",
            ['language.french'], "password",
            "Apave - Directeur du Développement International
            Egis International - Directeur Général Délégué
            Egis Road Operation - Directeur Général
            Dalkia Hongrie - Directeur Général",

            [
                "businesspractice.industry"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.costkilling",
                "typemissions.audit",
                "typemissions.strategic",
                "typemissions.execution",
                "typemissions.change"
            ],
            [
                "professionalexpertises.administrativemanagement",
                "professionalexpertises.businessunitmanager",
                "professionalexpertises.operations"
            ],
            [
                "workexperience.masterplan",
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Thoumyre", "Etienne", "etienne.thoumyre@gt-executive.com",
            "etienne.thoumyre@orange.fr",
            ['language.french', 'language.english'], "password",
            "DAF de transition DI Finances – Grant Thornton
            CFO France - BAXI GROUPE Ltd
            Group Management Controller - PLASTIC OMNIUM
            Ecole Polytechnique Zurich Alumni",

            [
                "businesspractice.industry"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.execution",
                "typemissions.costkilling",
                "typemissions.audit"
            ],
            [
                "professionalexpertises.businessunitmanager",
                "professionalexpertises.humanresources",
                "professionalexpertises.controlling",
                "professionalexpertises.operations"
            ],
            [
                "workexperience.cashandinvestmentmanagement",
                "workexperience.decisionmakingprocess",
                "workexperience.cashoptimization",
                "workexperience.management"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Torres", "Daniel", "daniel.torres@gt-executive.com",
            "daniel.torres@dixhuit-agence.fr",
            ['language.french'], "password",
            "Création agence conseil Dix-Huit/Lyan
            Création groupe 15-30 : création, production, stratégie média (radio et films pub)
            Conseil depuis 2000 du president CGPME Rhone Alpes",

            [
                "businesspractice.retail"
            ],
            [
                "typemissions.execution"
            ],
            [
                "professionalexpertises.salesbusinessdevelopment",
                "professionalexpertises.marketinginnovation",
                "professionalexpertises.businessunitmanager",
                "professionalexpertises.communication",
                "professionalexpertises.operations"
            ],
            [
                "workexperience.implementcommunicationplan",
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Trolliet", "Marie-Laure", "marie-laure.trolliet@gt-executive.com",
            "marielaure.trolliet@yahoo.fr",
            ['language.french'], "password",
            "DAF et Directeur des Risques – ICARE Groupe Europ Assistance, Cardif
            Directeur Financier Régional AXA Travel, Groupe Axa Assistance
            Directeur de l'Audit Interne (monde entier) Groupe Axa Assistance
            ESSEC",

            [
                "businesspractice.services",
                "businesspractice.finance"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.execution",
                "typemissions.audit",
                "typemissions.costkilling"
            ],
            [
                "professionalexpertises.businessunitmanager",
                "professionalexpertises.financeaccounting",
                "professionalexpertises.operations"
            ],
            [
                "workexperience.iriskmanagement"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Velot", "Jacques", "jacques.velot@gt-executive.com",
            "velot@icloud.com",
            ['language.french'], "password",
            "Directeur Général Xerox Business Solutions France
            Directeur Général Asie Pacifique IER (Groupe Bolloré)
            Directeur Financier Etats-Unis IER (Groupe Bolloré)
            Xerox Alumni",

            [
                "businesspractice.services",
                "businesspractice.public",
                "businesspractice.it"
            ],
            [
                "typemissions.strategic",
                "typemissions.execution",
                "typemissions.organisationtransformation",
                "typemissions.change"
            ],
            [
                "professionalexpertises.administrativemanagement",
                "professionalexpertises.businessunitmanager",
                "professionalexpertises.marketinginnovation",
                "professionalexpertises.it"
            ],
            [
                "workexperience.implementationofnewtechnologies",
                "workexperience.outsourcing"
            ],
            [
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ], [
            "Wams", "Nanno", "nanno.wams@gt-executive.com",
            "nanno.wams@gmail.com",
            ['language.french', 'language.german', 'language.english'], "password",
            "Directeur Financier et IT de Pays/Région : - DS Smith France –Espagne
                  - Lafarge Allemagne, Benelux, Pologne, Corée du Sud
            CFO Division Mondiale : Sodexo Division Bases Vie & Asie-Australie
            CFO Corporate -Euronext C- : ESI Group
            Reseaux : HEC, Daubigny",

            [
                "businesspractice.industry",
                "businesspractice.services",
                "businesspractice.it"
            ],
            [
                "typemissions.organisationtransformation",
                "typemissions.strategic",
                "typemissions.execution",
                "typemissions.change"
            ],
            [
                "professionalexpertises.salesbusinessdevelopment",
                "professionalexpertises.businessunitmanager",
                "professionalexpertises.financeaccounting",
                "professionalexpertises.operations"
            ],
            [
                "workexperience.conceptionofbudgetprocessandimplementationofbudgettools",
                "workexperience.businessdevelopmentpriorities",
                "workexperience.organizatiotransformation",
                "workexperience.decisionmakingprocess",
                "workexperience.performancediagnosis",
                "workexperience.diagnosisfunction",
                "workexperience.transformation",
                "workexperience.management"
            ],
            [
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, true, false, false, false, false, true, 12, 2000, false],
                [false, false, true, false, false, false, true, 24, 2000, false]
            ]
        ],
//        [
//            "FName", "LName", "Email",
//            "RescueEmail",
//            [ 'language.french' ], "password",
//            "UserResume",
//
//            [
//                ("businesspractice.")+
//            ],
//            [
//                ("typemissions.")+
//            ],
//            [
//                ("professionalexpertises.)+
//            ],
//            [
//                ("workexperience.")+
//            ],
//            [
//                 ([
//                   smallCompany, mediumCompany, largeCompany,
//                   southAmerica, northAmerica, asia, emea,
//                   cumuledMonth, dailyFees, peremption
//                 ])+
//            ]
//        ],

    ];

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // get the user manger (fos_user repo)
        $userManager = $this->container->get('fos_user.user_manager');

        // get all kind of repo
        $practiceRepo    = $manager->getRepository('MissionBundle:BusinessPractice');
        $missionKindRepo = $manager->getRepository('MissionBundle:MissionKind');
        $workExpRepo     = $manager->getRepository('MissionBundle:WorkExperience');
        $proExpRepo      = $manager->getRepository('MissionBundle:ProfessionalExpertise');
        $langRepo        = $manager->getRepository('ToolsBundle:Language');
        $companySizeRepo = $manager->getRepository('MissionBundle:CompanySize');
        $continentRepo   = $manager->getRepository('MissionBundle:Continent');

        $smallComp    = $companySizeRepo->findOneBy(['name' => 'company_size.small']);
        $mediumComp   = $companySizeRepo->findOneBy(['name' => 'company_size.medium']);
        $largeComp    = $companySizeRepo->findOneBy(['name' => 'company_size.large']);
        $southAmerica = $continentRepo->findOneBy(['name' => 'continent.south_america']);
        $northAmerica = $continentRepo->findOneBy(['name' => 'continent.north_america']);
        $asia         = $continentRepo->findOneBy(['name' => 'continent.asia']);
        $emea         = $continentRepo->findOneBy(['name' => 'continent.emea']);

        $i = 0;

        foreach ($this->arrayDatas as $oneData) {

            // create Entity with fName, lName, email, country
            /** @var User $newUser */
            $newUser = $userManager->createUser();
            $newUser
                ->setFirstName($oneData[0])
                ->setLastName($oneData[1])
                ->setEmail($oneData[2])
                ->setEmergencyEmail($oneData[3])
            ;

            // set languages
            foreach ($oneData[4] as $oneLang) {
                $newUser->addLanguage($langRepo->findOneBy(['name' => $oneLang]));
            }

            // set password and status
            $newUser
                ->setPlainPassword($oneData[5])
                ->setRoles(['ROLE_ADVISOR'])
                ->setEnabled(true)
            ;
            $newUser->setStatus(User::REGISTER_NO_STEP);

            // set User resume
            $newUser->setUserResume($oneData[6]);

            // set user Datas
            foreach ($oneData[7] as $oneBuisiness) {
                $newUser->addBusinessPractice($practiceRepo->findOneBy(['name' => $oneBuisiness]));
            }

            foreach ($oneData[8] as $oneMisKind) {
                $newUser->addMissionKind($missionKindRepo->findOneBy(['name' => $oneMisKind]));
            }

            foreach ($oneData[9] as $oneProExp) {
                $newUser->addProfessionalExpertise($proExpRepo->findOneBy(['name' => $oneProExp]));
            }

            $userManager->updateUser($newUser, true);

            // gen Experience shaping
            $j = 0;
            foreach ($oneData[11] as $oneExpShap) {

                /** @var \MissionBundle\Entity\UserWorkExperience $workExp */
                $workExp = new UserWorkExperience();

                $workExp
                    ->setWorkExperience($workExpRepo->findOneBy(['name' => $oneData[10][$j]]))
                    ->setCumuledMonth($oneExpShap[7])
                    ->setDailyFees($oneExpShap[8])
                    ->setPeremption($oneExpShap[9])
                    ->setUser($newUser)
                ;

                switch ($oneExpShap) {
                    case $oneExpShap[0] == true:
                        $workExp->addCompanySize($smallComp);
                    case $oneExpShap[1] == true:
                        $workExp->addCompanySize($mediumComp);
                    case $oneExpShap[2] == true:
                        $workExp->addCompanySize($largeComp);
                    case $oneExpShap[3] == true:
                        $workExp->addContinent($southAmerica);
                    case $oneExpShap[4] == true:
                        $workExp->addContinent($northAmerica);
                    case $oneExpShap[5] == true:
                        $workExp->addContinent($asia);
                    case $oneExpShap[6] == true:
                        $workExp->addContinent($emea);
                }

                $manager->persist($workExp);
                $j++;
            }

            if (key_exists(12, $oneData)) {
                $this->linkPhone($oneData[12], $newUser, $manager);
                $this->linkAddress($oneData[12], $newUser, $manager);
            }

            $newUser->setPublicId(md5(uniqid() . $newUser->getUsername() . $i));
            // save user in db
            $userManager->updateUser($newUser, true);
            $i++;
        }
        $manager->flush();
    }

    /**
     * @param                         $oneData
     * @param \UserBundle\Entity\User $user
     * @param ObjectManager           $manager
     */
    private function linkAddress($oneData, $user, $manager)
    {
        $address = new Address();

        $address
            ->setNumber($oneData[2])
            ->setStreet($oneData[3])
            ->setStreet2($oneData[4])
            ->setZipcode($oneData[5])
            ->setCity($oneData[6])
            ->setCountry($oneData[7])
        ;
        $manager->persist($address);
        $user->addAddress($address);
    }

    /**
     * @param                         $oneData
     * @param \UserBundle\Entity\User $user
     * @param ObjectManager           $manager
     */
    private function linkPhone($oneData, $user, $manager)
    {
        $phoneNumber = new PhoneNumber();
        $phoneNumber
            ->setPrefix($manager->getRepository('ToolsBundle:PrefixNumber')->findoneBy(['prefix' => $oneData[0]]))
            ->setNumber($oneData[1])
        ;
        $manager->persist($phoneNumber);
        $manager->flush();
        $user->setPhone($phoneNumber);
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 13;
    }
}
