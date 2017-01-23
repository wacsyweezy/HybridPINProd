$(document).ready(function() {
    $('select#roleid').change(function(){
        var type = 
        if(this.val()=="1") {
            $('div#external').removeClass('hidden');
        }
        else {
            $('div#external').addClass('hidden');
        }
    })
})