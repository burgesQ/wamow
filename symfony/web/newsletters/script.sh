#! /bin/sh

scp root@consultants.wantmore.work:/var/www/html/consultants/tarBall.tar.gz . ;
tar zxvf tarBall.tar.gz ;
mv tarBall/* . ;
mv newsletters/* . ;
mkdir -p ../wp-content/uploads ;
mv -r 2017 ../wp-content/uploads/ ; 
rm -rf tarBall newsletters
