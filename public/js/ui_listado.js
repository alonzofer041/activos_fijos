var Ui_listado={
	store:{
		items:[],
		item_reciente:{}
	},
	init:function(){
		
		self=this;
		$("#assign").bind('click',function(){
			self.enviar_asignacion();
		});

		$("#print_tag").bind('click',function(){			
			$("#dts").val(JSON.stringify(self.store.items));
		    document.frm_print.submit();
		});

		$(".item-resguardo").bind('click',function(){
			iden=$(this).data('k');
			self.ui_seleccionar_item(iden);
			self.update_items(iden);
			self.ui_update();
		});
	},
	enviar_asignacion:function(){
		$("#dts2").val(JSON.stringify(self.store.items));
		document.frm_assign.submit();
	},
	update_items:function(idem){
		indice=this.store_buscar_item(idem);
		if(indice==-1){
			this.store.items.push(idem);
		}
		else{
			this.store.items.splice(indice,1);
		}		
	},
	store_buscar_item:function(item){
		indice=-1;
		for(i=0;i<this.store.items.length;i++){
			if(item==this.store.items[i])
				indice=i;
		}
		return indice;
	},
	ui_update:function(){
		if(this.store.items.length!=0){
			$("#assign").prop('disabled',false);
			$("#print_tag").prop('disabled',false);
		}
		else{
          $("#assign").prop('disabled',true);
          $("#print_tag").prop('disabled',true);
		}
	},
	ui_seleccionar_item:function(idem){
		

		indice=this.store_buscar_item(idem);		
		
		ui_dom=$("#item_"+idem);
		if(indice==-1){			
			ui_dom.addClass('alert-warning');
			$("#contenido_"+idem).addClass('alert-warning');
			$("#contenido2_"+idem).css({"background-color":'#fff3cd'});
		}
		else{
			ui_dom.removeClass('alert-warning');
			$("#contenido_"+idem).removeClass('alert-warning');
			$("#contenido2_"+idem).css({"background-color":'#FFFFFF'});
		}	
	},
}