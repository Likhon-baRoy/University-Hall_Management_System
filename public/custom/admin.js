// this jQuery is created for giving alert pop-up confirmation whenever delete button is pressed
(function($){
    $(document).ready(function() {

        //delete btn alert
        $('.delete-form').submit(function(e){

            let conf =confirm('Are you Sure ?');

            if (conf) {
                return true;
            }else{
                e.preventDefault();
            }
        });

        $('.data-table-element').DataTable();
    });
})(jQuery)
