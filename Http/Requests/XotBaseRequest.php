<?php

declare(strict_types=1);

namespace Modules\Cms\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Modules\Cms\Contracts\PanelContract;

// use Modules\Food\Models\Profile;
// --- Rules ---

/**
 * Class XotBaseRequest.
 */
abstract class XotBaseRequest extends FormRequest
{
    // use FormRequestTrait;

    // public function __construct(){
    // $this->setContainer(factory(Profile::class));
    // $this->setContainer(app());
    // }

    public PanelContract $panel;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    public function setPanel(PanelContract $panelContract): self
    {
        $this->panel = $panelContract;

        return $this;
    }

    public function validatePanel(PanelContract $panelContract, ?string $act = ''): void
    {
        $this->setPanel($panelContract);
        $this->prepareForValidation();
        $rules = $panelContract->rules($act);
        $this->validate($rules, $panelContract->rulesMessages());
    }

    /**
     * Get the validated data from the request.
     *
     * @return array
     */
    /*
    public function validated()
    {
        $rules = $this->container->call([$this, 'rules']);
        return $this->only(collect($rules)->keys()->map(
function ($rule) {
            return explode('.', $rule)[0];
        })->unique()->toArray());
    }
    */
    /**
     * https://stackoverflow.com/questions/28854585/laravel-5-form-request-data-pre-manipulation?rq=1.
     **/

    /**
     * Cerco di rilevare quando viene chiamato.
     */
    public function modifyInput(array $data): void
    {
        dddx($data);
    }

    protected function prepareForValidation(): void
    {
        $data = $this->request->all();
        $date_fields = collect($this->panel->fields())->filter(
            static fn ($item): bool => Str::startsWith($item->type, 'Date') && isset($data[$item->name])
        )->all();
        foreach ($date_fields as $date_field) {
            $value = $data[$date_field->name]; // metterlo nel filtro sopra ?
            /*
            *  Se e' un oggetto e' già convertito
            **/
            if (! \is_object($value)) {
                $func = 'Conv'.$date_field->type;
                $value_new = $this->$func($date_field, $value);
                $this->request->add([$date_field->name => $value_new]);
            }
        }
    }

    /**
     * Cerco di rilevare quando viene chiamato.
     *
     * @return array
     */
    public function validationData()
    {
        dddx('aaa');

        return [];
    }

    /**
     * @param  string  $field
     * @param  string  $value
     * @return Carbon|string
     */
    public function ConvDate($field, $value)
    {
        if ($value == null) {
            return $value;
        }

        $value_new = Carbon::createFromFormat('d/m/Y', $value);
        if ($value_new === false) {
            throw new \Exception('['.__LINE__.']['.__FILE__.']');
        }

        return $value_new;
    }

    /**
     * @param  string  $field
     * @param  string  $value
     * @return Carbon|string
     */
    public function ConvDateTime($field, $value)
    {
        if ($value == null) {
            return $value;
        }

        $value_new = Carbon::createFromFormat('d/m/Y H:i', $value);
        if ($value_new === false) {
            throw new \Exception('['.__LINE__.']['.__FILE__.']');
        }

        return $value_new;
    }

    /**
     * @param  string  $field
     * @param  string  $value
     * @return Carbon|string
     */
    public function ConvDateTime2Fields($field, $value)
    {
        if ($value == null) {
            return $value;
        }

        $value_new = Carbon::createFromFormat('d/m/Y H:i', $value);
        if ($value_new === false) {
            throw new \Exception('['.__LINE__.']['.__FILE__.']');
        }

        return $value_new;
    }
}
