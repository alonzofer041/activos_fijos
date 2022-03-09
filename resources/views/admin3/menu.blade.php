<?php
$permisos=listarPermisosUsuario(Auth::user()->idusuario);
?>
<ul id="accordion-menu">
@foreach($permisos as $permiso)	
@if($permiso->permitidos!=0)
   <li class="dropdown">
	    <a href="javascript:;" class="dropdown-toggle">
		  <span class="{{$permiso->icono}}"></span><span class="mtext">{{$permiso->nommodulo}}</span>
	    </a>
	    <ul class="submenu">
	        @foreach($permiso->permisos as $el1)
	        <?php	      
		      if(action_exists($el1->url))
		      	$url_temp=action($el1->url);
		      else
		      	$url_temp="#";
		    ?>
			<li><a href="{{$url_temp}}">{{$el1->nompermiso}}</a></li>
			@endforeach
		</ul>
	</li>	    
@endif		
@endforeach
</ul>
