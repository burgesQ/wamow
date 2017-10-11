################################
################################
##
##  OVH WMW SERVEUR1 :PRODUCTION SETTINGS
##  support@ovh.com
## 
################################
################################

# App conf
set :application, "WantMoreWork-Plateform"
set :app_path,    "app"
# Server conf
set :domain,      "app@wamow.co"
set :use_sudo,    false

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Rails migrations will run

set :deploy_to,     "/home/app/www/production/"
set :current_stage, "production"
set :url_base,      "wamow.co"

#Git production branch
set :branch, "prod"
