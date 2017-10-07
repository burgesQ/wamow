################################
################################
##
##  OVH WMW SERVEUR1 :STAGING SETTINGS
##  support@ovh.com
## 
################################
################################

# App conf
set :application, "WantMoreWork-Plateform"
set :app_path,    "app"
# Server conf
set :domain,      "app@staging.wamow.co"
# set :domain,      "app@staging.consultants.wantmore.work"
set :use_sudo,    false

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Rails migrations will run

set :deploy_to,     "/home/app/www/staging/"
set :current_stage, "staging"
set :url_base,      "staging.wamow.co"
# set :url_base,      "staging.consultants.wantmore.work"

#Git stagging branch
set :branch, "preprod"
