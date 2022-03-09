var Ui={
	store:{
		ai_alumnos:0,
		alumnos:[],
		partidas:[],
		ubicaciones:[],
		tipos:[]
	},
	dom_tabla:'',
	dom_botom:'',
	dom_nombre:'',
	dom_apellido:'',
	dom_textarea:'',
	dom_guardar:'',
	dom_plantilla:'',
	dom_eliminar:'',
	token:'',
	url_busq_proveedor:'',
	init:function(config){
		this.dom_tabla=config.dom_tabla;
		this.dom_botom=config.dom_botom;
		this.dom_nombre=config.dom_nombre;
		this.dom_apellido=config.dom_apellido;
		this.dom_textarea=config.dom_textarea;
		this.guardar=config.dom_guardar;
		this.dom_eliminar=config.dom_eliminar;
		this.dom_edit=config.dom_edit;
		this.dom_plantilla=config.dom_plantilla;
		this.store.partidas=config.partidas;
		this.store.tipos=config.tipos;
		this.store.ubicaciones=config.ubicaciones;
		this.token=config.token
		this.url_busq_proveedor=config.url_busq_proveedor;

		self=this;

		$(this.dom_botom).bind('click',function(){
			self.ui_agregar_registro();
		});

		$(this.guardar).bind('click',function(){
			self.save();
		});

		$(this.dom_tabla ).on( "click",this.dom_eliminar, function() {
			iden=$(this).data('k');
		    $(this).parent().parent().remove();
		    self.store_eliminar_registro(iden);
		});

		$(this.dom_tabla ).on( "input",this.dom_edit, function() {
			iden=$(this).data('k');
		});

		$(this.dom_tabla ).on( "input",this.dom_edit, function() {
			iden=$(this).data('k');
			name=$("#nombre_"+iden).val();
			last=$("#apellido_"+iden).val();		    
			self.store_editar({
				id:iden,
				nombre:name,
				apellido:last,
			});
		});

		if(config.activos.length!=0){
			this.store_carga_activos(config.activos);
		}

		
	},
	ui_agregar_registro:function(){		
		var today = new Date();
		idtoday='new_'+today.getFullYear()+(today.getMonth()+1)+today.getDate()+today.getHours()+today.getMinutes()+today.getSeconds();
		this.store.ai_alumnos++;
		dgvs={
			iden:idtoday,
		    nombre:'',
			marca:'',
			modelo:'',
			razon:'',
			descripcion:'',
			serie:'',
			clave:'',
			identificacion:'',
			cantidad:'',
			precio:'',
			inventariable:'',
			partida:0,
			nompartida:'',
			tipoactivo:0,
			nomtipoactivo:'',
		}
		$(this.dom_tabla).append(tmpl(this.dom_plantilla,dgvs));
		this.ui_activar_editables(idtoday);
		this.store_agregar_registro({
			nombre:'',
			marca:'',
			modelo:'',
			razon:'',
			descripcion:'',
			serie:'',
			clave:'',
			identificacion:'',
			cantidad:'',
			precio:'',
			inventariable:'',
			nomtipoactivo:'',
			partida:0,
			tipoactivo:0,
			id:idtoday,
			nomtipoactivo:'',
		});
	},
	ui_activar_editables:function(iden){
		self=this;
		$("#nmbract_"+iden).editable({
			                          url:''
			                         ,emptytext:'Nombre del activo'
			                         ,success:function(response,newValue)
			                           {
			                           	 self.store_editar(
				                           	 {
				                           	 	iden:$(this).attr('data-pk'),
				                           	    valor:newValue,
				                           	    atributo:'nombre'
				                           	 }
			                           	 );
			                           	 
			                           }
			                         ,mode:'inline'
			                        });
		$("#marca_"+iden).editable({
			                          url:''
			                         ,emptytext:'Marca'
			                         ,success:function(response,newValue)
			                           {
			                           	 self.store_editar(
				                           	 {
				                           	 	iden:$(this).attr('data-pk'),
				                           	    valor:newValue,
				                           	    atributo:'marca'
				                           	 }
			                           	 );
			                           }
			                         ,mode:'inline'
			                        });
		$("#modelo_"+iden).editable({
			                          url:''
			                         ,emptytext:'Modelo'
			                         ,success:function(response,newValue)
			                           {
			                           	 self.store_editar(
				                           	 {
				                           	 	iden:$(this).attr('data-pk'),
				                           	    valor:newValue,
				                           	    atributo:'modelo'
				                           	 }
			                           	 );
			                           }
			                         ,mode:'inline'
			                        });
		//razon social del proveedor con buscador
		$("#razon_"+iden).selectize({
		      valueField: 'nomproveedor',
		      labelField: 'nomproveedor',
		      searchField: 'nomproveedor',
		      placeholder: 'Busqueda...',
		      maxItems:1,
		      create:true, 
		      onChange:function(newvalue){      
		        self.store_editar(
	                           	 {
	                           	 	iden:$(this).attr('data-pk'),
	                           	    valor:newvalue,
	                           	    atributo:'razon'
	                           	 }
				                 );
		        
		      return true;
		      },            
		      load: function(query, callback) {
		            if (!query.length) return callback();
		            $.ajax({
		                url: self.url_busq_proveedor,
		                data: {
		                q: query,             
		                _token:self.token
		                }, 
		                type: 'POST',
		                error: function(res) {  
		                    console.log(res.responseText);
		                    callback();
		                },
		                success: function(res) { 
		                    console.log(res);
		                    callback(res);
		                }
		            });
		        }
		  });

		//razon social del proveedor con buscador
		$("#razon_"+iden).editable({
			                          url:''
			                         ,emptytext:'Razon social'
			                         ,success:function(response,newValue)
			                           {
			                           	 self.store_editar(
				                           	 {
				                           	 	iden:$(this).attr('data-pk'),
				                           	    valor:newValue,
				                           	    atributo:'razon'
				                           	 }
			                           	 );
			                           }
			                         ,mode:'inline'
			                        });
		$("#descripcion_"+iden).editable({
			                          url:''
			                         ,emptytext:'Descripcion'
			                         ,success:function(response,newValue)
			                           {
			                           	 self.store_editar(
				                           	 {
				                           	 	iden:$(this).attr('data-pk'),
				                           	    valor:newValue,
				                           	    atributo:'descripcion'
				                           	 }
			                           	 );
			                           }
			                         ,mode:'inline'
			                        });
		$("#serie_"+iden).editable({
			                          url:''
			                         ,emptytext:'No. serie'
			                         ,success:function(response,newValue)
			                           {
			                           	 self.store_editar(
				                           	 {
				                           	 	iden:$(this).attr('data-pk'),
				                           	    valor:newValue,
				                           	    atributo:'serie'
				                           	 }
			                           	 );
			                           }
			                         ,mode:'inline'
			                        });
		$("#clave_"+iden).editable({
			                          url:''
			                         ,emptytext:'Numero de inventario'
			                         ,success:function(response,newValue)
			                           {
			                           	 self.store_editar(
				                           	 {
				                           	 	iden:$(this).attr('data-pk'),
				                           	    valor:newValue,
				                           	    atributo:'clave'
				                           	 }
			                           	 );
			                           }
			                         ,mode:'inline'
			                        });
		$("#identificacion_"+iden).editable({
			                          url:''
			                         ,emptytext:'Numero de identificacion'
			                         ,success:function(response,newValue)
			                           {
			                           	 self.store_editar(
				                           	 {
				                           	 	iden:$(this).attr('data-pk'),
				                           	    valor:newValue,
				                           	    atributo:'identificacion'
				                           	 }
			                           	 );
			                           }
			                         ,mode:'inline'
			                        });
		$("#cantidad_"+iden).editable({
			                          url:''
			                         ,success:function(response,newValue)
			                           {
			                           	 self.store_editar(
				                           	 {
				                           	 	iden:$(this).attr('data-pk'),
				                           	    valor:newValue,
				                           	    atributo:'cantidad'
				                           	 }
			                           	 );
			                           	 //actualizacion de total y subtotales
			                           	 cantidad=newValue;
			                           	 iden=$(this).attr('data-pk');
			                           	 precio=$("#precio_"+iden).html();
			                           	 if(isNaN(precio)!='')
			                           	 	precio=0;
			                           	 else
			                           	 	precio=parseFloat(precio);
			                           	 iva=.16;
			                           	 subtotal=cantidad*precio;
			                           	 impuesto=subtotal*iva;
			                           	 total=subtotal+impuesto;
			                           	 console.log(total);
			                           	 $("#subt_"+iden).html(subtotal);
			                           	 $("#iva_"+iden).html(impuesto);
			                           	 $("#total_"+iden).html(total);
			                           	 //actualizacion de total y subtotales

			                           }
			                         ,mode:'inline'
			                        });
		$("#precio_"+iden).editable({
			                          url:''
			                         ,success:function(response,newValue)
			                           {
			                           	 self.store_editar(
				                           	 {
				                           	 	iden:$(this).attr('data-pk'),
				                           	    valor:newValue,
				                           	    atributo:'precio'
				                           	 }
			                           	 );
			                           	 //actualizacion de total y subtotales
			                           	 precio=parseFloat(newValue);
			                           	 iden=$(this).attr('data-pk');
			                           	 cantidad=$("#cantidad_"+iden).html();
			                           	 if(isNaN(cantidad)!='')
			                           	 	cantidad=0;
			                           	 else
			                           	 	cantidad=parseFloat(cantidad);
			                           	 iva=.16;
			                           	 subtotal=cantidad*precio;
			                           	 impuesto=subtotal*iva;
			                           	 total=subtotal+impuesto;			                           	 
			                           	 $("#subt_"+iden).html(subtotal);
			                           	 $("#iva_"+iden).html(impuesto);
			                           	 $("#total_"+iden).html(total);
			                           	 //actualizacion de total y subtotales
			                           }
			                         ,mode:'inline'
			                        });
		$("#inventariable_"+iden).editable({
			                          url:''
			                         ,success:function(response,newValue)
			                           {
			                           	 self.store_editar(
				                           	 {
				                           	 	iden:$(this).attr('data-pk'),
				                           	    valor:newValue,
				                           	    atributo:'inventariable'
				                           	 }
			                           	 );
			                           }
			                         ,mode:'inline'
			                         ,value:1
			                         ,source: [
								              {value: 1, text: 'Si'},
								              {value: 0, text: 'No'}
								           ]
								      });
		$("#ubicacion_"+iden).editable({
			                          url:''
			                         ,success:function(response,newValue)
			                           {
			                           	 self.store_editar(
				                           	 {
				                           	 	iden:$(this).attr('data-pk'),
				                           	    valor:newValue,
				                           	    atributo:'idubicacion'
				                           	 }
			                           	 );
			                           }
			                         ,mode:'inline'
			                         ,value:1
			                         ,source: self.store.ubicaciones
								      });
	    $("#partida_"+iden).editable({
			                          url:''
			                         ,emptytext:'Partida'
			                         ,success:function(response,newValue)
			                           {
			                           	 self.store_editar(
				                           	 {
				                           	 	iden:$(this).attr('data-pk'),
				                           	    valor:newValue,
				                           	    atributo:'partida'
				                           	 }
			                           	 );
			                           }
			                         ,mode:'inline'			                         
			                         ,source:self.store.partidas
								      });
	    $("#tipobien_"+iden).editable({
			                          url:''
			                         ,emptytext:'Tipo de activo'
			                         ,success:function(response,newValue)
			                           {
			                           	 self.store_editar(
				                           	 {
				                           	 	iden:$(this).attr('data-pk'),
				                           	    valor:newValue,
				                           	    atributo:'tipoactivo'
				                           	 }
			                           	 );
			                           }
			                         ,mode:'inline'			                         
			                         ,source:self.store.tipos
								      });
	},
	store_agregar_registro:function(info){
		this.store.alumnos.push(info);
	},
	store_eliminar_registro:function(iden){
		indice=-1;
		for(i=0;i<this.store.alumnos.length;i++){
			if(this.store.alumnos[i].id==iden)
				indice=i;
		}

		if(indice!=-1){
			this.store.alumnos.splice(indice,1);
		}
	},
	store_editar:function(datos){
		indice=-1;
		for(i=0;i<this.store.alumnos.length;i++){
			if(this.store.alumnos[i].id==datos.iden)
				indice=i;
		}

		if(indice!=-1){
			this.store.alumnos[indice][datos.atributo]=datos.valor;
		}
		console.log(datos);
		console.log(this.store.alumnos[indice][datos.atributo]);
	},
	store_carga_activos(lista){
		for(i=0;i<lista.length;i++){
			dgvs={
				iden:lista[i].id,
				nombre:lista[i].nombre,
				marca:lista[i].marca,
				modelo:lista[i].modelo,
				razon:lista[i].razon,
				descripcion:lista[i].descripcion,
				serie:lista[i].serie,
				clave:lista[i].clave,
				identificacion:lista[i].identificacion,
				cantidad:lista[i].cantidad,
				precio:lista[i].precio,
				inventariable:lista[i].inventariable,
				partida:lista[i].partida,
				nompartida:lista[i].nompartida,
				tipoactivo:lista[i].tipoactivo,
				nomtipoactivo:lista[i].nomtipoactivo,
				idubicacion:lista[i].idubicacion,
			};
			console.log(dgvs);
			$(this.dom_tabla).append(tmpl(this.dom_plantilla,dgvs));
			this.ui_activar_editables(lista[i].id);
			this.store_agregar_registro(lista[i]);
		}
	},
	save:function(){		
		$(this.dom_textarea).val(JSON.stringify(this.store.alumnos));
		document.frm.submit();		
	}
}