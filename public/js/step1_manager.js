var Step1_Manager={
    dom_cambio:'#change'
    ,dom_total:'#tot'
    ,dom_check:'.sino'
    ,format2:function(n, currency) {
    return currency + "" + n.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
    }
   ,actualiza_cambio:function(){
       cambio=0;
       $(".frm-pago").each(function(){
         if($(this).val()!='')
          cambio+=parseFloat($(this).val());
       });

       cambio=parseFloat(cambio)-$(this.dom_total).data('tt');
       $(this.dom_cambio).html(format2(cambio,''));
   } 
   ,actualiza_total:function(){
       total=0;
      $(".hab").each(function(){
            total+=parseFloat($(this).data('sb'));
      });
      $(this.dom_total).html(format2(total,''));
      $(this.dom_total).data('tt',total);
      if($(this.dom_cambio).length)
         this.actualiza_cambio();
   }
   ,actualiza_subtotal_fila:function(k,band){
       if(band==1){
           if($('#cmb_'+k).val()!=-1){
             t1=parseFloat($("#rw_"+k).data('pr'));
             ttmp2=$('#cmb_'+k).val();
             ttmp3=ttmp2.split('_');
             por=parseFloat(ttmp3[1]);
             tnvpr=t1*(1-por);
             $("#rw_"+k).data('sb',tnvpr);
           }
           else{
             $("#rw_"+k).data('sb', $("#rw_"+k).data('pr'));
           }
       }
       else{
           $("#rw_"+k).data('sb',0);
       }
   }
   ,cambia_fila:function(k,chk){
       if(chk==1){
           $("#rw_"+k).removeClass('bg-warning');
           $("#rw_"+k).addClass('hab');
           $('#cmb_'+k).prop('disabled',false);
           this.actualiza_subtotal_fila(k,1);
       }
       else{
           $("#rw_"+k).addClass('bg-warning');
           $("#rw_"+k).removeClass('hab');
           $('#cmb_'+k).prop('disabled', 'disabled');
           this.actualiza_subtotal_fila(k,0);
       }
   }
   ,init:function(){
       var self=this;
       $(this.dom_check).bind('click',function(){
           iden=$(this).data('k');
           if($(this).prop('checked') == true) {
               self.cambia_fila(iden,1);
           }
           else
               self.cambia_fila(iden,0);
        
            self.actualiza_total();
       });
   }
}