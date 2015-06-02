<?php

include "Exceptions.php";
include "Git.php";

date_default_timezone_set('America/Los_Angeles');

$git = new Valerian\Git\Git('/Library/WebServer/Documents/development/autosystem');

//Get git status
echo "GIT STATUS";
var_dump($git->status());

//Get git diff
echo "GIT DIFF";
var_dump($git->diff());

//Get git pull
echo "GIT PULL";
var_dump($git->pull());

//Get git log
echo "GIT LOG";
var_dump($git->log(5));

//Get last log
echo "GIT LAST LOG";
$lastLog = $git->getLastLog();
var_dump($lastLog);

//Get git show
echo "GIT SHOW";
$show = $git->show($lastLog['commit']);
var_dump($show);

//Get git branches
echo "GIT LOCAL BRANCHES";
var_dump($git->getLocalBranches());

//Get git branches
echo "GIT REMOTE BRANCHES";
var_dump($git->getRemoteBranches());

//Get git current branch
echo "GIT CURRENT BRANCH";
var_dump($git->getCurrentBranch());
