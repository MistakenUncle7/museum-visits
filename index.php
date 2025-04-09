<?php include 'assets/php/sql.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Museo</title>
  <link rel="icon" href="assets/images/museum.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
  <div class="wrapper">
    <div class="container m-3 p-3 bigbox">
      <h1>Visitas a Museos</h1><hr>
      <form id="myForm" class="row">
        <div class="col-3">
          <label for="start">Inicio:</label>
          <input type="date" class="form-control" id="start" name="start" placeholder="dd/mm/aaaa" onchange="updateDate(this.value, 'start-date')">
        </div>
  
        <div class="col-3">
          <label for="end">Fin:</label>
          <input type="date" class="form-control" id="end" name="end" placeholder="dd/mm/aaaa" onchange="updateDate(this.value, 'end-date')">
        </div>
  
        <div class="col-3">
          <label for="motives">Motivos:</label>
          <select id="motives" name="motives" class="form-control" required onchange="addFilter('motives', 'Motivos')">
            <option value="" disabled selected>Seleccione Motivo</option>
            <?php
            $sql = "SELECT DISTINCT Motivo FROM `motivos`";
            $result = $conn->query($sql);
            getOptions($result, "Motivo");
            ?>
          </select>
        </div>
  
        <div class="col-3">
          <label for="nationality">Nacionalidad:</label>
          <select id="nationality" name="nationality" class="form-control" required onchange="addFilter('nationality', 'Nacionalidad')">
           <option value="" disabled selected>Seleccione País</option>
           <?php
            $sql = "SELECT DISTINCT Nombre FROM `pais`";
            $result = $conn->query($sql);
            getOptions($result, "Nombre");
            ?>
          </select>
        </div>
    
        <div class="col-3">
          <label for="residence">Residencia:</label>
          <select id="residence" name="residence" class="form-control" required onchange="addFilter('residence', 'Residencia')">
            <option value="" disabled selected>Seleccione País</option>
            <?php
            $sql = "SELECT DISTINCT Nombre FROM `pais`";
            $result = $conn->query($sql);
            getOptions($result, "Nombre");
            ?>
          </select>
        </div>
  
        <div class="col-3">
          <label for="language">Lenguas:</label>
          <select id="language" name="language" class="form-control" required onchange="addFilter('language', 'Lenguas')">
            <option value="" disabled selected>Seleccione Idiomas</option>
            <?php
            $sql = "SELECT DISTINCT Nombre FROM `lenguaje`";
            $result = $conn->query($sql);
            getOptions($result, "Nombre");
            ?>
          </select>
        </div>
  
        <div class="col-3">
          <label for="freq">Frecuencia:</label>
          <select id="freq" name="freq" class="form-control" required onchange="addFilter('freq', 'Frecuencia')">
            <option value="" disabled selected>Seleccione Frecuencia</option>
            <?php
            $sql = "SELECT DISTINCT Rango FROM `frec_visita`";
            $result = $conn->query($sql);
            getOptions($result, "Rango");
            ?>
          </select>
        </div>
  
        <div class="col-3">
          <label for="studies">Estudios:</label>
          <select id="studies" name="studies" class="form-control" required onchange="addFilter('studies', 'Estudios')">
            <option value="" disabled selected>Seleccione Estudios</option>
            <?php
            $sql = "SELECT DISTINCT Grado FROM `escolaridad`";
            $result = $conn->query($sql);
            getOptions($result, "Grado");
            ?>
          </select>
        </div>

        <div class=" mt-3 btns">
          <button type="button" class="btn btn-secondary" onclick="clearForm()">Limpiar</button>
          <button type="button" class="btn btn-primary" onclick="makeQuery()">Buscar</button>
        </div>
      </form>
    </div>

    <div class="container m-3 p-3 filters">
      <table class="table table-borderless">
        <thead>
          <tr>
            <th class="col-small">Filtros</th>
            <th class="col-large">Valores</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Inicio: </td>
            <td id="start-date">Sin seleccionar fecha</td>
          </tr>
          <tr>
            <td>Fin: </td>
            <td id="end-date">Sin seleccionar fecha</td>
          </tr>
          <tr>
            <td>Motivos:</td>
            <td data-filter="Motivos"></td>
          </tr>
          <tr>
            <td>Nacionalidad:</td>
            <td data-filter="Nacionalidad"></td>
          </tr>
          <tr>
            <td>Residencia:</td>
            <td data-filter="Residencia"></td>
          </tr>
          <tr>
            <td>Lenguas:</td>
            <td data-filter="Lenguas"></td>
          </tr>
          <tr>
            <td>Frecuencia:</td>
            <td data-filter="Frecuencia"></td>
          </tr>
          <tr>
            <td>Estudios:</td>
            <td data-filter="Estudios"></td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="container text-center m-3 p-3 stats">
      <div class="row">
        <div class="col">
          <div>Visitas Totales</div>
          <div></div>
        </div>

        <div class="col">
          <div>Visitas Nacionales</div>
          <div></div>
        </div>

        <div class="col">
          <div>Visitas Extranjeros</div>
          <div></div>
        </div>

        <div class="col">
          <div>Lengua más hablada</div>
          <div></div>
        </div>

        <div class="col-3">
          <div>Motivo</div>
          <div></div>
        </div>
      </div>
    </div>

    <div class="container m-3 p-3 bigbox">
      <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" data-bs-toggle="tab" href="#visits">Visitas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="tab" href="#countries">País</a>
        </li>
      </ul>

      <div class="tab-content">
        <div class="container tab-pane active" id="visits">
          <table class="table table-hover">
            <thead>
            </thead>
            <tbody>
              
            </tbody>
          </table>
        </div>
        <div class="container tab-pane fade" id="countries">
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="assets/js/scripts.js"></script>
</body>
</html>