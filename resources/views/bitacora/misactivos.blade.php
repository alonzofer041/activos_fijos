@extends('admin3.app')
@section('estilos')
.contenedores{
    margin-bottom:10px;
    border-left:5px solid #0099FF;
}
.encabezados{
    padding-bottom:20px;
}
.marca{
    background-color:#0099FF;
    color:#FFF;
}
.marca:hover{
    color:#FFF;
}
@endsection
@section('contenido')
<div id="misactivos" class="container">
    <div class="row">
        <div class="col-xs-12 col-md-4">
            <div class="bg-white pd-30 box-shadow border-radius-5 xs-pd-20-10 height-100-p">
                <h4 class="text-blue encabezados">Mis resguardos.</h4> 
                <h5 class="text-blue encabezados" v-if="activos_filtrados">Click en el resguardo para ver mis activos</h5>         
                <form action="" v-for="resguardo in resguardos">
                <a href="#" 
                class="contenedores lt-item list-group-item flex-column align-items-start"
                v-on:click="consultar(resguardo.no_resguardo)" v-bind:class="{marca:no_resguardo==resguardo.no_resguardo}">
                    <div class="d-flex w-100 justify-content-between">
                        <h5>#@{{resguardo.no_resguardo}}</h5>
                    </div>
                    <p class="mb-1">
                        @{{resguardo.nomusuario}} @{{resguardo.apellidos}}
                        <span href="#" class="print" :data-k="resguardo.idresguardo" style="font-size:18px"><i class="icon-copy fa fa-print" aria-hidden="true"></i></span>
                    </p>
                </a>
                <br>
                </form>
            </div>
        </div>
        <form action="{{action('ResguardoController@print')}}" target="blank" name="frm_print" method="POST">
{{csrf_field()}}
<input type="hidden" id="cvdfs" name="cvdfs">				
</form>
        <div class="col-xs-12 col-md-8">
            <div class="bg-white pd-30 box-shadow border-radius-5 xs-pd-20-10 height-100-p">
                <h4 class="text-blue encabezados">Mis activos.</h4>
                
                <a v-for="activo in filas" href="#" class="contenedores lt-item list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"> @{{activo.nombre}}</h5>
                    </div>
                    <p class="mb-1">
                        <strong>Marca:</strong> @{{activo.marca}}
                        <br>
                        <strong>Modelo:</strong> @{{activo.modelo}}
                        <br>
                        <strong>Factura:</strong> @{{activo.factura}}
                        <br>
                        <strong>Razón social:</strong> @{{activo.razon_social}}
                        <br>
                        <strong>Partida:</strong> @{{activo.recurso}}
                        <br>
                        <strong>Total:</strong> @{{activo.total}}
                    </p>
                </a>
               
            </div>
        </div>

        <!-- <div class="col-xs-12 col-md-4">
            <div class="bg-white pd-30 box-shadow border-radius-5 xs-pd-20-10 height-100-p">
                <h4 class="encabezados text-blue">Activos bajo resguardo</h4>
                <a v-for="activo in filas" href="#"
                class="contenedores lt-item list-group-item list-group-item-action flex-column align-items-start">
                    <div class="v-flex w-100 justify-content-between">
                        <h5 class="mb-1">#@{{activo.no_resguardo}}</h5>
                    </div>
                    <p class="mb-1">
                        <strong>Nombre del activo:</strong> <br>
                        <strong>Clave del activo:</strong> <br>
                        <strong>Marca:</strong> <br>
                        <strong>Modelo:</strong> <br>
                    </p>
                </a>
            </div>
        </div> -->
    </div>
</div>
@endsection
@section('vuejs')
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    $(document).ready(function(){
        $(".print").bind("click",function(event){
            event.stopPropagation();
            iden=$(this).data('k');
            $("#cvdfs").val(iden);
            document.frm_print.submit();
        });
    });
</script>
<script>
    <?php
        $cadenaactivos="";
        $coma="";
        foreach($activos as $activo){
            $cadenaactivos.=$coma.json_encode($activo);
            $coma=",";
        }

        $cadenaresguardos="";
        $coma="";
        foreach($resguardos as $resguardo){
            $cadenaresguardos.=$coma.json_encode($resguardo);
            $coma=",";
        }
    ?>
    let valor=document.getElementById("valor");
    var app=new Vue({
        el:"#misactivos",
        data:{
            // eventoactivo:false,
            activos:[
                <?php echo $cadenaactivos;?>
            ],
            resguardos:[
                <?php echo $cadenaresguardos;?>
            ],
            no_resguardo:"",
            no_resguardo_anterior:"",
            activos_filtrados:false
        },
        computed:{
            filas:function(){
                numresguardo=this.no_resguardo;
                return this.activos.filter(function(item){
                    if(numresguardo==""){
                        return true;
                    }
                    else{
                        if(item.no_resguardo==numresguardo){
                            return true;
                        }
                        
                    }
                });
            }
            // contenido:function(){
            //     var dt={
            //         numeroresguardo:this.no_resguardo
            //     };
            //     contenido=JSON.stringify(dt);
            //     return contenido;
            // }
        },
        methods:{
            // consultar(numres){
            //     this.no_resguardo=numres;
            // }
            consultar(numres){
                this.no_resguardo_anterior=this.no_resguardo;
                if(this.no_resguardo_anterior=="" || this.no_resguardo_anterior!=numres){
                    this.no_resguardo=numres;
                    this.activos_filtrados=true;
                }
                else if(this.no_resguardo_anterior==numres){
                    this.no_resguardo="";
                    this.activos_filtrados=false;
                }
            }
        }
    });
</script>
@endsection