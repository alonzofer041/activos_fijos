var Ui={
	store:{
		ai_activos:0,
		activos:[]
	},

	dom_tabla:'',
    dom_clave_interna:'',
    dom_nom_activo:'',
    dom_num_serie:'',
    dom_identificacion:'',
    dom_cantidad:'',
    dom_precio_u:'',
    dom_descripcion:'',
    dom_partida:'',
    dom_inventariable:'',
    dom_agregar:'',
    dom_textarea:'',
    dom_guardar:'',
    init:function(config){
	    this.dom_tabla=config.dom_tabla;
	    this.dom_clave_interna=config.dom_clave_interna;
	    this.dom_nom_activo=config.dom_nom_activo;
	    this.dom_num_serie=config.dom_num_serie;
	    this.dom_identificacion=config.dom_identificacion;
	    this.dom_cantidad=config.dom_cantidad;
	    this.dom_precio_u=config.dom_precio_u;
	    this.dom_descripcion=config.dom_descripcion;
	    this.dom_partida=config.dom_partida;
	    this.dom_inventariable=config.dom_inventariable;
	    this.dom_agregar=config.dom_agregar;
	    this.dom_textarea=config.dom_textarea;
	    this.dom_edit=config.dom_edit;
		this.dom_eliminar=config.dom_eliminar;
		this.guardar=config.dom_guardar;

	    self=this;

	    $(this.dom_agregar).bind('click',function(){
			self.ui_agregar_registro();
		});

		$(this.dom_tabla ).on( "input",this.dom_edit, function() {
			iden=$(this).data('k');
			clave_interna=$("#clave_interna_"+iden).val();
			nom_activo=$("#nom_activo_"+iden).val();		    
			num_serie=$("#num_serie_"+iden).val();		    
			identificacion=$("#identificacion_"+iden).val();		    
			cantidad=$("#cantidad_"+iden).val();		    
			precio_u=$("#precio_u_"+iden).val();		    
			descripcion=$("#descripcion_"+iden).val();		    
			partida=$("#partida_"+iden).val();		    
			inventariable=$("#inventariable_"+iden).val();		    
			self.store_editar({
				id:iden,
				clave_interna:clave_interna,
				nom_activo:nom_activo,
				num_serie:num_serie,
				identificacion:identificacion,
				cantidad:cantidad,
				precio_u:precio_u,
				descripcion:descripcion,
				partida:partida,
				inventariable:inventariable,
			});
		});

		$(this.guardar).bind('click',function(){
			self.save();
		});

		$(this.dom_tabla ).on( "click",this.dom_eliminar, function() {
			iden=$(this).data('k');
		    $(this).parent().parent().remove();
		    self.store_eliminar_registro(iden);
		});

    },

    ui_agregar_registro:function(){
		/*dom_clave_interna=$(this.dom_clave_interna).val();
		dom_nom_activo=$(this.dom_nom_activo).val();
		dom_num_serie=$(this.dom_num_serie).val();
		dom_identificacion=$(this.dom_identificacion).val();
		dom_cantidad=$(this.dom_cantidad).val();
		dom_precio_u=$(this.dom_precio_u).val();
		dom_descripcion=$(this.dom_descripcion).val();
		dom_partida=$(this.dom_partida).val();
		dom_inventariable=$(this.dom_inventariable).val();*/
		this.store.ai_activos++;
		// $(this.dom_tabla).append('<tr><td style="vertical-align:middle;"><div><input type="text" name="clave_interna" class="inn edit" id="clave_interna_'+this.store.ai_activos+'" data-k="'+this.store.ai_activos+'"></div><div><input type="text" name="nom_activo" class="inn edit" id="nom_activo_'+this.store.ai_activos+'" data-k="'+this.store.ai_activos+'"></div></td><td style="vertical-align:middle;"><div><input type="text" name="num_serie" class="inn edit" id="num_serie_'+this.store.ai_activos+'" data-k="'+this.store.ai_activos+'"></div><div><input type="text" name="identificacion" class="inn edit" id="identificacion_'+this.store.ai_activos+'" data-k="'+this.store.ai_activos+'"></div></td><td style="vertical-align:middle;"><div><input type="text" name="cantidad" class="inn edit" id="cantidad_'+this.store.ai_activos+'" data-k="'+this.store.ai_activos+'"></div><div><input type="text" name="precio_u" class="inn edit" id="precio_u_'+this.store.ai_activos+'" data-k="'+this.store.ai_activos+'"></div></td><td style="vertical-align:middle;"><div><textarea class="intx edit" name="descripcion" id="descripcion_'+this.store.ai_activos+'" data-k="'+this.store.ai_activos+'"></textarea></div></td><td style="vertical-align:middle;"><div><select class="inn edit" name="partida" id="partida_'+this.store.ai_activos+'" data-k="'+this.store.ai_activos+'"><option>Selecciona</option></select></div><div><select class="inn edit" name="inventariable" id="inventariable_'+this.store.ai_activos+'" data-k="'+this.store.ai_activos+'"><option>Selecciona</option></select></div></td><td style="vertical-align: middle;"><button class="eliminar mas" data-k="'+this.store.ai_activos+'">-</button></td></tr>');
		$(this.dom_tabla).append(tmpl("fila-activo",{"indice":1,"nombre":"Trapeador","matr":"Matricula"}));
		/*this.store_agregar_registro({
			dom_clave_interna:dom_clave_interna,
			dom_nom_activo:dom_nom_activo,
			dom_num_serie:dom_num_serie,
			dom_identificacion:dom_identificacion,
			dom_cantidad:dom_cantidad,
			dom_precio_u:dom_precio_u,
			dom_descripcion:dom_descripcion,
			dom_partida:dom_partida,
			dom_inventariable:dom_inventariable,
			id:this.store.ai_activos,
		});*/
	},
	/*store_agregar_registro:function(info){
		this.store.activos.push(info);
		console.log(this.store.activos);
	},*/

	store_editar:function(datos){
		indice=-1;
		for(i=0;i<this.store.activos.length;i++){
			if(this.store.activos[i].id==datos.id)
				indice=i;
		}

		if(indice!=-1){
			this.store.activos[indice].clave_interna=datos.clave_interna;
			this.store.activos[indice].nom_activo=datos.nom_activo; 
			this.store.activos[indice].num_serie=datos.num_serie; 
			this.store.activos[indice].identificacion=datos.identificacion; 
			this.store.activos[indice].cantidad=datos.cantidad; 
			this.store.activos[indice].precio_u=datos.precio_u; 
			this.store.activos[indice].descripcion=datos.descripcion; 
			this.store.activos[indice].partida=datos.partida; 
			this.store.activos[indice].inventariable=datos.inventariable; 
		}

		console.log(this.store.activos);
	},

	save:function(){
		$(this.dom_textarea).val(JSON.stringify(this.store.activos));
		
	},

	store_eliminar_registro:function(iden){
		indice=-1;
		for(i=0;i<this.store.activos.length;i++){
			if(this.store.activos[i].id==iden)
				indice=i;
		}

		if(indice!=-1){
			this.store.activos.splice(indice,1);
		}

		console.log(this.store.activos);
	},


}