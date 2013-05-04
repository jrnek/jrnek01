JRNEK FRAMEWORK README
========

Hämta och installera
========
### Hämta
Klona eller ladda ner jrnek Framework från github(https://github.com/jrnek/jrnek01)
du kan använda git clone <code>github.com/jrnek/jrnek01.git</code>

### Installation
## .HTACCESS
Om du inte har valt att lägga filerna i root på din webserver måste du ändra
<code>#RewriteBase /~febj13/phpmvc/kmom0810</code> till jrneks huvudmapp på webbservern

## Databas
För att databasen ska fungera måste katalogen site/data vara skrivbar av webservern
om inget annat använd <code>cd jrnek chmod 777 site/data</code>

## Initierad
Nu bör du kunna peka webläsaren till jrnek framework! Du behöver dock installera 
några moduler för att allt ska fungera som det ska. Följ länken under Installation som 
heter modules/install. Den initierar databasen och skapar bla. två användare: admin/admin och doe/doe

Utseende och konfiguration
===========

## Utseende
Öppna site/config.php. Under $jr->config['theme'] kan du ändra utssende och information,
följande variabler nedan återfinns här.

### Header
Under raden <code>header => 'Jrnek Framework'</code> kan du ändra det som ska presenteras i headern.

### Footer
Under raden <code>footer => jrnek &copy; by Felix</code> kan du ändra det som ska presnterads i footer.

### Logo
För att ändra logo behöver du din önskade logo vara placerad under site/theme/dittvaldatema 
(det tema som står valt under 'path'). Sen ändrar du bara logo => till bildens namn.

### CSS
Specifiera den css-fil som du vill använda under 'stylesheet'. Katalogen som används är den 
som är specifierad under 'path'. Vill du utgå från jrneks grundtema kan du inkludera dess css 
med: <code>@import url(../../../themes/grid/style.css);</code>


## Innehåll
För att skapa innehåll behöver du vara inloggad som en användare.
Logga in med antingen admin:admin eller doe:doe.

### Meny
Under <code>$jr->config['menus']</code> kan du skapa menyer som du 
kan använda i ramverket. En meny betår av en array med en "Key" som är
länkens namn och ett "value" som är den controller läknen pekar på.
Du kan sen specifiera till vilken del av sidan menyn ska skickas.
Detta göra du i <code>$jr->config['theme']</code> under 'menu_to_region'.
Den består av en array där Key är vilken meny samt ett value som specifierar
till vilken region menyn ska skickas.


### Blog
Blog
För att skapa ett blog inlägg peka webläsaren mot content/create eller följ länken under Content
 som heter create. För att skapa ett blog-inlägg ska "Type" vara post. Du kan välja på tre filter:

 > plain: 	Inga taggar etc stöds
 > html: 	Du kan använda html taggar när du skapar sidor
 > bbcode: 	Du kan använda bbcode när du skapar sidor.

När inläggat är skapat kan du se det från menyvalet "Blog"

### Page
För att skapa en sida följer du samma steg som för att skapa en blog (se ovan).
Men som "Type" väljer du page. Dina sidor kan du sedan se via menyvalet "Page".
Vill du att sidan som du skapade ska finnas med som ett val i huvudmenyn kan du
enkelt lägga till den under <code>$jr->config['menus']</code> se ovan. Som value
använder du <code>'page/view/keyTillSidan'</key> key till sidan är det som du
skrev in under "Key" när du skapade den.

