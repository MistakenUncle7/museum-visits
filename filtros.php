<?php

// Simulacion del retorno del un arreglo al consultar la base de datos
$resultSQL = [
    ["ID"=>"1", "Nombre"=>"Opcion 1"],
    ["ID"=>"1", "Nombre"=>"Opcion 2"],
    ["ID"=>"1", "Nombre"=>"Opcion 3"],
    ["ID"=>"1", "Nombre"=>"Opcion 4"]];

?>

<!-- Impresion de las opciones de los filtros en el select -->
<select id="patito" key="opcion" onchange="agregarFiltro()">
    <option value="">--Seleccione una opcion--</option>
    <?php
        for($i = 0; $i < count($resultSQL); $i++) {
            echo "<option value='".$resultSQL[$i]['ID']."'>".$resultSQL[$i]['Nombre']."</option>";
        }
    ?>
</select>

<div>
    <table>
        <thead>
            <tr>
                <th>Filtro</th>
                <th>Valores</th>
            </tr>
        </thead>
        <tbody id="resumen-filtros">

        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    let filtros = {};
    console.log(filtros);
    function agregarFiltro() {
        let f = $('#patito');
        filtros[f.attr('key')] = filtros[f.attr('key')] || [];
        /* if(!(f.attr('key') in filtros)) {
            filtros[f.attr('key')] = [];
        } */
        let text = $('#patito option:selected').text();
        filtros[f.attr('key')].push([f.val(), text]);
        
        renderFiltro();
    }

    function renderFiltro() {
        html = "";
        Object.keys(filtros).forEach(fs => {
            if (filtros[fs].length > 0) {
                fhtml = "<tr><td>"+fs+"</tr></td>";
                buttons = [];
                i = 0;
                filtros[fs].forEach(f => {
                    buttons.push("<button onclick=\"quitarFiltro('"+fs+"',"+i+")\">"+f[1]+" (X)</button>");
                    i++;
                });
                fhtml += buttons.join("</td>/td>");
                fhtml += "</td></tr>";
            }
            html += fhtml;
        });
        $('#resumen-filtros').html(html)
    }

    function quitarFiltro(k, i) {
        filtros[k].splice(i, 1);
        renderFiltro();
    }
</script>