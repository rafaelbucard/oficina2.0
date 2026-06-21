<?php

namespace App\Services;

/**
 * Regras de negócio do orçamento: itens, cálculo de totais e snapshot.
 */
class QuoteService
{
    /**
     * Converte os arrays paralelos do formulário em uma lista de itens validados,
     * já com o total de cada linha calculado.
     *
     * @return array<int, array{item_type:string, description:string, quantity:float, unit_price:float, line_total:float}>
     */
    public function parseItems(array $input): array
    {
        $types       = $input['item_type']   ?? [];
        $descriptions = $input['description'] ?? [];
        $quantities  = $input['quantity']    ?? [];
        $prices      = $input['unit_price']  ?? [];

        $items = [];
        $count = count($descriptions);

        for ($i = 0; $i < $count; $i++) {
            $description = trim((string) ($descriptions[$i] ?? ''));
            if ($description === '') {
                continue;
            }

            $type     = in_array(($types[$i] ?? ''), ['peca', 'servico'], true) ? $types[$i] : 'peca';
            $quantity = $this->toFloat($quantities[$i] ?? 1);
            $price    = $this->toFloat($prices[$i] ?? 0);
            $quantity = $quantity > 0 ? $quantity : 1;

            $items[] = [
                'item_type'   => $type,
                'description' => $description,
                'quantity'    => $quantity,
                'unit_price'  => $price,
                'line_total'  => round($quantity * $price, 2),
            ];
        }

        return $items;
    }

    public function itemsSubtotal(array $items): float
    {
        return round(array_sum(array_column($items, 'line_total')), 2);
    }

    public function total(array $items, float $discount = 0): float
    {
        return round(max(0, $this->itemsSubtotal($items) - $discount), 2);
    }

    /** Monta o snapshot de cliente/veículo gravado no orçamento. */
    public function snapshot(array $client, ?array $vehicle): array
    {
        return [
            'client_name'    => $client['name'],
            'client_phone'   => $client['phone'],
            'client_contact' => $client['contact'] ?? null,
            'vehicle_plate'  => $vehicle['plate'] ?? null,
            'vehicle_type'   => $vehicle['type'] ?? null,
            'vehicle_brand'  => $vehicle['brand'] ?? null,
            'vehicle_model'  => $vehicle['model'] ?? null,
            'vehicle_year'   => $vehicle['year'] ?? null,
        ];
    }

    public function toFloat(mixed $value): float
    {
        if (is_string($value)) {
            $value = trim($value);
            // Formato brasileiro "1.234,56": remove separador de milhar e troca decimal.
            if (str_contains($value, ',')) {
                $value = str_replace('.', '', $value);
                $value = str_replace(',', '.', $value);
            }
        }
        return (float) $value;
    }
}
