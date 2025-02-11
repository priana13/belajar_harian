

<div id="{{ $id }}" class="@if($default == 'close') hidden @endif modal-content " >
<div  class="fixed inset-x-0 bottom-0 h-full w-full  bg-black opacity-70 z-10 @if($default == 'close')  @endif close_modal">
</div>
    <div  class="fixed inset-x-3  -bottom-[1px] z-20 bg-white shadow-top rounded-xl p-5 max-w-[30rem] mx-auto ">
    <div class="">
        {{ $slot }}
    </div>
    <script >
        document.addEventListener('DOMContentLoaded', function() {
            const openModalButtons = document.querySelectorAll('.open-modal');
            const closeModalButtons = document.querySelectorAll('.modal-overlay, .modal-close');
            var modal_id = {{ $id }};
            openModalButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const modalId = button.getAttribute('data-modal-id');
                    const modal = document.getElementById(modalId);
                    modal_id = modal;
                    modal.classList.remove('hidden');
                    console.log(modal_id);
                });
            });

    
            


            document.addEventListener('click', function(event) {
              const clickedElement = event.target;
            //   console.log(clickedElement);
                if (clickedElement.classList.contains('close_modal')) {
                    modal_id.classList.add('hidden'); 
                }
            });         
            
        });
    </script> 
    </div>
</div>

