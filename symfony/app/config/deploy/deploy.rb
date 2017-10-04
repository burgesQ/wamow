#set :application, "set your application name here"
#set :domain,      "#{application}.com"
set :stage_dir,     "symfony/app/config/deploy"
set :stages,        %w{staging production}
set :default_stage, "staging"

# Git
set :scm,                    :git
set :repository,             "git@github.com:WantMoreWork/plateformewantmorework.git"
set :git_enable_submodules,  true
set :deploy_via,             :remote_cache
set :keep_releases,          5
set :app_path,     "symfony/app"
#set :var_path,    "symfony/var"
set :web_path,     "symfony/web"
set :normalize_asset_timestamps, false
set :log_level, :error

#SF
set :model_manager,          "doctrine"
set :shared_children,        [app_path + "/logs", app_path + "/sessions", web_path + "/uploads", "symfony/vendor", app_path +  "/exports", web_path + "/newsletters", web_path + "/wp-content"]
set :dump_assetic_assets,    false
set :shared_files,           ["symfony/app/config/parameters.yml", "symfony/app/config/config_prod.yml"]
set :symfony_console,        "symfony/app/console"
set :use_composer,           false

set :writable_dirs,          [app_path + "/cache", app_path + "/logs", web_path + "/uploads", app_path + "/sessions", app_path +  "/exports", web_path + "/newsletters"]
set :webserver_user,         "www-data"
set :file_permissions_users, ["www-data"]
set :permission_method,      :acl
set :use_set_permissions,    true
set :user,                   "configurator"

namespace :Wmw do
    task :mail do
        transaction do
            # run "echo 'Deploiement en #{stage} realise' | mail -s 'WMW > Deploiement realise' david@wantmore.work kzerbib@gmail.com qutn.burgess@gmail.com"
        end
    end
    task :Composer do
        transaction do
          run "cd #{release_path}/symfony && curl -sS https://getcomposer.org/installer | php"
          run "cd #{release_path}/symfony &&  SYMFONY_ENV=prod php composer.phar install #{fetch(:composer_options)} "
        end
    end
end

logger.level = Logger::MAX_LEVEL

before "symfony:cache:warmup", "Wmw:Composer"
# Run migrations before warming the cache
# before "symfony:cache:warmup", "symfony:doctrine:migrations:migrate"

after "deploy:update", "Wmw:mail"             #Mail alerte
after "deploy:update", "deploy:cleanup"
