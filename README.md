# aliexpress-API-SDK
Dosome daily actions like updateStock from DB or getOrders, with 3 simple files and SDK provided by Aliexpress.

# How to Start
Firts, you need to follow the steps from the official aliexpress page. 
Obviulsy you need to be a Seller of Aliexpress Marketplace.

# Become a Seller.
https://developers.aliexpress.com/en/doc.htm?spm=a219a.7386653.0.0.52cb9b710V1E5V&docId=118481&docType=1

# Tutorial to get the API and Secret Keys.
https://learning.es.aliexpress.com/course/learn?spm=ae-es-pc.ae-university-es-pc-list.courserlist_courses.11.2f776cb07JFTen&id=131&type=article

# Getting access Token.
https://developers.aliexpress.com/en/doc.htm?spm=ae-es-pc.ae-university-es-pc-module.courseIntro.15.44c1pt7Ppt7PV7&docId=108969&docType=1

# Download SDK (in this project, I will use a PHP SDK)
https://developers.aliexpress.com/en/doc.htm?spm=a219a.7386653.0.0.52cb9b710V1E5V&docId=108108&docType=1

# Setting up
Create a folder, and put in the SDK folder and rename it like "SDK". 
Then go to "SDK/top/TopClient" and search "$appKey" and assign it the value of your AppKey and do the same with the "$secretKey" with your App Secret.

Place it in the main folder, you now just should only see the "SDK" folder.

Then clone the repo and move the files to this main folder. You wil see.

/SDK
README.md
config-sample.php
feed_Aliexpress.php
ordersAliexpress.php

Change the name of "config-sample.php" to just "config.php" and assign the value of the variables. (Database name, username, password, port, Host and SessionKey)

It's done. Just execute the file that you need.



