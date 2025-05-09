<?php

namespace App\Services;

use App\Models\Card;
use Illuminate\Support\Collection;

class CardService
{

    public $summarizedCards;
    /**
     * Collection of attributes
     *
     * @param Card|null $card
     * @return array
     */
    public function formAttributes(?Card $card = null): array
    {
        if ($card) {
            $action = route('cards.update', $card->id);
            $method = 'PUT';
            $form = 'update';
        } else {
            $action = route('cards.store');
            $method = 'POST';
            $form = 'store';
        }

        return compact('action', 'method', 'form');
    }


    public function storeOrUpdate(array $data, ?Card $card = null): Card
    {
        $preparedData = [
            'name' => $data['name'],
            'number' => $data['number'],
            'amount' => $data['amount'] ?? 0,
        ];

        if ($card) {
            $card->fill($preparedData);
            $card->save();
        } else {
            $card = Card::create($preparedData);
        }

        return $card;
    }


    /**
     * Sum card amount form all receives or given
     *
     * @param Collection|null $receives
     * @return CardService
     */
    public function sumCardsWithKey(?Collection $receives = null): CardService
    {
        if (!$receives) {
            $receives = \App\Models\Receive::query()->where('type', 'deposit')->orWhere('passed', true)->get();
        }

        $this->summarizedCards = \App\Models\Receive::query()->withoutGlobalScope('due_at')
        ->where(function ($query) {
            $query->where('type', 'deposit')
            ->orWhere('passed', true);
        })
        ->selectRaw('card_id, CAST(SUM(amount) AS UNSIGNED) AS sum')
            ->groupBy('card_id')
            ->get();

        return $this;
    }

    public function updateCardsAmount()
    {
        $cards = $this->summarizedCards;
        foreach ($cards as $card) {
            Card::query()->whereId($card['card_id'])->update([
                'amount' => $card['sum'],
            ]);
        }
    }
}
