<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers;
use App\Models\Question;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationLabel = 'Questions';
    protected static ?string $navigationGroup = 'Quizzes Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('question')
                    ->required()
                    ->label('Question')
                    ->columnSpan(2), // Make the question field span across 2 columns
                
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
                    ->relationship('quiz', 'title') // Assuming your Quiz model has a title field
                    ->required()
                    ->label('Select Quiz')
                    ->columnSpan(2), // Quiz select spans across 2 columns

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question')->label('Question'),
                Tables\Columns\TextColumn::make('options')
                    ->label('Options')
                    ->formatStateUsing(fn($state) => implode(', ', array_values(json_decode($state, true)))),                   
                Tables\Columns\TextColumn::make('quiz.title')->label('Quiz'), // Display associated quiz title
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }

    public static function render(): View|Factory|Application|string
    {
        return view('filament.resources.quiz-resource.pages.view-quiz', [
            'questions' => $this->record->questions,
        ]);
    }
}
