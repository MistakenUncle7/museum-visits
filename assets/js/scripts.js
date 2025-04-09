
// Function to clear the form when pressing the button, it will ask for confirmation
function clearForm() {
    if (confirm("¿Está seguro de que desea limpiar el formulario?")) {
        // Reset the form fields
        document.getElementById("myForm").reset();

        // Clear date filters
        document.getElementById("start-date").innerText = "Sin seleccionar fecha";
        document.getElementById("end-date").innerText = "Sin seleccionar fecha";

        // Empty the object selectedFilters
        Object.keys(selectedFilters).forEach(key => delete selectedFilters[key]);

        // Delete the filters from the table
        document.querySelectorAll("button.btn.bt-sm.m-1.btn-success").forEach((btn) => {
            btn.parentNode.removeChild(btn);
        })
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

// Function to create the WHERE conditions for the query
function queryConditions(dict) {
    let whereConditions = [];
    Object.keys(dict).forEach((key) => {
        switch (key) {
            case "Inicio":
                whereConditions.push("visitas.fecha_reg > '"+selectedFilters[key]+"'")
                break;
            case "Fin":
                whereConditions.push("fecha_reg < '"+selectedFilters[key]+"'")
                break;
            case "Motivos":
                whereConditions.push("m.Motivo in ('"+selectedFilters[key].join("','")+"')");
                break;
            case "Nacionalidad":
                whereConditions.push("p2.Gentilicio in ('"+selectedFilters[key].join("','")+"')");
                break;
            case "Residencia":
                whereConditions.push("p1.Nombre in ('"+selectedFilters[key].join("','")+"')");
                break;
            case "Lenguas":
                whereConditions.push("l1.Nombre in ('"+selectedFilters[key].join("','")+"')");
                break;
            case "Frecuencia":
                whereConditions.push("f.Rango in ('"+selectedFilters[key].join("','")+"')");
                break;
            case "Estudios":
                whereConditions.push("e.Grado in ('"+selectedFilters[key].join("','")+"')");
                break;
        }
    });
    if (whereConditions.length === 0){
        return "";
    }
    whereConditions = " WHERE "+whereConditions.join(" AND ");

    console.log(whereConditions);

    return whereConditions;
}

function makeQuery() {
    // Get the start and end dates
    const startDate = document.getElementById("start-date").innerText;
    const endDate = document.getElementById("end-date").innerText;

    // Turns the date filters into an Array and adds them to "selectedFilters"
    if (startDate !== "Sin seleccionar fecha") {
        let arrayDate = [startDate];
        selectedFilters["Inicio"] = arrayDate;
    }
    if (endDate !== "Sin seleccionar fecha") {
        let arrayDate = [endDate]
        selectedFilters["Fin"] = arrayDate;
    }

    console.log("Filters for query:", selectedFilters);

    whereConditions = queryConditions(selectedFilters);

    updateTable(whereConditions);

    updateStats(whereConditions);

    /* // Send the filters to the server
    fetch("assets/php/update_filters.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(whereConditions) // We send the string that includes all of the WHERE conditions
    })
    .then((response) => response.json())
    .then((data) => {
        
        // Update the stats section with the response
        updateStats(data);
        
        // Update the table with the data
        updateTable(data); //[{'sexo':'F','nacionalidad':2,'escolaridad':1},{'sexo':'M','nacionalidad':15,'escolaridad':4}]

        let resultados = {'nacionales':0,'extranjeros':0,'total_visitas':0,'lengua_mas_hablada':{},'motivo_mas_frecuente':{}};
        data.forEach((row) => {
            //{'sexo':'F','nacionalidad':2,'escolaridad':1}
            resultados[row.lengua]++; //-> {'español':0,'ingles':15,'frances':7}
        });
    })
    .catch((error) => console.error("Error:", error)); */
}

// Function to update the stats section
function updateStats(query) {

    // Send the filters to the server
    fetch("assets/php/update_filters.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(query)
    })
    .then((response) => response.json())
    .then((data) => {

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

    })
    .catch((error) => console.error("Error:", error));

}

function updateTable(query) {
    // Send the filters to the server
    fetch("assets/php/update_table.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(query)
    })
    .then((response) => response.json())
    .then((data) => {

        const table = document.getElementById('country-table');
        table.innerHTML = ""; // Clear existing rows

        // Populate the table with the data
        data.forEach((row) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${row.sexo || "N/A"}</td>
                <td>${row.edad || "N/A"}</td>
                <td>${row.residencia || "N/A"}</td>
                <td>${row.nacionalidad || "N/A"}</td>
                <td>${row.escolaridad || "N/A"}</td>
                <td>${row.estado_escolar || "N/A"}</td>
                <td>${row.primera_leng || "N/A"}</td>
                <td>${row.segunda_leng || "N/A"}</td>
                <td>${row.frecuencia_visita || "N/A"}</td>
                <td>${row.motivo || "N/A"}</td>
                <td>${row.medio_transporte || "N/A"}</td>
                <td>${row.tiempo_traslado || "N/A"}</td>
                <td>${row.tipo_grupo || "N/A"}</td>
                <td>${row.tamano_grupo || "N/A"}</td>
                <td>${row.menores_grupo || "N/A"}</td>
            `;
            table.appendChild(tr);
        });


    })
    .catch((error) => console.error("Error:", error));
}
