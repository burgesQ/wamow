#! /bin/sh

rm -rf trans trans.zip
mkdir trans
cp symfony/app/Resources/translations/* ./trans
cp symfony/src/ToolsBundle/Resources/translations/* ./trans
cp symfony/src/UserBundle/Resources/translations/* ./trans
zip -r trans.zip trans
