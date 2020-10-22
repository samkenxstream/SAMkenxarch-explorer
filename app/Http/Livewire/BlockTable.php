<?php

declare(strict_types=1);

namespace App\Http\Livewire;

use App\Http\Livewire\Concerns\CanViewMore;
use App\Models\Block;
use App\ViewModels\ViewModelFactory;
use ARKEcosystem\UserInterface\Http\Livewire\Concerns\HasPagination;
use Illuminate\View\View;
use Livewire\Component;

final class BlockTable extends Component
{
    use CanViewMore;
    use HasPagination;

    public function render(): View
    {
        return view('livewire.block-table', [
            'blocks' => ViewModelFactory::paginate(Block::latestByHeight()->paginate()),
        ]);
    }
}