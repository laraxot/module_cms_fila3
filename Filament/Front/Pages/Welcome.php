<?php
/**
 * @see https://laraveldaily.com/post/filament-custom-edit-profile-page-multiple-forms-full-design
 */

declare(strict_types=1);

namespace Modules\Cms\Filament\Front\Pages;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Modules\Blog\Models\Post;
use Modules\Quaeris\Models\QuestionChart;

class Welcome extends Page implements HasTable
{
    use InteractsWithTable;
    // use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    // protected static string $view = 'cms::filament.front.pages.welcome';
    protected static string $view = 'pub_theme::home';

    protected static string $layout = 'pub_theme::layouts.app';

    protected function getTables(): array
    {
        return [
            // 'articlesTable',
        ];
    }

    public function articlesTable(Infolist $infolist): Infolist
    {
        /*
        return $table
            ->columns([
                TextColumn::make('title'),

            ]);
            */
        return $infolist
            ->schema([
                TextEntry::make('title'),
                TextEntry::make('slug'),
                // ...
            ])
            ->record(Post::first())
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(QuestionChart::query())

            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])

            ->columns([
                View::make('pub_theme::home.article'),
                // Split::make([
                //     TextColumn::make('id')
                //         ->label('#'),
                //     TextColumn::make('question'),
                //     /*TextColumn::make('lessons_count')
                //     ->label('Lessons')
                //     ->counts('lessons'),
                //     */
                // ]),
            ])
            ->actions([
                /*Action::make('Lessons')
                    ->icon('heroicon-m-academic-cap')
                    ->url(fn (Course $record): string => LessonResource::getUrl('index', [
                        'tableFilters[course][value]' => $record,
                    ])),*/
                // ViewAction::make(),
                // EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function url(string $name, array $parameters = []): string
    {
        $parameters['lang'] = app()->getLocale();
        $record = $parameters['record'];
        if ('show' === $name) {
            $container0 = class_basename($record);
            $container0 = Str::plural($container0);
            $container0 = Str::snake($container0);
            $parameters['container0'] = $container0;
            $parameters['item0'] = $record;

            return route('test', $parameters);
        }
        $parameters['container0'] = 'articles';
        $parameters['item0'] = 'zibibbo';

        return route('test', $parameters);
    }
}
