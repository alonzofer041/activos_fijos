var Map_Manager={
	bts:[],
	col_sel:'#dc143c',
	col_ocup:'#CCCCCC',
	url:'',
	total:0,
	limite_sel:10000,
	datos:{}
	,rgbToHex:function (rgb) { 
		  var hex = Number(rgb).toString(16);
		  if (hex.length < 2) {
		       hex = "0" + hex;
		  }
          return hex;
    }
	,fullColorHex:function(cadena_rgb) {   

	  //limpiar el rgb
	  limpio=cadena_rgb.replace("rgb","");
	  limpio=limpio.replace("(","");
	  limpio=limpio.replace(")","");
	  tmp=limpio.split(',');
	  r=tmp[0];
	  g=tmp[1];
	  b=tmp[2];
	  var red = this.rgbToHex(r);
	  var green = this.rgbToHex(g);
	  var blue = this.rgbToHex(b);
	  return red+green+blue;
	}
	,paint:function(trg,color){
      
	  if(typeof(trg)=="object")
	  {
	    //le estoy mandando el tag g    
	    // trg.childNodes[1].style.fill=color;
	    $(trg).children().first().attr("fill",color);
	  }
	  else{
	   if(typeof(trg)=="number") 
	    id=trg;
	    else{
	      if(typeof(trg)=="string")
	        id=trg; 
	    }
	    if(color=='')
	    	color=this.col_sel;
	    
	    //$("#"+id).children('path').attr("fill",color);
	    $("#"+id).children().first().attr("fill",color);

	  }  
    }
    ,click:function(g){
            edita=0;
            quitar=0;
            colorrgb=$(g).children().first().attr("fill");    	
        	if(colorrgb!=this.col_ocup){
    	    	if(colorrgb==this.col_sel){    	    
    	    		anterior=$(g).data("color-orig");
    	    		this.paint(g,anterior);
    	    		edita=1;
    	    	}
    	    	else{
    	    	  if(this.bts.length<=(this.limite_sel-1)){  
    	          this.paint(g,this.col_sel);
    	          edita=1;
    	          quitar=1;
    	    	  }
    	    	  else{                      
    	    	      // alert('Usted solo puede seleccionar hasta '+this.limite_sel+' asientos');
                      $("#myModal2").modal("show");
    	    	  }
    	    	}
    	    	if(edita==1){
        	    	this.update({
        	        	    	  idbutaca:g.id,	
        	        	    	  precio:$(g).data("precio"),	
        	        	    	  nombre:$(g).data("nombre"),
        	        	    	  idbutacaxfuncion:$(g).data("idbutacaxfuncion"),
        	        	    	  idprograma:$(g).data("idprograma"),
        		        	    });    
    	    	}
    	    	// this.update(g);
        	}
        	
        	
        	this.after_click({
        	    accion:quitar,
        	    nombre:$(g).data("nombre"),
        	    seccion:$(g).data("seccion"),
        	    precio:$(g).data("precio"),
        	    idbutacafuncion:$(g).data("idbutacaxfuncion"),
        	    idprograma:$(g).data("idprograma"),
        	    idbutaca:$(g).attr('id'),
        	    color:$(g).data("color-orig"),
        	});
        	
    }
    ,over:function(g){
        return false;
    }
    ,is_selected:function(iden){
    	bnd=-1;
    	for(i=0;i<=this.bts.length-1;i++){
    		// if(iden==this.bts[i].idbutaca)
    		if(iden==this.bts[i].idbutacaxfuncion)
    			bnd=i;
    	}
    	return bnd;
    }
    ,update:function(g){
		// if(typeof(g)=="object")
		// {		    
		//     iden=g.id;
		//     pr=$(g).data('precio');
		//     nom=$(g).data('nombre');
		// }
		// else{	   
		//     iden=g;
		//     pr=$("#"+iden).data('precio');
		//     nom=$("#"+iden).data('nombre');
		// }
    	// indice=this.is_selected(g.id);
    	indice=this.is_selected(g.idbutacaxfuncion);
    	if(indice==-1){
    		// this.bts.push({idbutaca:iden,
    		// 	           precio:pr,
    		// 	           nombre:nom
    		// 	           });
    		this.bts.push(g);
    	}
    	else{		
    	    //console.log('epa3!!!');
    	    //console.log(g.idbutacaxfuncion);
    		this.bts.splice(indice, 1);
    	}
    }
    ,render:function(){
    	//aqui conviertes el arreglo this.bts o a cadena
    	//y lo guardas en un hidden
    	console.log(this.bts[0].toString());    	
    }
    ,get_data:function(){
    	//ajax para obtener los datos de las butacas    	
    	$("#PALCOS").LoadingOverlay("show");
    	var map=this;
		$.ajax({
		        url: this.url,
		        type: "post",
		        data: this.datos,
		        dataType: 'json',
		        cache: false,
		        success: function (info) {
		        	$(info.bts).each(function(item,value){		        	    
		        		$("#"+value.idbutaca).data("color-orig",value.color);		        		
		        		$("#"+value.idbutaca).data("precio",value.precio);		        		
		        		$("#"+value.idbutaca).data("status",value.status);		        		
		        		$("#"+value.idbutaca).data("idbutacaxfuncion",value.idbutacaxzona);		        		
		        		$("#"+value.idbutaca).data("pertenece",value.pertenece);		        		
		        		$("#"+value.idbutaca).data("idprograma",value.idprograma);		        		
		        		$("#"+value.idbutaca).data("nombre",value.nomseccion+' '+value.numbutaca);

		        		if(value.status==1)
		        		map.paint(value.idbutaca,value.color);
		        	    else{
		        	    	if(value.pertenece!=0){
		        	    	map.paint(value.idbutaca,map.col_sel);
		        	    	}
		        	    	else{		        	    		
		        	    	 map.paint(value.idbutaca,map.col_ocup);	
		        	    	}
		        	    }
		        	});
		        	$("#PALCOS").LoadingOverlay("hide");
		        	map.after_get();
				},
	    });
	    
    }
    ,after_click:function(g){
    	return false;
    }
    ,after_get:function(){
        alert('que tak');
        return false;
    }
    ,load_data:function(){
        return false;
    }
    ,load_dom:function(){
        //console.log('aqui ta2') ;
        // return false;
        return true;
    }

}


function click_mng(evt){
  var e = window.event || evt;  
  var targ = e.target || e.srcElement;

  // g=evt.target.parentNode;
  g=targ.parentNode;
  Map_Manager.click(g);
}


function over_mng(evt){
  var e = window.event || evt;  
  var targ = e.target || e.srcElement;

  // g=evt.target.parentNode;
  g=targ.parentNode;
  Map_Manager.over(g);
}

function load_dom(){   

    Map_Manager.load_dom();
}