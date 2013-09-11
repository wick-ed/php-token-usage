About php-token-usage
===============

An appserver demo application for analyzing PHP token usage within different projects. You can choose which tokens to search for and will get some nice graphs as result.
The whole app is based on the appserver project.
See https://github.com/techdivision/TechDivision_ApplicationServer

Usage
===============
Before using this project you have to add some data to analyze.
You can add several projects with several different versions by simply copying them in the folder META-INF/data/projects.
They have to be of the form <PROJECT_NAME>/<VERSION>.
Add at least 2 versions per project to get reasonable results.

Example:

yii
	1.1.05
	1.1.10
	1.1.13
