<?php

declare(strict_types=1);

namespace Modules\Cms\Actions\Panel;

use Modules\Cms\Contracts\PanelContract;
use Spatie\QueueableAction\QueueableAction;

final class DestroyAction
{
    use QueueableAction;

    public function execute(PanelContract $panelContract, array $data): PanelContract
    {
        $model = $panelContract->getRow();
        // viene chiamata quest'azione con i dati del pannello da cancellare e richiesta di distruzione
        // la chiamata all'Azione proviene da:
        // XotPanelController.php metodo magico __call con argomenti destroy e xotRequest
        // ContainersController.php sempre con stessi argomenti di XotPanelController.php
        // ContainersController.php funzione __callRouteAct con argomenti:
        /* 0 => "destroy"
        1 => array:8 [
          0 => "intellinet"
          1 => "it"
          2 => "profiles"
          3 => "1"
          4 => "reports"
          5 => "3"
          6 => "keywords"
          7 => "8" */
        // quindi la route da distruggere
        // PanelMiddleware.php di Cms

        // a rules passi il parametro act=>edit
        // rules prende i campi del pannello del modello da cancellare
        // Esempio (nel caso di belongsToMany su Profile->Reports->Keywords):
        /*0 => {#2245
            +"type": "Text"
            +"name": "id"
            +"comment": "not in Doctrine"
          }
          1 => {#2200
            +"type": "Text"
            +"name": "keyword"
            +"comment": "not in Doctrine"
          }*/
        // Poi $act viene impostato a null
        // essendo null diverso da stringa
        // entra nella funzione che restituire SOLO i campi non except
        // in caso di campi ['Cell', 'CellLabel'] guarda se ci sono sotto-campi
        // poi inizia a restituire le regole
        // se non esistono regole nel campo la mette vuota
        // se le regole sono pivot_rules ti restituisce le regole per disassociare dalla tabella pivot CREDO
        // ma comunque non c'entra con il caso della belongsToMany

        // alla fine restituisce il panel a XotPanelController.php che ri-sputa fuori il panel
        // dopo aver richiamato \Modules\Xot\Actions\!!!MODEL!!!\DestroyAction
        // passando modello da cancellare ($row), richiesta dalla route($data) e nomi dei campi da cancellare ($rules)

        // Quindi VEDERE \Modules\Xot\Actions\Model\DestroyAction

        $rules = $panelContract->rules('edit');
        $act = str_replace('\Panel\\', '\Model\\', self::class);
        $act = str_replace('\Cms\\', '\Xot\\', $act);

        app('\\'.$act)->execute($model, $data, $rules);

        if (method_exists($panelContract, 'destroyCallback')) {
            $panelContract->destroyCallback(['row' => $model]);
        }

        return $panelContract;
    }
}
