<?php
include_once("model/model.php"); 

class rejestracja_Model extends Model 
{
	public function __construct()  
	{
		parent::__construct(); 
	
		$this->rejestracja();

		include_once("view/rejestracja.phtml"); 
	}
	
	public function rejestracja() 
	{
		if(isset($_POST['rejestruj']))
		{
			$result = $this->sql_query("SELECT * FROM `konto` WHERE `login`='".addslashes($_POST['login'])."' LIMIT 1"); 

			if($_POST['login'] == "" || $_POST['haslo'] == "" || $_POST['imie'] == "" || $_POST['nazwisko'] == "" || $_POST['email'] == "" || $_POST['nr_telefonu'] == "")
				$this->redirect("index.php?con=rejestracja", "error", "Nie wprowadzono danych."); 
			else if($result) 
				$this->redirect("index.php?con=rejestracja", "error", "Takie konto już istnieje."); 
			else if(strlen($_POST['imie']) > 20 || !preg_match('@^[a-zA-Z]{3,20}$@', $_POST['imie']))
				$this->redirect("index.php?con=rejestracja", "error", "Podane imię jest nieprawidłowe!"); 
			else if(strlen($_POST['nazwisko']) > 40 || !preg_match('@^[a-zA-Z]{2,40}$@', $_POST['nazwisko']))
				$this->redirect("index.php?con=rejestracja", "error", "Podane nazwisko jest nieprawidłowe!");
			else if(strlen($_POST['email']) > 60 || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
				$this->redirect("index.php?con=rejestracja", "error", "Adres e-mail jest nieprawidłowy!");
			else if(strlen($_POST['nr_telefonu']) > 20 || !preg_match('@^[0-9]{6,20}$@', $_POST['nr_telefonu']))
				$this->redirect("index.php?con=rejestracja", "error", "Numer telefonu jest nieprawidłowy!");
			else 
			{
				mysql_query("INSERT INTO `konto`(`Login`, `Haslo`, `Imie`, `Nazwisko`, `Email`, `Nr_Telefonu`)VALUES ('".addslashes($_POST['login'])."', md5('".addslashes($_POST['haslo'])."'), 
					'".addslashes($_POST['imie'])."','".addslashes($_POST['nazwisko'])."','".addslashes($_POST['email'])."',
					'".addslashes($_POST['nr_telefonu'])."')")or die(mysql_error());
				$tresc = "<h2>Dziękujemy za rejestracje w aplikacji </h2><br>E-Konferencja.";
				$this->sendMail($_POST['email'], "E-Konferencje", $tresc);
				$this->redirect("index.php?con=login", "success", "Utworzyłeś pomyślnie konto! Możesz się teraz zalogować."); 
			}
		}
 	}
}
?>