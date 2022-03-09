@extends('movimientos.asignar_activo_base')

@section('detallesmov')
<div>
    <div class="form-group">
        <label>Detalles del motivo</label>
        <textarea class="form-control"  name="descripcionmotivo" v-model="descripcionmotivo" v-on:input="$emit('modjson',descripcionmotivo)" required></textarea>
    </div>

    <div class="form-group">
        <label>Clave</label>
        <textarea class="form-control"  name="clave" v-model="clave" v-on:input="$emit('modjson',clave)" required></textarea>
    </div>

    <div class="form-group">
        <label>Observaciones</label>
        <textarea class="form-control"  name="observaciones" v-model="observaciones" v-on:input="$emit('modjson',observaciones)" required></textarea>
    </div>

    <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">Cantidad</label>
        <div class="col-sm-12 col-md-10">
            <input class="form-control" value="1" type="number" name="cantidad"  v-model="cantidad" v-on:input="$emit('modjson',cantidad)"required>
        </div>
    </div>

    <div class="form-group">
        <label>Fecha de termino</label>
        <input for="example-datetime-local-input" class="form-control datetimepicker" placeholder="Choose Date and time" type="date" name="fechatermino" v-model="fechatermino" v-on:input="$emit('modjson',fechatermino)" required>
    </div>

    <div class="form-group">
        <label>Nueva ubicación</label>
        <textarea class="form-control"  name="nuevaubicacion" v-model="nuevaubicacion" v-on:input="$emit('modjson',nuevaubicacion)"required></textarea>
    </div>
</div>

@endsection



@section('tipomov')
<!------------------------------------------------------------------------------>
        @if($tipomovimiento == 1)
            @php
            $nommovimiento = 'Venta';
            @endphp
            <div>
                <!----------Switch Info ---------->
                <input type="hidden" name="tipomovimiento" value="{{$tipomovimiento}}">
                <!----------Switch Info ---------->
                <div class="form-group">
                    <label class="col-sm-12 col-md-6 col-form-label">Precio de la venta</label>
                    <div class="col-sm-12 col-md-6">
                        <input class="form-control"  type="number" min="0" step="0.50" name="ventaprecio"
                        v-model="ventaprecio"
                        v-on:input="$emit('modjson',ventaprecio)" required>
                    </div>
                </div>
            </div>
<!------------------------------------------------------------------------------>
        @elseif($tipomovimiento ==2)
            @php
            $nommovimiento = 'Baja';
            @endphp
            <div>
                <!----------Switch Info ---------->
                <input type="hidden" name="tipomovimiento" value="{{$tipomovimiento}}">
                <!----------Switch Info ---------->
                <div class="form-group">
                    <label>Fecha de desuso</label>
                    <input for="example-datetime-local-input" class="form-control datetimepicker" placeholder="Choose Date and time" type="date" name="bajafecha" v-model="bajafecha" v-on:input="$emit('modjson',bajafecha)" required>
                </div>
            </div>
<!------------------------------------------------------------------------------>
        @elseif($tipomovimiento ==3)
            @php
            $nommovimiento = 'Mantenimiento';
            @endphp
            <div>
                <!----------Switch Info ---------->
                <input type="hidden" name="tipomovimiento" value="{{$tipomovimiento}}">
                <!----------Switch Info ---------->
                <div class="form-group">
                    <label>Fecha de proximo mantenimiento</label>
                    <input for="example-datetime-local-input" class="form-control datetimepicker" placeholder="Choose Date and time" type="date" name="mantenimientofecha" v-model="mantenimientofecha" v-on:input="$emit('modjson',mantenimientofecha)" required>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-12 col-md-6 col-form-label">Costo</label>
                    <div class="col-sm-12 col-md-6">
                        <input class="form-control"  type="number" min="0" step="0.50" name="mantenimientocosto"
                        v-model="mantenimientocosto"
                        v-on:input="$emit('modjson',mantenimientocosto)" required>
                    </div>
                </div>

            </div>
<!------------------------------------------------------------------------------>
        @endif
@endsection
