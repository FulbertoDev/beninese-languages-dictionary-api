<div>
    <div class="flex flex-col items-center">
        <img class="h-16" src="{{asset('images/logo.png')}}" alt="">
        <span class="text-base text-gray-900  uppercase font-semibold text-center mb-4">
            Soutenez votre dictionnaire préféré
        </span>

        @if($deviceUuidFound || $deviceStepPassed)
            <form wire:submit="generatePaymentLink" class="mb-8 w-full">
                <div class="flex flex-col space-y-3 my-4">
                    <div class="grid gap-6 mb-6 grid-cols-1 md:w-2/3 w-full md:mx-auto">
                        <div>
                            <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 ">Nom
                            </label>
                            <input wire:model="lastName" type="text" id="last_name"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                   placeholder="Doe" required/>
                        </div>
                        <div>
                            <label for="first_name"
                                   class="block mb-2 text-sm font-medium text-gray-900 ">
                                Prénom(s)
                            </label>
                            <input wire:model="firstName" type="text" id="first_name"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                   placeholder="Doe" required/>
                        </div>
                        <div>
                            <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 ">
                                Numéro de téléphone
                            </label>
                            <input wire:model="phone" type="tel" id="phone"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                   required/>
                            <span id="helper-text-explanation" class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Ex: 229 01 XX XX XX XX
                        </span>

                        </div>
                        <div>
                            <label for="amount"
                                   class="block mb-2 text-sm font-medium text-gray-900 ">Montant</label>
                            <input wire:model="amount" type="number" id="amount" min="{{$minAmount}}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                   required/>
                            @if(!$deviceStepPassed)
                                <span id="helper-text-explanation"
                                      class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    Montant minimum: {{$minAmount}} F.CFA {{$minAmount==995 ? '≈ 1,5€':''}}
                                    <span class="block font-semibold">La limitation du montant minimun ne s'applique que sur votre premier soutien.</span>
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($this->reason!=='gift' && !$deviceStepPassed)
                        <span class="text-justify text-blue-500 font-medium text-sm py-3">
                    En soutenant votre dictionnaire, vous débloquez par la même occasion l'accès à tous les mots
                    actuellement disponibles.
                        </span>
                    @endif

                    <button type="submit"
                            class="md:w-2/3 md:mx-auto disabled:opacity-50 disabled:cursor-not-allowed text-white bg-primary hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full  sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 mt-2 mb-12">
                        <span wire:loading.class="hidden">Continuer</span>
                        <span wire:loading>
                        <svg aria-hidden="true"
                             class="inline w-6 h-6 text-white animate-spin  fill-blue-600"
                             viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                fill="currentColor"/>
                            <path
                                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                fill="currentFill"/>
                        </svg>
                    </span>
                    </button>
                </div>
            </form>
        @else
            <div class="w-3/4">
                <div class="flex flex-col space-y-3 my-4">
                    <div>
                        <label for="device_uuid" class="block mb-2 text-sm font-medium text-gray-900 ">
                            Identifiant d'installation
                        </label>
                        <input wire:model="deviceUuid" type="text" id="device_uuid"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        />
                    </div>
                    <button wire:click="verifyDevice" type="button"
                            class="text-white bg-primary btn hover:text-white disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.class="hidden">Continuer <i class="mdi mdi-arrow-right"></i></span>
                        <span wire:loading>
                        <svg aria-hidden="true"
                             class="inline w-6 h-6 text-white animate-spin  fill-blue-600"
                             viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                fill="currentColor"/>
                            <path
                                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                fill="currentFill"/>
                        </svg>
                        </span>
                    </button>

                    <span wire:click="skipStep"
                          class="text-blue-600 underline hover:cursor-pointer text-center">Passer</span>
                </div>
            </div>
        @endif


    </div>
</div>
