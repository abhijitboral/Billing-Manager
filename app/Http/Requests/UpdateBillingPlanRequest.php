<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateBillingPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isSuperAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'rules' => ['required', 'array', 'min:1'],
            'rules.*.source_product_id' => ['required', 'integer', 'min:1'],
            'rules.*.target_product_id' => ['required', 'integer', 'min:1'],
            'rules.*.source_campaign_id' => ['required', 'integer', 'min:1'],
            'rules.*.target_campaign_id' => ['required', 'integer', 'min:1'],
            'rules.*.days_to_next_billing' => ['required', 'integer', 'min:1', 'max:65535'],
            'rules.*.target_mid' => ['required', 'string', 'max:100'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $rows = array_values($this->input('rules', []));
            $seen = [];

            foreach ($rows as $index => $row) {
                $sourceProduct = (string) ($row['source_product_id'] ?? '');
                $sourceCampaign = (string) ($row['source_campaign_id'] ?? '');

                if ($sourceProduct === '' || $sourceCampaign === '') {
                    continue;
                }

                $key = $sourceProduct.'|'.$sourceCampaign;

                if (isset($seen[$key])) {
                    $validator->errors()->add(
                        "rules.$index.source_product_id",
                        'The same Source Product ID and Source Campaign ID combination cannot be repeated.'
                    );
                }

                $seen[$key] = true;
            }
        });
    }

    public function messages(): array
    {
        return [
            'rules.required' => 'Add at least one billing rule.',
            'rules.min' => 'Add at least one billing rule.',
            'rules.*.source_product_id.required' => 'Source Product ID is required.',
            'rules.*.target_product_id.required' => 'Target Product ID is required.',
            'rules.*.source_campaign_id.required' => 'Source Campaign ID is required.',
            'rules.*.target_campaign_id.required' => 'Target Campaign ID is required.',
            'rules.*.days_to_next_billing.required' => 'Days to Next Billing is required.',
            'rules.*.target_mid.required' => 'Target/Assigned MID is required.',
        ];
    }
}
