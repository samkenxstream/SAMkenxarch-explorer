<?php

declare(strict_types=1);

use App\Models\Block;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

use function Tests\configureExplorerDatabase;

beforeEach(function () {
    configureExplorerDatabase();

    $previousBlock = Block::factory()->create(['height' => 1]);

    $this->subject = Block::factory()->create([
        'previous_block' => $previousBlock->id,
        'height'         => 10000,
        'total_amount'   => '5000000000',
        'total_fee'      => '4800000000',
        'reward'         => '200000000',
    ]);
});

it('should have transactions', function () {
    expect($this->subject->transactions())->toBeInstanceOf(HasMany::class);
    expect($this->subject->transactions)->toBeInstanceOf(Collection::class);
});

it('should have a delegate that forged the block', function () {
    Wallet::factory()->create(['public_key' => $this->subject->generator_public_key]);

    expect($this->subject->delegate())->toBeInstanceOf(BelongsTo::class);
    expect($this->subject->delegate)->toBeInstanceOf(Wallet::class);
});

it('should have a previous block', function () {
    expect($this->subject->previous())->toBeInstanceOf(HasOne::class);
    expect($this->subject->previous)->toBeInstanceOf(Block::class);
});

it('should order blocks by their height from new to old', function () {
    expect($this->subject->latestByHeight())->toBeInstanceOf(Builder::class);
});

it('should only query blocks that were forged by the given public key', function () {
    expect($this->subject->generator('some-public-key'))->toBeInstanceOf(Builder::class);
});
