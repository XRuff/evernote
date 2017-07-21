<?php

namespace XRuff\Evernote;

use Evernote\Model\Note;
use Evernote\Model\PlainTextNoteContent;
use Nette\Object;

class Model extends Object
{
	public function newNote()
	{
		return new Note();
	}

	/**
	 * @param string $text
	 */
	public function newPlainTextNote($text)
	{
		$note = $this->newNote();
		$note->content = new PlainTextNoteContent($text);
		return $note;
	}
}
