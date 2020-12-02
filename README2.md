# ug-advisor
Under Graduate Advisor Program

Giving students and colleges the oppertunity to plan together their college plan. The system has 3 primary users. The college student, their advisor, and the college systtetm administrator.

## Locally Installing this Application

1. [Download XAMPP](https://www.apachefriends.org/download.html)
1. Follow the instructions for your opperating system
1. Clone or download this directory
1. Open the ug-advisor directory and copy all the files 
1. Find you XAMPP app and start the app
1. Next start the server, note the ip addrress
1. Next click on Volumes tab, next to /opt/lamp click mount
1. On your desktop you should see LAMPP click on this
1. Open this directory /lampp/htdocs/
1. Move the ug-advisor directory to htdocs directory
1. On the XAMPP app window under General tab click Go to Application
1. http://192.168.64.2/ug-advisor note IP number may be different
1. http://192.168.64.2/ug-advisor/php/start.php will automatically create the DB



## Technology Stack
- Apache
- mysql
- php
- javascript
- html
- css

## Database Design
![the design schema of the database](https://lucid.app/lucidchart/invitations/accept/6b81b5cf-623d-4ab3-97ed-6d44ce345007)

## Future Changes
This application needs to be developed in a MVC (model view controler). The database needs to be redesigned and normalized to 2NF. The data is specific to Southern Connecticut State University and should be generalized so that the system could use any college.

## Original Team Members
- Joshua Connor
- Josh Kenney (pull request)
- Nick Santini
- Greg Rodriguez
