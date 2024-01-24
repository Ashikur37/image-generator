<?php

namespace App\Filament\Resources;

use App\Enums\ImageStatusEnum;
use App\Filament\Resources\ImageResource\Pages;
use App\Jobs\GenerateImageJob;
use App\Models\Image;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\ViewField;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Collection;

class ImageResource extends Resource
{
    protected static ?string $model = Image::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('keyword')->required()
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('keyword')->searchable(),
                TextColumn::make('status')->badge()->colors([
                    'success' => ImageStatusEnum::COMPLETED,
                    'warning' => ImageStatusEnum::PROCESSING,
                    'danger'  => ImageStatusEnum::FAILED,
                    'info'    => ImageStatusEnum::NEW
                ]),
                TextColumn::make('progress_percentage')->label('Progress'),
            ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\Action::make('Retry')
                    ->action(function (Image $image): void {
                        $image->retry();
                    })->icon('heroicon-o-arrow-path')
                    ->visible(fn (Image $image) => $image->status === ImageStatusEnum::FAILED),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('Retry')
                        ->icon('heroicon-o-arrow-path')
                        ->action(function (Collection $images) {
                            foreach ($images as $image) {
                                if ($image->status === ImageStatusEnum::FAILED) {
                                    $image->retry();
                                }
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('Generate Image')->icon('heroicon-o-camera'),
            ])->defaultSort('created_at', 'desc');;
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('keyword'),
                TextEntry::make('status')
                    ->badge()
                    ->colors([
                        'success' => ImageStatusEnum::COMPLETED,
                        'warning' => ImageStatusEnum::PROCESSING,
                        'danger'  => ImageStatusEnum::FAILED,
                        'info'    => ImageStatusEnum::NEW
                    ]),
                TextEntry::make('prompt'),
                ViewEntry::make('image_path')
                    ->view('components.show-image')
                    ->visible(fn (Image $image) => $image->status === ImageStatusEnum::COMPLETED)
                    ->label('Image'),
                TextEntry::make('response'),
                TextEntry::make('created_at')
                    ->since()->label('Created Date'),
                TextEntry::make('updated_at')
                    ->since()->label('Updated Date'),
            ])
            ->columns(1)
            ->inlineLabel();
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageImages::route('/'),
        ];
    }
}
