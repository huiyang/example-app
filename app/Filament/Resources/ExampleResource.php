<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Example;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ExampleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ExampleResource\RelationManagers;
use Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction;

class ExampleResource extends Resource
{
    protected static ?string $model = Example::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                \Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction::make()
                    ->modifyTitleUsing(function($state, $record) {
                        if ($state['description'] == $state['event']) {
                            $aliases = [
                                'CoachNote' => 'pre-session note',
                            ];
                            $className = isset($state['subject']) ? class_basename($state['subject']) : class_basename($record->subject_type);
                            $className  = $aliases[$className] ?? Str::lower(Str::snake($className, ' '));

                            $causer = $state['causer'];
                            $causerName = $causer->name ?? $causer->first_name ?? $causer->last_name ?? $causer->username ?? 'Unknown';
                            $update_at  = \Carbon\Carbon::parse($state['update'])->translatedFormat(config('filament-activitylog.datetime_format'));

                            return new HtmlString(
                                sprintf(
                                    'The <strong>%s</strong> was <strong>%s</strong> by <strong>%s</strong>. <br><small> Updated at: <strong>%s</strong></small>',
                                    $className,
                                    $state['event'],
                                    $causerName,
                                    $update_at
                                )
                            );
                        }
                    })
                    ,
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageExamples::route('/'),
        ];
    }
}
