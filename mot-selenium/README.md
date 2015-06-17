# dvsa-mot-selenium

This is the Selenium tests project for DVSA MOT. It's written in Java and uses TestNG framework. The IDE used for project development is [Eclipse](www.eclipse.org) and documentation is based on it but there is no obstacles to use different IDE to work.

##  Getting Started with the project:

### Install Eclipse
Download the latest eclipse: Eclipse IDE for Java Developers ; 
<http://www.eclipse.org/downloads/>

### Install Maven plugin for Eclipse
In Eclipse, `Help > Install New Software`

* Name: Maven
* Location: http://download.eclipse.org/technology/m2e/releases
* Eclipse will guide you through the steps. Follow the install process.

### Instal TestNG plugin for Eclipse

In Eclipse, `Help > Install New Software`

* Name: TestNG
* Location: http://beust.com/eclipse
* Eclipse will guide you through the steps. Follow the install process.

###Import new project from Eclipse
Import downloaded repository with Selenium tests  
 
	`File-> Import -> Maven -> Existing Maven Project`

## Running tests

There are several options to run tests:

* Using TestNG runner from Eclipse
* Using Maven runner from Eclipse
* Using Maven from the command
* Invoking them from Jenkins

###Running tests with TestNG from Eclipse
We can run tests with TestNG runner for the whole project, specific test suite or single test. Just open context menu (right click on it) on the desirable project, file or test method and chose `Run As..` TestNG Test

###Running tests with maven from Eclipse 

Open context menu for the Selenium tests project , chose `Run As..` and proper Maven command.

### Running tests with maven from cmd

cd into project directory (with pom.xml file)

`mvn clean test` – will clean dependences, download them, compile and run

`mvn test` – will run only test

`mvn dependency:resolve` - only downloads dependencies without 

###Running tests from Jenkins
In configuration panel set in Build section Goals: test or clean test

Run `Build now` on the project to trigger tests

Check out the repo to \vosa-mot\selenium



##Some Selenium best practices:

Use PageObjects pattern

Be fluent with 

* return this, varargs, generics, 
* reuse your model and jodatime

Be robust and portable 

* Prefered selector order : id > name > css > xpath 
* Avoid Thread.sleep prefer Wait or FluentWait
*  Use relative URLs
*  Don’t rely on specific Driver implementation
*  Create your dataset

Know your new tool

* Keep up to date (versions and usage pattern)
* Troubleshooting 
	* jre 1.6
	*  IE (zoom, Protected mode setting )
	* Firefox/firebug startpage
* How to deal with UI components like... fileupload, datepicker, ajaxtables,...
* Detect when selenium isn't the good tool for the job
* Don't be afraid to hack around selenium

