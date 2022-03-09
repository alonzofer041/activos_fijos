var Map_Manager={
	store:{
		seleccionados:[],
		activos:[],
		activos_seleccionados:[],
	},
	color_seleccionado:"#ffff00",
	modo:'',
	svg_root:null,
	plantilla_point:'<g class="pointer"><circle cx="@@x" cy="@@y" fill="none" r="10" stroke="#b71c1c" stroke-width="2"><animate attributeName="r" from="8" to="20" dur="1.5s" begin="0s" repeatCount="indefinite"/><animate attributeName="opacity" from="1" to="0" dur="1.5s" begin="0s" repeatCount="indefinite"/></circle><circle cx="@@x" cy="@@y" fill="#b71c1c" r="10"/></g>',
	update_mode:function(){
		if(this.store.activos_seleccionados.length!=0)
			this.modo='Add';
		else
			this.modo='';
	},
	init:function(config){
		this.svg_root=SVG("#utm_mapa");		
		this.load_activos(config.activos);
	},
	load_activos:function(lst_activos){
		$("#lista_activos").empty();		
		for(i=0;i<lst_activos.length;i++){
			// store
			this.store.activos.push(lst_activos[i]);
			// store

			//ui
			$("#lista_activos").append(tmpl("fila-activo",lst_activos[i]));
			//ui
		}
	},
	update_selected:function(iden){
		indice=-1;
		for(i=0;i<this.store.activos_seleccionados.length;i++){
			if(this.store.activos_seleccionados[i]==iden){
				indice=i;
			}
		}

		if(indice==-1)
		    this.store.activos_seleccionados.push(iden);
		else
			this.store.activos_seleccionados.splice(indice,1);

		this.update_mode();
	},
	search_selected:function(iden){
		indice=-1;
		for(i=0;i<this.store.seleccionados.length;i++){
			if(this.store.seleccionados[i].id==iden){
				indice=i;
			}
		}
		return indice;
	},
	agregar_selected:function(iden,colour){
		this.store.seleccionados.push({id:iden,color:colour});		
	},
	paint:function(color_dom,color){
		$(color_dom).attr("fill",color);
	},
	findout:function(g){		
		path=$(g).find("path");
		if(path.length){
			return path[0];
		}
		else{
			rect=$(g).find("rect");
			if(rect.length)
			 return rect[0];
			else{
				polygon=$(g).find("polygon");
				return polygon[0];
			}
		}
	},
	create_point:function(g){
		/*
		<g class="pointer"><circle cx="@@x" cy="@@y" fill="none" r="10" stroke="#b71c1c" stroke-width="2"><animate attributeName="r" from="8" to="20" dur="1.5s" begin="0s" repeatCount="indefinite"/><animate attributeName="opacity" from="1" to="0" dur="1.5s" begin="0s" repeatCount="indefinite"/></circle><circle cx="@@x" cy="@@y" fill="#b71c1c" r="10"/></g>
		*/
		minisvg=SVG(g).nested().x(10).y(10).width(10).height(10);
		minisvg.circle().radius(10).fill("#b71c1c");

	},
	add_points:function(g){
		geometrico=this.findout(g);
		rb=SVG(g).rbox();
		
		// pivotx=rb.x;
		// pivoty=rb.y;		
		// // this.svg_root.rect(100, 100).move(pivotex,pivotey).fill('#f06')
		
		
		// // this.svg_root.rect(100, 100).transform({translateX:196.33992}).fill('#f06');
		// // pivotx=bbox.x;
		// // pivoty=bbox.y;
		cadena='';
		console.log(this.store.activos_seleccionados.length);
		for(i=1;i<=this.store.activos_seleccionados.length;i++){
			pl=this.plantilla_point;
			tmp=pl.replace(/@@x/g,(i+1)*20);
			tmp=tmp.replace(/@@y/g,(i+1)*10);
			imp=SVG(tmp);
			gimp=SVG('#'+$(g).attr('id'));
			gimp.add(imp);
		}
		// console.log(cadena);
		// imp=SVG(cadena);
		// gimp=SVG('#'+$(g).attr('id'));
		// gimp.add(imp);
		// this.create_point(g);
	},
	click:function(g){
		objeto_fill=this.findout(g);		
		// iden=$(g).attr('id');
		// index_sel=this.search_selected(iden);
		// if($(objeto_fill).attr('fill')==this.color_seleccionado){			
		// 	if(index_sel!=-1){
		// 		color=this.store.seleccionados[index_sel].color;
		// 	}
		// 	else
		// 		color='#FFFFFF';
		// }
		// else{			
		// 	if(index_sel==-1){
		// 		this.agregar_selected(iden,$(objeto_fill).attr('fill'));
		// 	}
		// 	color=this.color_seleccionado;
		// }
		// this.paint(objeto_fill,color);
		
		if(this.modo=='Add'){
			this.add_points(g);
		}
		else{

		}
	},
	over:function(g){
		// objeto_fill=this.findout(g);
		// console.log(objeto_fill);
		return true;
	}
}

function click_mng(evt){
	var e=window.event || evt;
	var targ=e.target || e.srcElement;
	Map_Manager.click(targ.parentNode);
	// console.log(targ.parentNode);
}

function over_mng(evt){
	var e=window.event || evt;
	var targ=e.target || e.srcElement;
	Map_Manager.over(targ.parentNode);	
}