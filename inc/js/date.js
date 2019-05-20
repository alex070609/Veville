$(document).ready(function(){

    $(function(){
        $('#date_depart').datepicker({minDate: 0, maxDate: "+20D"});
        $('#date_fin').datepicker({minDate: '+7D', maxDate: "+2M    "});
    });

}); // fin du document ready