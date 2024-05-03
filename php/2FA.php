<!DOCTYPE html>

<html>
    <head>
        <title>2FA</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
        <script src="../js/2FA.js"></script>
        <link rel="stylesheet" href="../css/style.css">

    </head>
    <body>
        <div class="form" id="form">
        <h2 style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;">Autentificazione a due Fattori</br>
        Inserisci i token che ti sono stati inviati:</h2>
        <input type="text" id="codice" placeholder="Inserisci il tuo Token" name="codice" required>   
        <input type="text" id="codice2" placeholder="Inserisci il tuo Token" name="codice2" required>
        <input type="text" id="codice3" placeholder="Inserisci il tuo Token" name="codice3" required>
        <input type="text" id="codice4" placeholder="Inserisci il tuo Token" name="codice4" required>
        <button class="button-blu" type="submit" id="verifica">Verifica</button>
        </div>
    </body>
</html>