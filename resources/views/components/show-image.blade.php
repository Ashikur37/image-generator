<div>
    <div class="relative w-full h-36">
        <div class="flex flex-col items-center justify-center mt-2">
            <img  class="w-[512px]" src="{{  $getRecord()->image_url }}" alt="{{$getRecord()->keyword}}"/>
            <a class="underline" href="{{  $getRecord()->image_url }}" download target="_blank">
                Download
            </a>
        </div>
    </div>
</div>
