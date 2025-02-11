<div>    

    <?php
       $tanggal =  \Carbon\Carbon::parse( $getState())
    
    ?>

    {{ $tanggal->format('d M Y') }} - <strong>{{ $tanggal->diffForHumans() }}</strong> 
</div>
