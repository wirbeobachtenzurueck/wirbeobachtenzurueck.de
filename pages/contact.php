<?php
mb_internal_encoding('UTF-8');

session_start();

require_once '../scripts/php/captcha.php';

$captchaQuestion = generateCaptcha();

header('Content-Type: text/html; charset=UTF-8');

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontakt</title>
    <script src="../scripts/js/contactscript.js"></script>
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="icon" href="../media/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="../media/favicon.ico" type="image/x-icon">  
</head>

<body>
    <header>
        <div class="logo-container">
            <a href="/">
                <img src="../media/wirbeobachten-logo-2.svg" alt="Wir beobachten zurück Logo">
            </a>
        </div>
        <nav class="buttons-top">
                <div class="top-button">
                    <a href="../pages/generator.html" class="generator">Antragsgenerator</a>
                </div>        
                <div class="bottom-buttons">
                        <a href="../pages/faq.html" class="faq">FAQ</a>
                        <a href="../pages/contact.php" class="contact">Kontakt</a>
                        <a href="../pages/support.html" class="support">Support</a>
                </div>       
        </nav>
    </header>

    <div class="content-container">
        <div class="content-head">
            <h2>Kontakt</h2>
            <h5>Sie haben Fragen zu Ihrem Antrag beim Verfassungsschutz, oder zu unserem Projekt? <p></p> Sie haben eine "positive" Antwort vom Verfassungsschutz erhalten, <br>werden beobachtet und möchten wissen, <br>was Sie nun als Nächstes tun können? <p></p> Sie möchten dazu beitragen, <br>das fragwürdige Verhalten des Verfassungsschutzes journalistisch aufzuklären? <p></p> Dann treten Sie gern mit uns in Kontakt! <br>Wir garantieren vollständigen Informantenschutz. <p></p> Sie können uns neben dem Kontaktformular <br>Ihre Informationen auch pseudonym oder anonym via Threema mitteilen: <br><a href="https://threema.id/9H4BU5AT" target="_blank" rel="noopener noreferrer">https://threema.id/9H4BU5AT</a></h5>
        </div>
        <br>
        <form id="kontaktForm" method="POST" action="../scripts/php/send_email.php" enctype="multipart/form-data">
    
            <!-- Eingabefelder -->
                <div class="mb-3">
                    <label for="name" class="form-label">Vorname, Name: </label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Emma Mustermann" required autocomplete="off">
                </div>
        
                <div class="mb-3">
                    <label for="email" class="form-label">Email: </label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Muster@email.com" required autocomplete="off">
                </div>
        
                <div class="mb-3">
                    <label for="message" class="form-label">Ihre Nachricht: </label>
                    <textarea class="form-control" id="message" name="message" rows="3" placeholder="Geben Sie hier Ihre Nachricht ein und nutzen Sie gerne die 'Dateien-anhängen' Funktion. Erlaubte Formate: .pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .zip, .rar, .7zip" required></textarea>
                </div>
                
            <div class="mb-3">
                <label for="attachment" class="form-label">Dateien anhängen (max. 15 MB insgesamt): </label>
                <input type="file" class="form-control" id="attachment" name="attachment[]" accept=".pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .zip, .rar, .7zip" multiple>
            </div>

    	    <div class="mb-3">
    		<label for="captcha" class="form-label">Sicherheitsfrage: Was ergibt <?php echo $captchaQuestion; ?>?</label>
    		<input type="number" class="form-control" id="captcha" name="captcha" placeholder="10" required autocomplete="off">
	    </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="datenschutz" name="datenschutz" required>
                <label class="form-check-label" for="datenschutz">
                    <a href="../pages/privacypolicy.html" target="_blank">Datenschutzerklärung (*)</a>
                </label>
            </div>

                <br>
		<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <button type="submit" class="btn" id="submit-button">Absenden</button>
                <button type="reset" class="btn" id="reset-button">Abbrechen</button>
        </form>
		<div id="loading" style="display: none;">
		    Nachricht wird gesendet...
		</div>

		<div id="response" style="display: none;"></div>
    	</div>


	<footer>
            <p>
                <a href="../pages/about.html">Impressum</a>
                | <a href="../pages/privacypolicy.html">Datenschutz</a>
                <br>
                <a href="mailto:kontakt@wirbeobachtenzurueck.de">kontakt@wirbeobachtenzurueck.de</a>
            </p>
            <p>© 2024 wirbeobachtenzurueck.de<br><span class="footer-credit">with <span class="heart">❤</span> by Ducky &amp; Loui</span></p>
        </footer>

</body>
</html>
