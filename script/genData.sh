#! /bin/sh

rm -rf app/cache &&
##* Assets Installation order for JS/CSS  :
    php app/console assets:install &&
    php app/console assetic:dump web &&
##* Gen Db  :
    php app/console doctrine:database:drop --force ;
    php app/console doctrine:database:create -vvv &&
    php app/console doctrine:schema:update --force -vvv &&
##* Load Fixture  :
    php app/console doctrine:fixtures:load  --no-interaction -vvv --append --fixtures=src/ToolsBundle/DataFixtures/ORM/LoadConfig.php &&
    php app/console doctrine:fixtures:load  --no-interaction -vvv --append --fixtures=src/MissionBundle/DataFixtures/ORM/LoadWorkExperience.php &&
    php app/console doctrine:fixtures:load  --no-interaction -vvv --append --fixtures=src/MissionBundle/DataFixtures/ORM/LoadBusinessPractice.php &&
    php app/console doctrine:fixtures:load  --no-interaction -vvv --append --fixtures=src/MissionBundle/DataFixtures/ORM/LoadMissionKind.php &&
    php app/console doctrine:fixtures:load  --no-interaction -vvv --append --fixtures=src/MissionBundle/DataFixtures/ORM/LoadProfessionalExpertise.php &&
    php app/console doctrine:fixtures:load  --no-interaction -vvv --append --fixtures=src/ToolsBundle/DataFixtures/ORM/LoadLanguage.php &&
    php app/console doctrine:fixtures:load  --no-interaction -vvv --append --fixtures=src/ToolsBundle/DataFixtures/ORM/LoadPrefix.php &&
    php app/console doctrine:fixtures:load  --no-interaction -vvv --append --fixtures=src/CompanyBundle/DataFixtures/ORM/Tests/LoadCompany.php &&   
    php app/console doctrine:fixtures:load  --no-interaction -vvv --append --fixtures=src/UserBundle/DataFixtures/ORM/Tests/LoadContractor.php &&
    php app/console doctrine:fixtures:load  --no-interaction -vvv --append --fixtures=src/UserBundle/DataFixtures/ORM/Tests/LoadAdvisor.php &&
    php app/console doctrine:fixtures:load  --no-interaction -vvv --append --fixtures=src/MissionBundle/DataFixtures/ORM/Tests/LoadMission.php &&
    php app/console doctrine:fixtures:load  --no-interaction -vvv --append --fixtures=src/MissionBundle/DataFixtures/ORM/Tests/LoadStep.php &&
    php app/console doctrine:fixtures:load  --no-interaction -vvv --append --fixtures=src/BlogBundle/DataFixtures/ORM/LoadNewsLetters.php &&
    chmod -R 777 app/cache app/logs
