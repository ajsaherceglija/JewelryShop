# JOINT GROUP PROJECT

## [CS 240: Database Systems](#cs-240-database-systems-requirements)

## [CS 260: Cloud Computing](#cs-260-cloud-computing-requirements)

## [CS 270 : Design and Implementation in Web Environment](#cs-270-design-and-implementation-in-web-environment-requirements)

You are asked to create a database driven application and deploy it in cloud. Your work must satisfy the
minimum for each course which is described in the following text.

Keep in mind that delivery of the projects may be different. The aim is that you present your project once for
all three courses. However, each professor may ask you to present the projects and/or written report
specifically for that course.

Deadline to turn your projects in, and the type of the delivery will be announced during the course of the
semester.

## CS 240 Database Systems requirements

You are asked to create a database driven application. The application must satisfy minimally the following
requirements:

1. The number of tables is not defined, but a general guidance is 5 - 6 tables or 5-6 relationships. More is welcome.
2. Database must be a relational one.
3. Project can be done using any relational DBMS. Oracle is preferred, but others are allowed as well.

The grade for CS 240 Database systems course will be distributed in the following way:

1. 15% for the project report.
2. 30% for the ERD.
3. 25% for the SQL queries (covering all aspects: one table query, multiple table queries connected via
    PK-FK, aggregated functions with GROUP BY, use of HAVING, use of DISTINCT, INSERT,
    UPDATED, DELETE, dealing with dates, aspects of strings (like, %, _, UPPER, LOWER, etc.), use
    of aliases, outer joins, etc.).
4. 5% a proper use of meaningful triggers (at least one). For example: Auto number trigger does not
    qualify, but the one that keeps derived attribute updated is OK.
5. 6 % for proper and meaningful use of procedure, function and a view (2% each).
6. 4 % for appropriate use of indexes.
7. 10% for the application functionalities (dynamic queries, meaningful reports).
8. 5% presentation and Q&A.

The written report should have the description of every aspect of Database Development Lifecycle phases.
Besides those, ERD must be presented, as well as data dictionary (list of all tables, attributes, data types, data
domains, comments, etc.). One of the appendices should feature the list of queries (SQL and description)
you use in the reporting part of your project.

Some hints and pointers what to watch out for:

1. Report is not funny and interesting communication with the professor, but a professional document
    talking about database and the application. So no smileys and witty comments.
2. Include the last part of the report saying what are the possible extensions or improvement for the
    project in the future.
3. Application should have 5-10 reports (SQL Queries) that are driven by parameters (fields), and should
    be connected between each other with links. For example, if you have a list of all your customers,
    with their lifetime purchase or purchase over time, you should be able to click on that amount, which
    would take you to another report that lists all invoices of that person, for that period, that adds up to
    the amount you clicked on.
4. Use lists of values when entering or updating information.
5. Make use of DBLC illustration from Chapter 9 to get an idea what the written report should contain.
6. Written report is allowed to be turned in as one complete report for all three courses, as long as it
    contains the above mentioned topics.

The project is worth 100 points, weighted at 15% of the total grade.

## CS 260 Cloud computing requirements

Student Guidelines:

1. Students are required to use Oracle Cloud instance to complete the project.
2. Students are required to establish log in credentials in Oracle Academy SSST program (5% of the
    project grade)
3. Students are required to establish its own cloud tenancy and present it (5% of the project grade).
4. Students are required to follow the instruction to create IaaS environment on their Oracle Cloud tenancy.
   * Complete the procedure “Oracle Cloud Infrastructure – Create a Compute VM Instance with Oracle Linux Server **“**. Create an instance of Oracle Linux 7.8. (20% of the grade)
   * Complete the procedure “Oracle Cloud Infrastructure – Compute VM Instance - Configure and Connect to a Linux GUI and Java IDE”. (on the same instance created in first step) (20% of the project)
     * Use the sample of java program of your choice and run it through Java IDE
       environment. Present output. (10% of the grade)
   * Create and Ubuntu VM – using Canonical Ubuntu 22.04 when creating an instance and:
   (20 % of the grade)
     * Install and configure Nginx
5. Install and configure php 8.2.
6. Create & run simple .php file in browser and present the output.
7. Create Autonomous Data Base in your cloud tenancy, which you will use to create data base project.
    Use the following instructions “Oracle Academy Cloud Program Autonomous Database Startup
    Guide”. After installation, create few DB tables of your choice. For students that have DB project
    they can show their project. For students that do not have DB project you can create basic three
    tables and present it. (10% of the grade)
8. Create load balancer in your tenancy. (5% of the grade)
9. Present individually your project in 10 minutes (5% of the grade)

Versions of the systems mentioned above are subject to change as newer versions become available.
Therefore, that should be checked and confirmed with all three professors from this joint project initiative.

The project is worth 100 points, weighted at 15% of the total grade.

## CS 270 Design and Implementation in Web Environment requirements

The project consists of three parts:

1. Project implementation
    * Website design
    * Web application implementation
       * Front-end (end-users)
       * Back-end (administration)
2. Final report
3. Project presentation

The result must be a well-structured, visually appealing web application, with the minimal functionality
defined below.

1. **Project implementation**

   *(grading 80%)*
   
   <br>
   You are asked to design and develop a web application using PHP and relational database.
   <br><br>
   From the Web design course perspective, the focus of the project is on the application and not the database.
   The project should satisfy these minimal requirements:

   - You **must** use PHP, HTML and CSS.
   - You **may not** use CMS (WordPress or any other).
   - Using JavaScript is **optional**.
   - Design **must** be responsive.
   - Your site **must** be a dynamic one and must interact with the database.
     - Application must have forms to insert/update/delete/view data
   - You must implement both front- and back-end parts of the application.
   - _Front-end **must** have:_
     - User registration and authorization (login/logout)
     - Viewing of data for your topic (i.e. music, forums, shopping lists ... etc.) and the user profile
            (personal data)
     - Some transaction that will change the data in the database (i.e. user makes a new post or user
            buys something)
   - _Back-end must have:_
       - Login (for the administrator)
       - Managing the database through the application back-end (CRUD – Create, Read, Update,
          Delete)
   - Your site should use Cookies and/or Sessions
   - Everything else is your own choice. However, beware that your project needs to be serious work.
   <br><br>

2. **Final report**
   
    _(grading 10%)_

   <br>
   The final report should provide a good overview of the project and should cover all items and sub-items
   listed under the Project implementation heading. It should describe all the various steps followed and should
   also include the main screenshots from the application. The report should include the following sections:

   1. TITLE: Give the report a title that reflects the nature of the project
   2. ABSTRACT (max 300 words): Include a brief description of the aim of the project, what you did
      and the main outcomes. Even though this part is at the beginning of your report it is recommended to
      prepare it last.
   3. INTRODUCTION: Write a brief non-technical background and introduction to the project.
   4. PROJECT IMPLEMENTATION: This is the core part of the report. Discuss all the steps you took
       with reference to all the items and sub-items listed under the heading Project implementation.
   5. LESSONS LEARNED and CONCLUSIONS: Write a brief discussion about your project,
       pointing out what you have learned about the design and development through this application. In a
       few sentences draw some conclusions about your project.
   6. REFERENCES: Write in FULL the references (books, articles, web articles, websites, etc.) that you
       used if any.
   <br><br>

3. Final presentation

   _(grading 10%)_

   <br>
   Each group will have 15 minutes to present their project.
   Each presentation must consist of three parts:
   - Power Point or similar presentation of the main concepts
   - Demonstration of the application
   - Questions and answers

The project is worth 100 points, weighted at 25% of the total grade.
