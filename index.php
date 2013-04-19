<?php
//Phase: Bootstrap
//session_start();
define('JRNEK_INSTALL_PATH', dirname(__FILE__));
define('JRNEK_SITE_PATH', JRNEK_INSTALL_PATH . '/site');

require_once(JRNEK_INSTALL_PATH.'/src/bootstrap.php');
$jr = CJrnek::Instance();

//Phase: Frontcontrollerroute 
$jr->FrontControllerRoute();

//Phase ThemeEningeRender
$jr->ThemeEngineRender();


/*
Började med att uppdatera Index så att den följde nya sättet
med $this->views. Passade även på att uppdatera den
med lite länkar till de olika sidorna.

Kopierade CMGuestbook och började ändra den efter instruktionerna
 vilken funkade bra. Tjuvkikade lite på koden för att se hur mos tänkt
 sig med sessionen, men annars tycker jag att klarade av det mesta på egen hand.
 CCUser var inte heller några större problem. Jag använder dock den lite längre
 metoden för en Redirect ($this->RedirectTo) Snodde index.tpl.php från mos för att 
 testa om allt fungerade. Märkte att min create_url inte var uppdaterad för att ta emot argument.
 Detta löstes med en liten if sats som kollar om argument ska med eller inte. Efter det fungerade
 allt bra efter ett par mindre stavfel etc.

Känns som att jag fattat mer nu. enkelt att skriva de nya kontrollerna, acp etc. 

Snodde CForm rakt av. 
Kul felsökning CheckIfsubmitted verkade inte funka, kunde inte förstå varför. Skapa en instans
av jrnek och började lägga till messages i sessionen. Till slut hittade jag felet. Hade skrivit $var istället
för $val på ett ställe, saknar intelisence

Stora problem med Formulär för användarens profil. Till att börja med tycker jag
att instruktionerna var väldigt knapphändiga. Åter igen får man bara massa kod som
inte är helt lätt att förstå sig på. Gjorde ett försöka till att skriva allt själv. 
Men får en massa fel som är svåra att felsöka då man inte skrivit det själv. Slutar med
att jag kopierar hela CForm från mos, men får då fortfarande fel. Call to a member function GetHTML() on a non-object in..
Detta är nu i stort sätt omöjligt för mig att felsöka. Jag är helt vilse i koden
efter all kopiering. Skulle vilja skriva det själv, men känner att jag inte har kunskaperna 
till det. Tillslut visade det sig att jag skrivit akronym istället för acronym när jag skapade
databasen. Jag ändrade detta och fortsatte testa i tron om att databasen initierades varje gång
jag laddade om sidan. Men så var självklart inte fallet, utan ReadAll retunerade självklart 
en array med bla. akronym => ... Tog ett tag att hitta detta, då de felmeddelande jag fick inte
var helt klara.
*/

