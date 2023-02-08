@include("layouts.app")
@include("informes_gestion.modals.buscar-judicatura-modal")
@include("informes_gestion.modals.buscar-funcionario-modal")

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@php
    $meses = ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
    $nombramientos = ['PROPIETARIO', 'PROPIETARIO EN REGIMEN DE DISPONIBILIDAD', 'INTERINO', 'SUPLENTE'];
@endphp
<br>
<div class="col-sm-8 d-flex justify-content-center " style=" margin: auto; padding: 30px;
        
        background-color: #0055FF;
        color: white;
        border-radius: 10px;
        ">
        
    <form method="post" action="{{ url('guardarIG') }}">
    @csrf
    <div class="mb-3 row ">
        <h4 style="text-align: center;"> INGRESO DE INFORMES DE GESTION</h4>
    </div>
    <div class="mb-3 row">
        <label for="tribunal" class="col-sm-2 col-form-label">Tribunal</label>
        <div class="col-sm-8">
            <input type="hidden" name="tribunal" id="tribunal" value="{{ old('tribunal', '') }}">
            <input type="textbox" class="form-control-plaintext" id="nombre_tribunal" name="nombre_tribunal" value="{{ old('nombre_tribunal') }}" style="color:white;">
        </div>

        <div class="col-sm-2">
            <a class="btn btn-outline-light" id="btn-buscar-tribunal">Buscar</a>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="" class="form-label col-sm-2">Funcionario</label>
        <div class="col-sm-8">
            <input type="text" class="form-control-plaintext" id="nombre_funcionario" name="nombre_funcionario" value="{{ old('nombre_funcionario','') }}" style="color:white;">
            <input type="hidden" name="funcionario" id="funcionario" value="{{ old('funcionario', '') }}">
        </div>

        <div class="col-sm-2">
            <a class="btn btn-outline-light" id="btn-buscar-funcionario">Buscar</a>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="" class="form-label col-sm-2">Nombramiento</label>
        <div class="col-sm-10">
            <select name="nombramiento" id="" class="form-select" value="{{ old('nombramiento') }}">
            @foreach($nombramientos as $nombramiento)
                    <option value="{{$nombramiento}}"  @if (old("nombramiento") == $nombramiento ) {{ 'selected' }} @endif>{{$nombramiento}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="fecha_recepcion" class="form-label col-sm-2">Fecha de Recepción:</label>
        <div class="col-sm-4">
            <input type="date" class="form-control" id="fecha_recepcion" name="fecha_recepcion" value="{{ old('fecha_recepcion') }}">
        </div>
        <label for="mes_evaluado" class="form-label col-sm-2">Mes Evaluado</label>
        <div class="col-sm-4">
            <select name="mes_evaluado" id="" class="form-select"s>
                @foreach($meses as $mes)
                    <option value="{{$mes}}"  @if (old("mes_evaluado") == $mes ) {{ 'selected' }} @endif>{{$mes}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="" class="form-label col-sm-4">En tramite a inicio de mes:</label>
        <div class="col-sm-2">
            <input type="number" class="form-control" id="" name="inicio_mes" value="{{ old('inicio_mes') }}">
        </div>
        <label for="" class="form-label col-sm-4">En tramite a final de mes:</label>
        <div class="col-sm-2">
            <input type="number" class="form-control" id="" name="final_mes" value="{{ old('final_mes') }}">
        </div>
    </div>

    

    <div class="form-check form-switch">
        <input type="checkbox" class="form-check-input" name="en_tiempo" id="en_tiempo" checked value="EN TIEMPO">
        <label class="form-check-label" for="en_tiempo">En tiempo</label>
    </div>
    <input type="submit" value="Enviar"> 

    
    </form>
</div>

<hr>

<table class="table table-bordered">
<thead>
    <tr>
        <td>Nombre Tribunal</td>
        <td>Funcionario</td>
        <td>Mes Evaluado</td>
        <td>Fecha Recepcion</td>
        <td>Tramite Inicio</td>
        <td>Tramite Fin</td>
        <td>Cumplio</td>
        <td>Tipo</td>
        <td>Departamento</td>
    </tr>
</thead>

@foreach($informes as $informe)
    <tr>
        <td>{{$informe->nombre_judicatura}}</td>
        <td>{{$informe->nombres}} {{$informe->apellidos}}</td>
        <td>{{$informe->mes_evaluado}}</td>
        <td>{{ $informe->fecha_recepcion }}</td>
        <td>{{$informe->tramite_inicio}}</td>
        <td>{{$informe->tramite_fin}}</td>
        <td>{{$informe->cumplio}}</td>
        <td>{{$informe->tipo}}</td>
        <td>{{$informe->departamento}}</td>

    </tr>
@endforeach
</table>


<script>
    var myModalTribunal = new bootstrap.Modal(document.getElementById('buscar-judicatura-modal'), {})
    var myModalFuncionario = new bootstrap.Modal(document.getElementById('buscar-funcionario-modal'), {})

    document.getElementById("btn-buscar-tribunal").addEventListener("click", function(){
        myModalTribunal.show()
        document.getElementById("mostrarJudicaturas").innerHTML = "";
        document.getElementById("buscandoJudicatura").value = "";
    })

    document.getElementById("btn-buscar-funcionario").addEventListener("click", function(){
        myModalFuncionario.show()
        document.getElementById("mostrarFuncionarios").innerHTML = "";
        document.getElementById("buscandoFuncionario").value = "";
    })

    document.getElementById("buscandoJudicatura").addEventListener("keyup", function(){
        if(event.keyCode === 13){
            document.getElementById("mostrarJudicaturas").innerHTML = "";
            fetch("http://127.0.0.1/ute/public/buscar-judicatura" + "/" + document.getElementById("buscandoJudicatura").value, {
                    method: 'GET',
                    headers: {
                        "Content-Type": "application/json",
                        'Access-Control-Allow-Origin': '*'
                    }
            })
            .then(response => response.json())
            .then(function(dato){
                dato.forEach(element => {
                    document.getElementById("mostrarJudicaturas").innerHTML += "<tr><td class='id_tribunal' id='" + element.id_judicatura + "'>" + element.nombre_judicatura + ", " + element.departamento + "</td></tr>"
                })
                document.querySelectorAll(".id_tribunal").forEach(el => {
                    el.addEventListener("click", e => {
                        const id = e.target.getAttribute("id");
                        document.getElementById("nombre_tribunal").value = e.target.innerHTML
                        document.getElementById("tribunal").value = e.target.getAttribute("id")
                        myModalTribunal.hide()
                    });
                });
            })

        }
    })

    document.getElementById("buscandoFuncionario").addEventListener("keyup", function(){
        if(event.keyCode === 13){
            
            document.getElementById("mostrarFuncionarios").innerHTML = "";
            fetch("http://127.0.0.1/ute/public/buscar-funcionario" + "/" + document.getElementById("buscandoFuncionario").value, {
                    method: 'GET',
                    headers: {
                        "Content-Type": "application/json",
                        'Access-Control-Allow-Origin': '*'
                    }
            })
            .then(response => response.json())
            .then(function(dato){
                console.log("ok")
                dato.forEach(element => {
                    document.getElementById("mostrarFuncionarios").innerHTML += "<tr><td class='id_funcionario' id='" + element.id_usuario + "'>" + element.nombres + " " + element.apellidos + "</td></tr>"
                })
                document.querySelectorAll(".id_funcionario").forEach(el => {
                    el.addEventListener("click", e => {
                        const id = e.target.getAttribute("id");
                        document.getElementById("nombre_funcionario").value = e.target.innerHTML
                        document.getElementById("funcionario").value = id
                        myModalFuncionario.hide()
                    });
                });
            })

        }
    })
    
</script>