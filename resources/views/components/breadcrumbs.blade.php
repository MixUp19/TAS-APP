@props(['steps' => [], 'currentStep' => 1])

<nav aria-label="Progreso del proceso" class="my-6">
    <ol class="flex items-center justify-center gap-2 sm:gap-3 md:gap-4">
        @foreach($steps as $index => $step)
            @php
                $stepNumber = $index + 1;
                $isCompleted = $stepNumber < $currentStep;
                $isCurrent = $stepNumber === $currentStep;
                $isPending = $stepNumber > $currentStep;
                
                if ($isCompleted) {
                    $bgColor = 'bg-green-500';
                    $textColor = 'text-green-600';
                    $borderColor = 'border-green-500';
                } elseif ($isCurrent) {
                    $bgColor = 'bg-primary';
                    $textColor = 'text-primary';
                    $borderColor = 'border-primary';
                } else {
                    $bgColor = 'bg-gray-300';
                    $textColor = 'text-gray-400';
                    $borderColor = 'border-gray-300';
                }
            @endphp
            
            <li class="flex items-center gap-2 sm:gap-3">
                <div class="flex flex-col items-center gap-2">
                    <div class="relative">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full {{ $bgColor }} flex items-center justify-center text-white font-bold text-sm sm:text-base transition-all duration-300 {{ $isCurrent ? 'ring-4 ring-opacity-30 ring-primary shadow-lg scale-110' : '' }}">
                            @if($isCompleted)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            @else
                                {{ $stepNumber }}
                            @endif
                        </div>
                    </div>
                    
                    <span class="text-xs sm:text-sm font-medium {{ $isCurrent ? 'font-bold ' . $textColor : ($isCompleted ? $textColor : 'text-gray-400') }} text-center max-w-[80px] sm:max-w-none transition-all duration-300">
                        {{ $step['name'] }}
                    </span>
                </div>
                
                @if(!$loop->last)
                    <div class="hidden sm:block w-12 md:w-16 lg:w-24 h-1 {{ $isCompleted ? 'bg-green-500' : 'bg-gray-300' }} transition-all duration-300 mb-6"></div>
                @endif
            </li>
        @endforeach
    </ol>
</nav>

<style>
    .bg-primary {
        background: linear-gradient(135deg, hsl(190, 93%, 45%) 0%, hsl(190, 93%, 38%) 100%);
    }
    
    .text-primary {
        color: hsl(190, 93%, 38%);
    }
    
    .border-primary {
        border-color: hsl(190, 93%, 41%);
    }
    
    .ring-primary {
        --tw-ring-color: hsla(190, 93%, 41%, 0.3);
    }
</style>
