<?php

namespace App\Filament\Resources\QuizResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('question')
                    ->required()
                    ->label('Question')
                    ->columnSpan(2), 
                 
                Forms\Components\TextInput::make('question_order')
                    ->numeric()
                    ->label('Question order')
                    ->columnSpan(2),

                Forms\Components\Repeater::make('options')
                    ->label('Options')
                    ->required()
                    ->schema([
                        Forms\Components\TextInput::make('option_1')
                            ->required()
                            ->label('Option 1')
                            ->placeholder('Enter Option 1')
                            ->columnSpan(3),

                        Forms\Components\TextInput::make('option_2')
                            ->required()
                            ->label('Option 2')
                            ->placeholder('Enter Option 2')
                            ->columnSpan(3),

                        Forms\Components\TextInput::make('option_3')
                            ->required()
                            ->label('Option 3')
                            ->placeholder('Enter Option 3')
                            ->columnSpan(3),

                        Forms\Components\TextInput::make('option_4')
                            ->required()
                            ->label('Option 4')
                            ->placeholder('Enter Option 4')
                            ->columnSpan(3),
                    ])

                    ->columnSpan(2), // Repeater spans across 2 columns

                Forms\Components\Select::make('correct_option')
                    ->options([
                        1 => 'Option 1',
                        2 => 'Option 2',
                        3 => 'Option 3',
                        4 => 'Option 4',
                    ])
                    ->required()
                    ->label('Correct Option')
                    ->columnSpan(1), // Correct option takes up 1 column

                    Forms\Components\Select::make('quiz_id')
                    ->label('Quiz')
                    ->columnSpan(1)
                    ->default($this->record->quiz_id ?? null) // Set the current quiz_id (if available)
                    ->hidden() // Make the field hidden
                    ->disabled() // Optionally keep this if you want to prevent any interaction, but hidden will suffice
                



            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('question')->label('Question'),
                Tables\Columns\TextColumn::make('question_order')->label('Order'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
