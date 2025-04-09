
// Function to clear the form when pressing the button, it will ask for confirmation
function clearForm() {
    if (confirm("¿Está seguro de que desea limpiar el formulario?")) {
        // Reset the form fields
        document.getElementById("myForm").reset();

        // Clear date filters
        document.getElementById("start-date").innerText = "Sin seleccionar fecha";
        document.getElementById("end-date").innerText = "Sin seleccionar fecha";
      }
}

// Function to update the date in the filters table
function updateDate(date, id) {
    // Get the target <td> element
    const targetTd = document.getElementById(id);

    // Update the <td> with the selected date
    targetTd.innerText = date || "Sin seleccionar fecha";
}

// Object to store selected filters for the query
const selectedFilters = {};

// Function to add a filter button to the table filters
function addFilter(selectId, filterName) {
    // Get the selected value from the <select> element
    const selectedElement = document.getElementById(selectId);
    const selectedValue = selectedElement.value;

    // Get the corresponding <td> element in the table
    const tableTd = document.querySelector(`td[data-filter="${filterName}"]`);

    // Check if the value is already added
    if (selectedFilters[filterName]?.includes(selectedValue)) {
        alert("Este filtro ya ha sido agregado.");
        return;
    }

    // Add the value to the selectedFilters object
    selectedFilters[filterName] = selectedFilters[filterName] || [];
    selectedFilters[filterName].push(selectedValue);

    // Create a button for the selected value
    const button = document.createElement("button");
    button.className = "btn bt-sm m-1 btn-success";
    button.innerText = selectedValue;

    // Add an event listener to remove the button and update the query
    button.addEventListener("click", () => {
        // Remove the button from the table
        button.remove();

        // Remove the value from the selectedFilters object
        const index = selectedFilters[filterName].indexOf(selectedValue);
        if (index > -1) {
            selectedFilters[filterName].splice(index, 1);
        }

        // If no more values exist for the filter, delete the key
        if (selectedFilters[filterName].length === 0) {
            delete selectedFilters[filterName];
        }

        console.log("Updated filters:", selectedFilters);
    });

    // Append the button to the <td>
    tableTd.appendChild(button);

    console.log(selectedFilters);
}

function makeQuery() {
    // Get the start and end dates
    const startDate = document.getElementById("start-date").innerText;
    const endDate = document.getElementById("end-date").innerText;

    // Makes a copy of the selected filters and adds the date filters
    const filters = {...selectedFilters };
    if (startDate !== "Sin seleccionar fecha") {
        filters["start"] = startDate;
    }
    if (endDate !== "Sin seleccionar fecha") {
        filters["end"] = endDate;
    }

    console.log("Filters for query:", filters);

    let filtros = [];
    Object.keys(selectedFilters).forEach((f) => {
        switch(f){
            case "Motivos":
                filtros.push("motivos.Motivo in ('"+selectedFilters[f].join("','")+"')");
            break;
            case "Frecuencia":
                filtros.push("frec_visita.Nombre in ('"+selectedFilters[f].join("','")+"')");
            break;

        }
    });
    filtros = " where "+filtros.join(" and ");

    console.log(filtros);0

    // Send the filters to the server
    fetch("assets/php/process_form.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(filtros)
    })
    .then((response) => response.json())
    .then((data) => {
        
        // Update the table with the data
        updateTable(data); //[{'sexo':'F','nacionalidad':2,'escolaridad':1},{'sexo':'M','nacionalidad':15,'escolaridad':4}]

        let resultados = {'nacionales':0,'extranjeros':0,'total_visitas':0,'lengua_mas_hablada':{},'motivo_mas_frecuente':{}};
        data.forEach((row) => {
            //{'sexo':'F','nacionalidad':2,'escolaridad':1}
            resultados[row.lengua]++; //-> {'español':0,'ingles':15,'frances':7}
        });

        // Update the stats section with the response
        updateStats(data);
    })
    .catch((error) => console.error("Error:", error));
}

// Function to update the stats section
function updateStats(data) {
    const statsContainer = document.querySelector(".stats .row");

    // Update the stats dynamically
    statsContainer.innerHTML = `
        <div class="col">
          <div>Visitas Totales</div>
          <div>${data.total_visits || 0}</div>
        </div>
        <div class="col">
          <div>Visitas Nacionales</div>
          <div>${data.national_visits || 0}</div>
        </div>
        <div class="col">
          <div>Visitas Extranjeros</div>
          <div>${data.foreign_visits || 0}</div>
        </div>
        <div class="col">
          <div>Lengua más hablada</div>
          <div>${data.most_spoken_language || "N/A"}</div>
        </div>
        <div class="col-3">
          <div>Motivo</div>
          <div>${data.most_frequent_reason || "N/A"}</div>
        </div>
    `;
}

function updateTable(data) {
    
}