<?php
require_once "phing/Task.php";

class snippetInjectorTask extends Task {
	protected $file;
		
	public function init()	{
	} 
	
	public function main() {
		if(!file_exists($this->file))
			return false;
		$snippets = unserialize(file_get_contents($this->file));
		if(!$snippets)
			throw new BuildException("Invalid snippet file");

		foreach($snippets as $snippet) {
			$file = str_replace("%PATH_Icinga%",$this->project->getUserProperty("PATH_Icinga"),$snippet["file"]);
			$mark;
			$name = $snippet["mark"];
			$ext = preg_replace("/.*\.(\w{1,5})$/","$1",$file);
			switch($ext) {
				case 'txt':
				case 'ini':	
				case 'pl':
					$mark = "#PLUGIN[".$name."]";
					break;
				case 'xml':
				case 'html:':
					$mark = "<!-- PLUGIN[".$name."] -->";
					break;
				case 'css':
				case 'php':
				case 'js':
					$mark = "/*PLUGIN[".$name."]*/";
					break;
				default:
					throw new BuildException("Unknown filetype ".$ext);
			}
			$snippetFileContent = file_get_contents($file);
			// remove previous snippets
			$snippetFileContent = preg_replace("/.*PLUGIN\[".$name."\].*[\r\n]+([\w\W\r\n]+)[\r\n]+.*?PLUGIN\[".$name."\].*?[\r\n]+/","",$snippetFileContent);
			
			// append this snippet
			$snippetText = "\n".$mark."\n".$snippet["content"]."\n".$mark."\n";
			file_put_contents($file,$snippetFileContent.$snippetText);
		}
	}
	
	public function setFile($file) {
		$this->file = $file;
	}
}
