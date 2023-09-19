<?php

declare(strict_types=1);

namespace Modules\Cms\Models\Panels\Actions;

// -------- models -----------

// -------- services --------
// -------- bases -----------
use Modules\Xot\Services\ArtisanService;

/**
 * Class ArtisanAction.
 */
class ArtisanAction extends XotBasePanelAction
{
    public bool $onContainer = false; // onlyContainer

    public bool $onItem = true; // onlyContainer

    public string $icon = '<i class="far fa-file-excel fa-1x"></i>';

    /**
     * ArtisanAction constructor.
     */
    public function __construct(protected string $cmd, protected array $cmd_params = [])
    {
    }

    public function handle(): string
    {
        return ArtisanService::act($this->cmd);
    }

    // end handle
}
