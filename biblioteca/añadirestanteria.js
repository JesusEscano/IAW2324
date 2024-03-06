let estanterias = [];

function añadirestanteria(estanteria){
    let table = $("#tablaarmario tbody");
    table.append(`<tr id="${estanteria.id}">
    <td>${estanteria.numero}</td>
    <td>${estanteria.seccion}</td>
    <td>${estanteria.estantes}</td>
    <td>
    <button class="mb-1 btn btn-sm btn-warning editbtn" data-target="#editModal" data-toggle="modal" data-id="${estanteria.id}">Editar</button>
    <button class="mb-1 btn btn-sm btn-danger deletebtn">Borrar</button>
    </td>
    </tr>`);
}

function limpiarformulario() {  
    $("#numero").val("");
    $("#seccion").val("");
    $("#estantes").val("");
    console.log("Formulario limpiado.");
}

function generarId() {  
    return Math.floor(Math.random() * 1000000)
}

$(document).ready(function() {
    $("#armario").submit(function(e){
        e.preventDefault();

        let estanteria = {
            id: generarId(),
            numero: $("#numero").val(),
            seccion: $("#seccion").val(),
            estantes: $("#estantes").val()
        }

        estanterias.push(estanteria);
        añadirestanteria(estanteria);

        limpiarformulario();
    });
    
    $(document).on("click", "#limpiararmariobtn", function(){
        limpiarformulario();
    });

    $("#editForm").submit(function(e){
        e.preventDefault();

        let estanteriaId = $("#editarArmarioId").val();
        let estanteriaIndex = estanterias.findIndex((estanteria)=>estanteria.id == estanteriaId);
        let estanteria = estanterias[estanteriaIndex];

        estanteria.numero = $("#numeroEditar").val();
        estanteria.seccion = $("#seccionEditar").val();
        estanteria.estantes = $("#estantesEditar").val();

        let row = $(`#${estanteria.id}`);
        row.find("td:eq(0)").text(estanteria.numero);
        row.find("td:eq(1)").text(estanteria.seccion);
        row.find("td:eq(2)").text(estanteria.estantes);

        $("#editModal").modal("hide");
    });

    $(document).on("click", ".editbtn", function(){
        let estanteriaId = $(this).data("id");

        let estanteriaIndex = estanterias.findIndex((estanteria)=>estanteria.id == estanteriaId);
        let estanteria = estanterias[estanteriaIndex];

        $("#numeroEditar").val(estanteria.numero);
        $("#seccionEditar").val(estanteria.seccion);
        $("#estantesEditar").val(estanteria.estantes);
        $("#editarArmarioId").val(estanteria.id);

        $("#editModal").modal("show");
    });
});

$(document).on("click", ".deletebtn", function() {
    let row = $(this).closest("tr");
    let estanteriaId = row.attr("id");
    let estanteriaIndex = estanterias.findIndex((estanteria) => estanteria.id == estanteriaId);
    estanterias.splice(estanteriaIndex, 1);
    row.remove();
});