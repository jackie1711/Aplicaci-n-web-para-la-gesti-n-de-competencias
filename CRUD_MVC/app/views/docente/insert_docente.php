<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Docentes</title>
</head>
<body>
    <h1>Registro de Docentes</h1>
    <hr>

    <form action="index.php?controller=docente&action=insert" method="POST">

        <b><label for="Especialidad"> Especialidad: </label></b>
        <input type="text" name = "Especialidad" placeholder = "Especialidad">
        <br><br>

        <b><label for="MateriaImpartida"> Materia Impartida: </label></b>
        <input type="text" name = "MateriaImpartida" placeholder = "MateriaImpartida">
        <br><br>

        <input type="submit" name = "enviar" value = "Enviar">
    </form>

    <br><br>
    <a href="index.php?controller=docente&action=consult">
    <button>Mostrar lista de docentes</button>
    </a>
</body>
</html>