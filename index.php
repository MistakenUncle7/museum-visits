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
      <form class="row">
        <div class="col-3">
          <label for="start">Inicio:</label>
          <input type="date" class="form-control" id="start" placeholder="dd/mm/aaaa">
        </div>
  
        <div class="col-3">
          <label for="end">Fin:</label>
          <input type="date" class="form-control" id="end" placeholder="dd/mm/aaaa">
        </div>
  
        <div class="col-3">
          <label for="motives">Motivos:</label>
          <select id="motives" class="form-control" required>
            <option value="" disabled selected>Añadir Motivo</option>
          </select>
        </div>
  
        <div class="col-3">
          <label for="nationality">Nacionalidad:</label>
          <select id="nationality" class="form-control" required>
           <option value="" disabled selected>Añadir País</option>
          </select>
        </div>
    
        <div class="col-3">
          <label for="residence">Residencia:</label>
          <select id="residence" class="form-control" required>
            <option value="" disabled selected>Añadir País</option>
          </select>
        </div>
  
        <div class="col-3">
          <label for="language">Lenguas:</label>
          <select id="language" class="form-control" required>
            <option value="" disabled selected>Añadir Idiomas</option>
          </select>
        </div>
  
        <div class="col-3">
          <label for="freq">Frecuencia:</label>
          <select id="freq" class="form-control" required>
            <option value="" disabled selected>Añadir Frecuencia</option>
          </select>
        </div>
  
        <div class="col-3">
          <label for="studies">Estudios:</label>
          <select id="studies" class="form-control" required>
            <option value="" disabled selected>Añadir Estudios</option>
          </select>
        </div>

        <div class="col-3 mt-3">
          <button type="button" class="btn btn-primary">Buscar</button>
          <button type="button" class="btn btn-secondary">Limpiar</button>
        </div>
      </form>
    </div>

    <div class="container m-3 p-3 bigbox filters">
      
    </div>

    <div class="container text-center m-3 p-3 bigbox stats">
      <div class="row">
        <div class="col">
          Visitas Totales
        </div>

        <div class="col">
          Visitas Nacionales
        </div>

        <div class="col">
          Visitas Extranjeros
        </div>

        <div class="col">
          Lengua más hablada
        </div>

        <div class="col-3">
          Motivo
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>