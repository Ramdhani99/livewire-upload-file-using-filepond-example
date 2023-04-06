<div>
    <form wire:submit.prevent="uploadImages">
        {{-- watermark --}}
        <span>Watermark</span>
        <div wire:ignore x-data x-init="() => {
        
            FilePond.registerPlugin(
                FilePondPluginImagePreview,
            )
        
            const pond = FilePond.create($refs.filepondWatermark)
        
            pond.setOptions({
                instantUpload: true,
                allowMultiple: false,
                server: {
                    process: (fieldName, file, metadata, load,
                        error, progress, abort, transfer, options) => {
                        @this.upload('watermark', file, load, error, progress)
                    },
                    revert: (filename, load) => {
                        @this.removeUpload('watermark', filename, load)
                    },
                },
                acceptedFileTypes: 'image/\*',
        
                imageCropAspectRatio: '1:1',
        
                styleLoadIndicatorPosition: 'center top',
                styleButtonRemoveItemPosition: 'left top',
                styleButtonProcessItemPosition: 'right top',
            })
        }">
            <input type="file" x-ref="filepondWatermark" class="h-full w-full cursor-pointer bg-transparent" />
        </div>
        {{-- end watermark --}}
        {{-- options --}}
        <div class="mb-6 flex w-full flex-wrap items-end justify-center gap-4">
            <label class="flex w-5/12 flex-col lg:w-2/12">
                <span>Images file extension</span>
                <select wire:model.defer="options.extension" class="border-2">
                    <option value="">webp (Default)</option>
                    <option value="jpg">jpg</option>
                    <option value="png">png</option>
                </select>
            </label>
            <label class="flex w-5/12 flex-col lg:w-2/12">
                <span>Images Quality (Default: 50%)</span>
                <input wire:model.defer="options.quality" type="number" min="10" max="100" class="border-2">
            </label>
            <label class="flex w-5/12 flex-col lg:w-2/12">
                <span>Watermark position</span>
                <select wire:model.defer="options.watermarkPosition" @if (!$this->watermark) disabled @endif
                        class="border-2">
                    <option value="">Top Left (Default)</option>
                    <option value="top">Top</option>
                    <option value="top-right">Top Right</option>
                    <option value="left">Left</option>
                    <option value="center">Center</option>
                    <option value="right">Right</option>
                    <option value="bottom-left">Bottom Left</option>
                    <option value="bottom">Bottom</option>
                    <option value="bottom-right">Bottom Right</option>
                </select>
            </label>
            <label class="flex w-5/12 flex-col lg:w-2/12">
                <span>Watermark Size (Default: 10%)</span>
                <input wire:model.defer="options.watermarkSize" type="number" min="10" max="50"
                       @if (!$this->watermark) disabled @endif class="border-2">
            </label>
        </div>
        {{-- end options --}}

        {{-- main filepond --}}
        <span>Images</span>
        <div wire:ignore x-data x-init="() => {
        
            FilePond.registerPlugin(
                FilePondPluginImagePreview,
            )
        
            const pond = FilePond.create($refs.filepondInput)
        
            pond.setOptions({
                instantUpload: true,
                required: true,
                allowMultiple: true,
                server: {
                    process: (fieldName, file, metadata, load,
                        error, progress, abort, transfer, options) => {
                        @this.upload('images', file, load, error, progress)
                    },
                    revert: (filename, load) => {
                        @this.removeUpload('images', filename, load)
                    },
                },
                acceptedFileTypes: 'image/\*',
        
                imageCropAspectRatio: '1:1',
        
                styleLoadIndicatorPosition: 'center top',
                styleButtonRemoveItemPosition: 'left top',
                styleButtonProcessItemPosition: 'right top',
            })
        }">
            <input type="file" x-ref="filepondInput" class="h-full w-full cursor-pointer bg-transparent" />
        </div>
        {{-- end main filepond --}}

        {{-- buttons --}}
        <div class="fixed bottom-0 left-1/2 z-10 my-6 -translate-x-1/2">
            <button type="submit"
                    class="rounded-xl bg-black px-4 py-2 text-white disabled:cursor-not-allowed disabled:brightness-75"
                    @if (!$this->images) disabled @endif>
                <span wire:target="uploadImages" wire:loading.remove>Submit</span>
                <span wire:target="uploadImages" wire:loading>Loading, please wait.</span>
            </button>
            <button type="button" wire:click="cleanupFiles"
                    class="rounded-xl bg-black px-4 py-2 text-white disabled:cursor-not-allowed disabled:brightness-75">
                Refresh Page
            </button>
        </div>
        {{-- end buttons --}}
    </form>

    @pushOnce('css')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/filepond/4.30.4/filepond.min.css"
              integrity="sha512-GZs7OYouCNZCZFJ46MulDG9BOd9MjYuJv06Be1vVVQv8EdFP76llX+SUoEK2fJvFiKVO34UKBZ2ckU0psBaXeg=="
              crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
              integrity="sha384-M+iyn5BlZ/T9O8ykrFAp2nFHbgsqRDIfEGYCbSuY7K5GHoZfU2WM0MZzCEpsaWHD" crossorigin="anonymous">
    @endPushOnce

    @pushOnce('js')
        <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"
                integrity="sha384-FuqioRgmzaC5P6ig19ItnBKab2MFa3rcN2bK7JSd/bXf8hBRXIXOaDfr4TPpy/2+" crossorigin="anonymous">
        </script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/filepond/4.30.4/filepond.min.js"
                integrity="sha512-l+50U3iKl0++46sldyNg5mOh27O0OWyWWsU2UnGfIVcxC+fEttAvao0Rns9KclIELHihYJppMWmM5sWof0M7uA=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @endPushOnce

    @pushOnce('scripts')
        {{-- browser event to reload the page --}}
        <script>
            window.addEventListener('reloadPage', (event) => {
                location.reload()
            })
        </script>
        {{-- 
            browser event to delete the files in livewire-tmp when user reload/refresh or go to another page. 
            sadly when user close the tab. this function won't work
        --}}
        <script>
            window.onbeforeunload = () => {
                Livewire.emit('cleanupFiles')
            }
        </script>
    @endPushOnce
</div>
