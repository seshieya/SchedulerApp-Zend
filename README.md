# SchedulerApp-Zend (Scheduler Web Application for Trades/Contractors) - IN PROGRESS 
# Live demo coming soon.

Project is a work in progress therefore code is rough, and not thoroughly commented. It is updated frequently.


## Summary:


My current job involves manually creating work schedules in Microsoft Word. Application will make schedule creation easy and more automated. Schedules can be automatically emailed to trades/contractors at a time specified by an authenticated user.

## Current Highlights:
- Designed front-end and back-end architecture based on Model-View-Control concepts.
- Used dependency injections in factories to create database connections and sessions
- Used Zend Framework's Bcrypt library to encrypt passwords before storing them to the database. Only authenticated users can create schedules.
- Implemented user authentication and access filtering through creating service classes and event listeners. Non-authenticated user will be redirected to the login page if they visit a page they do not have access to.
- Integrated option for user to download schedule as PDF file or email to trades/contractors, after schedule has been generated.
- Developed an algorithm to generate dates that fall on Monday to Friday (excludes weekends). Dates generation is based on a start date that the user provides.
-	Built function to insert or remove more rows of inputs when user clicks add or delete buttons, and send these additional inputs to be stored to a database.


## Other main features for the project that I have not yet implemented:
 
- Upon emailing a schedule to a trade, there is a confirmation button in the email. If the trade clicks on the button, it will open up a browser window and redirect the trade to the Scheduler app, where it will trigger the server to send an email to the employee that created the schedule. The email will indicate that the trade has viewed and confirmed that they will be attending as per the date listed on the schedule.
