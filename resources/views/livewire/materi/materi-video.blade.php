<div class="my-3"> 

   <div id="materi_video"></div>

    <script src="https://www.youtube.com/iframe_api"></script>
   
    <script>

        function getYouTubeVideoId(url) {
            const regex = /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/;
            const match = url.match(regex);
            return match ? match[1] : null;
        }



        let player;
        function onYouTubeIframeAPIReady() {
            player = new YT.Player('materi_video', {
                height: '360',
                width: '100%',
                videoId: getYouTubeVideoId('{{ $video_url }}'),
                disablekb: 1,
                controls:0,               
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                }
            });
        }
    
        function onPlayerReady(event) {
            console.log('Player is ready.');
        }
    
        function onPlayerStateChange(event) {
            console.log('Player state changed.');
        }
    </script>



</div>
