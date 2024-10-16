<x-fbshare-layout>


    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v10.0" nonce="YOUR_NONCE"></script>



    <img class="mx-auto mt-5" src="{{ asset('images/test.png') }}" />

    <div class="w-[1200px] h-[630px] border border-gray-500 mt-10 mx-auto bg-gray-800 text-center">
    <div class="border-b border-gray-500 text-9xl text-white">
    {{ $result->pace_km }}/km
    </div>
</div>













</x-fbshare-layout>


