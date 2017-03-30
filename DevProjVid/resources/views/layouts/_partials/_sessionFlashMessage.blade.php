<div class='row'>

    <div class='col-md-8 col-md-offset-2'>

        <!-- ----------------------- -->
        <!-- flash_message_important -->

        @if(Session::has('flash_message_important'))

        <div class="alert alert-success">

            <button 
                type="button" 
                class="close" 
                data-dismiss="alert"
                aria-hidden="true">&times</button>

            {{ Session::get('flash_message_important') }}

        </div>

        @endif

        <!-- ------------- -->
        <!-- flash_message -->

        @if(Session::has('flash_message'))

        <div class="alert alert-success alert-flash-message">

            {{ Session::get('flash_message') }}

        </div>

        @endif

    </div>

</div>

<script>

    $('div.alert-flash-message').delay(3000).slideUp(300);

</script>