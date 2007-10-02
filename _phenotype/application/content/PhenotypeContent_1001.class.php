<?php 
class PhenotypeExpandingList extends PhenotypeContent_1001 {}
class PhenotypeContent_1001 extends PhenotypeContent
{
	// Expandierende Liste
	var $content_type = 1001;
	var $skins = Array (); // erlaubte Skins

	var $nostatus = 1;

	function setDefaultProperties()
	{
		$this->set("bez", "Neue Liste");
		$this->set("liste", Array ());
	}

	function init($row, $block_nr = 0)
	{
		parent :: init($row, $block_nr);

		// Hier das Formular und damit auch die Updatefunktion initialisieren

		$this->form_headline("Meta");
		
		$this->form_textfield("Bezeichnung", "bez", 200);
		$this->form_textarea("Beschreibung<br>(intern)", "comment", 300, 5);
		
		if (count($this->get("liste"))!=0)
		{
			$this->form_headline("Listenelemente");
			$i = 0;
			foreach ($this->get("liste") AS $v)
			{
				$i ++;
				$this->set("item_".$i, 1);
				$this->form_checkbox("", "item_".$i, $v);
			}
			$this->form_expandinglist("Vorschau","preview", $this->id);
		}
		$this->form_headline("Neue Elemente hinzufügen");
		$this->form_textfield("1. Element", "element1", 200);
		$this->form_textfield("2. Element", "element2", 200);
		$this->form_textfield("3. Element", "element3", 200);

	}

	function update()
	{
		// Die Updatemethode wird überschrieben, wenn beim Auswerten eines Formulars
		// Properties verändert werden sollen. Beim externen Zugriff auf das Contentobjekt,
		// d.h. ohne Formular, wird diese nicht aufgerufen.
		parent :: update();
		$_listeneu = Array ();
		foreach ($this->get("liste") AS $v)
		{
			$i ++;
			if ($this->get("item_".$i) == 1)
			{
				$_listeneu[] = $v;
			}
			$this->clear("item_".$i);
		}
		$this->set("liste", $_listeneu);

		for ($i = 1; $i <= 3; $i ++)
		{
			if ($this->get("element".$i) != "")
			{
				$this->addItem($this->get("element".$i));
			}
			$this->clear("element".$i);
		}
		$this->clear("preview");
	}

	function addItem($v)
	{
		$_liste = $this->get("liste");
		if (!in_array($v, $_liste))
		{
			$_liste[] = $v;
			asort($_liste);
			$this->set("liste", $_liste);
		}
	}

	function store()
	{
		//2do Test für Nils

		for ($i = 1; $i <= count($this->get("liste")); $i ++)
		{
			$this->clear("item_".$i);
		}
		$this->clear("element1");
		$this->clear("element2");
		$this->clear("element3");
		parent :: store();
	}

}
 ?>