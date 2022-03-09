@extends('admin3.app')
@section('contenido')
<form class="app form-horizontal" action="{{action('MovimientoController@elegir_guardar')}}" method="POST" enctype="multipart/form-data" >
    {{csrf_field()}}
    <div class=" bg-white box-shadow" >
        



        <!--<a>{{$activos[0]}}</a>-->
        <activo-component 
        v-bind:act-string="{{$activos}}" 
        v-bind:pers-string="{{$personas}}" 
        >
        </activo-component>
        
    </div>
</form>

@endsection


@section('vuejs')

<script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
<link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">

<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>


<script>



Vue.component('tipomov-component',{
    data: function() {
        return{
            ventaprecio:'',
            bajafecha:'',
            mantenimientofecha:'',
            mantenimientocosto:'',
        }
    },
    template: `@yield('tipomov')`,
});

Vue.component('detalle-component',{
    data: function() {
        return{
            observaciones:'',
            cantidad:'',
            descripcionmotivo:'',
            clave:'',
            fechatermino:'',
            nuevaubicacion:'',

        }
    },
    template: `@yield('detallesmov')`


});

Vue.component('activo-component', {
    components: {
    Multiselect: window.VueMultiselect.default,
    },
    data: function() {
        return{
        selected: [],
        nommov:'',
        checked: false,
        show:'',
        jsonobj:[],
        jsonstring:'',
        id:'',
        motivo:'',
        selmov:'Seleccione un tipo de movimiento',
        value: '',
        }
    },
    template: `
            <div class="row " >
                <div class="col-lg-4 col-md-4 col-sm-3S mb-30 ">
                    <div class="pd-20 bg-white ">
                        <div class="col-sm-3S mb-30 col-lg-12 col-md-12 ">
                            <div class="col-12 list-group-item">
                                <label class="typo__label">Seleccione los activos </label>
                                <multiselect v-model="value" :options="actString"  :multiple="true" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Seleccione uno" label="nombre" track-by="idactivo" :preselect-first="false" @remove="removejson" @select="makejson"
                                    >
                                <template slot="selection" slot-scope="{ values, search, isOpen }">
                                    <span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">@{{ values.length }} options selected</span></template>
                                </multiselect>
                                <!--<pre class="language-json"><code>@{{ value  }}</code></pre> 
                                @{{ value  }} -->

                                <div class=" list-group-item list-group-item-action"
                                    v-for="(activo,index) in value"
                                    :activo="activo"
                                    :key="activo.idactivo"
                                    v-on:click="compchange(activo.idactivo)"
                                >
                                    <h3>@{{activo.nombre}}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-8 col-md-8 col-sm-3S mb-30">
                    <div class="pd-20 bg-white border-radius-4 ">
                        <!--<h3>@{{message}} @{{message2}}</h3>-->
                        <div class="col-sm-3S mb-30 col-lg-12 col-md-12 ">
                            
                            <div class="list-group-item">                      

                                <div class="form-group col-12" >
                                    <label>Motivo del movimiento</label>
                                    <textarea class="form-control"  name="motivo" v-model="motivo" required></textarea>
                                </div>
                                <div
                                v-for="(activo,index) in value"
                                v-show="show == activo.idactivo">

                                    <h3>@{{activo.nombre}}</h3>
                                    <detalle-component class="col-12" 
                                        v-on:modjson="modjson"
                                    >
                                    </detalle-component>

                                    <div>
                                        
                                        <tipomov-component class="col-12" 
                                        v-on:modjson="modjson" ></tipomov-component>
                                    </div>
                                </div>
                                <input type="hidden" name="datos" :value="jsonstring" id="json" required>
                                <input type="submit" class="btn btn-primary btn-block" value="Guardar"
                        v-if="value.length !== 0 && show !== '' " >
                                
                            </div>

                        </div>

                    </div>

                </div>
            </div>`,
    props:{
        actString: Array,
        persString: Array,
    },
    methods: {

    compchange(ref){
        this.show = ref
    },

    removejson(option){
        // console.log(option)
        var id = option.idactivo
        this.jsonobj.forEach(remove,this.id)
        function remove(item,index,arr, thisid){
            if(arr[index].id == id){
                delete arr[index];
            }
        }
    },

    makejson(option){
        var id = {id: option.idactivo};
        this.jsonobj.push(id)
    },

    modjson(det){
        var name = event.target.getAttribute('name');

        var id = this.show
        this.jsonobj.forEach(formotiv, this.det, this.id)
        function formotiv(item, index, arr, thisdet, thisid){
             if (arr[index].id == id) {
                if (!(name in arr[index])){
                    Object.defineProperty(arr[index], name, {value : det, writable : true, enumerable : true, configurable : true});
                }
                else{
                    arr[index][name] = det
                }

            }
         }
         this.jsonstring = JSON.stringify(this.jsonobj)
         console.log(this.jsonstring)
    },

    checkForm: function (e) {
        if (this.motivo !== '') {
          window.alert('ora')
        }

        this.errors = [];

        if (this.motivo == '') {
          this.errors.push('Name required.');
        }

        e.preventDefault();
      },

    
    addTag (newTag) {
      const tag = {
        name: newTag,
        code: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
      }
      this.options.push(tag)
      this.value.push(tag)
    }

  }
});

// function validateMyForm()
// {
//     var x = document.getElementById("json").value
//     var y = JSON.parse(x)
//     console.log(y)

//     y.forEach(validate)
//     function validate(item, index, arr){

//         console.log(arr[index])
//          // if (arr[index].id == id) {
//          //    if (!(name in arr[index])){
//          //        Object.defineProperty(arr[index], name, {value : det, writable : true, enumerable : true, configurable : true});
//          //    }
//          //    else{
//          //        arr[index][name] = det
//          //    }

//         //}
//      }

//     alert(x)
//     return false

// }

new Vue({
  el: '.app',

  data: {

  },
});


</script>
@endsection
