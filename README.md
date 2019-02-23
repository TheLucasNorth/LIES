# LIES
[![CodeFactor](https://www.codefactor.io/repository/github/thelucasnorth/lies/badge/master)](https://www.codefactor.io/repository/github/thelucasnorth/lies/overview/master) [![GPLv3 license](https://img.shields.io/badge/License-GPLv3-blue.svg)](http://perso.crans.org/besson/LICENSE.html)

*Please note this branch is no longer being updated. Please see v2*

Lucas' Ironically-named Election System. An open source STV election web-app for ballot collection and result calculation.

Welcome to the LIES STV Voting System developed by Lucas North.

The system is designed to support a theoretically unlimited number of candidates in a theoretically unlimited number of elections, in which a theoretically unlimited number of voters can participate. The actual limits are set by database capability ceilings.

Initial setup must be done by running the CREATE.sql script in the setup folder. After this, database details must be added in config.php.

Documentation on most features is available at /doc. This page will not load until the database has been set up.

The default admin login is "admin" with Admin Code "ADMINPASSWORD". The admin password is controlled by changing the admin password field on the Election Setup page in the admin portal.

After initial setup, the name of the election, the root directory, and the privacy policy URL must be configured. This can be done by going to /admin/electionSetup.php

Elections can be added on the admin/electionSetup.php page.

Candidates are added by visiting /admin/ and clicking on the name of the relevant election.
