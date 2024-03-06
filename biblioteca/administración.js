let libros = [];

function añadirlibro(libro){
    let table = $("#tablalibro tbody");
    table.append(`<tr id="${libro.id}">
    <td>${libro.titulo}</td>
    <td>${libro.autor}</td>
    <td>${libro.editorial}</td>
    <td>${libro.año}</td>
    <td>${libro.paginas}</td>
    <td>${libro.unidades}</td>
    <td>${libro.sinopsis}</td>
    <td>
    <button class="mb-1 btn btn-sm btn-warning editbtn" data-target="#editModal" data-toggle="modal" data-id="${libro.id}">Editar</button>
    <button class="mb-1 btn btn-sm btn-danger deletebtn">Borrar</button>
    </td>
    </tr>`);
}

function limpiarformulario() {  
    $("#titulo").val("");
    $("#autor").val("");
    $("#editorial").val("");
    $("#añop").val("");
    $("#paginas").val("");
    $("#unidades").val("");
    $("#sinopsis").val("");
    console.log("Formulario limpiado.");
}

function generarId() {  
    return Math.floor(Math.random() * 1000000)
}

$(document).ready(function() {
    $("#libros").submit(function(e){
        e.preventDefault();

        let libro = {
            id: generarId(),
            titulo: $("#titulo").val(),
            autor: $("#autor").val(),
            editorial: $("#editorial").val(),
            año: $("#añop").val(),
            paginas: $("#paginas").val(),
            unidades: $("#unidades").val(),
            sinopsis: $("#sinopsis").val(),
        }

        libros.push(libro);
        añadirlibro(libro);

        limpiarformulario();
    });
    
    $(document).on("click", "#limpiabtn", function(){
        limpiarformulario();
    });

    $("#editForm").submit(function(e){
        e.preventDefault();

        let libroId = $("#editarLibroId").val();
        let libroIndex = libros.findIndex((libro)=>libro.id == libroId);
        let libro = libros[libroIndex];

        libro.titulo = $("#tituloEditar").val();
        libro.autor = $("#autorEditar").val();
        libro.año = $("#añopEditar").val();
        libro.editorial = $("#editorialEditar").val();
        libro.paginas = $("#paginasEditar").val();
        libro.unidades = $("#unidadesEditar").val();
        libro.sinopsis = $("#sinopsisEditar").val();

        let row = $(`#${libro.id}`);
        row.find("td:eq(0)").text(libro.titulo);
        row.find("td:eq(1)").text(libro.autor);
        row.find("td:eq(2)").text(libro.editorial);
        row.find("td:eq(3)").text(libro.año);
        row.find("td:eq(4)").text(libro.paginas);
        row.find("td:eq(5)").text(libro.unidades);
        row.find("td:eq(6)").text(libro.sinopsis);

        $("#editModal").modal("hide");
    });

    $(document).on("click", ".editbtn", function(){
        let libroId = $(this).data("id");

        let libroIndex = libros.findIndex((libro)=>libro.id == libroId);
        let libro = libros[libroIndex];

        $("#tituloEditar").val(libro.titulo);
        $("#autorEditar").val(libro.autor);
        $("#añopEditar").val(libro.año);
        $("#editorialEditar").val(libro.editorial);
        $("#paginasEditar").val(libro.paginas);
        $("#unidadesEditar").val(libro.unidades);
        $("#sinopsisEditar").val(libro.sinopsis);
        $("#editarLibroId").val(libro.id);

        $("#editModal").modal("show");
    });
});

$(document).on("click", ".deletebtn", function() {
    let row = $(this).closest("tr");
    let libroId = row.attr("id");
    let libroIndex = libros.findIndex((libro) => libro.id == libroId);
    libros.splice(libroIndex, 1);
    row.remove();
});