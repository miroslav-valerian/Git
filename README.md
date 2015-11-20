# Git
Git wrapper

Requirements
============
Git wrapper requires PHP 5.3.2 or higher and git.


Installation
=============

The best way to install Valerian/Git is using  [Composer](http://getcomposer.org/):

```sh
$ composer require valerian/git
```

Getting Started
===============

```php
$git = new Valerian\Git('path to folder with project');

//Get git statuslicense.md
var_dump($git->status());

//Get git diff
var_dump($git->diff());

//Get git pull
var_dump($git->pull());

//Get git log (last 5 logs)
var_dump($git->log(5));

//Get last log
$lastLog = $git->getLastLog();
var_dump($lastLog);

//Get git show
$show = $git->show($lastLog['commit']);
var_dump($show);

//Get git local branches
var_dump($git->getLocalBranches());

//Get git branches
var_dump($git->getRemoteBranches());

//Get git current branch
var_dump($git->getCurrentBranch());

```