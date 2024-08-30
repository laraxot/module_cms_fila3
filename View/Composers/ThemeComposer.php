<?php

declare(strict_types=1);

namespace Modules\Cms\View\Composers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Modules\Cms\Datas\FooterData;
use Modules\Cms\Datas\HeadernavData;
use Modules\Cms\Models\Menu;
use Modules\Cms\Models\Page;
use Modules\Cms\Models\PageContent;
use Webmozart\Assert\Assert;

class ThemeComposer
{
    /**
     * Undocumented function.
     *
     * @return array|null
     */
    public function getMenu(string $menu_name)
    {
        $menu = Menu::firstOrCreate(['title' => $menu_name]);

        return $menu->items ?? [];
    }

    public function getMenuUrl(array $menu): string
    {
        if (empty($menu)) {
            return '#';
        }
        $lang = app()->getLocale();
        if ('internal' == $menu['type']) {
            return route('page_slug.show', ['lang' => $lang, 'page_slug' => $menu['url']]);
        }
        if ('external' == $menu['type']) {
            return $menu['url'];
        }
        if ('route_name' == $menu['type']) {
            return route($menu['url'], ['lang' => $lang]);
        }

        return '#';
    }

    public function showPageContent(string $slug): Renderable
    {
        Assert::isInstanceOf($page = Page::firstOrCreate(['slug' => $slug], ['title' => $slug, 'content_blocks' => []]), Page::class, '['.__LINE__.']['.__FILE__.']');
        // $page = Page::firstOrCreate(['slug' => $slug], ['content_blocks' => []]);
        $blocks = $page->content_blocks;
        if (! is_array($blocks)) {
            $blocks = [];
        }
        $page = new \Modules\UI\View\Components\Render\Blocks(blocks: $blocks, model: $page);

        return $page->render();
    }

    public function showPageSidebarContent(string $slug): Renderable
    {
        Assert::isInstanceOf($page = Page::firstOrCreate(['slug' => $slug], ['sidebar_blocks' => []]), Page::class, '['.__LINE__.']['.__FILE__.']');
        // $page = Page::firstOrCreate(['slug' => $slug], ['content_blocks' => []]);

        $page = new \Modules\UI\View\Components\Render\Blocks(blocks: $page->sidebar_blocks, model: $page);

        return $page->render();
    }

<<<<<<< HEAD
    public function showContent(string $slug): \Illuminate\Contracts\Support\Renderable
    {
        Assert::isInstanceOf($page = PageContent::firstOrCreate(['slug' => $slug], ['blocks' => []]), PageContent::class, '['.__LINE__.']['.__FILE__.']');

        $page = new \Modules\UI\View\Components\Render\Blocks(blocks: (array) $page->blocks, model: $page);
=======
    public function showContent(string $slug): Renderable
    {
        Assert::isInstanceOf($page = PageContent::firstOrCreate(['slug' => $slug], ['blocks' => []]), PageContent::class, '['.__LINE__.']['.__FILE__.']');

        if (! is_array($page->blocks)) {
            return view('ui::empty');
        }

        $page = new \Modules\UI\View\Components\Render\Blocks(blocks: $page->blocks, model: $page);
>>>>>>> 59d5a90254706a869215b533bf368ab9d8f0904c

        return $page->render();
    }

    public function getPages(): Collection
    {
        $pages = Page::all();

        return $pages;
    }

    public function getPageModel(string $slug): ?Page
    {
        return Page::where('slug', $slug)->first();
    }

    public function getUrlPage(string $slug): string
    {
        $page = $this->getPageModel($slug);
        if (null !== $page) {
            return '/'.app()->getLocale().'/pages/'.$slug;
        }

        return '#';
    }

    public function headernav(): Renderable
    {
        $headernav = HeadernavData::make();

        return $headernav->view();
    }

    public function footer(): Renderable
    {
        $footer = FooterData::make();

        return $footer->view();
    }
}
