<?php
include_once("model/model.php"); 

class artykuly_Model extends Model 
{
	public $wynik = false;
	
	public function __construct()  
	{
		parent::__construct(); 
		
		$this->artykuly();
		$this->grupujoceny();
		
	}
	public function grupujoceny()
	{
		if(isset($_POST['grupuj2']))
		{
			if($_POST['grupujoceny'] == "")
			{
				$artykuly = $this->sql_query("SELECT a.Tytul,r.Tresc,o.Ocena
				FROM artykul a 
				LEFT OUTER JOIN recenzja r ON a.ID_Artykul = r.ID_Artykul
				LEFT OUTER JOIN ocena o ON r.ID_Ocena = o.ID_Ocena
				WHERE o.Ocena is NULL");
				include __DIR__ . "/../view/artykuly.phtml";
			}
			else if($_POST['grupujoceny'] < "-5" || $_POST['grupujoceny'] > "5")
			{
				$artykuly = $this->sql_query("SELECT a.Tytul,r.Tresc,o.Ocena
				FROM artykul a 
				LEFT OUTER JOIN recenzja r ON a.ID_Artykul = r.ID_Artykul
				LEFT OUTER JOIN ocena o ON r.ID_Ocena = o.ID_Ocena");
				$this->redirect("index.php?con=artykuly", "error", "Podaj ocene pomiędzy -5 a 5");
				include __DIR__ . "/../view/artykuly.phtml";
			}
			else 
			{
				$artykuly = $this->sql_query("SELECT a.Tytul,r.Tresc,o.Ocena
				FROM artykul a 
				LEFT OUTER JOIN recenzja r ON a.ID_Artykul = r.ID_Artykul
				LEFT OUTER JOIN ocena o ON r.ID_Ocena = o.ID_Ocena
				WHERE o.Ocena =".$_POST['grupujoceny']."");
				include __DIR__ . "/../view/artykuly.phtml";
			}
		}
	}
	public function artykuly() 
	{		
		$artykuly = $this->sql_query("SELECT a.Tytul,r.Tresc,o.Ocena,r.ID_Recenzja, a.ID_Artykul
		FROM artykul a 
		LEFT OUTER JOIN recenzja r ON a.ID_Artykul = r.ID_Artykul
		LEFT OUTER JOIN ocena o ON r.ID_Ocena = o.ID_Ocena
		WHERE a.Opublikowany!='0'");	
		
		//$idrecenzja = $this -> sql_query("SELECT ID_Recenzja FROM recenzja");
		
		if(isset($_POST['recenzja']))	
		{
			$recenzja = $this->sql_query("SELECT * FROM recenzja
			WHERE ID_Recenzja = ".addslashes($_POST['recenzja'])."");
			$artykul = $this->sql_query("SELECT * FROM artykul
			WHERE ID_Artykul = ".$recenzja[0]['ID_Artykul']."");
			$recenzent = $this->sql_query("SELECT * FROM recenzent
			WHERE ID_Recenzent = ".$recenzja[0]['ID_Recenzent']."");
			$autor = $this->sql_query("SELECT * FROM konto
			WHERE ID_Konto = ".$recenzent[0]['ID_Konto']."");
			
			include __DIR__ . "/../view/wyswietlrecenzje.phtml";
		}
		else if(isset($_POST['wroc']))
			include __DIR__ . "/../view/artykuly.phtml";
		else if(isset($_POST['art_wys']))
		{
			$tresc = $this -> sql_query("SELECT Tresc FROM artykul
			WHERE ID_Artykul =".addslashes($_POST['art_wys'])."");
			include __DIR__ . "/../view/art_wysw.phtml";
		}
		else if(!isset($_POST['grupuj2']))
			include __DIR__ . "/../view/artykuly.phtml";
	}
}
	
?>