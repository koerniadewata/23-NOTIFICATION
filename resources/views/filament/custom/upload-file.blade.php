<div>
    <x-filament::breadcrumbs :breadcrumbs="[
        '/admin/students' => 'Students',
        '' => 'List',
    ]" />

    <h1>Upload File</h1>
    <div class="flex justify-between mt-1">
        <div class="font-bold text-3xl">Students</div>
        <div>
            {{ $data }}
        </div>
    </div>
    <div class="w-full max-w-sm flex mt-2">
        <form wire:submit.prevent="save" class="w-full">
            <div class="flex items-center mb-4">

                <input 
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                    id="fileInput" 
                    type="file" 
                    wire:model="file"
                >            
                <button                
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" 
                    type="submit"
                    style="background-color: #3490dc; border-color: #3490dc;"
                >
                    Unggah
                </button>
            </div>
        </form>
    </div>
</div>
