<?php

namespace App\Filament\Resources\LessonResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Actions\AttachAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;


class QuizzesRelationManager extends RelationManager
{
    protected static string $relationship = 'quizzes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('quizzes')
                    ->label('Quizzes')
                    ->relationship('quizzes', 'title') // Relationship with 'Lessons'
                    ->multiple() // Allow selecting multiple lessons
                    ->preload(),
                Forms\Components\TextInput::make('order')->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Quiz Title')->sortable(),
                Tables\Columns\TextColumn::make('order')->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()->form(fn(AttachAction $action): array => [
                    $action->getRecordSelect(),
                    Forms\Components\TextInput::make('order')->numeric(),
                ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }
}
