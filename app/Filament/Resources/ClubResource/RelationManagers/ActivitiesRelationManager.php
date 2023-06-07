<?php

namespace App\Filament\Resources\ClubResource\RelationManagers;

use App\Models\Student;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('attendance')
                    ->label('Student Attendance')
                    ->icon('heroicon-s-table')
                    ->form([
                        Forms\Components\CheckboxList::make('students')
                            ->disableLabel()
                            ->options(fn ($record) => Student::where('club_id', $record->club_id)->pluck('name', 'id'))
                            ->bulkToggleable(),
                    ])
                    ->action(function ($data, $record) {
                        $record->students()->sync($data['students']);
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
