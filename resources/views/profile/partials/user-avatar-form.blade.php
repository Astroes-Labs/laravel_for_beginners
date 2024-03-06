<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
           User Avatar
        </h2>
        <img width="50" height="50" class="rounded-full" src="{{ "/storage/$user->avatar" }}" alt="User Avatar">
        
        <form method="POST" action="{{ route('profile.avatar.ai') }}" class="mt-4">
            @csrf
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
               Generate Avatar from AI
            </p>
            <x-primary-button>Generate Avatar</x-primary-button> 
        </form>    
        
        <p class="mt-4 mb-4 text-sm text-gray-600 dark:text-gray-400">
           OR
         </p>
    </header>

    @if (session('message'))
        <div class="text-red-500">
            {{ session('message') }}
        </div>
    @endif
    <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data">
    @method('patch')
    @csrf
        <div>
            <x-input-label for="name" value="Upload Avatar from your Device" />
            <x-text-input id="avatar" name="avatar" type="file" class="mt-1 block w-full" :value="old('avatar', $user->avatar)"  autofocus autocomplete="avatar" />
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>



        <div class="flex items-center gap-4 mt-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button> 
        </div>
    </form>
</section>
