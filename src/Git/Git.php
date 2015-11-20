<?php

namespace Valerian\Git;

/**
 * Git wrapper
 * 
 * @author Ing. Miroslav Valerián <info@miroslav-valerian.cz>
 * 
 */
use DateTime;

class Git
{

	/** @var string */
	protected $path;

    /**
	 * @param string $path
	 */
	public function __construct($path)
	{
		$this->path = $path;
	}

    /**
	 * @return array
	 */
	public function pull()
	{
		return $this->execute(
			'git pull 2>&1'
		);
	}

	/**
	 * @param string $message
	 * @return array
	 */
	public function commit($message = null)
	{
		return $this->execute(
			'git commit -m "' . $message . '" 2>&1'
		);
	}

    /**
	 * @return array
	 */
	public function log($limit = null)
	{
		$output = $this->execute(
			'git log 2>&1'
		);

		$out = null;
		if ($output) {
			$textNote = null;
			foreach ($output as $line) {
				$line = trim($line);
				$posCommit = strpos($line, 'commit');
				if ($posCommit !== FALSE) {
					$commit = explode('commit', $line);
					$commit = trim($commit[1]);
					$out[$commit]['commit'] = $commit;
					$textNote = false;
				}

				$posAuthor = strpos($line, 'Author:');
				if ($posAuthor !== FALSE) {
					$out[$commit]['author']['name'] = null;
					$out[$commit]['author']['email'] = null;
					$author = explode('Author:', $line);
					if (isset($author[1])) {
						$author = explode('<', $author[1]);
						$out[$commit]['author']['name'] = $author[0];
						if (isset($author[1])) {
							$out[$commit]['author']['email'] = str_replace('>', '', $author[1]);
						}
					}
				}
				
				$posDate = strpos($line, 'Date:');
				if ($posDate !== FALSE) {
					$date = explode('Date:', $line);
					$datetime = DateTime::createFromFormat('D M j H:i:s Y O', trim($date[1]));
					$out[$commit]['date'] = $datetime;
					$textNote = true;
				} else {
					if ($textNote) {
						if (strlen($line) > 0) {
							$out[$commit]['note'] = $line;
						}
					}
				}
			}
		}
		if ($limit) {
			$out = array_slice($out, 0, $limit);
		}
		return $out;
	}

    /**
	 * @return array
	 */
	public function getLastLog()
	{
		$log = $this->log(1);
		return reset($log);
	}

	public function status()
	{
		return $this->execute(
			'git status -u no 2>&1'
		);
	}

	/**
	 * @param string $revision
	 * @return array
	 */
	public function revert($revision)
	{
		return $this->execute(
			'git revert ' . $revision . ' 2>&1'
		);
	}

	public function diff($from = "2014-05-01 14:00:00", $to = "2015-07-01 14:00:00")
	{
		$output = $this->execute(
            'git diff'
        );

        return join("\n", $output);
	}
	
	/**
	 * @return string
	 */
	public function getCurrentBranch()
	{
		$output = $this->execute(
			'git rev-parse --abbrev-ref HEAD 2>&1'
		);
		return $output[0];
	}

    /**
	 * Get all local branches
	 * @return array
	 */
	public function getLocalBranches()
	{
		$output = $this->execute(
			'git branch -a 2>&1'
		);
		$branches = array();
		if ($output) {
			foreach ($output as $line) {
				$pos = strpos($line, 'remotes/origin/');
				$line = str_replace('*', '', $line);
				$trimLine = trim($line);
				if ($pos === FALSE) {
					$branches[] = $trimLine;
				}
			}
		}
		return $branches;
	}

    /**
	 * Get all remote branches
	 * @return array
	 */
	public function getRemoteBranches()
	{
		$output = $this->execute(
			'git branch -a 2>&1'
		);
		$branches = array();
		if ($output) {
			foreach ($output as $line) {
				$pos = strpos($line, 'remotes/origin/');
				$line = str_replace('*', '', $line);
				$trimLine = trim($line);
				if ($pos !== FALSE) {
					$branches[] = $trimLine;
				}
			}
		}
		return $branches;
	}

    /**
	 * @param string $revision
	 * @return string
	 */
	public function show($revision)
	{
		return $this->execute(
			'git show ' . $revision . ' 2>&1'
		);
	}

    /**
	 * 
	 * @param string $command
	 * @return array
	 */
	private function execute($command)
	{
		$cwd = getcwd();
		chdir($this->path);
		$output = null;
		$returnValue = null;
		exec($command, $output, $returnValue);
		chdir($cwd);

		if ($returnValue !== 0) {
			throw new GitException("Command " . $command . " failed. Return value ".$returnValue);
		}

		return $output;
	}

}
