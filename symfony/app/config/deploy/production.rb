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
# set :domain,      "app@platform.wantmore.work"
set :domain,      "app@staging.consultants.wantmore.work"
set :use_sudo,    false

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Rails migrations will run

set :deploy_to,     "/home/app/www/production/"
set :current_stage, "production"
# set :url_base,     "http://consultants.wantmore.work"
set :url_base,      "http://wamow.co"

#Git production branch
set :branch, "prod"
