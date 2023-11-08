<?php

declare(strict_types=1);

namespace Modules\Cms\Contracts;

// use Illuminate\Database\Query\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Modules\Cms\Models\Panels\Actions\XotBasePanelAction;
use Modules\UI\Datas\FieldData;
use Modules\Xot\Contracts\UserContract;
use Psr\Http\Message\StreamInterface;
use Spatie\LaravelData\DataCollection;

/**
 * Undocumented interface.
 *
 * @property Model        $row
 * @property RowsContract $rows
 */
#[AllowDynamicProperties]
interface PanelContract
{
    public function setRow(Model $model): self;

    // public function setRows(Relation $rows): self;

    /**
     * Undocumented function.
     *
     * @param Relation|Builder $rows
     */
    public function setRows($rows): self;

    /**
     * Undocumented function.
     *
     * @return Relation|Builder
     */
    public function getRows();

    public function setItem(string $guid): self;

    public function setParent(?self $panel): self;

    /**
     * Undocumented function.
     *
     * @return RowsContract
     */
    public function rows(array $data = null);

    /**
     * ---.
     */
    public function update(array $data): self;

    /**
     * Ritorna la view.
     *
     * @return View
     */
    public function view(array $params = null);

    /* -- move to getActions('item')
     * Undocumented function.
     *
     * @return PanelActionContract

    public function itemAction(string $act);
    */

    public function getAction(string $name): XotBasePanelAction;

    /**
     * Undocumented function.
     *
     * @return Collection<PanelContract>
     */
    public function getActions(?string $name);

    public function relatedUrl(string $name, string $act = 'index'): string;

    public function setLabel(string $label): Model;

    public function postType(): string;

    public function imgSrc(array $params): string;

    public function getRow(): Model;

    /**
     * Undocumented function.
     *
     * @return File|UploadedFile|StreamInterface|resource|string
     */
    public function pdf(array $params = []);

    public function pdfFilename(array $params = []): string;

    public function setInAdmin(bool $in_admin): self;

    public function setRouteParams(array $route_params): void;

    public function getXotModelName(): ?string;

    public function url(string $act = 'show'/* , ?array $params = [] */): string;

    /* moved ti getActions('item')
     * Undocumented function.
     *
     * @return Collection<XotBasePanelAction>

    public function itemActions(array $params = []): Collection;
    */
    public function id(bool $is_admin = null): string;

    public function title(): ?string;

    public function with(): array;

    public function setName(string $name): self;

    public function tabs(): array;

    /**
     * @return Collection&iterable<PanelContract>
     */
    public function getBreads();

    public function getRouteParams(): array;

    public function guid(bool $is_admin = null): ?string;

    public function getParent(): ?self;

    public function fields(): array;

    public function actions(): array;

    public function getModuleNameLow(): string;

    public function getModuleName(): string;

    public function getName(): string;

    public function getOrderField();

    /**
     * Undocumented function.
     */
    public function callAction(string $act);

    /**
     * Undocumented function.
     */
    public function out(array $params = []);

    /**
     * Undocumented function.
     */
    public function callItemActionWithGate(string $act);

    /**
     * Undocumented function.
     *
     * @return Collection<PanelContract>
     */
    public function getParents();

    /**
     * ---.
     */
    public function formLivewireEdit(array $params = []): string;

    /**
     * @return DataCollection<FieldData>
     */
    public function getFields(string $act): DataCollection;

    public function isRevisionBy(UserContract $userContract): bool;

    public function isAuthoredBy(UserContract $userContract): bool;

    public function isModeratedBy(UserContract $userContract): bool;

    public function isAdminedBy(UserContract $userContract): bool;

    public function related(string $relationship): self;

    public function relatedName(string $name, int $id = null): self;

    /**
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public function getBuilder();

    public function getTradMod(): string;

    /**
     * Undocumented function.
     */
    public function filters(): array;

    /**
     * @return int|string|null
     */
    public function optionId(Model $model);

    public function optionLabel(Model $model): string;

    // public function isInternalPage(): bool;

    public function rules(?string $act = ''): array;

    public function getRules(?string $act = ''): array;

    public function rulesMessages(): array;

    // --------------------- ACTIONS -------------------

    public function urlContainerAction(string $act, array $params = []);

    // public function containerAction(string $act): XotBasePanelAction;

    public function rowsPaginated(): LengthAwarePaginator;

    public function hasLang(): bool;

    /**
     * @return array
     */
    public function orderBy();

    public function areas(): Collection;

    /**
     * @phpstan-return view-string
     */
    public function getView(): string;

    /**
     * @phpstan-return view-string
     */
    public function getViewWork(): string;

    public function getViews(): array;

    public function getPath(bool $is_admin = null): string;

    public function optionsModelClass(string $model_class, array $where = [], array $where_in = []): array;
}
