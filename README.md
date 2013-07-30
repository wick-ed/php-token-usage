TechDivision_ApplicationServerProject
=====================================

A webapp project-stub for the applicationserver.


Get Started
-----------

First of all you have to install the ApplicationServer Package.
You can download it here http://appserver.io

You also need composer installed on your system http://getcomposer.org

To create your first webapp using the project-stub do:

    cd /opt/appserver/webapps
    composer.phar create-project techdivision/techdivision_applicationserverproject YOUR_WEBAPPS_NAME dev-master

Now restart your appserver daemon and go to

    http://localhost:8586/YOUR_WEBAPPS_NAME