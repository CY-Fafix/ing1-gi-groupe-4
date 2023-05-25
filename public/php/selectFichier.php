<!DOCTYPE html>
<html>
<body>

<form enctype="multipart/form-data" action="analyseFichier.php" method="POST">
    <input type="hidden" name="MAX_FILE_SIZE" value="50000" />
    Choisir le fichier: <input name="userfile" type="file" />
    <input type="submit" value="Analyser le fichier" />
</form>

</body>
</html>
