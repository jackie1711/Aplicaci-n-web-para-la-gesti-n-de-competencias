<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listas</title>
</head>
<body>
    <h1>LISTAS DE DOCENTES</h1>
    <hr>

    <table border="1">
        <thead>
            <th>ID</th>
            <th>Especialidad</th>
            <th>Materia Impartida</th>
            <th>Acciones</th>

        </thead>
        <tbody>
            <?php
                while($row = $docentes -> fetch_assoc()){
               
            ?>
                <!-- Zona de html-->
                <tr>
                    <!-- crea la tabla-->
                    <td><?php echo $row['idDocentes']; ?></td>
                    <td><?php echo $row['Especialidad']; ?></td>
                    <td><?php echo $row['MateriaImpartida']; ?></td>
                    <td>
                        <a href="index.php?controller=docente&action=update&id=<?php echo $row['idDocentes']; ?>">
                            <button>Editar</button>
                        </a>
                        <a href="index.php?controller=docente&action=delete&id=<?php echo $row['idDocentes']; ?>" 
                           onclick="return confirm('¿Estás seguro de eliminar este docente?')">
                            <button>Eliminar</button>
                        </a>
                    </td>

                </tr>
            <?php    
                } 
            ?>
        </tbody>
    </table>
    <br><br>
    <a href="index.php?controller=docente&action=insert">
    <button>Regresar a registro de docentes</button>
    </a>

</body>
</html>