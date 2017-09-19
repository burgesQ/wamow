#! /bin/sh

scp root@consultants.wantmore.work:/var/www/html/consultants/tarBall.tar.gz . ;
tar zxvf tarBall.tar.gz ;
mv tarBall/* . ;
mv newsletters/* . ;
rm -rf tarBall newsletters
